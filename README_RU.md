# OpenCart Персонализованные шаблоны Pro
[![License: GPLv3](https://img.shields.io/badge/license-GPL%20V3-green?style=plastic)](LICENSE)

Расширение может подменять любой tpl / twig файл в движке, в том числе tpl / twig файлы любых сторонних модулей. Обладает расширенным набором условий для подмены шаблонов.

## Другие языки

* [English](README.md)

## Лог изменений

* [CHANGELOG_RU.md](docs/CHANGELOG_RU.md)

## Скриншоты

* [SCREENSHOTS.md](docs/SCREENSHOTS.md)

## Преимущества

* Использует механизм событий, работает без внедрения в файлы. 
* Совместим с 99% тем оформления.
* Не влияет на производительность. Расширение используется только тогда, когда надо. Ненужные вычисления не проводятся.
* Дружественный интерфейс.

## Возможности

Расширение позволит:

* подменить шаблон для страниц выбранных товаров / категорий / производителей / информационных страниц / товаров в выбранных категориях / товаров выбранных производителей и у любых модулей, располагающихся на них;
* подменить шаблон для мобильных, планшетов, десктопов / устройств Apple, Android / для операционных систем Windows, MacOS, Linux / для браузеров Chrome, Firefox, Opera и так далее;
* подменить шаблон для выбранных языков / валют / групп пользователей / магазинов;
* подменить шаблон в зависимости от диапазона стоимости корзины / веса, кол-ва товаров;
* подменить шаблон в зависимости от указанных $_GET параметров;

## Доступные фильтры

Для любых страниц:

* группы пользователей;
* языки;
* валюты;
* магазины (поддерживает мультистор);
* мобильные устройства (Мобильные, Планшеты, Десктопы);
* платформы (Windows, macOS, Linux, etc);
* браузеры (Chrome, Firefox, Opera, etc);
* корзина - диапазоны (подытог, итого, вес, кол-во товаров);
* настраиваемые $_GET параметры.

Дополнительные фильтры для отдельных страниц:

* Страница товара
    * выбранные товары;
    * товары из выбранных категорий;
    * товары выбранных производителей.
* Страница категории
    * выбранные категории.
* Страница производителя
    * выбранные производители.
* Информационная страница
    * выбранные информационные страницы.

Можно комбинировать все эти параметры. Например, подменить шаблон extension/module/featured у модуля "Рекомендуемые товары" на extension/module/featured_apple, только когда модуль располагается на странице товаров производителя "Apple" при валюте USD.

## Совместимость

* OpenCart 2.3, 3.x, 4.x.
* Совместим с 99% тем оформления.

## Демо

Админка 

* [https://custom-templates-pro.shtt.blog/admin/](https://custom-templates-pro.shtt.blog/admin/) (автовход)

Каталог 

* [https://custom-templates-pro.shtt.blog/](https://custom-templates-pro.shtt.blog/)

На демо сайте есть верхнее меню для быстрой навигации.

## Описание демо

В демо подменяются 9 шаблонов:

1. для[ главной](https://custom-templates-pro.shtt.blog/index.php?route=common/home), для настольных ПК (Desktop);
2. для категорий[ Windows](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_45),[ PC](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=20_26),[ MP3 Плееры](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=34);
3. для производителей[ Apple](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=8),[ Hewlett-Packard](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=7),[ Sony](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=10), для языков Russian;
4. для товаров из категории[ Windows](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_45),[ Macs](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_46);
5. для товаров производителей[ Canon](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=30);
6. для выбранных товаров -[ Apple Cinema 30](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=42),[ HP LP3065](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=47);
7. шаблон модуля "Рекомендуемые товары" для товаров производителей[ Canon](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=30),[ Palm](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=6);
8. шаблон модуля "Рекомендуемые товары" для страниц категорий[ Windows](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_45),[ Macs](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_46);
9. шаблон модуля "Рекомендуемые товары" для группы пользователей "[Гость](https://custom-templates-pro.shtt.blog/index.php?route=common/home)".

## Установка

* Установите расширение через стандартный раздел установки дополнений.
* Перейдите в раздел модулей и установите модуль "Custom templates Pro".

## Руководство

#### Нюансы

* Пути к шаблонам указываются без расширения файла. Например, common/home или extension/module/featured.
* Шаблон, который нужно заменить назначается в первом всплывающем окне. Шаблон, который его заменит, во втором (в форме с условиями). 
* Будьте внимательны, не забывайте о сторонних модификаторах, которые вносят изменения в файлы шаблонов. В модуле есть функция показа списка модификаторов, которые вносят изменения в файл заменяемого шаблона. Это значительно облегчает поиск нужных модификаторов. Рекомендую использовать любой удобный редактор модификаций для быстрой правки модификаторов.

#### Пример

Допустим, нам нужно подменить шаблон определенного товара (product.twig) на свой (product_showcase.twig). Для этого:

* создаем копию файла catalog/view/theme/наша-тема/product/product.twig и называем ее product_showcase.twig;
* открываем product_showcase.twig и вносим необходимые нам изменения;
* переходим на страницу расширения "Custom templates Pro" и создаем новую замену. В первом всплывающем окне вводим product/product, во втором; product/product_showcase и выбираем нужные нам условия или товары, у которых нужно подменить шаблон;
* сохраняем настройки. 

## Лицензия

* [GPL v3.0](LICENSE.MD)

## Спасибо за использование моих дополнений!

Я решил сделать все свои OpenCart-дополнения бесплатными и с открытым исходным кодом, чтобы они могли приносить пользу сообществу. Разработка, поддержка и обновление этих дополнений требуют времени и усилий.

Если мои дополнения помогли вам в вашем проекте, и вы хотите поддержать мою работу, я буду благодарен за любую сумму пожертвований.

### 💙 Поддержать меня можно через:

* [PayPal](https://paypal.me/TalgatShashakhmetov?country.x=US&locale.x=en_US)
* [CashApp](https://cash.app/$TalgatShashakhmetov)

Ваше участие мотивирует меня продолжать развивать и улучшать эти инструменты. Спасибо за вашу поддержку!
