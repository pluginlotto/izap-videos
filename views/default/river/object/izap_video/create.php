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
   * New izap-video river entry.
   */
  $object = $vars['item']->getObjectEntity();
  $video_src = elgg_get_site_url() . 'izap_videos_files/file/' . $object->guid . '/' . elgg_get_friendly_title($object->title) . '.flv';
  $player_path = elgg_get_site_url() . 'mod/izap-videos/player/izap_player.swf';
  $image_path = elgg_get_site_url() . 'mod/izap-videos/thumbnail.php?file_guid=' . $object->guid;

  if ($object->imagefile) {
    $image = $image_path;
  } else {
    $image = elgg_get_site_url() . 'mod/izap-videos/_graphics/trans_play.png';
  }

  if ($object->imagefile) {
    if (isset($object->video_url)) {
      $content = '<img src="' . $object->imagefile . '"style= "max-height:90px; max-width: 90px;background-color:black;cursor:pointer;margin-left: 0px;" class="play" id="upload_div_' . $object->guid . '" onclick = "video(' . $object->guid . ')"/>';
      $content .= '<img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/' . 'c-play.png" class="play" id="play_' . $object->guid . '" onclick = "video(' . $object->guid . ')"/>';
    } else {
      $content = '<img src="' . $image . '" style= "max-height:90px; max-width: 90px;background-color:black;cursor:pointer" id="upload_div_' . $object->guid . '" class="upload_div" onclick = "video(' . $object->guid . ')"/>';
      $content .= '<img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/' . 'c-play.png" class="play" id="play_' . $object->guid . '" onclick = "video(' . $object->guid . ')"/>';
    }
  } else {
    $content = '<img src="' . $image . '" style= "max-height:90px; max-width: 90px;background-color:black;cursor:pointer" id="upload_div_' . $object->guid . '" class="upload_div" onclick = "video(' . $object->guid . ')"/>';
  }

  if ($object->video_url) { 
    parse_str(parse_url($object->video_url, PHP_URL_QUERY), $my_array_of_vars);
    $content .= "<p><iframe width='200' height='200' style='display:none' src='//www.youtube.com/embed/" . $my_array_of_vars['v'] . "?volume=30&autoplay=1&vTitle=" . $izap_video->title . "&showTitle=yes' frameborder='0' id='video_" . $object->guid . "' allowfullscreen></iframe></p>";
  } else {
    $content .= "<p class='video_" . $object->guid . "' style='display:none;' id='video_" . $object->guid . "' >
           <object width='200' height= '200' id='flvPlayer'>
            <param name='allowFullScreen' value='true'>
            <param name='wmode' value='transparent'>
             <param name='allowScriptAccess' value='always'>
            <param name='movie' value='" . $player_path . "?movie=" . $video_src . "&volume=30&autoload=on&autoplay=on&vTitle=" . $object->title . "&showTitle=yes' >
            <embed src='" . $player_path . "?movie=" . $video_src . "&volume=30&autoload=on&autoplay=on&vTitle=" . $object->title . "&showTitle=yes' width='100' height='100' allowFullScreen='true' type='application/x-shockwave-flash' allowScriptAccess='always' wmode='transparent'>
           </object></p>";
  }
  $content .= $object->description;
  echo elgg_view('river/elements/layout', array(
    'item' => $vars['item'],
    'message' => $content,
  ));
?>
<script>
    function video(id) {
      $("#video_" + id).show();
      $("#upload_div_" + id + "").hide();
      $("#play_" + id + "").hide();
    }
</script>

<style>
  .play{
    position:relative;
    margin: 3px -11px;
    cursor: pointer;
  }
</style>