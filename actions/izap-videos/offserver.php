<?php

  global $IZAPSETTINGS;
  if ($IZAPSETTINGS->apiUrl == '') {
    register_error('Register API Key for izap-elgg-bridge');
    forward(REFERER);
  }
  $videoValues = input($izap_videos->videourl, 'url');
  $izap_videos->videosrc = $videoValues->videosrc;
  $izap_videos->videotype = $videoValues->type;
  $izap_videos->orignal_thumb = $izap_videos->get_tmp_path('original_' . $videoValues->filename);
//  $izap_videos->orignal_thumb = "izap_videos/" . $videoValues->type . "/orignal_" . $videoValues->filename;
  $izap_videos->imagesrc = $izap_videos->get_tmp_path($videoValues->filename);
//  $izap_videos->imagesrc = "izap_videos/" . $videoValues->type . "/" . $videoValues->filename;
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
