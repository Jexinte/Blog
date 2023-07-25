<?php

namespace Controller;

use Model\Download;

class DownloadController
{

  public function handleDownloadFile(): ?array
  {
    $download = new Download();
    return $download->downloadPdfFile();
  }
}
