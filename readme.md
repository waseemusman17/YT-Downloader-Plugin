# YT-Downloader-Plugin

YT-Downloader-Plugin allows you to easily download YouTube videos directly through your WordPress website. This plugin is intended for admins to provide users with a simple interface to download videos in MP4 format, directly onto their system.

## Features

- **Simple Interface**: Users can paste a YouTube video URL and download the video.
- **Frontend Access**: Users can download videos via a frontend interface using a shortcode.
- **Automatic Video Download**: Once the user submits the URL, the video is automatically downloaded to their system.

## Prerequisites

- WordPress version 5.0 or higher
- PHP version 7.4 or higher
- **yt-dlp** installed and accessible from the server (for video downloading)
- A local or live WordPress installation where you can install plugins

## Installation

### 1. Install the Plugin

1. Download the latest release of `YT-Downloader-Plugin` from the [releases page](https://github.com/waseemusman17/YT-Downloader-Plugin/).
2. Log in to your WordPress dashboard.
3. Navigate to **Plugins > Add New > Upload Plugin**.
4. Choose the downloaded `.zip` file and click **Install Now**.
5. After installation, click **Activate** to enable the plugin.

### 2. Set Up `yt-dlp` on Your System

The plugin uses `yt-dlp` (a YouTube video downloader) to fetch and download videos. You'll need to install `yt-dlp` on your system.

#### For Windows:

1. Download `yt-dlp` from [yt-dlp's official repository](https://github.com/yt-dlp/yt-dlp/releases).
2. Extract the executable and place it in a folder (e.g., `C:\yt-dlp`).
3. Update the path to `yt-dlp.exe` in the plugin's PHP file (`yt-downloader-download.php`), like this:

```php
$yt_dlp_path = 'C:\\yt-dlp\\yt-dlp.exe';  // Update to the absolute path on your system
```
