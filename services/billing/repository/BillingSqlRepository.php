<?php

namespace app\services\billing\repository;

use app\models\Operation;
use app\models\Transaction;
use app\models\User;
use yii\base\Exception;

/**
 * Class BillingSqlRepository
 * @package app\services\billing\repository
 * @property Transaction[]  $transactions
 * @property Operation $operation
 * @property User $userFrom
 * @property User $userTo
 */
class BillingSqlRepository implements BillingRepositoryInterface {

    private $userFrom;
    private $userTo;
    private $operation;
    private $transactions;

    public function beginOperation(User $from, User $to): BillingRepositoryInterface
    {
        $this->operation = new Operation([
            'from_user_id' => $from->id,
            'to_user_id' => $to->id,
        ]);

        $this->userFrom = $from;
        $this->userTo = $to;

        return $this;
    }

    public function debitToUser(float $amount): BillingRepositoryInterface
    {
        $this->checkOperationExist();

        $this->transactions[] = new \app\models\Transaction([
            'sum' => $amount,
            'type' => 1,
        ]);

        $this->userTo->balance += $amount;

        return $this;
    }

    public function creditFromUser(float $amount): BillingRepositoryInterface
    {
        $this->checkOperationExist();

        $this->transactions[] = new \app\models\Transaction([
            'sum' => $amount,
            'type' => 0,
        ]);

        $this->userFrom->balance -= $amount;

        return $this;
    }

    public function endOperation()
    {
        $this->checkOperationExist();

        $dbTransaction = \Yii::$app->db->beginTransaction();
        try {
            $this->operation->save();
            $this->saveTransactions();
            $this->saveUserBalances();
        } catch (\Exception $exception) {
            $dbTransaction->rollBack();
            throw new Exception("Sorry. We are can not transform your money now cause of database issues");
        }

        $dbTransaction->commit();
    }

    private function saveTransactions() {
        $this->checkOperationExist();

        foreach ($this->transactions as $transaction) {
            $transaction->link('operation', $this->operation);
            $transaction->save();
        }
    }

    private function saveUserBalances() {
        $this->checkOperationExist();

        $this->userFrom->save();
        $this->userTo->save();
    }

    private function checkOperationExist() {
        if (!$this->operation) {
            throw new Exception("Please call beginPperation before use endOperation()");
        }
    }

}
