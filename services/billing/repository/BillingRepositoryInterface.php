<?php

namespace app\services\billing\repository;

use app\models\User;

interface BillingRepositoryInterface {
    function beginOperation(User $from, User $to) : BillingRepositoryInterface;
    function creditFromUser(float $amount) : BillingRepositoryInterface;
    function debitToUser(float $amount) : BillingRepositoryInterface;
    function endOperation();
}
