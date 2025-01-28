<?php

require __DIR__ . '/../vendor/autoload.php';

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use TelegramDownloaderBot\Core\Config;
use TelegramDownloaderBot\Core\TelegramClient;

try {
    $client = new TelegramClient();
    $telegram = $client->getTelegram();

    // Enable request limiter
    $telegram->enableLimiter();

    // Set custom Download and Upload paths
    $telegram->setDownloadPath(Config::get('download_path'));
    $telegram->setUploadPath(Config::get('upload_path'));

    // Add commands path
    $telegram->addCommandsPath(__DIR__ . '/Commands');

    // Handle telegram webhook request
    $telegram->handle();

} catch (TelegramException $e) {
    // Log telegram errors
    error_log("Telegram Error: " . $e->getMessage());
    
    // For debugging
    if (strpos($e->getMessage(), 'Invalid response') !== false) {
        error_log("Response Details: " . print_r(Request::getLastResponse(), true));
    }
} catch (\Exception $e) {
    // Log other errors
    error_log("General Error: " . $e->getMessage());
}