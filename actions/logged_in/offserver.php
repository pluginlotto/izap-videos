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

if (IzapBase::pluginSetting(array(
                'name' => 'izap_api_key',
                'plugin' => GLOBAL_IZAP_ELGG_BRIDGE,
            )) == '') {
      register_error('Register API Key for izap-elgg-bridge');
      forward(REFERER);
    }
$videoValues = $izap_videos->input($izap_videos->videourl, 'url');

//echo "dfh;hdsf";c($videoValues);exit;
//if (empty($videoValues->videosrc)) {
//  register_error(elgg_echo('izap_videos:error'));
//  forward(REFERER);
//  exit;
//}
if ($posted_array['title'] == '') {
  $izap_videos->title = $videoValues->title;
}

if (trim($posted_array['description']) == '') {
  $izap_videos->description = (is_array($videoValues->description)) ? elgg_echo('izap_videos:noDescription') : $videoValues->description;
}

if ($posted_array['tags'] == '' && isset($videoValues->videotags)) {
  $izap_videos->tags = string_to_tag_array($videoValues->videotags);
}

$izap_videos->videosrc = $videoValues->videosrc;
$izap_videos->videotype = $videoValues->type;
$izap_videos->orignal_thumb = "izap_videos/" . $videoValues->type . "/orignal_" . $videoValues->filename;
$izap_videos->imagesrc = "izap_videos/" . $videoValues->type . "/" . $videoValues->filename;
$izap_videos->videotype_site = $videoValues->domain;
$izap_videos->converted = 'yes';
$izap_videos->setFilename($izap_videos->orignal_thumb);
$izap_videos->open("write");
if ($izap_videos->write($videoValues->filecontent)) {
  $thumb = get_resized_image_from_existing_file($izap_videos->getFilenameOnFilestore(), 120, 90);
  $izap_videos->setFilename($izap_videos->imagesrc);
  $izap_videos->open("write");
  if (!$izap_videos->write($thumb)) {
    register_error(elgg_echo('izap_videos:error:saving_thumb'));
  }
} else {
  register_error(elgg_echo('izap_videos:error:saving_thumb'));
}