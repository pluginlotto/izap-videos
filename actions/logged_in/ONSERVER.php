<?php
/**
 * iZAP izap_videos
 *
 * @package Elgg videotizer, by iZAP Web Solutions.
 * @license GNU Public License version 3
 * @Contact iZAP Team "<support@izap.in>"
 * @Founder Tarun Jangra "<tarun@izap.in>"
 * @link http://www.izap.in/
 * 
 */

$return_values = $izap_videos->input(
        array(
        'file' => $_FILE,
        'mainArray' => 'attributes',
        'fileName' => 'videoFile',
        ),
        'file');

if(!is_object($return_values)) {
  register_error(elgg_echo('izap_videos:error:code:' . $return_values));
  forward($_SERVER['HTTP_REFERER']);
  exit;
}

if(empty($return_values->type) || ($return_values->is_flv !='yes' && !file_exists($return_values->tmpFile))) {
  register_error(elgg_echo('izap_videos:error:notUploaded'));
  forward($_SERVER['HTTP_REFERER']);
  exit;
}

$izap_videos->videotype = $return_values->type;
if($return_values->thumb) {
  $izap_videos->orignal_thumb = $return_values->orignal_thumb;
  $izap_videos->imagesrc = $return_values->thumb;
}else {
  $izap_videos->imagesrc = $CONFIG->wwwroot . 'mod/izap_videos/_graphics/video_converting.gif';
}


if($return_values->is_flv != 'yes') {
  $izap_videos->converted = 'no';
  $izap_videos->videofile = 'nop';
  $izap_videos->orignalfile = 'nop';
}
$tmpUploadedFile = $return_values->tmpFile;