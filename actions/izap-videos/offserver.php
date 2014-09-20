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
  $this->saveYouTubeVideoData($video_data);
  
