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
    $icon = elgg_view_entity_icon($izap_video, 'small');
} else {
    $icon = elgg_view_entity_icon($owner, 'tiny');
}

$container = $izap_video->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = $izap_video->excerpt;
if (!$excerpt) {
    $excerpt = elgg_get_excerpt($izap_video->description);
}

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

$metadata = elgg_view_menu('entity', array(
    'entity' => $vars['entity'],
    'handler' => 'izap-videos',
    'sort_by' => 'priority',
    'class' => 'elgg-menu-hz',
        ));

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

    $path = elgg_get_site_url() . 'test.flv';
    $player_path = elgg_get_site_url() . 'mod/izap-videos/player/izap_player.swf' ;  //echo $player_path;

    $video_path = elgg_get_site_url() . 'mod/izap-videos/video.php?file_guid=' . $izap_video->guid; //echo $video_path;
    $image_url = elgg_get_site_url() . 'mod/izap-videos/thumbnail.php?file_guid=' . $izap_video->guid;
    
     $html = "
           <object width='200' height='200' id='flvPlayer'>
            <param name='allowFullScreen' value='true'>
            <param name='wmode' value='transparent'>
             <param name='allowScriptAccess' value='always'>
            <param name='movie' value='" . $player_path . "?movie=" . $video_path . "&volume=30&autoload=on&autoplay=on&vTitle=" . $izap_video->title . "&showTitle=yes' >
            <embed src='" . $player_path . "?movie=" . $video_path . "&volume=30&autoload=on&autoplay=on&vTitle=" . $izap_video->title . "&showTitle=yes' width='100' height='100' allowFullScreen='true' type='application/x-shockwave-flash' allowScriptAccess='always' wmode='transparent'>
           </object>";
    
//    $html = '<video id="example_video_1" class="video-js vjs-default-skin" controls autoplay preload="none" width="640" height="264"
//               poster="http://video-js.zencoder.com/oceans-clip.png"
//               data-setup="{}">
// 		
//        <source src="' . $video_path . '" type="video/mp4">
// 		<p class="vjs-no-js">
//        To view this video please enable JavaScript, and consider upgrading to a web browser that 
//        <a href="#" target="_blank">supports HTML5 video</a></p>
//</video>';

    $body = "$text $html";
//    $html = '
//        
//<video width="360" height="203" id="player1" src="'.$video_path.'" type="video/mp4" controls="controls"></video>
//	
//<script>
//MediaElement("player1", {success: function(me) {
//	
//	me.play();
//	
//	me.addEventListener("timeupdate", function() {
//		document.getElementById("time").innerHTML = me.currentTime;
//	}, false);
//	
//	document.getElementById("pp")["onclick"] = function() {
//		if (me.paused)
//			me.play();
//		else
//			me.pause();
//	};
//
//}});
//</script>
//';

    echo elgg_view('object/elements/full', array(
        'entity' => $izap_video,
        // 'icon' => $icon,
        'summary' => $summary,
        'body' => $body
    ));
} else {
    // brief view
   
    $params = array(
        'entity' => $izap_video,
        'metadata' => $metadata,
        'subtitle' => $subtitle,
        'content' => $excerpt,
    );
    $params = $params + $vars;
    $list_body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($icon, $list_body);
}

