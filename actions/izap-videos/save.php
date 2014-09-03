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

//check video url exist in case of offserver
//if ($page_url == 'offserver') {
//  if (!$video_url) {
//    register_error(elgg_echo('izap-videos_videourl:save:failed'));
//    forward(REFERER);
//  }
//  if (!filter_var($video_url, FILTER_VALIDATE_URL)) {
//    register_error(elgg_echo('izap-videos_invalidvideourl:save:failed'));
//    forward(REFERER);
//  }
//} else {
//  if ($_FILES['upload_video']['size'] == 0) {
//    register_error(elgg_echo('izap-videos_uploadVideo:save:failed'));
//    forward(REFERER);
//  }
//
//  if (!in_array(strtolower(end(explode('.', $_FILES['upload_video']['name']))), array('avi', 'flv', '3gp', 'mp4', 'wmv', 'mpg', 'mpeg'))) {
//    register_error(elgg_echo('izap-videos_invalidformat:save:failed'));
//    forward(REFERER);
//  }
//}


if ($guid == 0) {
    $izap_videos = new IzapVideo();
    $izap_videos->subtype = "izap_video";
    $izap_videos->container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
    $new = true;
} else {
    $entity = get_entity($guid);
    if (elgg_instanceof($entity, 'object', 'izap_video') && $entity->canEdit()) {
        $izap_videos = $entity;
    } else {
        register_error(elgg_echo('izap_video:error:post_not_found'));
        forward(get_input('forward', REFERER));
    }
}

//$izap_videos = new IzapVideo();
$izap_videos->subtype = 'izap_video';
$izap_videos->title = $title;
$izap_videos->description = $description;
$izap_videos->getOwnerGUID();
$izap_videos->access_id = $access_id;
$izap_videos->container_guid = $container_guid;
$izap_videos->tags = string_to_tag_array($tags);
$izap_videos->video_url = $video_url;

if ($page_url == 'offserver' || $page_url == 'onserver') {
    switch ($page_url) {
        case 'offserver':
            $izap_videos->save();
            //elgg_clear_sticky_form('izap_videos');
            //  system_messages(elgg_echo('izap-videos:Save:success'));
            // forward($izap_videos->getURL());
            break;
        case 'onserver':
            if ($_FILES['upload_video']['error'] == 0) {
                $set_video_name = $izap_videos->get_tmp_path($_FILES['upload_video']['name']);
                $izap_videos->setFilename($set_video_name);
                $izap_videos->open("write");
                $izap_videos->write(file_get_contents($_FILES['upload_video']['tmp_name']));
                $izap_videos->tmpfile = $izap_videos->getFilenameOnFilestore();
                //$process_video = $izap_videos->processOnserverVideo($_FILES['upload_video']['tmp_name'], $dest_path);
            }

            if ($_FILES['upload_thumbnail']['error'] == 0) {
                $set_image_name = $izap_videos->get_tmp_path($_FILES['upload_thumbnail']['name']);
                $izap_videos->setFilename($set_image_name);
                $izap_videos->open("write");
                $izap_videos->write(file_get_contents($_FILES['upload_thumbnail']['tmp_name']));
                $izap_videos->imagefile = $izap_videos->getFilenameOnFilestore();
                //move_uploaded_file($_FILES['upload_thumbnail']['tmp_name'], $izap_videos->getFilenameOnFilestore());
            }

            if ($izap_videos->save()) {
                $get_guid = $izap_videos->getGUID();
                $get_entity = get_entity($get_guid);

                if (file_exists($get_entity->tmpfile)) {
                    if ($page_url == 'onserver') {
                        $izap_videos->videosrc = elgg_get_site_url() . 'izap_videos_files/file/' . $get_entity->guid . '/' . elgg_get_friendly_title($get_entity->title) . '.flv';
                        izap_save_fileinfo_for_converting_izap_videos($get_entity->tmpfile, $get_entity, $get_entity->access_id);
                        $izap_videos->converted = 'yes';
                    }
                }
            }
            break;
    }
    //create river if new entity
    if ($new == true) {
        elgg_create_river_item(array(
            'view' => 'river/object/izap_video/create',
            'action_type' => 'create',
            'subject_guid' => elgg_get_logged_in_user_guid(),
            'object_guid' => $izap_videos->getGUID(),
        ));
    }
    elgg_clear_sticky_form('izap_videos');
    system_messages(elgg_echo('izap-videos:Save:success'));
    forward($izap_videos->getURL());
} else {
    if ($izap_videos->save()) {
        forward($izap_videos->getURL());
    }
}