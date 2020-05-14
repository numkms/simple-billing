<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200509_142121_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(16)->notNull()->unique()->comment("Логин пользователя"),
            'auth_key' => $this->string()->comment("Ключ аутентификации"),
            'access_token' => $this->string()->comment("Токен доступа"),
            'balance' => $this->decimal(10,2)->comment("Баланс пользователя")->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
