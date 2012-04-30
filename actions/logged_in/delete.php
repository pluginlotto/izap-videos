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

$video = get_entity((int) get_input('video_id'));

if(elgg_instanceof($video, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE, GLOBAL_IZAP_VIDEOS_CLASS)) {
  $owner_username = $video->getOwnerUsername();
  if($video->delete()) {
    system_message(elgg_echo('izap_videos:deleted'));
  }else {
    register_error(elgg_echo('izap_videos:notdeleted'));
  }
}
forward(IzapBase::setHref(array(
        'action' => 'owner',
        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
        'page_owner' => $owner_username,
)));
exit;