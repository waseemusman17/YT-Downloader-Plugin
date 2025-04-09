<?php
function yvd_register_menu_page() {
    add_submenu_page(
        'tools.php',
        __('YouTube Downloader', 'yt-downloader-plugin'),
        __('YouTube Downloader', 'yt-downloader-plugin'),
        'manage_options',
        'yvd-downloader',
        'yvd_render_admin_page'
    );
}
add_action('admin_menu', 'yvd_register_menu_page');

function yvd_render_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('YouTube Video Downloader', 'yt-downloader-plugin'); ?></h1>
        <form id="yvd-form">
            <label for="yvd-url"><?php _e('Enter YouTube URL:', 'yt-downloader-plugin'); ?></label><br>
            <input type="url" id="yvd-url" name="yvd_url" style="width: 50%;" required>
            <button type="submit" class="button button-primary"><?php _e('Download', 'yt-downloader-plugin'); ?></button>
            <div id="yvd-message" style="margin-top: 1em;"></div>
        </form>
    </div>
    <?php
}
