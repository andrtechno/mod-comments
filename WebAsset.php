<?php

namespace panix\mod\comments;

use yii\web\AssetBundle;

class WebAsset extends AssetBundle {

    public $sourcePath = __DIR__.'/assets';

    public $jsOptions = array(
        'position' => \yii\web\View::POS_BEGIN
    );
    public $css = [
        'css/comments.css',
    ];

}
