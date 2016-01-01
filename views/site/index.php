<?php

/* @var $this yii\web\View */
/* @var $model models\Einstein */

$this->title = 'Albert Says: "' . $model->currentSnippet . '"';
?>
<div class="site-index">

    <div class="jumbotron">
        <p><img class="quote-image" src="/images/einstein.png" /></p>
        <blockquote class="quote">
            <?php echo $model->currentQuote; ?>
            <p class="attribution">- Albert Einstein (<a href="http://en.wikiquote.org/wiki/Albert_Einstein" target="_blank">wikiquote</a>)</p>
        </blockquote>
    </div>
</div>
