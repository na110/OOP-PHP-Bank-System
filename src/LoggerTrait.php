<?php
trait LoggerTrait
{
    public function log(string $message): void
    {
        echo "[Log]: " . $message . " at " . date('Y-m-d H:i:s') . "\n";
    }
}