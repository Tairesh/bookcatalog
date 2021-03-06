<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\ImageBehaviorBlockDelete;

/**
 * Книга. Таблица `books`
 *
 * @property integer $id
 * @property string $authorName
 * @property string $title
 * @property integer $year
 * @property string $image
 * @property boolean $isAvailable
 */
class Book extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'books';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['authorName', 'title', 'year', 'isAvailable'], 'required'],
            [['authorName', 'title'], 'string'],
//            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg'],
            [['year'], 'integer'],
            [['isAvailable'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'authorName' => 'Имя автора',
            'title' => 'Название',
            'year' => 'Год издания',
            'image' => 'Обложка',
            'isAvailable' => 'В наличии',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => ImageBehaviorBlockDelete::className(),
                'savePathAlias' => '@app/web/images/',
                'urlPrefix' => '/images/',
                'crop' => true,
                'attributes' => [
                    'image' => [
                        'crop' => false,
                        'thumbnails' => [
                            'preview' => [
                                'width' => 50,
                                'height' => 50,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

}
