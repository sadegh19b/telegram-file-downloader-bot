<?php

namespace TelegramFileDownloaderBot\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends SystemCommand
{
    protected $name = 'start';

    protected $description = 'Start command';

    protected $usage = '/start';
    
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $text = "👋 Welcome to the Telegram Downloader Bot!\n\n";
        $text .= "I can help you to get download link for any file from telegram. Send me:\n\n";
        $text .= "📄 Documents\n\n";
        $text .= "🖼 Photos\n\n";
        $text .= "🎥 Videos\n\n";
        $text .= "🎤 Voice Messages\n\n";
        $text .= "🎵 Audio Files\n\n";
        $text .= "⭕ Video Notes\n\n";
        $text .= "I'll give you a download link!";

        return $this->replyToChat($text);
    }
} 
