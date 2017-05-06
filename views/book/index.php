<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use branchonline\lightbox\Lightbox;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
    <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
<?php Pjax::end(); ?></div>
