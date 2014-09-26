<?php
  /*
   *    This file is part of izap-videos plugin for Elgg.
   *
   *    izap-videos for Elgg is free software: you can redistribute it and/or modify
   *    it under the terms of the GNU General Public License as published by
   *    the Free Software Foundation, either version 2 of the License, or
   *    (at your option) any later version.
   *
   *    izap-videos for Elgg is distributed in the hope that it will be useful,
   *    but WITHOUT ANY WARRANTY; without even the implied warranty of
   *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   *    GNU General Public License for more details.
   *
   *    You should have received a copy of the GNU General Public License
   *    along with izap-videos for Elgg.  If not, see <http://www.gnu.org/licenses/>.
   */

  /*
   * izap-video add new video form
   */
  $guid = elgg_extract('guid', $vars, null);
  if (!$guid) {
    echo elgg_view('forms/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/tabs', $vars);
  }
  $title = elgg_extract('title', $vars, '');
  $desc = elgg_extract('description', $vars, '');
  $tags = elgg_extract('tags', $vars, '');
  $access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
  $container_guid = elgg_extract('container_guid', $vars);
  if (!$container_guid) {
    $container_guid = elgg_get_logged_in_user_guid();
  }
  if ($guid) {
    $file_label = elgg_echo("izap-videos:replace");
    $submit_label = elgg_echo('save');
  } else {
    $file_label = elgg_echo("izap-videos:file");
    $submit_label = elgg_echo('save');
  }
?>

<?php
  $current_url = current_page_url();
  $upload_type = end(explode('/', $current_url));
  if (izap_is_onserver_enabled_izap_videos() == 'youtube' || izap_is_onserver_enabled_izap_videos() == 'yes' || izap_is_offserver_enabled_izap_videos() == 'yes') {
    if ($upload_type == 'offserver') {
      ?>
      <div class="row collapse">
        <label><?php echo elgg_echo('video_url'); ?></label>
        <?php echo elgg_view('input/text', array('name' => 'video_url', 'class' => 'xlarge', 'id' => 'id_url', 'placeholder' => 'Enter a URL')); ?>
        <label id="error" style="color:red;"></label>
      </div>
      <!-- Placeholder that tells Preview where to put the selector-->
      <div class="selector-wrapper" id="off_preview" style="display:none;">
        <div class="selector">
          <div class="thumb">
            <?php echo elgg_view('output/img', array('class' => 'thumb', 'id' => 'off_thumb', 'src' => '', 'alt' => elgg_echo('avatar'))); ?>
          </div>
          <div class="attributes">
            <?php echo elgg_view('input/text', array('name' => 'title', 'class' => 'title', 'id' => 'off_title')); ?>
            <?php echo elgg_view('input/longtext', array('name' => 'description', 'class' => 'description', 'id' => 'off_desc')); ?>
          </div>
        </div>       
      </div>
    <?php } elseif ($upload_type == 'onserver') { ?>

      <div>
        <label><?php echo elgg_echo('izap-videos:upload video'); ?></label><br />
        <?php echo elgg_view('input/file', array('name' => 'upload_video')); ?>
        <label id="error"></label>
      </div>

      <div>
        <label><?php echo elgg_echo('izap-videos:thumbnail'); ?></label><br />
        <?php echo elgg_view('input/file', array('name' => 'upload_thumbnail')); ?>
      </div>

      <div>
        <label><?php echo elgg_echo('title'); ?></label><br />
        <?php echo elgg_view('input/text', array('name' => 'title', 'value' => '')); ?>
      </div>

      <div>
        <label><?php echo elgg_echo('description'); ?></label>
        <?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => '')); ?>
      </div>

    <?php } elseif ($upload_type == 'youtube') { ?>
      <div>
        <label><?php echo elgg_echo('categories'); ?></label><br />
        <?php echo elgg_view('input/dropdown', array('name' => 'youtube_cats', 'options_values' => getYoutubeCategories())); ?>
      </div>
      <div>
        <label><?php echo elgg_echo('title'); ?></label><br />
        <?php echo elgg_view('input/text', array('name' => 'title', 'value' => '')); ?>
      </div>

      <div>
        <label><?php echo elgg_echo('description'); ?></label>
        <?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => '', 'type' => 'plain')); ?>
      </div>
    <?php } ?>

    <?php if ($guid) { ?>
      <div>
        <label><?php echo elgg_echo('title'); ?></label><br />
        <?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
      </div>

      <div>
        <label><?php echo elgg_echo('description'); ?></label>
        <?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
      </div>
    <?php } ?>  
    <div>
      <label><?php echo elgg_echo('tags(Optional)'); ?></label>
      <?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags, 'id' => 'tag')); ?>
    </div>

    <?php
    $categories = elgg_view('input/categories', $vars);
    if ($categories) {
      echo $categories;
    }
    // extendable view for other plugins
    echo elgg_view('izap_videos/form_extension');
    
    ?>
    <div  style="clear: both">
      <label><?php echo elgg_echo('access'); ?></label><br />
      <?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
    </div>

    <div class="elgg-foot">
      <?php
      echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
      if ($guid) {
        echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
      }
      echo elgg_view('input/hidden', array('name' => 'page_url', 'value' => $current_url));
      echo elgg_view('input/submit', array('value' => $submit_label, 'id' => 'upload_button'));
      ?>
    </div>
  <?php
  } else {
    $url = GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/all';
    register_error(elgg_echo('izap-videos:message:noAddFeature'));
    forward($url);
  }
?>
<script>
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
  ;
  //Video Preview Start Here 
  $("#id_url").on('input', function() {
    $.ajax({
      type: 'POST',
      url: '<?php echo elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/preview'; ?>',
      data: {url: $(this).val()},
      success: function(msg) {
        console.log(msg);
        var obj = $.parseJSON(msg);
        if(obj.title == null && obj.description == null){ 
          $("#error").html("We did not get expected response from YouTube. Please enter valid url.");
        }else if(obj.title != null || obj.title  != null){ 
          $("#off_preview").show();
        } 
        $("#off_title").val(obj.title);
        $("#off_desc").val(obj.description);
        $('#off_thumb').attr('src', obj.thumbnail);
        $("#tag").val(obj.tags);
      }
    });
  });
</script>
<style type="text/css">
  .error{
    color:red;
    font-weight: normal;
  }
  #error{
    color:red;
    font-weight: normal;
    font-size: 110%;
    display: inline;
  }
</style>
