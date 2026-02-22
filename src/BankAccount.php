<?php
require_once 'AccountInterface.php';
require_once 'LoggerTrait.php';
abstract class BankAccount implements AccountInterface
{
    use LoggerTrait;
    private string $accountNumber;
    private float $balance;
    private string $ownerName;

    protected static int $totalAccounts = 0;

    public function __construct(string $ownerName, float $initialBalance = 0)
    {
        $this->ownerName = $ownerName;
        $this->balance = $initialBalance;
        $this->accountNumber = uniqid("ACC-");

        self::$totalAccounts++;

        $this->log("Account created for: {$this->ownerName}");
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public static function getTotalAccounts(): int
    {
        return self::$totalAccounts;
    }

    public function deposit(float $amount): void
    {
        if ($amount > 0) {
            $this->balance += $amount;
            $this->log("Deposited: $amount$ to {$this->accountNumber}");
        } else {
            $this->log("Failed deposit attempt to {$this->accountNumber} (Invalid amount)");
        }
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= $this->balance && $amount > 0) {
            $this->balance -= $amount;
            $this->log("Withdraw: $amount$ from {$this->accountNumber}");
        } else {
            $this->log("Failed withdrawal attempt from {$this->accountNumber} (Insufficient funds)");
        }
    }

    public function __toString()
    {
        return "Account: $this->accountNumber | Owner: $this->ownerName | Balance: $this->balance";
        ;
    }

    public function __get(string $name)
    {
        if ($name === "info") {
            return $this->accountNumber . " - " . $this->ownerName;
        }
    }

    abstract public function calculateInterest(): float;
}