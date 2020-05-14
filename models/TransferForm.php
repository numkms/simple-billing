<?php

namespace app\models;

use app\services\billing\BillingService;
use app\services\billing\repository\BillingSqlRepository;
use yii\base\Model;
use Yii;

class TransferForm extends Model {
    public $nickname;
    public $amount;

    public function rules()
    {
        return [
            ['nickname', 'string'],
            ['amount', 'number'],
            [['amount', 'nickname'], 'required']
        ];
    }

    public function transfer(): bool {
        $fromUser = Yii::$app->user->identity;
        $targetUser = $this->getTargetUser();

        if (!$targetUser) {
            $this->addError('nickname', 'Can not find nickname to transfer');
        }

        if($fromUser && $targetUser) {
            $service = new BillingService(new BillingSqlRepository());
            try {
                $service->makeTransfer($fromUser, $targetUser, $this->amount);
            } catch (\Exception $e) {
                $this->addError('amount', $e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }

    public function getTargetUser() {
        return User::findOne(['login' => $this->nickname]);
    }
}
