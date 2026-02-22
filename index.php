<?php
require_once 'src/SavingsAccount.php';
require_once 'src/CurrentAccount.php';
// -----------------------------------
// Testing the System 
// -----------------------------------

echo "Bank System OOP Demo \n";

$savings = new SavingsAccount("Ahmed Mohamed", 1000);

$savings->withdraw(20);
$savings->withdraw(200);
echo $savings->info . "\n";
echo $savings . "\n";