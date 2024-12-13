# OpenCart Custom templates Pro
[![License: GPLv3](https://img.shields.io/badge/license-GPL%20V3-green?style=plastic)](LICENSE)

The extension can replace any tpl / twig file in the engine, including tpl / twig files of any third-party modules. It has an extended set of conditions for replacing templates. For example, this module can replace product/product.twig template with product/product_showcase.twig without file replacement.

## Other Languages

* [Russian](README_RU.md)

## Change Log

* [CHANGELOG.md](docs/CHANGELOG.md)

## Screenshots

* [SCREENSHOTS.md](docs/SCREENSHOTS.md)

## Advantages

* Uses the event mechanism, works without injection into files.
* Compatible with 99% of themes.
* Doesn't affect performance. The extension is used only when needed. Unnecessary calculations are not carried out.
* User friendly interface.

## Features

The extension will allow:

* change the template for pages of selected products / categories / manufacturers / information pages / products in selected categories / products of selected manufacturers and for any modules located on them;
* replace the template for mobile, tablets, desktops / Apple devices, Android / for operating systems Windows, macOS, Linux / for browsers Chrome, Firefox, Opera and so on;
* change the template for selected languages / currencies / user groups / stores;
* change the template depending on the range of the basket cost / weight, number of goods;
* change the template depending on the specified $_GET parameters;

## Available filters

For any page:

* user groups;
* languages;
* currencies;
* shops (supports multistore);
* mobile devices (Mobile, Tablets, Desktops);
* platforms (Windows, macOS, Linux, etc);
* browsers (Chrome, Firefox, Opera, etc);
* cart - ranges (subtotal, total, weight, number of products in a cart);
* custom $_GET parameters.

Additional filters for individual pages:

* Product page
    * selected products;
    * products from selected categories;
    * products of selected manufacturers.
* Category page
    * selected categories.
* Manufacturer's page
    * selected manufacturers.
* Information page
    * selected information pages.

You can combine all of these options. For example, change the extension/module/featured template.twig of the "Featured" module to extension/module/featured_apple.twig only when the module is located on the product page of the manufacturer "Apple" in USD currency.

## Compatibility

* OpenCart 2.3, 3.x, 4.x.
* Compatible with 99% of themes.

## Demo

Admin

* [https://custom-templates-pro.shtt.blog/admin/](https://custom-templates-pro.shtt.blog/admin/) (autologin)

Catalog

* [https://custom-templates-pro.shtt.blog/](https://custom-templates-pro.shtt.blog/)

The demo site has a top menu for quick navigation.

## Description of the demo site

In the demo, 9 templates are replaced:

* for the [main](https://custom-templates-pro.shtt.blog/index.php?route=common/home), for desktop PCs (Desktop);
* for categories [Windows](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_45), [PC](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=20_26), [MP3 Players](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=34);
* for manufacturers [Apple](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=8), [Hewlett-Packard](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=7), [Sony](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=10), for Russian language;
* for products from the category [Windows](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_45), [Macs](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_46);
* for products manufactured by [Canon](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=30);
* for selected products - [Apple Cinema 30](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=42),[ HP LP3065](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=47);
* Module template "Recommended products" for Canon, [Canon](https://custom-templates-pro.shtt.blog/index.php?route=product/product&product_id=30),[ Palm](https://custom-templates-pro.shtt.blog/index.php?route=product/manufacturer/info&manufacturer_id=6);
* Module template "Recommended products" for category pages [Windows](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_45),[ Macs](https://custom-templates-pro.shtt.blog/index.php?route=product/category&path=18_46);
* Module template "Recommended products" for user group "Guest".

## Installation

* Install the extension through the standard extension installation section.
* Go to the modules section and install the "Custom templates Pro" module.

## Management

#### Nuances

* Paths to templates are specified without a file extension. For example common/home or extension/module/featured.
* The template to be replaced is assigned in the first pop-up window. The template that will replace it is in the second one (in the conditional form).
* Be careful not to forget about third-party modifications that make changes to template files. The module has a function to display a list of modifications that make changes to the template file being replaced. This makes it much easier to find the right modifiers. I recommend using any convenient modification editor for quick editing of modifiers.

#### Example

Let's say we need to replace the template of a certain product (product.twig) with our own (product_showcase.twig). For this:

* make a copy of catalog/view/theme/our-theme/product/product.twig and name it product_showcase.twig;
* open product_showcase.twig and make the changes we need;
* go to the "Custom templates Pro" extension page and create a new replacement. In the first pop-up window, enter product / product, in the second; product/product_showcase and select the conditions we need or products for which we need to change the template;
* save the settings.

If you have any questions, write to the support thread or private messages.

## License

* [GPL v3.0](LICENSE.MD)

## Thank You for Using My Extensions!

I have decided to make all my OpenCart extensions free and open-source to benefit the community. Developing, maintaining, and updating these extensions takes time and effort.

If my extensions have been helpful for your project and you’d like to support my work, any donation is greatly appreciated.

### 💙 You can support me via:

* [PayPal](https://paypal.me/TalgatShashakhmetov?country.x=US&locale.x=en_US)
* [CashApp](https://cash.app/$TalgatShashakhmetov)

Your support inspires me to keep improving and developing these tools. Thank you!
