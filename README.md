### тестовая утилита для чистки *.odt файлов ###
***при работе с шаблонами OpenTBS часто возникают проблемы с поломокой файла шаблона. Добавляются стили и/или разделители старниц. Данная утилита чинит такой файл.***

* утилита в процесее разработки
* утилита: пересобирает xml в архиве (см. формат odt) 
* поэтому: могут сбиться стили после пересборки

---

запуск: 

`php san_pb2.php -s "<path to input file>" -d "<path to output file>"`

---

поломки типа:

`<text:p text:style-name="P1">[krit_w.1.
        <text:span text:style-name="T13">name_krit]</text:span>
</text:p>`

`<text:p text:style-name="P1">К[krit_w.4.nu<text:soft-page-break/>m_pok_plus_krit]
</text:p>`

 `<text:p text:style-name="P1">К[krit_w.10.n<text:soft-page-break/>um_pok_plus_krit]
 </text:p>`
