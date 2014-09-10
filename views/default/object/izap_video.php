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
   * object for izap-video
   * @package izap-video 
   */


  $full = elgg_extract('full_view', $vars, FALSE);
  $izap_video = elgg_extract('entity', $vars, FALSE); //echo $izap_video->access_id;
  if (!$izap_video) {
    return TRUE;
  }

  $owner = $izap_video->getOwnerEntity();
  if ($izap_video->imagefile) {
    $icon = elgg_view_entity_icon($izap_video, 'medium');
  } else {
    $icon = elgg_view_entity_icon($owner, 'tiny');
  }

  $container = $izap_video->getContainerEntity();
  $categories = elgg_view('output/categories', $vars);
  $description = elgg_get_excerpt($izap_video->description);

//$owner_icon = elgg_view_entity_icon($owner, 'tiny');
  $owner_link = elgg_view('output/url', array(
    'href' => "izap-videos/owner/$owner->username",
    'text' => $owner->name,
    'is_trusted' => true,
  ));
  $author_text = elgg_echo('byline', array($owner_link));
  $date = elgg_view_friendly_time($izap_video->time_created);

// The "on" status changes for comments, so best to check for !Off
  if ($izap_video->comments_on != 'Off') {
    $comments_count = $izap_video->countComments();
    //only display if there are commments
    if ($comments_count != 0) {
      $text = elgg_echo("comments") . " ($comments_count)";
      $comments_link = elgg_view('output/url', array(
        'href' => $izap_video->getURL() . '#comments',
        'text' => $text,
        'is_trusted' => true,
      ));
    } else {
      $comments_link = '';
    }
  } else {
    $comments_link = '';
  }
//show links in onserver video if video is converted
  if ($izap_video->videofile) {
    if ($vars['entity']->converted == 'yes') {
      $metadata = elgg_view_menu('entity', array(
        'entity' => $vars['entity'],
        'handler' => 'izap-videos',
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz',
      ));
    }
  } else {
    $metadata = elgg_view_menu('entity', array(
      'entity' => $vars['entity'],
      'handler' => 'izap-videos',
      'sort_by' => 'priority',
      'class' => 'elgg-menu-hz',
    ));
  }
  $subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
  if (elgg_in_context('widgets')) {
    $metadata = '';
  }

  if ($full) {
    $params = array(
      'entity' => $izap_video,
      'title' => false,
      'metadata' => $metadata,
      'subtitle' => $subtitle,
    );
    $params = $params + $vars;
    $summary = elgg_view('object/elements/summary', $params);
    $text = elgg_view('output/longtext', array('value' => $izap_video->description));

    $get_flv_file = file_exists(preg_replace('/\\.[^.\\s]{3,4}$/', '', $izap_video->videofile) . '_c.flv') ? "true" : "false";
    //  echo $get_flv_file;

    $video_src = elgg_get_site_url() . 'izap_videos_files/file/' . $izap_video->guid . '/' . elgg_get_friendly_title($izap_video->title) . '.flv';
    $player_path = elgg_get_site_url() . 'mod/izap-videos/player/izap_player.swf';  //echo $player_path;   
    $image_path = elgg_get_site_url() . 'mod/izap-videos/thumbnail.php?file_guid=' . $izap_video->guid;
    if ($izap_video->imagefile) {
      if ($izap_video->video_url) {
        $image = $izap_video->imagefile;
      } else {
        $image = $image_path;
      }
    } else {
      $image = elgg_get_site_url() . 'mod/izap-videos/_graphics/trans_play.png';
    }
    if ($get_flv_file == 'true') {
      $video_obj = new IzapVideo;

      $html = '
        <img src="' . $image . '" style= "height:400px; width: 670px;background-color: black;align:center;border-radius: 8px;cursor:pointer;" class="upload_div" />';
      if ($izap_video->imagefile) {
        $html .= '<img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/trans_play.png" class="play" style="align:center;cursor:pointer;width:670px;height:400px;"/>';
      }
      $data = "<p class='video' style='display:none;'>
           <object width='600' height= '400' id='flvPlayer'>
            <param name='allowFullScreen' value='true'>
            <param name='wmode' value='transparent'>
             <param name='allowScriptAccess' value='always'>
            <param name='movie' value='" . $player_path . "?movie=" . $video_src . "&volume=30&autoload=on&autoplay=on&vTitle=" . $izap_video->title . "&showTitle=yes' >
            <embed src='" . $player_path . "?movie=" . $video_src . "&volume=30&autoload=on&autoplay=on&vTitle=" . $izap_video->title . "&showTitle=yes' width='100' height='100' allowFullScreen='true' type='application/x-shockwave-flash' allowScriptAccess='always' wmode='transparent'>
           </object></p>";
    } elseif ($get_flv_file == 'false') {
      $html = '<img src="' . $image . '" style= "width:670px;height:400px;background-color:black;align:center;cursor:pointer;border-radius: 8px;" class="no-video" />';
      echo '<p class="notConvertedWrapper">' . elgg_echo("izap_videos:alert:not-converted") . '</p>';
      $data = "<p class='video' style='display:none;background-color:black;'></p>";
    } elseif ($izap_video->video_url) {
      $video_obj = new IzapVideo;

      $html = '
        <img src="' . $image . '" style= "width:670px;height:400px;background-color:black;align:center;cursor:pointer;border-radius: 8px;" class="upload_div" />';
      if ($izap_video->imagefile) {
        $html .= '<img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/trans_play.png" class="play" style="align:center;cursor:pointer;width:670px;height:400px;"/>';
      }
      parse_str(parse_url($izap_video->video_url, PHP_URL_QUERY), $my_array_of_vars);
      $data = "<p class='video' style='display:none;'><iframe width='600' height='400' src='//www.youtube.com/embed/" . $my_array_of_vars['v'] . "?volume=30&autoplay=1&vTitle=" . $izap_video->title . "&showTitle=yes' frameborder='0' id='video_" . $object->guid . "' allowfullscreen></iframe></p>";
    }
    $body = " $html $data $text $summary";

    echo elgg_view('object/elements/full', array(
      'entity' => $izap_video,
      'body' => $body,
      //  'summary' => $summary
    ));
  } else {
    // brief view

    $params = array(
      'entity' => $izap_video,
      'metadata' => $metadata,
      'subtitle' => $subtitle,
      'content' => $description,
    );
    $params = $params + $vars; //cho '<pre>'; print_R($params); 
    $list_body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($icon, $list_body);
  }
?>

<script>
  $(document).ready(function() {
    $('.upload_div').click(function() {
      $("p").show();
      $('.play').hide();
      $('.upload_div').hide();
    });
  });

  $(document).ready(function() {
    $('.play').click(function() {
      $("p").show();
      $('.play').hide();
      $('.upload_div').hide();
    });
  });
</script>

<style>
  .play{
    position:absolute;
    margin: 1px -683px;
    width:50px;
  }
  .notConvertedWrapper{
    width:660px;
    background-color: #FFC4C4;
    padding:5px;
    border-radius: 8px;
  }
</style>