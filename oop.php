<?php
# 1. INTERFACE
interface AccountInterface
{
    public function deposit(float $amount): void;
    public function withdraw(float $amount): void;
    public function getBalance(): float;
}

# 2. TRAIT
trait LoggerTrait
{
    public function log(string $message): void
    {
        echo "[Log]: " . $message . " at " . date('Y-m-d H:i:s') . "\n";
    }
}

# 3. ABSTRACT CLASS | METHOD & ENCAPSULATION
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

# 4 . INHERITANCE & POLYMORPHISM
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

class CurrentAccount extends BankAccount
{
    public function calculateInterest(): float
    {
        return 0;
    }
}

// -----------------------------------
// Testing the System 
// -----------------------------------

echo "Bank System OOP Demo \n";

$savings = new SavingsAccount("Ahmed Mohamed", 1000);

$savings->withdraw(20);
$savings->withdraw(200);
echo $savings->info . "\n";
echo $savings . "\n";