[![Build Status](https://travis-ci.org/BlackChaose/odt_utills.svg?branch=master)](https://travis-ci.org/BlackChaose/odt_utills) [![Maintainability](https://api.codeclimate.com/v1/badges/73655cfbb82b71146b14/maintainability)](https://codeclimate.com/github/BlackChaose/odt_utills/maintainability)[![Test Coverage](https://api.codeclimate.com/v1/badges/73655cfbb82b71146b14/test_coverage)](https://codeclimate.com/github/BlackChaose/odt_utills/test_coverage)

[![License](https://poser.pugx.org/nikita_kalitin/odt_utills/license)](https://packagist.org/packages/nikita_kalitin/odt_utills)[![Latest Unstable Version](https://poser.pugx.org/nikita_kalitin/odt_utills/v/unstable)](https://packagist.org/packages/nikita_kalitin/odt_utills)
---
### утилита для пересборки *.odt файлов ###
***при работе с шаблонами [OpenTBS](https://github.com/Skrol29/opentbs) часто возникают проблемы с поломкой файла шаблона. Добавляются стили и/или разделители старниц. Данная утилита чинит такой файл.***

* утилита: пересобирает xml в архиве (см. формат odt) 
* поэтому: могут сбиться стили после пересборки

---

используется: [tbszip](https://github.com/Skrol29/tbszip)

---
запуск в режиме консоли 

#### починка:

`php san_pb2.php -s "<path to input file>" -d "<path to output file>"`

#### чистка от разделителей страницы (<text:soft-page-break/>)

`php san_pb.php -s "<path to input file>" -d "<path to output file>"`

---

использование класса-обёртки

```
use OdtHelper\TemplateRepair;

    /**
     * @param $path_to_inputfile
     * @param $path_to_outputfile
     * @return string
     */

    TemplateRepair::repair($path_to_inputfile, $path_to_outputfile);
```
---

поломки типа:

`<text:p text:style-name="P1">[krit_w.1.
        <text:span text:style-name="T13">name_krit]</text:span>
</text:p>`

`<text:p text:style-name="P1">К[krit_w.4.nu<text:soft-page-break/>m_pok_plus_krit]
</text:p>`

 `<text:p text:style-name="P1">К[krit_w.10.n<text:soft-page-break/>um_pok_plus_krit]
 </text:p>`
 
 результат починки:

`<text:p text:style-name="P1">[krit_w.1.name_krit]<text:span text:style-name="T13"></text:span>
</text:p>`

`<text:p text:style-name="P1">К[krit_w.4.num_pok_plus_krit]<text:soft-page-break/>
</text:p>`

 `<text:p text:style-name="P1">К[krit_w.10.num_pok_plus_krit]<text:soft-page-break/>
 </text:p>`
