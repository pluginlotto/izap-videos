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
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
$guid = (int) get_input('file_guid', 0);
$entity = get_entity($guid);

$izapqueue_obj = new izapQueue();
$get_converted_video = $izapqueue_obj->get_converted_video($guid);

if (!elgg_instanceof($entity, 'object', 'izap_video')) {
    exit;
}

if ($get_converted_video) {
$get_video_name = end(explode('/', $get_converted_video[0]['main_file']));
$izapvideo_obj = new IzapVideo;
$set_video_name = $izapvideo_obj->get_tmp_path($get_video_name); 
$set_video_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $set_video_name) . '_c.flv';


$elggfile_obj = new ElggFile;
$elggfile_obj->owner_guid = $entity->owner_guid;
$elggfile_obj->setFilename($set_video_name);


if (file_exists($elggfile_obj->getFilenameOnFilestore())){// echo $elggfile_obj->getFilenameOnFilestore(); exit;  
$contents = $elggfile_obj->grabFile();
}

//$read_content = $elggfile_obj->getFilenameOnFilestore();
//$read_content = file_get_contents($read_content);


//echo mime_content_type($elggfile_obj->getFilenameOnFilestore()); exit;
//    echo filesize($read_content);
//    $finfo = finfo_open(FILEINFO_MIME_TYPE);
//    echo finfo_file($finfo, $read_content);
//    exit;


$content_type = 'video/x-flv';

header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
header("Pragma: public", true);
header("Cache-Control: public", true);

header("Content-Length: " . strlen($contents));
header("Content-type: {$content_type}", true);

echo $contents;
exit;
}