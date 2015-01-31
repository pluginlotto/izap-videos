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

// Get file GUID
$file_guid = (int) get_input('file_guid', 0);

// Get file thumbnail size
//$size = get_input('size', 'small');

$file = get_entity($file_guid);
if (!elgg_instanceof($file, 'object', 'izap_video')) {
    exit;
} 
$filename = end(explode('/', $file->imagesrc));
$filename = '' . $filename;

$izap_video_obj = new IzapVideo;
$thumbfile = $izap_video_obj->getTmpPath($filename);

// Grab the file
if ($thumbfile && !empty($thumbfile)) {
    $readfile = new ElggFile();
    $readfile->owner_guid = $file->owner_guid;
    $readfile->setFilename($thumbfile);
    if(file_exists($readfile->getFilenameOnFilestore())){
        $contents = $readfile->grabFile();
    }
    $content_type = 'image/jpeg';
    
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
    header("Pragma: public", true);
    header("Cache-Control: public", true);
    header("Content-Length: " . strlen($contents));
    header("Content-type: {$content_type}", true);

    echo $contents;
    exit;
}

