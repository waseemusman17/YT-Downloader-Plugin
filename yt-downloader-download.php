<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include WordPress functionality
require_once('../../../wp-load.php');

// Check if 'video_url' is provided
if (!isset($_GET['video_url'])) {
    die('Missing video URL');
}

$video_url = esc_url_raw($_GET['video_url']);

// Generate a unique filename for the video
$upload_dir = wp_upload_dir();
$target_dir = trailingslashit($upload_dir['basedir']) . 'yt-downloader/';
$target_url = trailingslashit($upload_dir['baseurl']) . 'yt-downloader/';

if (!file_exists($target_dir)) {
    wp_mkdir_p($target_dir);
}

$unique_file = $target_dir . 'yt_' . uniqid() . '.mp4';

// Command to download video via yt-dlp
$yt_dlp_path = 'C:\\yt-dlp\\yt-dlp.exe';  // Update to the absolute path on your system
$download_cmd = $yt_dlp_path . ' -f mp4 -o ' . escapeshellarg($unique_file) . ' ' . escapeshellarg($video_url);

// Run the command and capture output and error
exec($download_cmd, $output, $return_var);

// Log the output and return code for debugging
file_put_contents('yt-dl-debug.log', implode("\n", $output), FILE_APPEND);
file_put_contents('yt-dl-debug.log', "\nReturn Code: " . $return_var . "\n", FILE_APPEND);

// Check if the download was successful
if ($return_var !== 0) {
    die('Video download failed. Please check the URL or try again later.');
}

// Check if the file exists and prepare for download
if (file_exists($unique_file)) {
    // Set headers to prompt the file for download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($unique_file) . '"');
    header('Content-Length: ' . filesize($unique_file));

    // Clear the output buffer and send the file to the browser
    ob_clean();
    flush();
    readfile($unique_file);
    exit;
} else {
    die('Failed to locate the downloaded video file.');
}
