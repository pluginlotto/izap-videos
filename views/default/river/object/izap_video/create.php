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

  if ($object->imagesrc) {
    if ($object->video_url) {
      $thumbnail_image = $object->imagesrc;
      $style = 'max-height:90px; max-width: 90px;';
    } else {
      $thumbnail_image = $get_image;
      $style = 'max-height:90px; max-width: 90px;';
    }
  } else {
    $thumbnail_image = elgg_get_site_url() . 'mod/izap-videos/_graphics/trans_play.png';
    $style = 'background-color:black;max-height:90px; max-width: 90px;';
  }

  //load video by ajax
  $get_player_path = elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/viewvideo/' . $object->guid . '/200/200';

  //load video div
  $content = "<div id='load_video_" . $object->guid . "'>";
  $content .= '<img src="' . $thumbnail_image . '"  style= "' . $style . '" />';
  $content .= '<a href="' . $get_player_path . '" rel="' . $object->guid . '" class = "ajax_load_video"><img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/' . 'c-play.png" class="play_icon" /></a>';
  $content .= '</div>';
  $content .= $object->description;
  echo elgg_view('river/elements/layout', array(
    'item' => $vars['item'],
    'message' => $content,
  ));
?>

<script type="text/javascript">
  var video_loading_image = '<?php echo elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/ajax-loader_black.gif' ?>';
  $(".ajax_load_video").live('click', function() {
    $("#load_video_" + this.rel + "").html('<img src="' + video_loading_image + '" />');
    $("#load_video_" + this.rel + "").load('' + this.href + '');
    return false;
  });
</script>

<style type="text/css">
  .play_icon{
    margin: 4px -16px;
    width: 14px;
    pointer:cursor;
  }
</style>