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
$guid = (int) get_input('guid', 0);
$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', 'izap_video')) {
    exit;
}

$get_video_name = end(explode('/', $entity->tmpfile));
$izapvideo_obj = new IzapVideo;
$set_video_name = $izapvideo_obj->get_tmp_path($get_video_name);

$elggfile_obj = new ElggFile;
$elggfile_obj->setFilename($set_video_name);
$read_content = $elggfile_obj->getFilenameOnFilestore($set_video_name);
$read_content = file_get_contents($read_content);

  //  echo mime_content_type($entity->tmpfile); //exit;
//$finfo = finfo_open(FILEINFO_MIME_TYPE);
//echo finfo_file($finfo, $entity->tmpfile);
//exit;
$content_type = 'video/x-msvideo';

header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($read_content));
header("Content-type: {$content_type}", true);

echo $read_content;
exit;
