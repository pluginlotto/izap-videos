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
IzapBase::gatekeeper();
IzapBase::loadLib(array(
  'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
  'lib' => 'izap_videos_lib'
));
$user_guid = get_loggedin_userid();
$video = get_entity(get_input('guid', 0));

if(get_input('izap_action', FALSE) == 'remove'){
  izap_remove_favorited($video);
  system_message(elgg_echo('izap_videos:favorite_removed'));
}else if($video instanceof IzapVideos) {
  izapGetAccess_izap_videos();
  $old_array = $video->favorited_by;
  $new_array = array_merge((array) $old_array, (array) $user_guid);
  $video->favorited_by = array_unique($new_array);
  izapRemoveAccess_izap_videos();
  system_message(elgg_echo('izap_videos:favorite_saved'));
}
forward(REFERER);
exit;

