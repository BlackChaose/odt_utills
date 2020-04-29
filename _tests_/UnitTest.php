<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 24.10.19
 * Time: 11:32
 */
use PHPUnit\Framework\TestCase;
use OdtHelper\xmlTreeSearch;
use OdtHelper\TemplateRepair;

class StackTest extends TestCase
{
    public function testclearFlatTree()
    {
        $testSrc = "<root> txt <tag1> txt2 <tag2 /><text:p2 abc4>Тигра [var8;block=tbs:<page:break />row;mag<text:p abx>net=row</text:p>]!</text:p2> txt3 <tag3 /><text:p>[var0;block=tbs:row;magnet=row]</text:p> </tag1> txt4  <tagx><tagxx><tagxxx><text:p>Кенгуру [var3;block=tbs:<page:break />row;mag<text:p abx>net=row</text:p>]!</text:p></tagxxx></tagxx></tagx><text:p>[var1;block=tbs:row;magnet=row]</text:p></root>";
        $testModel = "<root> txt <tag1> txt2 <tag2 /><text:p2 abc4>Тигра [var8;block=tbs:row;magnet=row]!<page:break /><text:p abx></text:p></text:p2> txt3 <tag3 /><text:p>[var0;block=tbs:row;magnet=row]</text:p> </tag1> txt4  <tagx><tagxx><tagxxx><text:p>Кенгуру [var3;block=tbs:row;magnet=row]!<page:break /><text:p abx></text:p></text:p></tagxxx></tagxx></tagx><text:p>[var1;block=tbs:row;magnet=row]</text:p></root>";

        $prs = new xmlTreeSearch($testSrc);

        $prs->initTree();

        $prs->clearFlatTree();

        $this->assertSame(6,$prs->getNumOfIter());

        $this->assertSame($testModel, $prs->getResultString());

    }
    public function testTemplateRepair()
    {
        $inputSrc = "test_tpl/test.odt";
        $outputSrc = "out/test.odt";
        $etalon = "файл " . $outputSrc . " пересобран. Поломанных шаблонов TBS найдено и исправлено: 4";
        $this->assertEquals($etalon, TemplateRepair::repair($inputSrc,$outputSrc));
    }
}