/*
 * Form Valiadation
 */
$(document).ready(function() {
    $('form[name = video_upload]').validate({
        rules: {
            title: {
                required: true
            },
            video_url: {
                required: true
            },
            upload_video: {
                required: true
            }
        },
        messages: {
            title: {
                required: "Please enter the title"
            },
            video_url: {
                required: "Please enter the video url"
            },
            upload_video: {
                required: "Please select the video to upload"
            }
        }
    });
    /*
     * On submit hide upload button
     */
    $('#izap-video-form').submit(function() {
        $('#submit_button').hide();
        $('#progress_button').show();
    });

    /*
     * Offserver Video Preview
     */
    if ($(this).on) {
        $("#id_url").on('input', function() {
            preview_request($(this).val());
        });
    } else {
        $("#id_url").bind('input', function() {
            preview_request($(this).val());
        });
    }

    /*
     * Extention Validation
     */
    $('input[name = upload_video]').change(function() {
        var video_type = $('input[name = upload_video]').val();
        var get_ext = video_type.split('.');
        var izap = (get_ext[get_ext.length - 1] === 'avi' || get_ext[get_ext.length - 1] === 'flv' || get_ext[get_ext.length - 1] === 'mp4' || get_ext[get_ext.length - 1] === '3gp') ? "validate" : "invalidate";
        if (izap === "invalidate") {
            $('#error').html("Invalid video format");
            document.getElementById("upload_button").disabled = true;
        } else {
            $('#error').html("");
            document.getElementById("upload_button").disabled = false;
        }
    });

    $('input[name = upload_thumbnail]').change(function() {
        var thumbnail_type = $('input[name = upload_thumbnail]').val();
        var get_ext = thumbnail_type.split('.');
        var izap = (get_ext[get_ext.length - 1] === 'jpg' || get_ext[get_ext.length - 1] === 'jpeg' || get_ext[get_ext.length - 1] === 'png' || get_ext[get_ext.length - 1] === 'gif') ? "validate" : "invalidate";
        if (izap === "invalidate") {
            $('#thumbnail_err').html("Invalid thumbnail format");
            document.getElementById("upload_button").disabled = true;
        } else {
            $('#thumbnail_err').html("");
            document.getElementById("upload_button").disabled = false;
        }
    });


    $('form[name = video_upload]').submit(function() {
        if ($('form[name = video_upload]').validate().form()) {
        }
    });

});

/*
 * All Video Player
 */
function ajax_request() {
    $(".loader").addClass('active');
    $("#load_video_" + this.rel + "").html('<img src="' + video_loading_image + '" />');
    $("#load_video_" + this.rel + "").load('' + this.href + '');
    return false;
}
$('.ajax_load_video').click(ajax_request);
/*
 * Activity Video Player
 */
$(".ajax_load_video").live('click', function() {
    $("#load_video_" + this.rel + "").load('' + this.href + '');
    return false;
});

function preview_request(video_url) {
    if ($("#id_url").val() != '') {
        $.ajax({
            type: 'POST',
            url: preview_url,
            data: {url: video_url},
            success: function(msg) {
                var obj = $.parseJSON(msg);
                if (obj.title === null && obj.description === null) {
                    $("#off_preview").hide();
                    $("#error").show();
                    if ($("#id_url").val() !== '') {
                        $("#error").html("We did not get expected response from remote server. Please enter valid video url.");
                    } else if ($("#id_url").val() === '') {
                        $("#error").empty();
                    }
                    document.getElementById("upload_button").disabled = true;
                } else if (obj.title !== null) {
                    $("#error").hide();
                    document.getElementById("upload_button").disabled = false;
                    $("#off_preview").show();
                }
                $("#off_title").val(obj.title);
                $('#off_thumb').attr('src', obj.thumbnail);
                $("#tag").val(obj.tags);
                if ((typeof tinyMCE != "undefined") && (tinyMCE.activeEditor)) {
                    tinyMCE.activeEditor.setContent(obj.description);
                } else {
                    $("#off_desc").val(obj.description);
                }
            }
        });
    }
}
