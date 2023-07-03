<?php

namespace Model;


class Download
{


  public $file;

  public $file_size;

  public int $error_code;
  public function downloadPdfFile(): void
  {

     $this->file = "../src/Uploads/test.pdf";

    if (!empty($this->file) && file_exists($this->file)) {

      $this->file_size = filesize($this->file);

      header("Content-Length: " . $this->file_size);
      header('Content-Description: File Transfer');
      header("Content-Type: application/pdf");
      header("Pragma: public");
      header("Content-Disposition:attachment;filename=cv.pdf");
      header("HTTP/1.1 200");
      readfile($this->file);
    } else {
      $this->error_code = 500;
      header("HTTP/1.1 302");
      header("Location:?action=error&code=" . $this->error_code);
      exit;
    }
  }
}
