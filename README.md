mod-comments
===========
Module for Pixelion CMS

[![Latest Stable Version](https://poser.pugx.org/panix/mod-comments/v/stable)](https://packagist.org/packages/panix/mod-comments) [![Total Downloads](https://poser.pugx.org/panix/mod-comments/downloads)](https://packagist.org/packages/panix/mod-comments) [![Monthly Downloads](https://poser.pugx.org/panix/mod-comments/d/monthly)](https://packagist.org/packages/panix/mod-comments) [![Daily Downloads](https://poser.pugx.org/panix/mod-comments/d/daily)](https://packagist.org/packages/panix/mod-comments) [![Latest Unstable Version](https://poser.pugx.org/panix/mod-comments/v/unstable)](https://packagist.org/packages/panix/mod-comments) [![License](https://poser.pugx.org/panix/mod-comments/license)](https://packagist.org/packages/panix/mod-comments)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist panix/mod-comments "*"
```

or add

```
"panix/mod-comments": "*"
```

to the require section of your `composer.json` file.

Add to web config.
```
'modules' => [
    'comments' => ['class' => 'panix\mod\comments\Module'],
],
```

