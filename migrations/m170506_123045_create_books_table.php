<?php

use yii\db\Migration;

/**
 * Handles the creation of table `books`.
 */
class m170506_123045_create_books_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('books', [
            'id' => $this->primaryKey(),
            'authorName' => 'TEXT NOT NULL',
            'title' => 'TEXT NOT NULL',
            'year' => 'INTEGER(4) NOT NULL',
            'image' => 'TEXT DEFAULT NULL',
            'isAvailable' => 'BOOLEAN NOT NULL',
        ]);
        $this->createIndex('booksAuthor', 'books', ['authorName']);
        $this->createIndex('booksAvailable', 'books', ['isAvailable']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('books');
    }
}
