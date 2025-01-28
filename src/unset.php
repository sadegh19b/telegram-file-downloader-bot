<?php

require __DIR__ . '/../vendor/autoload.php';

use TelegramDownloaderBot\Core\TelegramClient;

try {
    $client = new TelegramClient();
    
    // Unset webhook
    $result = $client->getTelegram()->deleteWebhook();

    if ($result->isOk()) {
        echo "✅ Webhook has been removed successfully!\n";
    } else {
        echo "❌ Failed to remove webhook: " . $result->getDescription() . "\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 