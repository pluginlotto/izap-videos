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

$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$description = get_input("description");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
$guid = (int) get_input('guid');
$tags = get_input("tags");
$video_url = get_input("video_url");

elgg_make_sticky_form('izap_videos');

//check video url validation
if (!$video_url) {
  register_error(elgg_echo('izap_video:save:failed'));
  forward(REFERER);
}

if ($guid == 0) {
  $izap_video = new IzapVideo();
  $izap_video->subtype = "izap_video";
  $izap_video->container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
  $new = true;
} else {
  $izap_video = get_entity($guid);
  if (!$izap_video->canEdit()) {
    system_message(elgg_echo('izap_video:save:failed'));
    forward(REFERRER);
  }
}

$izap_videos = new IzapVideo();
$izap_videos->subtype = 'izap_video';
$izap_videos->title = $title;
$izap_videos->description = $description;
$izap_videos->access_id = $access_id;
$izap_videos->container_guid = $container_guid;
$izap_videos->tags = string_to_tag_array($tags);
$izap_videos->video_url = $video_url;

if ($_FILES['upload']['error'] == 0 && in_array(strtolower(end(explode('.', $_FILES['upload']['name']))), array('jpg', 'gif', 'jpeg', 'png'))) {

  $image_name = $_FILES['upload']['name'];
  $izap_videos->setFilename('izap_videos/uploaded/image_' . $image_name);
  $izap_videos->open("write");
  $izap_videos->write(file_get_contents($_FILES['upload']['tmp_name']));
}

//$processed_video = $obj->processOnserverVideo($obj->video_url, $dest_path);

//if ($izap_videos->save()) {
//  elgg_clear_sticky_form('izap_videos');
//  forward($izap_videos->getURL());
//}
