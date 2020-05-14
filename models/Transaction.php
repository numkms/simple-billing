<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $operation_id Идентификатор операции
 * @property int|null $type 0 - списание, 1 - зачисление
 * @property float|null $sum Сумма
 *
 * @property Operation $operation
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }


    public function getTypeAsString() {
        return $this->type == 0 ? "Credit" : "Debit";
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['operation_id'], 'required'],
            [['operation_id', 'type'], 'integer'],
            [['sum'], 'number'],
            [['operation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Operation::className(), 'targetAttribute' => ['operation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation_id' => 'Идентификатор операции',
            'type' => '0 - списание, 1 - зачисление',
            'sum' => 'Сумма',
        ];
    }

    /**
     * Gets query for [[Operation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperation()
    {
        return $this->hasOne(Operation::className(), ['id' => 'operation_id']);
    }
}
