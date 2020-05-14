<?php

namespace app\services\billing;

use app\models\User;
use app\services\billing\repository\BillingRepositoryInterface;
use yii\base\Component;
use yii\base\Exception;

class BillingService extends Component {

    public $minimumBalancePoint = -1000;

    private $repository;

    public function __construct(BillingRepositoryInterface $repository, $config = []) {
        $this->repository = $repository;
    }

    /**
     * @param User $from
     * @param User $to
     * @param float $amount
     * @throws Exception
     */
    public function makeTransfer(User $from, User $to, float $amount) {
        if ($this->isAvailableToTransfer($from, $to, $amount)) {
            $this
            ->repository
            ->beginOperation($from, $to)
            ->creditFromUser($amount)
            ->debitToUser($amount)
            ->endOperation();
        }
    }

    /**
     * @param User $from
     * @param User $to
     * @param float $amount
     * @return bool
     * @throws Exception
     */
    private function isAvailableToTransfer(User $from, User $to, float $amount) {

        $fromUserbalanceAfterTransaction = $from->balance - $amount;

        if($amount <= 0) {
            throw new Exception("Can not transfer money cause amount less than zero or equal zero");
        }

        if ($from->id == $to->id) {
            throw new Exception("Can not transfer money cause target user same with sender");
        }

        if ($fromUserbalanceAfterTransaction < $this->minimumBalancePoint) {
            throw new Exception("Can not transfer money cause balance cause insufficient funds");
        }

        return true;
    }
}
