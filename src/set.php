<?php

require __DIR__ . '/../vendor/autoload.php';

use TelegramFileDownloaderBot\Core\Config;
use TelegramFileDownloaderBot\Core\TelegramClient;

try {
    $client = new TelegramClient();
    
    // Set webhook
    $result = $client->getTelegram()->setWebhook(Config::get('webhook_url'));
    
    if ($result->isOk()) {
        echo "✅ Webhook has been set successfully!\n";
        echo "URL: " . Config::get('webhook_url') . "\n";
    } else {
        echo "❌ Failed to set webhook: " . $result->getDescription() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 
