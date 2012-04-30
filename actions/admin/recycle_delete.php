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
IzapBase::loadlib(array(
            'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
            'lib' => 'izap_videos_lib'
        ));
$flag = true;
$posted_array = get_input('attributes');
if (is_array($posted_array)) {
  foreach ($posted_array as $key => $value) {
    if (isset($value['action'])) {
      $video_guid = $key;
      $queue_object = new izapQueue();
      $video_to_be_deleted = $queue_object->get_from_trash($video_guid);
      if ($value['message'] != '') {
        notify_user($video_to_be_deleted[0]['owner_id'],
                $CONFIG->site_guid,
                elgg_echo('izap_videos:notifySub:video_deleted'),
                $value['message']
        );
      }
      if ($value['action'] == 'delete') {
        $video = get_entity($video_guid);
        if (elgg_instanceof($video, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE)) {
          $video->delete();
        }  else {
          $flag=false;
        }
        // delete from sqlite also
        $queue_object->delete($video_guid);
      } elseif ($value['action'] == 'restore') {
        if(!$queue_object->restore($video_guid)){
          $flag=false;
        }
      }
    }
  }

  // re-tirgger the queue processing
  izapTrigger_izap_videos();
}if($flag==true){
system_message(elgg_echo('izap_videos:adminSettings:deleted_from_trash'));
forward($_SERVER['HTTP_REFERER']);
exit;
}  else {
  system_message(elgg_echo('izap_videos:adminSettings:deleted_not_from_trash'));
forward($_SERVER['HTTP_REFERER']);
exit;
}