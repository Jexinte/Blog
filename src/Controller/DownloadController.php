<?php

/**
 * Handle Download 
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  DownloadController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Controller;

/**
 * Handle Download 
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  DownloadController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class DownloadController
{

    public string $file;

    public string $fileSize;

    public int $errorCode;
    
    /**
     * Summary of downloadPdfFile
     *
     * @return array
     */
    public function downloadPdfFile():?array
    {

        $this->file = "../public/uploads/cv.pdf";

        if (!file_exists($this->file)) {
            $this->errorCode = 500;
            return ["code_error" => $this->errorCode];
        }

        $this->fileSize = filesize($this->file);
        return ["file_logs" => $this->fileSize];
   
    }
  
}
