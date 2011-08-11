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
// get the posted data to session
elgg_make_sticky_form('izap_videos');

/**
 * check for form error
 */
if(IzapBase::hasFormError()) {
  if(sizeof(IzapBase::getFormErrors())) {
    foreach(IzapBase::getFormErrors() as $error) {
      register_error($error);
    }
  }
  forward(REFERRER);
  exit;
}

IzapBase::loadLib(array(
  'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
  'lib' => 'izap_videos_lib',
));

$posted_array = IzapBase::getPostedAttributes();
$izap_videos = new IzapVideos((int)$posted_array['guid']);
IzapBase::updatePostedAttribute('tags', string_to_tag_array($posted_array['tags']));
IzapBase::updatePostedAttribute('video_views', 1);
$izap_videos->setAttributes();


switch (strtolower($posted_array['videoType'])) {
  case 'offserver':
  // if url is not valid then send it back
    if(!filter_var($posted_array['videoUrl'], FILTER_VALIDATE_URL)) {
      register_error(elgg_echo('izap_videos:error:notValidUrl'));
      forward(REFERRER);
      exit;
    }
    include_once (dirname(__FILE__) . '/OFFSERVER.php');
    break;

  case 'onserver':
    $izap_videos->access_id = ACCESS_PUBLIC;
    if(empty($izap_videos->title)) {
      register_error(elgg_echo('izap_videos:error:emptyTitle'));
      forward(REFERRER);
      exit;
    }
    include_once (dirname(__FILE__) . '/ONSERVER.php');
    break;

    default:
      break;
}
// if we have the optional image then replace all the previous values
if($_FILES['attributes']['error']['videoImage'] == 0 && in_array(strtolower(end(explode('.', $_FILES['attributes']['name']['videoImage']))), array('jpg', 'gif', 'jpeg', 'png'))) {
  
  $izap_videos->setFilename($izap_videos->orignal_thumb);
  $izap_videos->open("write");
  $izap_videos->write(file_get_contents($_FILES['attributes']['tmp_name']['videoImage']));

  $thumb = get_resized_image_from_existing_file($izap_videos->getFilenameOnFilestore(),120,90, true);

  $izap_videos->setFilename($izap_videos->imagesrc);
  $izap_videos->open("write");
  $izap_videos->write($thumb);
}


if(!$izap_videos->save()) {
  register_error(elgg_echo('izap_videos:error:save'));
  forward(REFERRER);
  exit;
}

// save the file info for converting it later  in queue
if($posted_array['videoType'] == 'ONSERVER' && $posted_array['guid'] == 0) {
  $izap_videos->videosrc = $CONFIG->wwwroot . 'pg/izap_videos_files/file/' . $izap_videos->guid . '/' . friendly_title($izap_videos->title) . '.flv';
  if(izap_get_file_extension($tmpUploadedFile) != 'flv') { // will only send to queue if it is not flv
    izapSaveFileInfoForConverting_izap_videos($tmpUploadedFile, $izap_videos, $posted_array['access_id']);
  }
}

// delete the sticky form
elgg_clear_sticky_form($posted_array['plugin']);

system_message(elgg_echo('izap_videos:success:save'));
forward($izap_videos->getUrl());
exit;