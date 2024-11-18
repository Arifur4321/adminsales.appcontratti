<?php
 


namespace App\PDF;

use TCPDF;

class CustomPDF extends TCPDF
{
    public $headerContent = '';
    public $footerContent = '';
    public $headerEveryPage = false;
    public $footerEveryPage = false;

    
    public function setHeaderContent($headerContent, $headerEveryPage)
    {
        $this->headerContent = $headerContent;
        $this->headerEveryPage = $headerEveryPage;
    }

  
    public function setFooterContent($footerContent, $footerEveryPage)
    {
        $this->footerContent = $footerContent;
        $this->footerEveryPage = $footerEveryPage;
    }

   
    public function Header()
    {
        if ($this->headerContent && ($this->headerEveryPage || $this->PageNo() == 1)) {
            $this->SetY(10);
            $this->SetFont('helvetica', 'B', 12);
            $headerHTML = $this->adjustImagesInContent($this->headerContent, 50);
            $this->writeHTML($headerHTML, true, false, true, false, '');
            $this->SetY(60);  
        } else {
            $this->SetY(10);  
        }
    }

   
    public function Footer()
    {
        if ($this->footerContent && ($this->footerEveryPage || $this->PageNo() == 1)) {
            $this->SetY(-30);
            $this->SetFont('helvetica', 'I', 8);
            $footerHTML = $this->adjustImagesInContent($this->footerContent, 20);
            $this->writeHTML($footerHTML, true, false, true, false, 'C');
            $this->SetY(-40);  
        }

        
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    
    private function adjustImagesInContent($content, $maxHeight)
    {
       
        $doc = new \DOMDocument();
        @$doc->loadHTML($content);

       
        $images = $doc->getElementsByTagName('img');

     
        foreach ($images as $image) {
         
            $image->setAttribute('style', 'max-height: '.$maxHeight.'px; width: auto; display: block; margin: 0 auto;');
        }

        
        return $doc->saveHTML();
    }
 
    public function AddWatermark()
    {
        $this->SetAlpha(0.1);  
        $this->SetFont('helvetica', 'B', 50);
        $this->SetTextColor(150, 150, 150);  
        $this->StartTransform();
        $this->Rotate(45, $this->getPageWidth() / 2, $this->getPageHeight() / 2);
        $this->Text($this->getPageWidth() / 4, $this->getPageHeight() / 2, 'GIACOMO FREDDI');
        $this->StopTransform();
        $this->SetAlpha(1);  
    }

  
    public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false)
    {
        parent::AddPage($orientation, $format, $keepmargins, $tocpage);
        $this->AddWatermark();
    }
}    