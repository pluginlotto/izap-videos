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

$videoId = get_input('videoId');
$video = get_entity($videoId);

if(!elgg_instanceof($video, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE, GLOBAL_IZAP_VIDEOS_CLASS)) {
  register_error(elgg_echo('izap-videos:not_valid_video'));
  forward(REFERER);
  exit;
}

IzapBase::loadLib(array('plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN, 'lib' => 'izap_videos_lib'));
$attribs = $video->getAttributes();

$newVideo = new IzapVideos();
foreach($attribs as $attribute => $value) {
  $newVideo->$attribute = $value;
}
$newVideo->views = 1;
$newVideo->owner_guid = elgg_get_logged_in_user_guid();
$newVideo->container_guid = elgg_get_logged_in_user_guid();
$newVideo->access_id = $video->access_id;
$newVideo->copiedFrom = $video->owner_guid;
$newVideo->copiedVideoId = $videoId;
$newVideo->copiedVideoUrl = $video->getUrl();

izapCopyFiles_izap_videos($video->owner_guid, $video->imagesrc);

if($video->videotype == 'uploaded') {
  izapCopyFiles_izap_videos($video->owner_guid, $video->videofile);
  izapCopyFiles_izap_videos($video->owner_guid, $video->orignalfile);
}

c($newVideo);exit;
if($newVideo->save()) {
  system_message(elgg_echo('izap_videos:success:videoCopied'));
  forward($newVideo->getURL());
}else {
  system_message(elgg_echo('izap_videos:success:videoNotCopied'));
  forward(REFERER);
}
exit;