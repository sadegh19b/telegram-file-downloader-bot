# Telegram File Downloader Bot

A Telegram bot that allows users to send files, images, audio, video, and voice messages from Telegram and receive download links in return.

## Table of Contents
- [Telegram File Downloader Bot](#telegram-file-downloader-bot)
  - [Table of Contents](#table-of-contents)
  - [Features](#features)
  - [Important Notes](#important-notes)
  - [Requirements](#requirements)
  - [Setup Instructions](#setup-instructions)
  - [Proxy Configuration (Optional)](#proxy-configuration-optional)
  - [Usage](#usage)
  - [Webhook Management](#webhook-management)
    - [Setting Webhook](#setting-webhook)
    - [Removing Webhook](#removing-webhook)
  - [Security Notes](#security-notes)
  - [License](#license)

## Features

- Accepts all types of files:
  - 📄 Documents
  - 🖼 Photos
  - 🎥 Videos
  - 🎤 Voice Messages
  - 🎵 Audio Files
  - ⭕ Video Notes
- Generates unique download links for each file
- Automatically cleans up files every night at midnight
- Secure file handling
- Proxy support for restricted networks

## Important Notes

⚠️ **File Size Limitation**: According to [Telegram Bot FAQ](https://core.telegram.org/bots/faq#how-do-i-download-files):
- Maximum file size: 20MB for downloads via getFile method
- Maximum file size: 50MB for bot file uploads
- Currently, larger files are not supported by Telegram Bot API

## Requirements

- PHP 7.4 or higher
- Composer
- Web server (Apache/Nginx)
- SSL certificate (required for Telegram webhooks)
- Cron (for cleanup task)

## Setup Instructions

1. Clone this repository
2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file and configure it:
   ```bash
   cp .env.example .env
   ```

4. Edit the `.env` file with your:
   - Telegram Bot Token (get it from @BotFather)
   - Bot Username
   - Your domain webhook URL
   - Your base URL for file downloads

5. Create an downloads and uploads directories:
   ```bash
   mkdir downloads
   mkdir uploads
   chmod 777 downloads
   chmod 777 uploads
   ```

6. Set up your web server:
   - Point your domain to the `src/bot.php` file
   - Ensure SSL is properly configured (required for Telegram webhooks)
   - Configure file permissions

7. Register the webhook with Telegram:
   ```bash
   php src/set.php
   ```

8. Add a cron job to run the cleanup script daily at midnight:
   ```bash
   0 0 * * * php /path/to/your/project/src/cleanup.php
   ```

## Proxy Configuration (Optional)

If you need to use a proxy to connect to Telegram servers:

1. Add your proxy URL to the `.env` file:
   ```env
   # SOCKS5 proxy
   PROXY_URL=socks5://127.0.0.1:1080

   # HTTP proxy
   PROXY_URL=http://proxy.example.com:8080

   # Proxy with authentication
   PROXY_URL=http://username:password@proxy.example.com:8080
   ```

2. Supported proxy types:
   - HTTP
   - HTTPS
   - SOCKS4
   - SOCKS5

## Usage

1. Start a chat with your bot on Telegram
2. Send any type of file
3. The bot will respond with a download link
4. Files are automatically cleaned up every night at midnight

## Webhook Management

### Setting Webhook
- To set webhook: `php src/set.php`
- The webhook URL must be HTTPS
- Make sure your server is accessible from the internet

### Removing Webhook
- To remove webhook: `php src/unset.php`
- Use this when:
  - You want to stop the bot
  - You want to move the bot to a different server
  - You're troubleshooting webhook issues

## Security Notes

- Make sure your `downloads` and `uploads` directories are properly secured
- Configure your web server to handle file downloads securely
- Don't expose sensitive files through the download URL
- Set appropriate file size limits in your PHP configuration
- When using a proxy, prefer HTTPS or SOCKS5 for better security

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.