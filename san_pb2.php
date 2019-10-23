<?php
/**
 * sanitize odt file -> delete page_breaks tags
 *
 */

include_once('./lib/tbszip.php');
require_once('./xmlTreeSearch.php');
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

//fixme: /.*(\[.*(<.*>).*\]).*\/.*/gm

$file_path_src = $options["s"]??null;
$file_path_dest = $options["d"]??null;

if(empty($file_path_src)){echo "Error! source is null!\n"; die;}
//if(empty($file_path_dest)){echo "Error! dest is null!"; die;}

$dataFile = "content.xml";
$zipFile = $options["s"];

$zip = new clsTbsZip();

$zip->Open($zipFile);

$ok = $zip->FileExists($dataFile);
$tst1= "<root> txt <tag1> txt2 <tag2 /> txt3 <tag3 />     </tag1> txt4  <tagx><tagxx><tagxxx>Argh!</tagxxx></tagxx></tagx></root>";

if($ok){
    $xml = $zip->FileRead($dataFile);
    //echo $xml;
    //$parse = new \Helpers\xmlTreeSearch($xml);
    $parse = new \Helpers\xmlTreeSearch($tst1);
    $parse->initTree();
    $parse->printFlatTree();

    echo $parse->getNodeType('<tag1:p red>');
    echo "\n";
    echo $parse->getNodeType('</tag1:p>');
    echo "\n";
    echo $parse->getNodeType('<tag2:p />');
    echo "\n";



    $parse->getNodeName('<tag1:p red>');
    echo "\n";
    $parse->getNodeName('</tag1:p>');
    echo "\n";
    $parse->getNodeName('<tag2:p />');
    echo "\n";
    //$parse->printTree();

    //$result = array();
    //$xmlNew=preg_match_all('/.*(\[.*(<.*>).*\]).*\/.*/gm',$xml,$result);
    //$res = $zip->FileReplace('content.xml', $xmlNew, TBSZIP_STRING); // replace the file by giving the content
    //$zip->Flush(TBSZIP_FILE, $file_path_dest );
    //echo "<pre>";
    //echo "result:  \n";
    //print_r($result);
    //echo "</pre>";
}

$zip->Close();
