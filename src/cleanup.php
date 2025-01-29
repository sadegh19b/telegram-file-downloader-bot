<?php

require __DIR__ . '/../vendor/autoload.php';

use TelegramFileDownloaderBot\Core\Config;

// Load configuration
Config::load();

$downloadPath = Config::get('download_path');

// Delete all files in the uploads directory
$files = glob($downloadPath . '*');
foreach ($files as $file) {
    if (is_file($file) && basename($file) !== '.gitkeep') {
        unlink($file);
    }
}

echo "Cleanup completed. All files have been removed.\n"; 
