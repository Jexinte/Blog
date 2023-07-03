<?php

namespace Controller;

use Model\Download;

class DownloadController
{

  public function handleDownloadFile():void
  {
  $download = new Download();
  $download->downloadPdfFile(); 
  }
}
