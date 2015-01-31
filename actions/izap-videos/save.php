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
 * Maintain input field values if saving fails
 * 
 * @version 5.0
 */
elgg_make_sticky_form('izap_videos');

elgg_load_library('elgg:izap_video');

$title = strip_tags(get_input('title', '', false));

$description = get_input("description");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid');
$guid = (int) get_input('guid');
$tags = get_input("tags");
$video_url = get_input("video_url");
$page_url = end(explode('/', get_input('page_url')));
$youtube_cats = get_input("youtube_cats");

$new = false;
// mark it as new vidoe if guid is not there yet or entity is actually new.
if ($guid == 0) {
	$new = true;
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

if (isset($_FILES['upload_video'])) {
	$izap_videos->checkFile($_FILES['upload_video']);
}
if (isset($title)) {
	$izap_videos->checkTitle($title);
}
if (isset($video_url)) {
	$izap_videos->checkUrl($video_url);
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
	'youtube_cats' => $youtube_cats,
);

if ($saved = $izap_videos->saveVideo($data)) {
	//create river if new entity
	if ($new) {
		if (is_callable('elgg_create_river_item')) {
			elgg_create_river_item(array(
				'view' => 'river/object/izap_video/create',
				'action_type' => 'create',
				'subject_guid' => elgg_get_logged_in_user_guid(),
				'object_guid' => $izap_videos->getGUID(),
			));
		} else {
			add_to_river('river/object/izap_video/create', 'create', elgg_get_logged_in_user_guid(), $this->getGUID());
		}
	}
	$saved->save();
	system_messages(elgg_echo('izap-videos:Save:success'));
	elgg_clear_sticky_form('izap_videos');
	forward($izap_videos->getURL($izap_videos->getOwnerEntity(), GLOBAL_IZAP_VIDEOS_PAGEHANDLER));
}
  