jQuery(document).ready(function ($) {
  $("#yt-downloader-form").on("submit", function (e) {
    e.preventDefault();

    var ytUrl = $("#yt-url").val();
    var nonce = ytDownloader.nonce;

    if (!ytUrl) {
      $("#yt-download-message").text("Please enter a valid YouTube URL.");
      return;
    }

    $("#yt-download-btn").prop("disabled", true);
    $("#yt-download-message").text("Processing download...");

    $.ajax({
      url: ytDownloader.ajax_url,
      method: "POST",
      dataType: "json", // Ensure JSON
      data: {
        action: "yt_downloader",
        yt_url: ytUrl,
        nonce: nonce,
      },
      success: function (response) {
        if (response.success) {
          if (response.data && response.data.message) {
            $("#yt-download-message").text(response.data.message);
          } else {
            $("#yt-download-message").text("Download started or completed.");
          }
        } else {
          if (response.data && response.data.message) {
            $("#yt-download-message").text(response.data.message);
          } else {
            $("#yt-download-message").text("An unknown error occurred.");
          }
        }
      },
      error: function (xhr, status, error) {
        $("#yt-download-message").text("AJAX error: " + error);
        console.error(xhr.responseText);
      },
      complete: function () {
        $("#yt-download-btn").prop("disabled", false);
      },
    });
  });
});
