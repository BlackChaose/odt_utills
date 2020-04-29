<?php
/**
 * CLI utility
 * sanitize odt file
 * 22-24/10/2019
 * nikita.s.kalitin@gmail.com
 */
use OdtHelper\xmlTreeSearch;

require_once ('vendor/autoload.php');
$options = getopt("s:d:v:");

if(empty($options)){
    echo "-s <path to source> -d <path to dst> \n";
    die;
}
if(!empty($options["v"])){
    echo "version 0.1 18-10-2019 \n";
    die;
}

$file_path_src = $options["s"]??null;
$file_path_dest = $options["d"]??null;

echo "\033[92m source:  ".$options['s']. "\e[39m\n";
echo "\033[93m destination:  ".$options['d']. "\e[39m\n";

if(empty($file_path_src)){echo "Error! source is null!\n"; die;}


$dataFile = "content.xml";
$zipFile = $options["s"];

$zip = new clsTbsZip();

$zip->Open($zipFile);

$ok = $zip->FileExists($dataFile);

if($ok){
    $xml = $zip->FileRead($dataFile);

    $parse = new xmlTreeSearch($xml);

    $parse->initTree();

    $parse->clearFlatTree();

    echo "\033[91m поломанных шаблонов TBS найдено и исправлено: ".$parse->getNumOfIter(). "\e[39m\n";

    $res = $zip->FileReplace('content.xml', $parse->getResultString(), TBSZIP_STRING); // replace the file by giving the content
    $zip->Flush(TBSZIP_FILE, $file_path_dest );
}

$zip->Close();
