<?php
require_once "BankAccount.php";
class CurrentAccount extends BankAccount
{
    public function calculateInterest(): float
    {
        return 0;
    }
}