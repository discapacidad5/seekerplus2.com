<?php

namespace SeekerPlus\AdsmanagerBundle\Models;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of UploadFileMover
 *
 * @author Manoj
 */
class UploadFileMover {

    public function moveUploadedFile(UploadedFile $file, $uploadBasePath,$relativePath,$fileName) {
        $originalName = $file->getFilename();
        // use filemtime() to have a more determenistic way to determine the subpath, otherwise its hard to test.
       // $relativePath = date('Y-m', filemtime($file->getPath()));
        $targetFileName = $relativePath . DIRECTORY_SEPARATOR . $originalName;
        $targetFilePath = $uploadBasePath . DIRECTORY_SEPARATOR . $targetFileName;
        $ext = $file->getExtension();
        $i=1;
        while (file_exists($targetFilePath) && md5_file($file->getPath()) != md5_file($targetFilePath)) {
            if ($ext) {
                $prev = $i == 1 ? "" : $i;
                $targetFilePath = $targetFilePath . str_replace($prev . $ext, $i++ . $ext, $targetFilePath);

            } else {
                $targetFilePath = $targetFilePath . $i++;
            }
        }


        $targetDir = $uploadBasePath . DIRECTORY_SEPARATOR . $relativePath;

        if (!is_dir($targetDir)) {
            $ret = mkdir($targetDir, 0777, true);
            if (!$ret) {
                throw new \RuntimeException("Could not create target directory to move temporary file into.");
            }
            copy($targetDir.'/../index.html',$targetDir."/index.html");
        }

        $file->move($targetDir,$fileName);

        return str_replace($uploadBasePath . DIRECTORY_SEPARATOR, "", $targetFilePath);
    }

}

?>
