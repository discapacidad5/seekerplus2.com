<?php

/**
 * @package plugin AdminExile
 * @copyright (C) 2010-2013 Michael Richey
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * AdminExile system plugin
 */
class plgSystemAdminExile extends JPlugin {

    private $app;
    private $key;
    private $log;
    
    function onAfterInitialise() {
        $this->app = JFactory::getApplication();
        // this plugin is meant for administrator
        if ($this->app->isAdmin()) {
	    $this->_bunchabullshit(); // stupid Joomla BS, check notes in the method
            // Once you're in - you're in
            if (JFactory::getUser()->id) {
                // check for block removals
                if ($this->app->input->get->get('adminexile_removeblock', false)) {
                    $ip = $this->app->input->get->get('ip', 0);
                    $firstattempt = $this->app->input->get->get('firstattempt', 0, 'RAW');
                    if ($this->_clearBlocks(array('ip' => $ip, 'firstattempt' => $firstattempt))) {
                        header('Content-Type: application/json');
                        die(json_encode(array('success' => true)));
                    }
                }
                // exit out of the plugin because, you're in :)
                return true;
            }

            // check for key in session
            $this->key = $this->params->get('key', 'adminexile');
            if ($this->app->getUserState("plg_sys_adminexile.$this->key", false))
                return true;

            // check for a blocked ip
            if (!$this->_ipcheck())
                return true;

            // check against black and whitelists
            if ($this->_blackwhite())
                return true;

            return $this->_keyauth();
//        } else {
//            return $this->_frontrestrict();
        }
    }
    function onAfterRoute() {
        if ($this->app->isAdmin()) return true;
        return $this->_frontrestrict();
    }
    function onUserLoginFailure() {
        if ($this->app->isSite())
            return true;
        if (!$this->_ipcheck()) {
            return true;
        }
        $this->_logInvalid();
        if (!$this->_ipcheck(true)) {
            return true;
        }
        return true;
    }

    public function _ipcheck($test = false) {
        if (!$this->params->get('bruteforce', 0))
            return true;
        if (!$test) {
            $this->_clearBlocks(); // clear expired blocks
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')->from('#__plg_system_adminexile')->where('ip = "' . $_SERVER['REMOTE_ADDR'] . '"');
            $db->setQuery($query);
            $result = $db->loadObject();
            $this->log = $result ? $result : false;
        }
        if ($this->log && $this->log->penalty != 0) {
            $this->_logInvalid();
            $this->_redirect();
            return false;
        }
        return true;
    }

