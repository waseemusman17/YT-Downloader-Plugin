jQuery(document).ready(function($) {
    $('#yvd-form').on('submit', function(e) {
        e.preventDefault();
        let url = $('#yvd-url').val();
        $('#yvd-message').html('Downloading...');

        $.post(yvd_ajax.ajax_url, {
            action: 'yvd_download_video',
            url: url,
            _ajax_nonce: yvd_ajax.nonce
        }, function(response) {
            if (response.success) {
                $('#yvd-message').html('<span style="color:green;">' + response.data + '</span>');
            } else {
                $('#yvd-message').html('<span style="color:red;">' + response.data + '</span>');
            }
        });
    });
});
