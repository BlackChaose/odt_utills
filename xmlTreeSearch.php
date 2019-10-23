<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 22.10.19
 * Time: 14:17
 */

namespace Helpers;

class xmlTreeSearch
{
    private $tree = array();
    private $flatTree = array();
    private $rawData = '';
    public $iter = 0;

    public function __construct($string)
    {
        $this->rawData = $string;
    }

    public function initTree()
    {
        $this->findOtag($this->rawData);
    }

    public function addElement($element, $level)
    {
        $this->flatTree[] = [$level => $element];
        for ($n = 0; $n < $level; $n++) {
            echo "-";
        }
        echo "$element";
        echo "\n";
    }

    /**
     * @param $str строка, в которой осуществляется поиск
     *
     */
    public function findOtag($str, $pLvl = 0)
    {
        echo($this->iter);
        $this->iter += 1;
        if (strlen($str) == 0) {
            return;
        }
        $buf = '';

        for ($i = 0; $i < strlen($str); $i++) {
            echo ".";
            if (substr($str, $i, 1) === '<') {
                if (strlen($buf) !== 0) {
                    $this->addElement($buf, $pLvl);
                    $buf = '';
                }
                $buf .= substr($str, $i, 1);
                continue;
            } else {
                if (substr($str, $i, 1) === '>') {
                    $buf .= substr($str, $i, 1);
                    $this->addElement($buf, $pLvl);
                    $buf = '';
                    continue;
                }
            }


            $buf .= substr($str, $i, 1);
        }

    }

    public function getNodeType($s)
    {
        $str = trim($s);
        if (substr($str, 0, 1) === '<' && substr($str, 1, 1) !== '/' && substr($str, -1, 1) === '>' && substr($str, -2,
                1) !== '/') {
            return 'openTag'; // opened tag
        } elseif (substr($str, 0, 1) === '<' && substr($str, 1, 1) === '/') {
            return 'closeTag'; // closed tag
        } elseif (substr($str, 0, 1) === '<' && substr($str, -2, 2) !== '/>'){
            return 'singleTag'; // single opened&closed tag
        }else{
            return 'noTag';
        }
    }

    public function convFlatToTree()
    {

    }

    public function printFlatTree()
    {
        foreach ($this->flatTree as $key => $value) {
            printf("|%4d|", $key);
            foreach ($value as $k => $v) {
                //print(" ".$k." | ".$v."\n");
                printf(" %s | %10s | \n", $k, $v);
            }
        }
    }

    public function printTree()
    {
        echo "<pre>";
        print_r($this->tree);
        echo "</pre>";
    }
}