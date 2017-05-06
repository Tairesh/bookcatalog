<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Log;
use app\models\EventType;
use app\models\Book;

/**
 * 
 */
class Logger extends Component
{
    
    /**
     * Логгирует действие
     * @param integer $eventType
     * @param array $eventData
     * @return boolean
     */
    public function log(int $eventType, $eventData = [])
    {
        $event = new Log([
            'ip' => Yii::$app->request->userIP,
            'userId' => Yii::$app->user->id,
            'eventType' => $eventType,
            'eventData' => count($eventData) > 0 ? json_encode($eventData) : null,
        ]);
        return $event->save();
    }
    
    /**
     * Авторизация пользователя
     * @return boolean
     */
    public function login()
    {
        return $this->log(EventType::LOGIN);
    }
    
    /**
     * Выход пользователя
     * @return boolean
     */
    public function logout()
    {
        return $this->log(EventType::LOGOUT);
    }
    
    /**
     * Создана книга
     * @param Book $model
     * @return boolean
     */
    public function bookCreated($model)
    {
        return $this->log(EventType::BOOK_CREATED, $model->attributes);
    }
    
    /**
     * Книга изменена
     * @param Book $model
     * @return boolean
     */
    public function bookUpdated($model)
    {
        return $this->log(EventType::BOOK_UPDATED, [
            'before' => $model->oldAttributes,
            'after' => $model->attributes
        ]);
    }
    
    /**
     * Удалена книга
     * @param Book $model
     * @return boolean
     */
    public function bookDeleted($model)
    {
        return $this->log(EventType::BOOK_DELETED, $model->attributes);
    }
    
}
