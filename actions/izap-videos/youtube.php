<?php

  $_SESSION['youtube_attributes'] = $this;
  $video = IzapGYoutube::getAuthSubHttpClient(get_input('token', false));

//get youtube api authorization via users application access.
  if (!($video instanceof IzapGYoutube)) {
    forward($video);
  } else {
    // if we already have access token for youtube. than redirect user directly
    // on upload page.
    forward(setHref(array(
      'action' => 'upload',
      'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
    )));
  }

