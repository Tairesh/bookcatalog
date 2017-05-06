<?php

namespace app\models;

/**
 * Перечисление типов логгируемых событий
 */
abstract class EventType
{
    
    const LOGIN = 1;
    const LOGOUT = 2;
    const BOOK_CREATED = 3;
    const BOOK_UPDATED = 4;
    const BOOK_DELETED = 5;
    
}
