<?php

require 'vendor/autoload.php';

function make_barcode($melli)
{
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    return '<img style="float: left" src="data:image/png;base64,' . base64_encode($generator->getBarcode($melli, $generator::TYPE_CODE_128)) . '">';
   
}
