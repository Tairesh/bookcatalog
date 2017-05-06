<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $time
 * @property string $ip
 * @property integer $userId
 * @property integer $eventType
 * @property string $eventData
 * 
 * @property User $user
 * @property string $userName
 * @property string $eventName
 */
class Log extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'ip', 'userId', 'eventType'], 'required'],
            [['time', 'userId', 'eventType'], 'integer'],
            [['ip', 'eventData'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Время',
            'ip' => 'IP',
            'userId' => 'ID пользователя',
            'userName' => 'Пользователь',
            'eventType' => 'Тип события',
            'eventName' => 'Название события',
            'eventData' => 'Данные события',
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    public function userName()
    {
        return $this->user->username;
    }
    
    public function getEventName()
    {
        switch ((int)$this->eventType) {
            case EventType::LOGIN:
                return 'Авторизация';
            case EventType::LOGOUT:
                return 'Выход';
            case EventType::BOOK_CREATED:
                return 'Книга добавлена';
            case EventType::BOOK_UPDATED:
                return 'Книга изменена';
            case EventType::BOOK_DELETED:
                return 'Книга удалена';
        }
    }
}
