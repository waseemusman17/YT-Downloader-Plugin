<?php
/**
 * Plugin Name: YT-Downloader-Plugin
 * Description: Download YouTube videos via yt-dlp and store them in the WordPress media library.
 * Version: 1.0.0
 * Author: Senior WP Dev
 * Text Domain: yt-downloader-plugin
 */

defined('ABSPATH') || exit;

define('YVD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YVD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load plugin components
require_once YVD_PLUGIN_DIR . 'includes/admin-page.php';
require_once YVD_PLUGIN_DIR . 'includes/downloader.php';
require_once YVD_PLUGIN_DIR . 'includes/ajax-handler.php';
require_once YVD_PLUGIN_DIR . 'includes/helpers.php';

// Enqueue admin assets
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook === 'tools_page_yvd-downloader') {
        wp_enqueue_script('yvd-admin', YVD_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], '1.0.0', true);
        wp_localize_script('yvd-admin', 'yvd_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('yvd_download_nonce'),
        ]);
    }
});

// Enqueue JS to handle the form submission and AJAX request
function yt_downloader_enqueue_scripts() {
    wp_enqueue_script('yt-downloader-frontend', plugin_dir_url(__FILE__) . 'assets/js/yt-downloader-frontend.js', array('jquery'), null, true);
    wp_localize_script('yt-downloader-frontend', 'ytDownloader', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('yt-downloader-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'yt_downloader_enqueue_scripts');

// Handle the video download request via AJAX
add_action('wp_ajax_yt_downloader', 'yt_downloader_ajax_request');
add_action('wp_ajax_nopriv_yt_downloader', 'yt_downloader_ajax_request');

function yt_downloader_ajax_request() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'yt-downloader-nonce')) {
        wp_send_json_error(array('message' => 'Invalid security token.'));
    }

    if (!isset($_POST['yt_url'])) {
        wp_send_json_error(array('message' => 'Missing video URL.'));
    }

    $url = sanitize_text_field($_POST['yt_url']);
    if (!filter_var($url, FILTER_VALIDATE_URL) || strpos($url, 'youtube.com') === false) {
        wp_send_json_error(array('message' => 'Invalid YouTube URL.'));
    }

    $yt_dlp_path = 'C:\\yt-dlp\\yt-dlp.exe'; // Update path if needed
    $tmp_file = tempnam(sys_get_temp_dir(), 'yt_') . '.mp4';

    $cmd = '"' . $yt_dlp_path . '" -o "' . $tmp_file . '" -f best "' . $url . '" 2>&1';
    exec($cmd, $output, $return_var);

    if ($return_var === 0 && file_exists($tmp_file)) {
        // Return success message and URL to download manually
        $file_url = plugin_dir_url(__FILE__) . 'download.php?file=' . urlencode($tmp_file); // Optional
        wp_send_json_success(array(
            'message' => 'Video downloaded successfully.',
            'download_url' => $file_url,
            'debug' => implode("\n", $output)
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Failed to download video.',
            'debug' => implode("\n", $output)
        ));
    }
}



function yt_downloader_form_shortcode() {
    ob_start();
    ?>
    <form id="yt-downloader-form" action="" method="post">
        <label for="yt-url">Enter YouTube URL:</label>
        <input type="text" id="yt-url" name="yt-url" required placeholder="https://www.youtube.com/watch?v=XXXXXX">
        <button type="submit" id="yt-download-btn">Download Video</button>
        <div id="yt-download-message"></div>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('yt_downloader', 'yt_downloader_form_shortcode');

function yt_downloader_shortcode_form() {
    ob_start();
    ?>
    <form id="yt-download-form">
        <input type="text" id="yt-video-url" placeholder="Enter YouTube URL" required style="padding:8px; width: 60%;">
        <button type="submit" style="padding:8px;">Download</button>
    </form>

    <script>
    document.getElementById('yt-download-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const videoUrl = document.getElementById('yt-video-url').value;
        if (!videoUrl) {
            alert("Please enter a YouTube URL.");
            return;
        }

        // Build the full plugin download URL with GET parameter
        const downloadUrl = "<?php echo plugins_url('yt-downloader-download.php', __FILE__); ?>?video_url=" + encodeURIComponent(videoUrl);

        // Redirect user to that URL (which starts the download)
        window.location.href = downloadUrl;
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('yt_downloader_form', 'yt_downloader_shortcode_form');
