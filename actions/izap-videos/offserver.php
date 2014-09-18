<?php

  global $IZAPSETTINGS;
  $arg = parse_url($IZAPSETTINGS->apiUrl);
  $api = explode('&', $arg['query']);
  $key = explode('=', $api[0]);
  if ($key[1] == '') { 
    register_error('Register API Key for offserver video');
    forward(REFERER);
  }
  $video_data = array(
    'url' => $this->videourl,
    'title' => $this->title,
    'description' => $this->description,
  );
  $videoValues = input($video_data);
  $this->videosrc = $videoValues->videosrc;
  $this->videotype = $videoValues->type;
  $this->orignal_thumb = $this->get_tmp_path('original_' . $videoValues->filename);
  $this->imagesrc = $this->get_tmp_path($videoValues->filename);
  $this->videotype_site = $videoValues->domain;
  $this->converted = 'yes';
  $this->setFilename($this->orignal_thumb);
  $this->open("write");
  if ($this->write($videoValues->filecontent)) {
    $thumb = get_resized_image_from_existing_file($this->getFilenameOnFilestore(), 120, 90);
    $this->setFilename($this->imagesrc);
    $this->open("write");
    if (!$this->write($thumb)) {
      register_error(elgg_echo('izap_videos:error:saving_thumb'));
    }
  } else {
    register_error(elgg_echo('izap_videos:error:saving_thumb'));
  }
