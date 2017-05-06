<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use branchonline\lightbox\Lightbox;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту книгу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'authorName:ntext',
            'title:ntext',
            'year',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model app\models\Book */
                    return Lightbox::widget([
                                'files' => [
                                    [
                                        'thumb' => $model->getImageUrl('image', 'preview'),
                                        'original' => $model->getImageUrl('image'),
                                        'title' => $model->title,
                                    ],
                                ]
                    ]);
                },
            ],
            'isAvailable:boolean',
        ],
    ]) ?>

</div>
