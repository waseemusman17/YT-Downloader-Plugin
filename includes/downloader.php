<?php


function yvd_download_video($url) {
    $upload_dir = wp_upload_dir();
    $tmp_file = tempnam(sys_get_temp_dir(), 'yt_');

    $yt_dlp_path = './yt-dlp/yt-dlp.exe'; // full path to yt-dlp.exe

    $cmd = '"' . $yt_dlp_path . '" ' .
       '-o "' . $tmp_file . '.%(ext)s" ' .
       '-f best --restrict-filenames --no-mtime ' .
       '"' . $url . '" 2>&1';

    exec($cmd, $output, $return_var);

    if ($return_var !== 0) {
        return new WP_Error('yt_dlp_failed', implode("\n", $output));
    }

    $downloaded_files = glob($tmp_file . '.*');
    if (empty($downloaded_files)) {
        return new WP_Error('no_file_found', 'Downloaded file not found.');
    }

    $file_path = $downloaded_files[0];
    $filename  = basename($file_path);

    $filetype = wp_check_filetype($filename, null);
    $attachment = [
        'guid'           => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $new_path = $upload_dir['path'] . '/' . $filename;
    rename($file_path, $new_path);
    $attach_id = wp_insert_attachment($attachment, $new_path);

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attach_id, $new_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return true;
}

