<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\ThemeAsset;

AppAsset::register($this);
ThemeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <ul class="navbar-nav nav">
        <li>
            &copy; <a href="https://www.twitter.com/CGFIndies" target="_blank">@CGFIndies</a> <?= date('Y') ?>
        </li>
        <li>
            <a rel="license" href="http://creativecommons.org/licenses/by-sa/2.0/" target="_blank">
                <img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/2.0/80x15.png" />
            </a>
            Albert Einstein image modified from:
            <a href="https://www.flickr.com/photos/8011986@N02/15125062494/" target="_blank">iPhone 6 Plus Einstein Wallpaper</a> by
            <a href="https://www.flickr.com/photos/8011986@N02/" target="_blank">Bill Broooks</a>
        </li>
        <li>
            <?= Yii::powered() ?>
        </li>
    </ul>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