    public function _clearBlocks($ip = false) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__plg_system_adminexile');
        if ($ip) {
            foreach ($ip as $var => $value)
                $ip[$var] = $var . '=' . $db->quote($value);
            $query->where($ip);
        } else {
            $query->where('penalty > 0 AND TIME_TO_SEC(TIMEDIFF(ADDDATE(`lastattempt`,INTERVAL `penalty` MINUTE),NOW())) <= 0');
        }
        $db->setQuery($query);
        $db->query();
        return true;
    }

    public function _notifybf() {
        if (!$this->params->get('bfemail', 1))
            return true;
        if (!$this->params->get('bfemailuser', false))
            return true;
        JFactory::getLanguage()->load('plg_system_adminexile', JPATH_ADMINISTRATOR);
        $email = JFactory::getUser($this->params->get('bfemailuser', false))->email;
        // prepare and send the email
        $config = JFactory::getConfig();
        $mailer = JFactory::getMailer();
        $mailer->setSender(array($config->get('config.mailfrom'), $config->get('config.fromname')));
        $mailer->addRecipient($email);
        $mailer->setSubject(JText::sprintf('PLG_SYS_ADMINEXILE_BFEMAIL_SUBJECT', $this->app->getCfg('sitename')));
        $mailer->setBody(JText::sprintf('PLG_SYS_ADMINEXILE_BFEMAIL_BODY', $this->log->ip, $this->log->attempts, $this->log->firstattempt, $this->log->lastattempt, $this->log->penalty));
        $send = & $mailer->Send();
        if ($send !== true) {
            error_log($send->message);
            $this->_killMessage($this->app, $send->message);
        }
    }

    public function _logInvalid($options = array()) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if ($this->log) {
            $query->update('#__plg_system_adminexile');
            $this->log->attempts++;
            if (!isset($options['blacklist'])) {
                if ($this->log->attempts >= $this->params->get('bfmax', 3)) {
                    $this->log->penalty = ($this->log->penalty == 0) ? $this->params->get('bfpenalty', 5) : $this->log->penalty * $this->params->get('bfpenaltymultiplier', 1);
                }
            }
            if ($this->log->attempts % $this->params->get('bfmax', 3) == 0) {
                $this->_notifybf();
            }
            switch ($this->params->get('bfpenaltymultiplier', 1)) {
                case 0:
                    $query->set('attempts=' . $this->log->attempt);
                    break;
                default:
                    $query->set('lastattempt=NOW(),attempts=' . $this->log->attempts . ',penalty=' . $this->log->penalty);
                    break;
            }
            $query->where('ip = ' . $db->quote($_SERVER['REMOTE_ADDR']));
        } else {
            $query->insert('#__plg_system_adminexile');
            $query->columns('ip,lastattempt,attempts,penalty');
            $query->values($db->quote($_SERVER['REMOTE_ADDR']) . ',NOW(),1,0');
        }
        $db->setQuery($query);
        $db->query();
    }

    public function _blackwhite() {
        if ($this->params->get('ipsecurity', 0)) {
            $visitor = $_SERVER['REMOTE_ADDR'];
            foreach (array('whitelist', 'blacklist') as $list) {
                $listnet = $list . 'net';
                $$listnet = array();
                $$list = explode("\n", $this->params->get($list, ''));
                foreach ($$list as $key => $item) {
                    if (preg_match('/\//', $item)) {
                        unset($$list[$key]);
                        array_push($$listnet, $item);
                    }
                }
            }
            if (count(array_merge($whitelistnet, $blacklistnet))) {
	      if(function_exists('gmp_pow')) {
                require_once('IPv6Net.class.php');
              } else {
		require_once('simplecidr.class.php');
              }
            }
            // white first
            if (in_array($visitor, $whitelist)) {
                $this->_authorized();
                return true;
            }
            foreach ($whitelistnet as $net) {
		if(function_exists('gmp_pow')) {
		  $ipnet = new IPv6Net($net);
		} else {
		  $ipnet = SimpleCIDR::getInstance($net);
		}
                if ($ipnet->contains($visitor)) {
                    $this->_authorized();
                    return true;
                }
            }
            if (in_array($visitor, $blacklist)) {
                $options = array('blacklist' => true);
                $this->_logInvalid($options);
                $this->_redirect();
                return true;
            }
            foreach ($blacklistnet as $net) {
		if(function_exists('gmp_pow')) {
		  $ipnet = new IPv6Net($net);
		} else {
		  $ipnet = SimpleCIDR::getInstance($net);
		}
                if ($ipnet->contains($visitor)) {
                    $options = array('blacklist' => true);
                    $this->_logInvalid($options);
                    $this->_redirect();
                    return true;
                }
            }
        }
        return false;
    }

    public function _keyauth() {
        // first, we look for valid mail link request
        if (@$email = $this->app->input->get->get('email', false)) {
            if ($this->params->get('maillink', true) && count($this->params->get('maillinkgroup', array()))) {
                $this->_maillink($email, $this->params->get('maillinkgroup', array()));
                $this->_redirect();
                return true;
            }
            // we don't exit here, because the key might be "email"!!!
        }
        if ($this->params->get('twofactor', false)) {
            if ($this->params->get('keyvalue', false) == $this->app->input->get->get($this->key, false)) {     
                $this->_authorized();
                return true;
            } else {
                if ($this->params->get('bruteforce', 0)) {
                    $options = array();
                    $options['new'] = $this->log ? false : true;
                    $this->_logInvalid($options);
                }
                $this->_redirect();
                return true;                
            }
            return true;
        } else {
            if(!isset($_GET[$this->key])) {
                if ($this->params->get('bruteforce', 0)) {
                    $options = array();
                    $options['new'] = $this->log ? false : true;
                    $this->_logInvalid($options);
                }
                $this->_redirect();
                return true;                   
            }
            $this->_authorized();
            return true;
        }
    }

    public function _authorized() {
        if ($this->params->get('bruteforce', 0)) {
            $this->_clearBlocks(array('ip' => $_SERVER['REMOTE_ADDR']));
        }
        $this->app->setUserState("plg_sys_adminexile.$this->key", true);
        header("Location: " . JURI::root() . "administrator");
    }

    public function _frontrestrict() {
        if(!$this->params->get('frontrestrict', 0)) return true;
        if($this->app->input->get('option') != 'com_users') return true;
        if($this->app->input->get('task') != 'user.login') return true;
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')->from('#__users')->where('username=' . $db->quote($this->app->input->post->get('username', '')));
        $db->setQuery($query);
        $user = JFactory::getUser($db->loadResult());
        $restrictgroup = $this->params->get('restrictgroup', array());
        foreach ($restrictgroup as $group) {
            if (in_array($group, $user->groups)) {
                // this will give a nice non-descript error
                $this->app->input->set('password', ''); // this doesn't work because jinput sucks balls
                $_POST['password']=''; // jrequest rocked!
                return true;
            }
        }
        return true;
    }

    public function _maillink($username = false, $groups = array()) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')->from('#__users')->where('username=' . $db->quote($username));
        $db->setQuery($query);
        $userid = $db->loadResult();
        if (is_numeric($userid)) {
            $user = JFactory::getUser($userid);
            $authorized = false;
            foreach ($user->groups as $group)
                if (in_array($group, $groups))
                    $authorized = true;

            if ($authorized) {
                // now that we know we're sending an email, load the language
                JFactory::getLanguage()->load('plg_system_adminexile', JPATH_ADMINISTRATOR);

                // building the /administrator URL
                $url = parse_url(JURI::root());
                $url['path'] = explode('/', preg_replace(array('/(^\/)/', '/(\/$)/'), '', $url['path']));
                $url['path'][] = 'administrator';
                $url['path'] = '/' . implode('/', $url['path']) . '/';
                $key = urlencode($this->params->get('key', 'adminexile'));
                if ($this->params->get('twofactor', false)) {
                    $url['query'] = http_build_query(array($key => urlencode($this->params->get('keyvalue', false))));
                } else {
                    $url['query'] = $key;
                }
                $url = http_build_url($url);

                // prepare and send the email
                $config = JFactory::getConfig();
                $mailer = JFactory::getMailer();
                $mailer->setSender(array($config->get('config.mailfrom'), $config->get('config.fromname')));
                $mailer->addRecipient($user->email);
                $mailer->setSubject(JText::sprintf('PLG_SYS_ADMINEXILE_EMAIL_SUBJECT', $this->app->getCfg('sitename')));
//                $mailer->setBody($url . "\n\n" . str_replace('{email}', $user->email, JText::_('PLG_SYS_ADMINEXILE_EMAIL_BODY')));
                $mailer->setBody(JText::sprintf('PLG_SYS_ADMINEXILE_EMAIL_BODY', $url, $user->email));
                $send = & $mailer->Send();
                if ($send !== true) {
                    error_log($send->message);
                    $this->_killMessage($this->app, $send->message);
                }
            }
        }
    }

    public function _redirect() {
        // this is for users who have successfully passed adminexile key/value but tripped the Brute Force detection
        $app = JFactory::getApplication();
        if ($app->getUserState("plg_sys_adminexile.$this->key", false)) {
            $app->setUserState("plg_sys_adminexile.$this->key", false);
        }
        // this is a new stealth feature - prevent /administrator session cookie from being set
        $hasheaders = false;
        foreach (headers_list() as $header) {
            if ($hasheaders)
                continue;
            if (preg_match('/Set-Cookie/', $header)) {
                $hasheaders = true;
            }
        }
        if ($hasheaders) {
            $phpversion = explode('.', phpversion());
            if ($phpversion[1] >= 3) { // identify as php 5.3
                header_remove('Set-Cookie');
            } else {
                header('Set-Cookie:');
            }
        }

        $redirecturl = $this->params->get('redirect', JURI::root());
        switch ($redirecturl) {
            case '{HOME}':
                $redirecturl = JURI::root();
                break;
            case '{404}':
                header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
                header("Status: 404 Not Found");
                if (!$this->params->get('fourofour', false)) {
                    die($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
                } else {
                    $find = array('{url}', '{serversignature}');
                    $replace = array($_SERVER['REQUEST_URI'], $_SERVER["SERVER_SIGNATURE"]);
                    die(str_replace($find, $replace, $this->params->get('fourofour')));
                }
                break;
            default:
                break;
        }
        if ($redirecturl != '{404}')
            header("Location: " . $redirecturl);
        return true;
    }

    private function _killMessage($app, $error) {
        $appReflection = new ReflectionClass(get_class($app));
        $_messageQueue = $appReflection->getProperty('_messageQueue');
        $_messageQueue->setAccessible(true);
        $messages = $_messageQueue->getValue($app);
        foreach ($messages as $key => $message) {
            if ($message['message'] == $error) {
                unset($messages[$key]);
            }
        }
        $_messageQueue->setValue($app, $messages);
    }

    private function _bunchabullshit() {
        /* so, manifest XML files will execute sql on install, but not on update
         * they will execute schema updates only if a schema already exists
         * there is no facility to create a table in an update if you don't already have one
         */
        $db = JFactory::getDbo();
        $tables = $db->getTableList();
        $prefix = $this->app->getCfg('dbprefix');
        $table = $prefix . 'plg_system_adminexile';
        if (!in_array($table, $tables)) {
            $createtable = file_get_contents(__DIR__ . '/sql/install.mysql.utf8.sql');
            $db->setQuery($createtable);
            $db->query();
            $query = $db->getQuery(true);
            $where = array(
                'type=' . $db->quote('plugin'),
                'folder=' . $db->quote('system'),
                'element=' . $db->quote('adminexile')
            );
            $query->select('extension_id,manifest_cache')->from('#__extensions')->where($where);
            $db->setQuery($query);
            $plugin = $db->loadObject();
            if ($plugin) {
                $pluginid = $plugin->extension_id;
                $manifest = json_decode($plugin->manifest_cache);
                $version = $manifest->version;
                // determine if there is a schema entry
                $query = $db->getQuery(true);
                $query->select('extension_id')->from('#__schemas')->where('extension_id = '.$pluginid);
                $db->setQuery($query);
                $result = $db->loadAssocList();
                $query = $db->getQuery(true);
                if(!count($result)) {
                    $query->insert($db->quoteName('#__schemas'));
                    $query->columns(array($db->quoteName('extension_id'), $db->quoteName('version_id')));
                    $query->values($pluginid . ', ' . $db->quote($version));
                } else {
                    $query->update('#__schemas');
                    $query->set('version_id = '.$db->quote($version));
                    $query->where('extension_id = '.$pluginid);
                }
                $db->setQuery($query);
                $db->execute();
            }
        }
    }

}

