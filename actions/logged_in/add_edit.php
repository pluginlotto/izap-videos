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
if (IzapBase::hasFormError()) {
  if (sizeof(IzapBase::getFormErrors())) {
    foreach (IzapBase::getFormErrors() as $error) {
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
$izap_videos = new IzapVideos((int) $posted_array['guid']);
IzapBase::updatePostedAttribute('tags', string_to_tag_array($posted_array['tags']));
IzapBase::updatePostedAttribute('video_views', 1);
$izap_videos->setAttributes();

if ($izap_videos->isNewRecord()) {  // only include for adding video
  switch ($izap_videos->videoprocess) {
    case 'offserver':
      // if url is not valid then send it back
      if (!filter_var($izap_videos->videourl, FILTER_VALIDATE_URL)) {
        register_error(elgg_echo('izap_videos:error:notValidUrl'));
        forward(REFERRER);
        exit;
      }
      include_once (dirname(__FILE__) . '/offserver.php');
      break;
    case 'onserver':
      $izap_videos->access_id = ACCESS_PUBLIC;
      include_once (dirname(__FILE__) . '/onserver.php');
      break;
    case 'youtube':
      // if url is not valid then send it back
      include_once (dirname(__FILE__) . '/youtube.php');
      forward(REFERRER);
      break;
    case 'default':
      $is_status = (get_input('status') == 200)?true:false;
      $id = get_intput('id');
      $izap_videos->videourl = 'http://www.youtube.com/watch?v='.$id;
      //handle youtube video upload when it get back to the same action.
      if (!filter_var($izap_videos->videourl, FILTER_VALIDATE_URL)) {
        register_error(elgg_echo('izap_videos:error:notValidUrl'));
        forward(REFERRER);
        exit;
      }
      include_once (dirname(__FILE__) . '/offserver.php');
      break;
  }
}

// if we have the optional image then replace all the previous values
if ($_FILES['attributes']['error']['videoimage'] == 0 && in_array(strtolower(end(explode('.', $_FILES['attributes']['name']['videoimage']))), array('jpg', 'gif', 'jpeg', 'png'))) {

  $izap_videos->setFilename($izap_videos->orignal_thumb);
  $izap_videos->open("write");
  $izap_videos->write(file_get_contents($_FILES['attributes']['tmp_name']['videoimage']));

  $thumb = get_resized_image_from_existing_file($izap_videos->getFilenameOnFilestore(), 120, 90, true);

  $izap_videos->setFilename($izap_videos->imagesrc);
  $izap_videos->open("write");
  $izap_videos->write($thumb);
}

if (!$izap_videos->save()) {
  register_error(elgg_echo('izap_videos:error:save'));
  forward(REFERRER);
  exit;
}

// save the file info for converting it later  in queue
if ($izap_videos->videoprocess == 'onserver') {
  $izap_videos->videosrc = $CONFIG->wwwroot . 'izap_videos_files/file/' . $izap_videos->guid . '/' . friendly_title($izap_videos->title) . '.flv';
  if (IzapBase::getFileExtension($tmpUploadedFile) != 'flv') { // will only send to queue if it is not flv
    izapSaveFileInfoForConverting_izap_videos($tmpUploadedFile, $izap_videos, $izap_videos->access_id);
  }
}

// delete the sticky form
elgg_clear_sticky_form(GLOBAL_IZAP_VIDEOS_PLUGIN);

system_message(elgg_echo('izap_videos:success:save'));
forward($izap_videos->getUrl());
exit;