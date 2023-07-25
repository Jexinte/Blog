<?php

namespace Repository;


class DownloadRepository
{


  public string $file;

  public string $fileSize;

  public int $errorCode;
  public function downloadPdfFile():?array
  {

    $this->file = "../public/uploads/test.pdf";

    if (!file_exists($this->file)) {
      $this->errorCode = 500;
      return ["code_error" => $this->errorCode];
    }

    $this->fileSize = filesize($this->file);
    return ["file_logs" => $this->fileSize];
   
  }
}
