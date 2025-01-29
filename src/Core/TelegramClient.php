<?php

namespace TelegramFileDownloaderBot\Core;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;
use GuzzleHttp\Client;

class TelegramClient
{
    protected Telegram $telegram;

    public function __construct()
    {
        // Load configuration
        Config::load();

        // Create Telegram API object
        $this->telegram = new Telegram(
            Config::get('bot_token'),
            Config::get('bot_username')
        );

        // Configure proxy if needed
        $this->configureProxy();
    }

    protected function configureProxy(): void
    {
        $proxyUrl = Config::get('proxy_url');
        if ($proxyUrl) {
            try {
                $client = new Client([
                    'proxy' => $proxyUrl,
                    'timeout' => 30,
                    'connect_timeout' => 10
                ]);
                
                Request::setClient($client);
                error_log("Proxy configured: " . $proxyUrl);
            } catch (\Exception $e) {
                error_log("Failed to set proxy: " . $e->getMessage());
            }
        }
    }

    public function getTelegram(): Telegram
    {
        return $this->telegram;
    }
} 
