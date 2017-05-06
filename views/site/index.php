<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Каталог книг';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Добро пожаловать!</h1>

        <p>
            <?= Html::a('Перейти в каталог', ['book/index'], ['class' => 'btn btn-lg btn-primary']) ?>
        </p>
    </div>
</div>
