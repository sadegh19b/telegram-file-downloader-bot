<?php

namespace TelegramFileDownloaderBot\Core;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Exception\TelegramException;

class FileHandler
{
    /**
     * @param File|object $file
     * @param string $fileType
     * @return array{success: bool, download_url: string, file_type: string}|null
     */
    public function handleFile(object $file, string $fileType): ?array
    {
        try {
            $fileId = $file->getFileId();
            $fileInfo = Request::getFile(['file_id' => $fileId]);

            if (!$fileInfo->isOk()) {
                error_log("Failed to get file info: " . $fileInfo->getDescription());

                return null;
            }

            /** @var File $fileResult */
            $fileResult = $fileInfo->getResult();
            $filePath = $fileResult->getFilePath();

            if (! $this->downloadFile($fileResult)) {
                return null;
            }

            return [
                'success' => true,
                'download_url' => $this->generateDownloadUrl($filePath),
                'file_type' => $fileType
            ];
        } catch (TelegramException $e) {
            error_log("Telegram Error in FileHandler: " . $e->getMessage());

            return null;
        } catch (\Exception $e) {
            error_log("General Error in FileHandler: " . $e->getMessage());

            return null;
        }
    }
    private function downloadFile(File $fileInfo): bool
    {
        try {
            return Request::downloadFile($fileInfo);
        } catch (TelegramException $e) {
            error_log("Error downloading file: " . $e->getMessage());

            return false;
        }
    }

    private function generateDownloadUrl(string $filename): string
    {
        return Config::get('base_url') . '/downloads/' . $filename;
    }
} 
