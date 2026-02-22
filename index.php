<?php

// 1. INTERFACE
// بنعرف عقد (Contract) بأن أي حساب لازم يimplement الدوال دي
interface AccountInterface
{
    public function deposit(float $amount): void;
    public function withdraw(float $amount): void;
    public function getBalance(): float;
}

// 2. TRAIT
// Trait بنستخدمه لإضافة وظيفة (Log) لأي كلاس من غير ما نعمل Inherit
trait LoggerTrait
{
    public function log(string $message): void
    {
        echo "[Log]: " . $message . " at " . date('Y-m-d H:i:s') . "<br>";
    }
}

// 3. ABSTRACT CLASS & ENCAPSULATION
// كلاس مجرد (مينفعع عمل منه Object) بيحتوي على المشتركات
abstract class BankAccount implements AccountInterface
{
    // Encapsulation: Properties خاصة ومينفعش يتوصلها من بره غير عن طريق Getters/Setters
    private string $accountNumber;
    private float $balance;
    private string $ownerName;

    // STATIC: متغير ثابت يحسب عدد الحسابات اللي اتعملت (خاص بالكلاس مش للأوبجكت)
    protected static int $totalAccounts = 0;

    // MAGIC METHOD: __construct بيتنفذ لما نعمل new Object
    public function __construct(string $ownerName, float $initialBalance = 0)
    {
        $this->ownerName = $ownerName;
        $this->balance = $initialBalance;
        // توليد رقم حساب عشوائي
        $this->accountNumber = uniqid('ACC-');

        // زيادة عدد الحسابات
        self::$totalAccounts++;

        // استخدام الـ Trait
        $this->log("Account created for {$this->ownerName}");
    }

    // Getter: طريقة آمنة لقراءة البيانات
    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    // Static Method: بيرجع عدد الحسابات الكلي
    public static function getTotalAccounts(): int
    {
        return self::$totalAccounts;
    }

    // Implementation of Interface
    public function deposit(float $amount): void
    {
        if ($amount > 0) {
            $this->balance += $amount;
            $this->log("Deposited {$amount}$ to {$this->accountNumber}");
        }
    }

    public function withdraw(float $amount): void
    {
        if ($amount > 0 && $this->balance >= $amount) {
            $this->balance -= $amount;
            $this->log("Withdrew {$amount}$ from {$this->accountNumber}");
        } else {
            $this->log("Failed withdrawal attempt from {$this->accountNumber} (Insufficient funds)");
        }
    }

    // MAGIC METHOD: __toString
    // بيسمح لنا نطبع الأوبجكت مباشرة كـ string
    public function __toString()
    {
        return "Account: {$this->accountNumber} | Owner: {$this->ownerName} | Balance: {$this->balance}$";
    }

    // MAGIC METHOD: __get
    // بيتنفذ لما نحاول نaccess property غير موجودة أو private
    public function __get(string $name)
    {
        if ($name === 'info') {
            return $this->accountNumber . " - " . $this->ownerName;
        }
        return "Property [{$name}] is not accessible.";
    }

    // Abstract Method: إجبار الكلاسات اللي وارثة انها تعرف الدالة دي
    abstract public function calculateInterest(): float;
}

// 4. INHERITANCE & POLYMORPHISM
// حساب التوفير (Savings Account)
class SavingsAccount extends BankAccount
{
    private float $interestRate = 0.05; // 5% فائدة

    // Overriding: إعادة تعريف سلوك السحب (مثلاً نمنع السحب لو أقل من 100 جنيه)
    public function withdraw(float $amount): void
    {
        if ($amount < 100) {
            echo "Cannot withdraw less than 100$ from Savings Account.<br>";
            return;
        }
        // بنستدعي دالة السحب الأصلية (Parent)
        parent::withdraw($amount);
    }

    // Implementation of Abstract Method
    public function calculateInterest(): float
    {
        return $this->getBalance() * $this->interestRate;
    }
}

// حساب جاري (Current Account)
class CurrentAccount extends BankAccount
{
    // Overriding: السحب في الحساب الجاري عادي جداً
    public function withdraw(float $amount): void
    {
        // هنا ممكن نضيف خاصية الـ Overdraft (السحب على المكشوف)
        parent::withdraw($amount);
    }

    // Implementation of Abstract Method
    public function calculateInterest(): float
    {
        return 0; // الحساب الجاري لا يوجد عليه فائدة
    }
}

// -----------------------------------
// Testing the System (Execution)
// -----------------------------------

echo "<h2>Bank System OOP Demo</h2>";

// إنشاء حساب توفير
$savings = new SavingsAccount("Ahmed Mohamed", 1000);
// Polymorphism: الدالة withdraw اتعاملت بطريقة مختلفة هنا
$savings->withdraw(50); // هيرفض لأنه أقل من 100
$savings->withdraw(200); // هيقبل

echo "Interest for Ahmed: " . $savings->calculateInterest() . "$<br>";
echo "<hr>";

// إنشاء حساب جاري
$current = new CurrentAccount("Sara Ali", 5000);
$current->deposit(500);
$current->withdraw(1000); // Polymorphism: السحب عادي جداً هنا

echo "Interest for Sara: " . $current->calculateInterest() . "$<br>";
echo "<hr>";

// استخدام الـ Magic Method __toString
echo "Account Details: " . $savings . "<br>";

// استخدام الـ Magic Method __get
echo "Dynamic Info: " . $savings->info . "<br>";
echo "<hr>";

// Static Property usage
echo "Total Bank Accounts Created: " . BankAccount::getTotalAccounts() . "<br>";

?>