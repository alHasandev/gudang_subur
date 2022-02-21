<?php

use Mpdf\Mpdf;

class MyMPDF extends Mpdf
{
  public $html = '';

  public function baseSetting()
  {
    $this->SetProtection(array('print'));
    $this->SetTitle("Acme Trading Co. - Invoice");
    $this->SetAuthor("Acme Trading Co.");
    $this->SetWatermarkText("Paid");
    $this->showWatermarkText = true;
    $this->watermark_font = 'DejaVuSansCondensed';
    $this->watermarkTextAlpha = 0.1;
    $this->SetDisplayMode('fullpage');
  }

  public function template($html)
  {
    $this->html = $html;
  }

  public function renderPDF()
  {

    $this->WriteHTML($this->html);
    $this->Output();
  }
}
