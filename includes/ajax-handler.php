<?php
add_action('wp_ajax_yvd_download_video', 'yvd_handle_ajax_download');

function yvd_handle_ajax_download() {
    check_ajax_referer('yvd_download_nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Unauthorized.', 'yt-downloader-plugin'));
    }

    $url = isset($_POST['url']) ? esc_url_raw(trim($_POST['url'])) : '';

    if (empty($url) || !preg_match('/^https?:\/\/(www\.)?youtube\.com\/watch\?v=/', $url)) {
        wp_send_json_error(__('Invalid YouTube URL.', 'yt-downloader-plugin'));
    }

    require_once YVD_PLUGIN_DIR . 'includes/downloader.php';

    $result = yvd_download_video($url);

    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    } else {
        wp_send_json_success(__('Video downloaded successfully!', 'yt-downloader-plugin'));
    }
}



