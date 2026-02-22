<?php
require_once 'BankAccount.php';
class SavingsAccount extends BankAccount
{
    private float $interestRate = 0.05;

    public function withdraw($amount): void
    {
        if ($amount < 100) {
            echo "Cannot withdraw less than 100$ from Savings Account \n";
            return;
        }

        parent::withdraw($amount);
    }

    public function calculateInterest(): float
    {
        return $this->getBalance() * $this->interestRate;
    }
}