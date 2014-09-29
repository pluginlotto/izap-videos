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

  $object = $vars['item']->getObjectEntity();
  $get_image = elgg_get_site_url() . 'mod/izap-videos/thumbnail.php?file_guid=' . $object->guid;
  $get_flv_file = file_exists(preg_replace('/\\.[^.\\s]{3,4}$/', '', $object->videofile) . '_c.flv') ? "true" : "false";
  if ($get_flv_file == 'false' || $object->converted == 'no') {
    $object->access_id = ACCESS_PRIVATE;
    $object->save();
  }
  global $IZAPSETTINGS;
  if ($object->videothumbnail) {
    $thumbnail_image = $object->videothumbnail;
    $style = 'width: 365px;height: 300px;';
  } elseif ($object->imagesrc) {
    $thumbnail_image = $get_image;
    $style = 'width: 365px;height: 300px;';
  } else {
    $style = 'background-color:black;width: 365px;height: 300px;';
  }

  //load video by ajax
  $get_player_path = elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/viewvideo/' . $object->guid . '/370/658';
  $description_length = strlen($object->description);
  if ($description_length > 263) {
    $path = $object->getURL();
    $description = substr(strip_tags($object->description), 0, 260) . "... <a href='" . $path . "'>View More</a>";
  }

  //load video div
  $content = "<div id='load_video_" . $object->guid . "'>";
  $content .= '<a href="' . $get_player_path . '" rel="' . $object->guid . '" class = "ajax_load_video"><img src="' . $thumbnail_image . '"  style= "' . $style . '" /></a>';
  $content .= '<a href="' . $get_player_path . '" rel="' . $object->guid . '" class = "ajax_load_video"><img src="' . $IZAPSETTINGS->graphics . 'c-play.png" class="activity_play_icon" /></a>';
  $content .= '</div>';
  $content .= $description;
  echo elgg_view('river/elements/layout', array(
    'item' => $vars['item'],
    'message' => $content,
  ));
?>

<script type="text/javascript">
  var video_loading_image = '<?php echo $IZAPSETTINGS->graphics . '/ajax-loader_black.gif' ?>';
</script>
<style type="text/css">
  .activity_play_icon{
    cursor: pointer;
    height: 52px;
    position: absolute;
    margin: 128px -208px;
  }
</style>