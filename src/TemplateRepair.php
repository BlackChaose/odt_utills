<?php


namespace OdtHelper;
/**
 * Class TemplateRepair
 * @package OdtHelper
 */
class TemplateRepair
{
    /**
     * @param $path_to_inputfile
     * @param $path_to_outputfile
     * @return string
     */
    public static function repair($path_to_inputfile, $path_to_outputfile){
        try {
            $dataFile = "content.xml";
            $zipFile = $path_to_inputfile;

            $zip = new \clsTbsZip();
            $zip->Open($zipFile);

            $ok = $zip->FileExists($dataFile);
            if ($ok) {
                $xml = $zip->FileRead($dataFile);

                $parse = new xmlTreeSearch($xml);

                $parse->initTree();

                $parse->clearFlatTree();

                $message = "файл " . $path_to_outputfile . " пересобран. Поломанных шаблонов TBS найдено и исправлено: " . $parse->getNumOfIter();
                $res = $zip->FileReplace('content.xml', $parse->getResultString(), TBSZIP_STRING);
                $zip->Flush(TBSZIP_FILE, $path_to_outputfile);
            }else{
                $message = 'не могу прочитать файл '.$path_to_outputfile;
            }
            $zip->Close();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $message;
    }
}