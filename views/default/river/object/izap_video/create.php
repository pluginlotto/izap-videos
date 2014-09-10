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
  
  if($object->imagefile){
    $thumbnail_image = $get_image; 
  }
  $get_player_path = elgg_get_site_url() .'';
  $content = '<a href="" class = "ajax_load_video"><img src="'.$thumbnail_image.'"  style= "max-height:90px; max-width: 90px;" /></a>';
  $content .= '<img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/' . 'c-play.png" class="play" id="play_' . $object->guid . '" onclick = "video(' . $object->guid . ')"/>';
  
  $content .= $object->description;
  echo elgg_view('river/elements/layout', array(
    'item' => $vars['item'],
    'message' => $content,
  ));
  