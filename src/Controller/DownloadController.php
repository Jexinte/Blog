<?php

namespace Controller;

use Repository\DownloadRepository;

class DownloadController
{

  public function handleDownloadFile(): ?array
  {
    $download = new DownloadRepository();
    return $download->downloadPdfFile();
  }
}
