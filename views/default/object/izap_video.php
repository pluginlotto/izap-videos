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
$izap_video = elgg_extract('entity', $vars, FALSE); 
//echo '<pre>'; print_r($izap_video);
if (!$izap_video) {
    return TRUE;
}
//echo $izap_video->tmpfile;
$owner = $izap_video->getOwnerEntity(); 
$container = $izap_video->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = $izap_video->excerpt;
if (!$excerpt) {
    $excerpt = elgg_get_excerpt($izap_video->description);
}

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
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

    $body = elgg_view('output/longtext', array(
        'value' => $izap_video->description,
        'class' => 'izap-video-post',
    ));

    $params = array(
        'entity' => $izap_video,
        'title' => false,
        'metadata' => $metadata,
        'subtitle' => $subtitle,
    );
    $params = $params + $vars;
    $summary = elgg_view('object/elements/summary', $params);

    echo elgg_view('object/elements/full', array(
        'summary' => $summary,
        'icon' => $owner_icon,
        'body' => $body,
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

    echo elgg_view_image_block($owner_icon, $list_body);
}

