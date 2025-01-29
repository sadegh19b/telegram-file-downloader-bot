<?php

namespace TelegramFileDownloaderBot\Commands\Message;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use TelegramFileDownloaderBot\Core\FileHandler;

class GenericmessageCommand extends SystemCommand
{
    private const MAX_DIRECT_DOWNLOAD_SIZE = 20 * 1024 * 1024; // 20MB in bytes
    private const FILE_TYPES = [
        'document' => '📄 Document',
        'photo' => '🖼 Photo',
        'video' => '🎥 Video',
        'voice' => '🎤 Voice Message',
        'audio' => '🎵 Audio File',
        'video_note' => '⭕ Video Note'
    ];

    protected $name = 'genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $file = $this->detectFileType($message);
        
        if (! $file) {
            return $this->showSupportedTypes();
        }

        try {
            $fileSize = $file['file']->getFileSize() ?? 0;

            return $fileSize > self::MAX_DIRECT_DOWNLOAD_SIZE
                ? $this->handleLargeFile($file['file'], $file['type'], $fileSize)
                : $this->handleRegularFile($file['file'], $file['type'], $fileSize);

        } catch (\Exception $e) {
            error_log("Error handling file: " . $e->getMessage());
        }

        return $this->showError();
    }

    private function detectFileType($message): ?array
    {
        foreach (self::FILE_TYPES as $type => $label) {
            $getter = 'get' . ucfirst($type);

            if ($message->$getter()) {
                if ($type === 'photo') {
                    $photos = $message->getPhoto();
                    $file = end($photos);
                } else {
                    $file = $message->$getter();
                }

                return ['file' => $file, 'type' => $type];
            }
        }

        return null;
    }

    private function handleLargeFile($file, string $type, int $size): ServerResponse
    {
        return $this->replyToChat(
            "⚠️ *File Size Limitation*\n\n" .
            "Sorry, this file is too large (" . $this->formatFileSize($size) . ")\n\n" .
            $this->getFileDetailsText($file, $type, $size) . "\n\n" .
            "ℹ️ *Maximum Size Limits: 20 MB*\n",
            ['parse_mode' => 'markdown']
        );
    }

    private function handleRegularFile($file, string $type, int $size): ServerResponse
    {
        try {
            $fileHandler = new FileHandler();
            $result = $fileHandler->handleFile($file, $type);

            if (! $result) {
                return $this->showError();
            }

            $downloadUrl = $result['download_url'];

            return $this->replyToChat(
                "✅ *Download link is ready!*\n\n" .
                $this->getFileDetailsText($file, $type, $size) . "\n\n" .
                "📥 *Download Link:*\n" .
                "`$downloadUrl`",
                ['parse_mode' => 'markdown']
            );
        } catch (\Exception $e) {
            error_log("Error in handleRegularFile: " . $e->getMessage());

            return $this->showError();
        }
    }

    private function showError(): ServerResponse
    {
        return $this->replyToChat(
            "❌ *Error:* Failed to process the file.\n\n" .
            "Please try again.",
            ['parse_mode' => 'markdown']
        );
    }

    private function showSupportedTypes(): ServerResponse
    {
        $types = implode("\n\n", self::FILE_TYPES);

        return $this->replyToChat(
            "✳️ Please send me any of these file types:\n\n" . $types,
        );
    }

    private function getFileDetailsText($file, string $type, int $size): string
    {
        return "*File Details:*\n\n" .
               "📝 *Name:* `" . ($file->getFileName() ?? 'file') . "`\n\n" .
               "📊 *Size:* `" . $this->formatFileSize($size) . "`\n\n" .
               "📎 *Type:* " . self::FILE_TYPES[$type];
    }

    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
} 