if (!function_exists('http_build_url')) {
    define('HTTP_URL_REPLACE', 1);          // Replace every part of the first URL when there's one of the second URL
    define('HTTP_URL_JOIN_PATH', 2);        // Join relative paths
    define('HTTP_URL_JOIN_QUERY', 4);       // Join query strings
    define('HTTP_URL_STRIP_USER', 8);       // Strip any user authentication information
    define('HTTP_URL_STRIP_PASS', 16);      // Strip any password authentication information
    define('HTTP_URL_STRIP_AUTH', 32);      // Strip any authentication information
    define('HTTP_URL_STRIP_PORT', 64);      // Strip explicit port numbers
    define('HTTP_URL_STRIP_PATH', 128);     // Strip complete path
    define('HTTP_URL_STRIP_QUERY', 256);    // Strip query string
    define('HTTP_URL_STRIP_FRAGMENT', 512); // Strip any fragments (#identifier)
    define('HTTP_URL_STRIP_ALL', 1024);     // Strip anything but scheme and host
// Build an URL
// The parts of the second URL will be merged into the first according to the flags argument. 
// 
// @param  mixed      (Part(s) of) an URL in form of a string or associative array like parse_url() returns
// @param  mixed      Same as the first argument
// @param  int        A bitmask of binary or'ed HTTP_URL constants (Optional)HTTP_URL_REPLACE is the default
// @param  array      If set, it will be filled with the parts of the composed url like parse_url() would return 

    function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = false) {
        $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');

        // HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
        if ($flags & HTTP_URL_STRIP_ALL) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
            $flags |= HTTP_URL_STRIP_PORT;
            $flags |= HTTP_URL_STRIP_PATH;
            $flags |= HTTP_URL_STRIP_QUERY;
            $flags |= HTTP_URL_STRIP_FRAGMENT;
        }
        // HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
        else if ($flags & HTTP_URL_STRIP_AUTH) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
        }

        // Parse the original URL
        $parse_url = is_array($url) ? $url : parse_url($url);

        // Scheme and Host are always replaced
        if (isset($parts['scheme']))
            $parse_url['scheme'] = $parts['scheme'];
        if (isset($parts['host']))
            $parse_url['host'] = $parts['host'];

        // (If applicable) Replace the original URL with it's new parts
        if ($flags & HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key]))
                    $parse_url[$key] = $parts[$key];
            }
        }
        else {
            // Join the original URL path with the new path
            if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
                if (isset($parse_url['path']))
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parts['path'], '/');
                else
                    $parse_url['path'] = $parts['path'];
            }

            // Join the original query string with the new query string
            if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
                if (isset($parse_url['query']))
                    $parse_url['query'] .= '&' . $parts['query'];
                else
                    $parse_url['query'] = $parts['query'];
            }
        }

        // Strips all the applicable sections of the URL
        // Note: Scheme and Host are never stripped
        foreach ($keys as $key) {
            if ($flags & (int) constant('HTTP_URL_STRIP_' . strtoupper($key)))
                unset($parse_url[$key]);
        }


        $new_url = $parse_url;

        return
                ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
                . ((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') . '@' : '')
                . ((isset($parse_url['host'])) ? $parse_url['host'] : '')
                . ((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
                . ((isset($parse_url['path'])) ? $parse_url['path'] : '')
                . ((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
                . ((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '')
        ;
    }

}