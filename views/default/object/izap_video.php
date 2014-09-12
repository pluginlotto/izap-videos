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
  if ($izap_video->imagesrc) {
    $icon = elgg_view_entity_icon($izap_video, 'medium');
  } else {
    $icon = elgg_view_entity_icon($owner, 'tiny');
  }

  $container = $izap_video->getContainerEntity();
  $categories = elgg_view('output/categories', $vars);
  $description = elgg_get_excerpt($izap_video->description);

//$owner_icon = elgg_view_entity_icon($owner, 'tiny');
  $owner_link = elgg_view('output/url', array(
    'href' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/owner/$owner->username",
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

  $get_flv_file = file_exists(preg_replace('/\\.[^.\\s]{3,4}$/', '', $izap_video->videofile) . '_c.flv') ? "true" : "false";
//show links in onserver video if video is converted
  if ($izap_video->videofile) { 
    if ($izap_video->converted == 'in_processing') {
      
    } elseif ($izap_video->converted == 'yes') {
      $metadata = elgg_view_menu('entity', array(
        'entity' => $vars['entity'],
        'handler' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz',
      ));
    } elseif ($izap_video->converted == 'no') {
      $metadata = elgg_view_menu('entity', array(
        'entity' => $vars['entity'],
        'handler' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz',
      ));
    }
  } else {
    $metadata = elgg_view_menu('entity', array(
      'entity' => $vars['entity'],
      'handler' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
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

    $get_image = elgg_get_site_url() . 'mod/izap-videos/thumbnail.php?file_guid=' . $izap_video->guid;
    if ($izap_video->imagesrc) {
      if ($izap_video->video_url) {
        $thumbnail_image = $izap_video->imagesrc;
        $style = 'height:400px; width: 670px;';
      } else {
        $thumbnail_image = $get_image;
        $style = 'height:400px; width: 670px;border-radius:8px;';
      }
    } else {
      // $thumbnail_image = elgg_get_site_url() . 'mod/izap-videos/_graphics/trans_play.png';
      $style = 'height:400px; width: 670px;background-color:black';
    }

    $get_player_path = elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/viewvideo/' . $izap_video->guid . '/400/670';

    //load video div
    $content = "<div id='load_video_" . $izap_video->guid . "'>";
    $content .= '<img src="' . $thumbnail_image . '"  style= "' . $style . '" />';
    $content .= '<a href="' . $get_player_path . '" rel="' . $izap_video->guid . '" class = "ajax_load_video"><img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/' . 'trans_play.png" class="play_icon"/></a>';
    $content .= '</div>';

    $body = " $content $text $summary";

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

<script type="text/javascript">
  var video_loading_image = '<?php echo elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/ajax-loader_black.gif' ?>';
  function ajax_request() {
    $("#load_video_" + this.rel + "").html('<img src="' + video_loading_image + '" />');
    $("#load_video_" + this.rel + "").load('' + this.href + '');
    return false;
  }

  $('.ajax_load_video').click(ajax_request);
</script>

<style type="text/css">
  .play_icon{
    cursor: pointer;
    width: 670px;
    height: 400px;
    position: absolute;
    margin: 1px -691px;

  }
</style>