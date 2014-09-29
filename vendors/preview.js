 /*
  * Form Valiadation
  */
 $(document).ready(function() {
    $('form[name = video_upload]').validate({
      rules: {
        title: {
          required: true,
        },
        video_url: {
          required: true,
          url: true,
        },
        upload_video: {
          required: true
        },
      },
      messages: {
        title: {
          required: "Please enter the title",
        },
        video_url: {
          required: "Please enter the video url",
          url: "Enter the valid url"
        },
        upload_video: {
          required: "Please select the video to upload"
        },
      }
    });
  });
  
/*
 * Extention Validation
 */  
$('input[name = upload_video]').change(function() {
    var video_type = $('input[name = upload_video]').val();
    var get_ext = video_type.split('.');
    var izap = (get_ext[get_ext.length - 1] == 'avi' || get_ext[get_ext.length - 1] == 'flv' || get_ext[get_ext.length - 1] == 'mp4' || get_ext[get_ext.length - 1] == '3gp') ? "validate" : "invalidate";
    if (izap == "invalidate") {
      $('#error').html("Invalid video format");
      document.getElementById("upload_button").disabled = true;
    } else {
      $('#error').html("");
      document.getElementById("upload_button").disabled = false;
    }
  });
  $('form[name = video_upload]').submit(function() {
    if ($('form[name = video_upload]').validate().form()) {
    }
  }); 
  
  