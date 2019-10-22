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

    public function addBranch($branch, $level)
    {
        $this->tree[] = [$level => $branch];
        for($n=1;$n<$level;$n++){
            echo "-";
        }
        echo "$branch";
        echo "\n";
    }

    /**
     * @param $str строка, в которой осуществляется поиск
     *
     */
    public function findOtag($str, $pLvl = 0)
    {
        echo ($this->iter);
        $this->iter +=1;
        if (strlen($str) == 0) {
            return;
        }
        $buf = '';
        for ($i = 0; $i < strlen($str); $i++) {
            echo ".";
            if (substr($str, $i, 1) === '<') {
                if(strlen($buf)!==0){
                    $this->addBranch($buf,$pLvl+=1);
                    $buf = '';
                }
                $buf .= substr($str, $i, 1);
                continue;
            }else if(substr($str, $i, 1)==='>'){
                $buf .= substr($str,$i,1);
                $this->addBranch($buf,$pLvl-=1);
                $buf='';
                continue;
            }


            $buf .= substr($str, $i, 1);
        }

    }

    public function printTree()
    {
        echo "<pre>";
        print_r($this->tree);
        echo "</pre>";
    }
}