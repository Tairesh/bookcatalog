<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

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
class Book extends ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'books';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['authorName', 'title', 'year', 'isAvailable'], 'required'],
            [['authorName', 'title', 'image'], 'string'],
            [['year'], 'integer'],
            [['isAvailable'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'authorName' => 'Имя автора',
            'title' => 'Название',
            'year' => 'Год издания',
            'image' => 'Обложка',
            'isAvailable' => 'В наличии',
        ];
    }
    
}
