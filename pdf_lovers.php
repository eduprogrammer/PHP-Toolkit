<?php

/* 
    Pdf Lovers: A library written in PHP based on GhostScript to parse PDF files.
    With that, you can merge and split Pdf's,
    convert them to PNG images and 
    from them extract texts.

    Just import the 'pdf_lovers.php' file onto your Web project,
    instantiate the class and call the functions!

    Copyright 2022. Eduardo Programador
    www.eduardoprogramador.com 
    consultoria@eduardoprogramador.com

    All Rights Reserved
*/

class PdfLovers {

    private $path;

    /* 
        The private constructor.
        You cannot instantiate it directly,
        also, you must call the static function init.
    */
    private function __construct($path_) {
        $this->path = $path_;
   
    }

    /*
        Default destructor
    */
    public function __destruct() {
        $this->path = null;
    }

    /* 
        The default method to use before invoking
        any other methods.
        This is a static method.

        Parameters:

            $path: A full path inside the server side 
            to get the files from.
    */
    public static function init($path_) : PdfLovers {
        return new PdfLovers($path_);
    }

    /*
        Extracts text from the PDF File 
        and outputs a txt file for downloading process.

        Parameters:

            $srcPdf: The single name of the PDF File without the full path.
            $outTxt: The output txt file without the full path.
    */
    public function pdfToTxt($srcPdf, $outTxt) : void {
        $cmd = "gs -sDEVICE=txtwrite -dBATCH -dNOPAUSE -o " . $this->path . "/" . $outTxt . " " . $this->path . "/" . $srcPdf;      
        shell_exec($cmd);    
        
        header('Content-Type: text/plain');
        header('Content-disposition: attachment; filename=' . $outTxt);
        header('Content-Transfer-Encoding: Binary');

        echo(readfile($this->path . '/' . $outTxt));

        
        
    }

    /*
        Convert the PDF pages in PNG files 
        and generates a ZIP file containing the raw images.

        Parameters:

            $srcPdf: the source PDF file without the full path
            $outImg: the output PNG file without the extension
            $numberOfPages: The total number of pages in the PDF file
    */
    public function pdfToImg($srcPdf, $outImg, $numberOfPages) : void {
        $cmd = "gs -sDEVICE=png16m -dBATCH -dNOPAUSE -dTextAlphaBits=4 -r300 -o " . $this->path . "/" . $outImg . "_%d.png " . $this->path . "/" . $srcPdf;            
        $test = shell_exec($cmd);     
        
        $zip = new ZipArchive();

        $zip->open($this->path .'/' . $outImg . '.zip', ZipArchive::CREATE);        

        for($i = 0; $i < $numberOfPages; $i++) {

            $zip->addFile($this->path . '/' . $outImg . '_' . ($i + 1) . '.png', $outImg . '_' . ($i + 1) . '.png');
            
        }        

            $zip->close();
        
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $outImg . '.zip');
            header('Content-Transfer-Encoding: Binary');

            echo(readfile($this->path . '/' . $outImg . '.zip'));
        
    }

    /* 
        Merges some different PDF files and combine them in one file.

        Parameters:

            $pdfs: An array that contains the PDF file names without the full paths.
            $outPdf: The output PDFfile without the full path.
    */
    public function mergePdf($pdfs, $outPdf) : void {
        $cmd = "gs -sDEVICE=pdfwrite -dBATCH -dNOPAUSE -q -sOutputFile=" . $this->path . "/" . $outPdf;      

        foreach($pdfs as $pdf) {
            $cmd .= " " . $this->path . "/" . $pdf;
        }

        shell_exec($cmd);                                                        

        header('Content-Type: application/pdf');
        header('Content-disposition: attachment; filename=' . $outPdf);
        header('Content-Transfer-Encoding: Binary');

        echo(readfile($this->path . '/' . $outPdf));

    }

    /*
        Splits one PDF file based upon the page count,
        creating sepated PDF files.

        Parameters:

            $srcPdf: The source PDF file without the full path
            $outPdf: The output PDF file without extension
            $numberOfPages: The total number of pages of the source PDF document
    */
    public function splitPdf($srcPdf, $outPdf, $numberOfPages) : void {
        
        $cmd = "gs -sDEVICE=pdfwrite -dBATCH -dNOPAUSE -q -dNOSAFER -o " . $this->path . "/" . $outPdf . "_%d.pdf " . $this->path . "/" . $srcPdf;            
        $test = shell_exec($cmd);     
        
        $zip = new ZipArchive();

        $zip->open($this->path .'/' . $outPdf . '.zip', ZipArchive::CREATE);        

        for($i = 0; $i < $numberOfPages; $i++) {

            $zip->addFile($this->path . '/' . $outPdf . '_' . ($i + 1) . '.pdf', $outPdf . '_' . ($i + 1) . '.pdf');
            
        }        

            $zip->close();
        
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $outPdf . '.zip');
            header('Content-Transfer-Encoding: Binary');

            echo(readfile($this->path . '/' . $outPdf . '.zip'));

    }


}

?>