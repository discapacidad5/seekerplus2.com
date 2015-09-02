<?php
namespace SeekerPlus\AdsmanagerBundle\Models;

/**
 * Description of Document
 *
 * @author Manoj
 */
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Exception\Exception;


class Document
{

    private $file;

    private $subDir;

    private $filePersistencePath;

    /** @var string */
    protected static $uploadDirectory = '/var/www/SeekerPlusWeb/web';

    static public function setUploadDirectory($dir)
    {
        self::$uploadDirectory = $dir;
    }

    static public function getUploadDirectory()
    {
        if (self::$uploadDirectory === null) {
            throw new \RuntimeException("Trying to access upload directory for profile files");
        }
        return self::$uploadDirectory;
    }
    public function setSubDirectory($dir)
    {
         $this->subDir = $dir;
    }

    public function getSubDirectory()
    {
        if ($this->subDir === null) {
            throw new \RuntimeException("Trying to access sub directory for profile files");
        }
        return $this->subDir;
    }


    public function setFile(File $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return new File(self::getUploadDirectory() . "/" . $this->filePersistencePath);
    }

     public function getOriginalFileName()
    {
        return $this->file->getClientOriginalName();
    }

    public function getFilePersistencePath()
    {
        return $this->filePersistencePath;
    }

    public function processFile($fileName)
    {
        if (! ($this->file instanceof UploadedFile) ) {
            return false;
        }
        $uploadFileMover = new UploadFileMover();
        $this->filePersistencePath = $uploadFileMover->moveUploadedFile($this->file, self::getUploadDirectory(),$this->subDir,$fileName);
    }
    
    public static function deleteDir($dirPath) {
    	if (! is_dir($dirPath)) {

    	}
    	$dirPath=self::getUploadDirectory().$dirPath;
    	
    	if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
    		$dirPath .= '/';
    	}
    	$files = glob($dirPath . '*', GLOB_MARK);
    	foreach ($files as $file) {
    		if (is_dir($file)) {
    			self::deleteDir($file);
    		} else {
    			unlink($file);
    		}
    	}
    		try {
    			rmdir($dirPath);
    		} catch (\Exception $e) {

    		}


    }
    public function deleteFile($file){
    	unlink(self::getUploadDirectory().$file);
    }
}
?>
