<?php

use app\models\User as User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m200514_200654_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%operation}}', [
            'id' => $this->primaryKey(),
            'from_user_id' => $this->integer(),
            'to_user_id' => $this->integer(),
            'timestamp' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP")
        ]);

        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'operation_id' => $this->integer()->notNull()->comment("Идентификатор операции"),
            'type' => $this->boolean()->comment("0 - списание, 1 - зачисление"),
            'sum' => $this->decimal(10,2)->comment("Сумма"),
        ]);

        $this->addForeignKey(
            'fk-operation-from_user',
            '{{%operation}}',
            'from_user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'no action'
        );

        $this->addForeignKey(
            'fk-operation-to_user',
            '{{%operation}}',
            'to_user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'no action'
        );

        $this->addForeignKey(
            'fk-transaction-operation',
            '{{%transaction}}',
            'operation_id',
            '{{%operation}}',
            'id',
            'CASCADE',
            'no action'
        );
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transaction}}');
        $this->dropTable('{{%operation}}');
    }
}
