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
$width= '100px';
$height = '100px';
$get_image = $object->imagefile;
//$thumb = "mod/izap-videos/thumbnail.php?file_guid=$object->guid";
$player_path = elgg_get_site_url() . 'mod/izap-videos/player/izap_player.swf' ;
//$content = $object->getVideoPlayer($player_path,$object,$width,$height);

//$path = 'izap-videos/player' . $object->guid ;
//if($get_image) {
//    $content = '<a href= "izap-videos/"><img src = "' . $thumb . '" width="50"/></a>';
//}

$content .= $object->description;
echo elgg_view('river/elements/layout', array(
    'item' => $vars['item'],
    'message' => $content,
));
