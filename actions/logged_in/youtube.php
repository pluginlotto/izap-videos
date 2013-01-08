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

//recode attributes in the session to consider them after upload.

$youtube_attributes = get_input('attributes');
foreach($youtube_attributes as $key => $value){
  $attributes[$key] = trim($value);
}
$_SESSION['youtube_attributes'] = $attributes;
$video = IzapGYoutube::getAuthSubHttpClient(get_input('token', false));

//get youtube api authorization via users application access.
if (!($video instanceof IzapGYoutube)) {
    forward($video);
}else{
    // if we already have access token for youtube. than redirect user directly
    // on upload page.
    forward(IzapBase::setHref(array(
                        'action' => 'upload',
                        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                    )));
}