<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

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
 * @property string $eventInfo
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
            [['ip', 'userId', 'eventType'], 'required'],
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
            'eventName' => 'Событие',
            'eventData' => 'Данные события',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'time',
                'updatedAtAttribute' => false,
            ],
        ];
    }


    public function getUser()
    {
        return User::findIdentity($this->userId);
    }
    
    public function getUserName()
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
    
    private function dataInline($data) {
        $res = [];
        foreach ($data as $attr => $val) {
            if ($attr === 'isAvailable') {
                $val = $val ? 'Да' : 'Нет';
            }
            $res[] = (new Book())->getAttributeLabel($attr).': '.$val;
        }
        return join(', ', $res);
    }
    
    public function getEventInfo()
    {
        $data = $this->eventData ? json_decode($this->eventData) : [];
        switch ((int)$this->eventType) {
            case EventType::LOGIN:
            case EventType::LOGOUT:
                return '';
                
            case EventType::BOOK_CREATED:
                return 'Добавлена книга: '. $this->dataInline($data);
            case EventType::BOOK_UPDATED:
                return 'Книга изменена. Было: '.$this->dataInline($data->before).'. Стало: '.$this->dataInline($data->after);
            case EventType::BOOK_DELETED:
                return 'Удалена книга: '. $this->dataInline($data);
        }
    }
}
