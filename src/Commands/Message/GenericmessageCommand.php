<?php

namespace TelegramDownloaderBot\Commands\Message;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use TelegramDownloaderBot\Core\FileHandler;

class GenericmessageCommand  extends SystemCommand
{
    protected $name = 'genericmessage';

    protected $description = 'Handle generic message';

    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $fileHandler = new FileHandler();

        // Check for any type of file
        if ($message->getDocument()) {
            $file = $message->getDocument();
            $fileType = 'document';
        } elseif ($message->getPhoto()) {
            $photos = $message->getPhoto();
            $file = end($photos);
            $fileType = 'photo';
        } elseif ($message->getVideo()) {
            $file = $message->getVideo();
            $fileType = 'video';
        } elseif ($message->getVoice()) {
            $file = $message->getVoice();
            $fileType = 'voice';
        } elseif ($message->getAudio()) {
            $file = $message->getAudio();
            $fileType = 'audio';
        } elseif ($message->getVideoNote()) {
            $file = $message->getVideoNote();
            $fileType = 'video_note';
        } else {
            return $this->replyToChat('Please send me any file to get a download link.');
        }

        try {
            $result = $fileHandler->handleFile($file, $fileType);

            if ($result) {
                return $this->replyToChat(
                    "✅ File uploaded successfully!\n\n📥 Download link:\n" . $result['download_url']
                );
            }
        } catch (\Exception $e) {
            error_log("Error handling file: " . $e->getMessage());
        }

        return $this->replyToChat('❌ Failed to save the file. Please try again.');
    }
} 