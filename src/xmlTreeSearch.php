<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 22.10.19
 * Time: 14:17
 */

namespace OdtHelper;

class xmlTreeSearch
{
    private $tree = array();
    private $flatTree = array();
    private $resultTree = array();
    private $resultString = '';
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
    }

    /**
     * @param $str строка, в которой осуществляется поиск
     *
     */
    public function findOtag($str, $pLvl = 0)
    {

        if (strlen($str) == 0) {
            return;
        }
        $buf = '';

        for ($i = 0; $i < strlen($str); $i++) {

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

    /**
     * @param $s node's text
     *
     * @return string result  type of node
     */
    public function getNodeType($s)
    {
        $str = trim($s);
        if (substr($str, 0, 1) === '<' && substr($str, 1, 1) !== '/' && substr($str, -1, 1) === '>' && substr($str, -2,
                1) !== '/') {
            return 'openTag'; // opened tag
        } elseif (substr($str, 0, 1) === '<' && substr($str, 1, 1) === '/') {
            return 'closeTag'; // closed tag
        } elseif (substr($str, 0, 1) === '<' && substr($str, -2, 2) === '/>') {
            return 'singleTag'; // single opened&closed tag
        } else {
            return 'noTag'; // node are not tag
        }
    }

    /**
     * @param $s    node
     *
     * @return mixed node's name or null
     */
    public function getNodeName($s)
    {
        $str = trim($s);
        $name = [];
        $result = [];
        if ($this->getNodeType($str) !== 'noTag') {
            preg_match_all('/<\S*/', $str, $name);
            foreach ($name[0] as $key => $value) {
                if (substr($value, 0, 2) == '</') {
                    $result[0][$key] = substr($name[0][$key], 2, strlen($name[0][$key]) - 2);
                } elseif (substr($value, 0, 1) === '<') {
                    $result[0][$key] = substr($name[0][$key], 1, strlen($name[0][$key]) - 1);
                }

                if (substr($value, -1, 2) === '/>') {
                    $result[0][$key] = substr($result[0][$key], 0, strlen($result[0][$key]) - 2);
                } elseif (substr($value, -1, 1) === '>') {
                    $result[0][$key] = substr($result[0][$key], 0, strlen($result[0][$key]) - 1);
                }
            }
        }
        return ($result[0] ?? null);
    }

    public function convFlatToTree()
    {
        //in process
    }

    /**
     * @param $string node
     *
     * @return bool true if not bugs and false if are bugged
     */
    public function checkSqBrs($string)
    {
        $leftSqBr = 0;
        $rightSqBr = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            if (substr($string, $i, 1) == '[') {
                $leftSqBr += 1;
            } elseif (substr($string, $i, 1) == ']') {
                $rightSqBr += 1;
            }
        }
        return ($leftSqBr == $rightSqBr);
    }

    public function getTypeSqBrs($string)
    {
        $leftSqBr = 0;
        $rightSqBr = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            if (substr($string, $i, 1) == '[') {
                $leftSqBr += 1;
            } elseif (substr($string, $i, 1) == ']') {
                $rightSqBr += 1;
            }
        }

        return ($leftSqBr > $rightSqBr) ? 'left' : 'right';
    }

    /**
     * clear FlatTree by bugs
     */
    public function clearFlatTree()
    {
        //get all bugged indexes of tbs tpl
        $bugInNodes = array();
        array_map(function ($val, $key) use (&$bugInNodes) {
            if (!$this->checkSqBrs($val[0])) {
                array_push($bugInNodes, $key);
            }
        }, $this->flatTree, array_keys($this->flatTree));

        //check left & right square brackets

        $buf_noTags = array();
        $buf_tags = array();
        for ($i = 0; $i < count($bugInNodes); $i += 2) {

            if ($this->getTypeSqBrs($this->flatTree[$bugInNodes[$i]][0]) === 'left' && $this->getTypeSqBrs($this->flatTree[$bugInNodes[$i + 1]][0]) === 'right') {
                for ($j = $bugInNodes[$i] + 1; $j <= $bugInNodes[$i + 1]; $j++) {
                    if ($this->getNodeType($this->flatTree[$j][0]) === 'noTag') {
                        array_push($buf_noTags, $this->flatTree[$j][0]);
                        unset($this->flatTree[$j]);
                        $this->iter += 1;
                    } elseif ($this->getNodeType($this->flatTree[$j][0]) !== 'noTag') {
                        array_push($buf_tags, $this->flatTree[$j][0]);
                        unset($this->flatTree[$j]);
                    }
                }

                $buf_block = array_merge($buf_noTags, $buf_tags);

                for ($k = $bugInNodes[$i] + 1; $k <= $bugInNodes[$i + 1]; $k++) {
                    $this->flatTree[$k][0] = array_shift($buf_block);
                }
                $buf_tags = [];
                $buf_noTags = [];
                $buf_block = [];
            }

        }
        $flat = array();
        for ($index = 0; $index < count($this->flatTree); $index++) {
            array_push($flat, $this->flatTree[$index][0]);
        }
        $this->resultString = implode('', $flat);
    }

    /**
     * @codeCoverageIgnoreStart
     */
    public function printFlatTree()
    {
        for ($i = 0; $i < count($this->flatTree); $i++) {
            printf("|%4d|", $i);
            foreach ($this->flatTree[$i] as $k => $v) {
                printf(" %s | %50s | \n", $k, $v);
            }
        }
    }

    public function printResultTree()
    {
        foreach ($this->resultTree as $key => $value) {
            printf("|%4d|", $key);
            foreach ($value as $k => $v) {
                //print(" ".$k." | ".$v."\n");
                printf(" %s | %50s | \n", $k, $v);
            }
        }
    }

    public function printTree()
    {
        echo "<pre>";
        print_r($this->tree);
        echo "</pre>";
    }
    /**
     * @codeCoverageIgnoreEnd
     */

    /**
     * @return string
     */
    public function getResultString()
    {
        return $this->resultString;
    }

    public function getNumOfIter()
    {
        return $this->iter;
    }
}