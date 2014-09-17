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

//maintain input field values if saving fails

  elgg_make_sticky_form('izap_videos');

  elgg_load_library('elgg:izap_video');

  $title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
  $description = get_input("description");
  $access_id = (int) get_input("access_id");
  $container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
  $guid = (int) get_input('guid');
  $tags = get_input("tags");
  $video_url = get_input("video_url");
  $page_url = end(explode('/', get_input('page_url')));

  if ($guid == 0) {
    $izap_videos = new IzapVideo();
  } else {
    $entity = get_entity($guid);
    if (elgg_instanceof($entity, 'object', 'izap_video') && $entity->canEdit()) {
      $izap_videos = $entity;
    } else {
      register_error(elgg_echo('izap_video:error:post_not_found'));
      forward(get_input('forward', REFERER));
    }
  }
  $data = array(
    'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
    'title' => $title,
    'description' => $description,
    'access_id' => $access_id,
    'container_guid' => $container_guid,
    'tags' => string_to_tag_array($tags),
    'videourl' => $video_url,
    'videoprocess' => $page_url,
  );

  if ($izap_videos->saveVideo($data)) {
    forward($izap_videos->getURL());
  }
  