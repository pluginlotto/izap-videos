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


/**
 * class for providing the api to fetch and convert video, that other plugins can
 * use to enable the feature of adding video from them.
 * (CURRENTLY SUPPORTING URLs ONLY)
 * just need to include the small code and you will get the video player
 * eg.
 *
 * if(is_plugin_enabled('izap_videos')){
 *    $video = new IZAPVideoApi($input); // input is URL or FILEPATH
 *    $return = $video->getFeed($width, $height);
 *  }
 *
 *
 */

class IZAPVideoApi {
  private $input;
  public $errors;

  public function __construct($input = '') {
    if(!empty($input)) {
      $this->input = $input;
    }
  }

  /**
   * converts the video
   *
   * @return <type>
   */
  public function convertVideo() { // experimental
    if(!izapSupportedVideos_izap_videos($this->input)) {
      return elgg_echo('izap_videos:error:code:106');
    }

    $convert_video = new izapConvert($this->input);
    if($convert_video->photo()) {
      if($convert_video->izap_video_convert()) {
        return $convert_video->getValuesForAPI();
      }
    }

    // if nothing is processes so far
    return FALSE;
  }

  /**
   * returns the video player code, if the input is URL
   *
   * @param int $width width of video player
   * @param int $height height of video playe
   * @param int $autoPlay autocomplete option
   * @return HTML player code
   */
  public function getVideoFeed($width = 640, $height = 385) {
    $izap_videos = new IzapVideos();
    $feed = $izap_videos->input($this->input, 'url');
    return $feed;
  }

  /**
   *
   * @return ElggEntity VIDEOS
   */
  public function getVideoEntity() {
    if(!filter_var( $this->input, FILTER_VALIDATE_URL)) {
      $this->errors[] = 'Not valid url, currently supported for OFFSERVER videos only';
      return FALSE;
    }

    // try saving the entity now
    $izap_videos = new IzapVideos();
    $izap_videos->access_id = ACCESS_PUBLIC;

    $return = $izap_videos->input($this->input, 'url');

    if(isset($return->success) && $return->success === FALSE) {
      $this->errors[] = $return->message;
      return FALSE;
    }

    if($return->videoSrc == '' || $return->fileContent == '') {
      $this->errors[] = elgg_echo('izap_videos:error');
      return FALSE;
    }


    $izap_videos->title = $return->title;
    $izap_videos->description = $return->description;
    $izap_videos->tags = string_to_tag_array($return->videoTags);

    $izap_videos->videosrc = $return->videoSrc;
    $izap_videos->videotype = $return->type;
    $izap_videos->orignal_thumb = "izap_videos/" . $return->type . "/orignal_" . $return->fileName;
    $izap_videos->imagesrc = "izap_videos/" . $return->type . "/" . $return->fileName;
    $izap_videos->videotype_site = $return->domain;
    $izap_videos->converted = 'yes';
    $izap_videos->setFilename($izap_videos->orignal_thumb);
    $izap_videos->open("write");
    if($izap_videos->write($return->fileContent)) {
      $thumb = get_resized_image_from_existing_file($izap_videos->getFilenameOnFilestore(),120,90, true);
      $izap_videos->setFilename($izap_videos->imagesrc);
      $izap_videos->open("write");
      if(!$izap_videos->write($thumb)) {
        $this->errors[] = elgg_echo('izap_videos:error:saving_thumb');
        return FALSE;
      }
    }else {
      $this->errors[] = elgg_echo('izap_videos:error:saving_thumb');
      return FALSE;
    }

    // if every thing is good till here now we can save it.
    if(!$izap_videos->save()) {
      $this->errors[] = register_error(elgg_echo('izap_videos:error:save'));
      return FALSE;
    }

    return $izap_videos;
  }

  public function getErrors() {
    return $this->errors;
  }
}