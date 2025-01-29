<?php

namespace TelegramFileDownloaderBot\Core;

use Dotenv\Dotenv;

class Config
{
    private static array $config = [];

    public static function load(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        // Validate required configuration
        $dotenv->required(['BOT_TOKEN', 'BOT_USERNAME', 'WEBHOOK_URL', 'BASE_URL'])->notEmpty();

        self::$config = [
            'bot_token' => $_ENV['BOT_TOKEN'],
            'bot_username' => $_ENV['BOT_USERNAME'],
            'webhook_url' => $_ENV['WEBHOOK_URL'],
            'base_url' => $_ENV['BASE_URL'],
            'proxy_url' => $_ENV['PROXY_URL'] ?? null,
        ];
    }

    public static function get(string $key): ?string
    {
        if (!isset(self::$config[$key])) {
            throw new \InvalidArgumentException("Configuration key '{$key}' not found");
        }

        return self::$config[$key];
    }
} 
