<?php

function yt_downloader_form_shortcode() {
    ob_start();
    ?>
    <form id="yt-downloader-form" action="" method="post">
        <label for="yt-url">Enter YouTube URL:</label>
        <input type="text" id="yt-url" name="yt-url" required placeholder="https://www.youtube.com/watch?v=XXXXXX">
        <button type="submit" id="yt-download-btn">Download Video</button>
        <div id="yt-download-message"></div>
    </form>
    <script>
        document.getElementById('yt-download-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const url = document.getElementById('yt-video-url').value;
            window.location.href = '<?php echo plugin_dir_url(__FILE__); ?>yt-downloader-download.php?video_url=' + encodeURIComponent(url);
        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('yt_downloader', 'yt_downloader_form_shortcode');
