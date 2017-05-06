<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $count integer */

$this->title = 'События отменены';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Было отменено <?= $count ?> событий.</p>
    
</div>
