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

/**
 * izap-video add new video form
 */
$guid = elgg_extract('guid', $vars, null);
if(!$guid){ 
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
  $submit_label = elgg_echo('upload');
}
?>

<?php
$current_url = current_page_url();
$upload_type = end(explode('/', $current_url));
if(!$guid){
if ($upload_type == 'offserver') {
  ?>
  <div>
    <label><?php echo elgg_echo('video_url'); ?></label>
    <?php echo elgg_view('input/text', array('name' => 'video_url', 'value' => $video_url)); ?>
  </div>
<?php } elseif($upload_type == 'onserver') { ?>

  <div>
    <label><?php echo elgg_echo('izap-videos:upload video'); ?></label><br />
    <?php echo elgg_view('input/file', array('name' => 'upload_video')); ?>
  </div>

  <div>
    <label><?php echo elgg_echo('izap-videos:thumbnail'); ?></label><br />
    <?php echo elgg_view('input/file', array('name' => 'upload_thumbnail')); ?>
  </div>
<?php } } ?>

<div>
  <label><?php echo elgg_echo('title'); ?></label><br />
  <?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>

<div>
  <label><?php echo elgg_echo('description'); ?></label>
  <?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>

<div>
  <label><?php echo elgg_echo('tags(Optional)'); ?></label>
  <?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>

<?php
$categories = elgg_view('input/categories', $vars);
if ($categories) {
  echo $categories;
}
?>
<div>
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
  echo elgg_view('input/submit', array('value' => $submit_label));
  ?>
</div>

<script src="/mod/izap-videos/vendors/validate.js" ></script>
<script>
  $(document).ready(function() {
    $('form[name = video_upload]').validate({
      rules: {
        title: {
          required: true,
        },
        description: {
          required: true,
        },
        video_url: {
          required: true,
          url:true,
        },
        upload_video: {
          required: true,
        },
       
      },
      messages: {
        title: {
          required: "Please Enter Title",
        },
        description: {
          required: "Please Enter Description",
        },
        video_url: {
          required: "Please Enter Video Url",
          url : "Enter Valid Url"
        },
        upload_video: {
          required: "Please select video to upload"
        },
       
      }
    });
  });
</script>

<style type="text/css">
  .error{
    color:maroon;
  }
</style>