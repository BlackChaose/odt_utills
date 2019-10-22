<?php
/**
 * sanitize odt file -> delete page_breaks tags
 *
 */
//set the correct xml headers to output to browser
//header("Content-type: text/xml");
include_once('./lib/tbszip.php');
$options = getopt("s:d:v:");

print_r($options);

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

if(empty($file_path_src)){echo "Error! source is null!\n"; die;}
//if(empty($file_path_dest)){echo "Error! dest is null!"; die;}

$dataFile = "content.xml";
$zipFile = $options["s"];

$zip = new clsTbsZip();

$zip->Open($zipFile);

$ok = $zip->FileExists($dataFile);
if($ok){
    $xml = $zip->FileRead($dataFile);
    //echo $xml;
    $xmlNew=preg_replace('/\<text:soft-page-break\/>/', '', $xml);
    $res = $zip->FileReplace('content.xml', $xmlNew, TBSZIP_STRING); // replace the file by giving the content
    $zip->Flush(TBSZIP_FILE, $file_path_dest );
    echo "res: ".$res." \n";
}

$zip->Close();
