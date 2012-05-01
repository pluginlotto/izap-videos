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
class IzapVideos extends IzapObject {

  private $form_attributes;

  public function __construct($guid = null) {
    global $IZAPSETTINGS;
    parent::__construct($guid);
    // set some initial values so that old videos can work
    if (empty($this->videosrc)) {
      $this->videosrc = $IZAPSETTINGS->filesPath . 'file/' . $this->guid . '/' . elgg_get_friendly_title($this->title) . '.flv';
    }

    // sets the defalut value for the old videos, if not set yet
    if (empty($this->converted)) {
      $this->converted = 'yes';
    }
    // set default form attributes
    $this->form_attributes = array(
        'title' => array(),
        'description' => array(),
        'container_guid' => array(),
        'access_id' => array(),
        'videourl' => array(),
        'videotype' => array(),
        'videoprocess' => array(),
        'tags' => array(),
        'categories' => array(),
        'comments_on' =>array()
    );
    if(!is_null($guid))
      return get_entity($guid);
  }

  protected function initialise_attributes() {
    parent::initializeAttributes();
    $this->attributes['subtype'] = GLOBAL_IZAP_VIDEOS_SUBTYPE;
  }

  public function getAttributesArray() {
    return $this->form_attributes;
  }

  /**
   * takes input and type of input and sends back the required parameters to save
   * a videos
   *
   * @param string $input video url, video file or video embed code
   * @param string $type url, file, embed
   * @return object
   */
  public function input($input, $type) {
    switch ($type) {
      case 'url':
        return $this->readUrl($input);
        break;
      case 'file':
        return $this->processFile($input);
        break;
      case 'embed':
        return $this->embedCode($input);
        break;
      default:
        return false;
        break;
    }
  }

   /**
   * used to read the url and process feed
   *
   * @param url $url url of the video site
   * @return object
   */
  protected function readUrl($url) {
    $urlFeed = new UrlFeed();
    $feed = $urlFeed->setUrl($url);
    return $feed;
  }

  /**
   * used to process the video file
   * @param <array> $file upload file name
   * @return object
   */
  protected function processFile($file) {
    $returnValue = new stdClass();
    $returnValue->type = 'uploaded';
    $fileName = $_FILES[$file['mainArray']]['name'][$file['fileName']];
    $error = $_FILES[$file['mainArray']]['error'][$file['fileName']];
    $tmpName = $_FILES[$file['mainArray']]['tmp_name'][$file['fileName']];
    $type = $_FILES[$file['mainArray']]['type'][$file['fileName']];
    $size = $_FILES[$file['mainArray']]['size'][$file['fileName']];
    // if error
    if ($error > 0) {
      return 104;
    }

    // if file is of zero size
    if ($size == 0) {
      return 105;
    }


    // check supported video type
    if (!izapSupportedVideos_izap_videos($fileName)) {
      return 106;
    }

    // check supported video size
    if (!izapCheckFileSize_izap_videos($size)) {
      return 107;
    }

    // upload the tmp file
    $newFileName = izapGetFriendlyFileName_izap_videos($fileName);
    $this->setFilename('tmp/' . $newFileName);
    $this->open("write");
    $this->write(file_get_contents($tmpName));
    $returnValue->tmpfile = $this->getFilenameOnFilestore();

    // take snapshot of the video
    $image = new izapConvert($returnValue->tmpfile);
    if ($image->photo()) {
      $retValues = $image->getValues(TRUE);
      if ($retValues['imagename'] != '' && $retValues['imagecontent'] != '') {
        $this->setFilename('izap_videos/uploaded/orignal_' . $retValues['imagename']);
        $this->open("write");
        if ($this->write($retValues['imagecontent'])) {
          $orignal_file_path = $this->getFilenameOnFilestore();

          $thumb = get_resized_image_from_existing_file($orignal_file_path, 120, 90);
          $this->setFilename('izap_videos/uploaded/' . $retValues['imagename']);
          $this->open("write");
          $this->write($thumb);

          $this->close();
          $returnValue->orignal_thumb = "izap_videos/uploaded/orignal_" . $retValues['imagename'];
          $returnValue->thumb = 'izap_videos/uploaded/' . $retValues['imagename'];
        }
      }
    }

    // check if it is flv, then dont send it to queue
    if (IzapBase::getFileExtension($returnValue->tmpfile) == 'flv') {
      $file_name = 'izap_videos/uploaded/' . $newFileName;

      $this->setFilename($file_name);
      $this->open("write");
      $this->write(file_get_contents($returnValue->tmpfile));

      $this->converted = 'yes';
      $this->videofile = $file_name;
      $this->orignalfile = $file_name;
      $returnValue->is_flv = 'yes';
      // remove the tmp file
      @unlink($returnValue->tmpfile);
    }
    return $returnValue;
  }

  /**
   * gets the video player according to the video type
   *
   * @param int $width width of video player
   * @param int $height height of video player
   * @param int $autoPlay autoplay option (1 | 0)
   * @param string $extraOptions extra options if available
   * @return HTML complete player code
   */
  public function getPlayer($width = 670, $height = 400, $autoPlay = 0, $extraOptions = '') {
    global $CONFIG, $IZAPSETTINGS;
    $html = '';

    if (filter_var($this->videosrc, FILTER_VALIDATE_URL)) {
      switch ($this->videotype) {
        case 'uploaded':
          if ($this->converted == 'yes') {
            $border_color1 = izapAdminSettings_izap_videos('izapBorderColor1');
            $border_color2 = izapAdminSettings_izap_videos('izapBorderColor2');
            $border_color3 = izapAdminSettings_izap_videos('izapBorderColor3');

            if (!empty($border_color3))
              $extraOptions .= '&btncolor=0x' . $border_color3;
            if (!empty($border_color1))
              $extraOptions .= '&accentcolor=0x' . $border_color1;
            if (!empty($border_color2))
              $extraOptions .= '&txtcolor=0x' . $border_color2;
            $html = "
           <object width='" . $width . "' height='" . $height . "' id='flvPlayer'>
            <param name='allowFullScreen' value='true'>
            <param name='wmode' value='transparent'>
             <param name='allowScriptAccess' value='always'>
            <param name='movie' value='" . $IZAPSETTINGS->playerPath . "?movie=" . $this->videosrc . $extraOptions . "&volume=30&autoload=on&autoplay=on&vTitle=" . $this->title . "&showTitle=yes' >
            <embed src='" . $IZAPSETTINGS->playerPath . "?movie=" . $this->videosrc . $extraOptions . "&volume=30&autoload=on&autoplay=on&vTitle=" . $this->title . "&showTitle=yes' width='" . $width . "' height='" . $height . "' allowFullScreen='true' type='application/x-shockwave-flash' allowScriptAccess='always' wmode='transparent'>
           </object>";
          }
          else {
            $html = elgg_echo('izap_videos:processed');
          }
          break;
        case 'embed':
        case 'others':
          $html = izapGetReplacedHeightWidth_izap_videos($height, $width, $this->videosrc);
          break;
      }
    } else {
      $html = izapGetReplacedHeightWidth_izap_videos($height, $width, $this->videosrc);
    }
    return $html;
  }

  /**
   * returns the thumbnail for the video
   *
   * @param boolean $pathOnly if we want the img src only or full <img ... /> tag
   * @param array $attArray attributes for the <img /> tag
   * @return HTML <img /> tag or image src
   */
  public function getThumb($pathOnly = false, $attArray = array(), $play_icon = false) {
    global $IZAPSETTINGS;
    $html = '';
    $imagePath = $IZAPSETTINGS->filesPath . 'image/' . $this->guid . '/' . elgg_get_friendly_title($this->title) . '.jpg';
    if ($pathOnly) {
      $html = $imagePath;
    } else {
      $attString = '';
      if (count($attArray) > 0) {
        foreach ($attArray as $att => $value) {
          $attString .= ' ' . $att . '="' . $value . '" ';
        }
      }
      $html = '<div style="position: relative; height:' . $att['height'] . 'px;width:' . $att['width'] . 'px;" title="' . $this->title . '">';
      $html .= '<img src="' . $imagePath . '"  ' . $attString . ' />';
      $html .= '<span class="izap_play_icon"><img src="' . $IZAPSETTINGS->graphics . 'c-play.png" /></span>';
      $html .= '</div>';
    }

    return $html;
  }

  public function getOrignalThumb() {
    global $IZAPSETTINGS;
    return $IZAPSETTINGS->filesPath . 'image/' . $this->guid . '/orignal/' . elgg_get_friendly_title($this->title) . '.jpg';
  }

  /**
   * displays the recently added or edited thumbnail of videos in the site's activity page
   * 
   * @global  type $IZAPSETTINGS
   * @param   type $width
   * @param   type $height
   * @return       string 
   */
  public function getAjaxedThumb($width = 250, $height = 200) {
    global $IZAPSETTINGS;
    $unique = md5($this->guid . '-' . $width . '-' . $height);
    $raw_video = IzapBase::setHref(
                    array(
                        'action' => 'rawvideo',
                        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                        'page_owner' => FALSE,
                        'vars' => array(
                            $this->guid, $height, $width
                        )
                    )
    );
    $html = '<div class="izap_ajaxed_thumb_div" id="load_video_' . $unique . '" style="position: relative;" >
      <a href="' . $raw_video . '" rel="' . $unique . '" class="izap_ajaxed_thumb">
        <img src="' . $this->getThumb(TRUE) . '" style="max-height:90px; max-width: 90px;" />
		<span class="izap_play_icon"><img src="' . $IZAPSETTINGS->graphics . 'c-play.png" /></span>
      </a>
      </div>';

    return $html;
  }

  /**
   * function to return the icon path
   * @uses getThumb()
   * @return url
   */
  public function getIconURL($size = 'medium') {
    return $this->getThumb(TRUE);
  }

  public function getURL() {
    global $CONFIG;
    return $CONFIG->wwwroot . 'videos/play/' . IzapBase::getContainerUsername($this) . '/' . $this->guid . '/' . elgg_get_friendly_title($this->title);
  }

  /**
   * updates the video views
   */
  public function updateViews() {
    if ($this->converted == 'yes') {
      IzapBase::getAllAccess();
      ++$this->views;
      IzapBase::removeAccess();
    }
  }

  /**
   * returns the video views
   *
   * @return int video views
   */
  public function getViews() {
    return (int) $this->views;
  }

  /**
   * checks if the video can be copied
   *
   * @return boolean
   */
  public function canCopy() {
    if ($this->owner_guid != elgg_get_logged_in_user_guid()
            && $this->converted == 'yes'
            && isloggedin()
    ) {
      return TRUE;
    }

    // default
    return FALSE;
  }

  /**
   * returns the full video attributes for copying the video
   *
   * @return object
   */
  public function getAttributes() {
    $attrib = new stdClass();
    $attrib->guid = $this->guid;
    $attrib->title = $this->title;
    $attrib->owner_guid = $this->owner_guid;
    $attrib->container_guid = $this->container_guid;
    $attrib->description = $this->description;
    $attrib->access_id = $this->access_id;
    $attrib->tags = $this->tags;
    $attrib->views = $this->views;
    $attrib->videosrc = $this->videosrc;
    $attrib->videotype = $this->videotype;
    $attrib->imagesrc = $this->imagesrc;
    $attrib->videotype_site = $this->videotype_site;
    $attrib->videotype_id = $this->videotype_id;
    $attrib->converted = $this->converted;
    $attrib->videofile = $this->videofile;
    $attrib->orignalfile = $this->orignalfile;
    return $attrib;
  }

  public function getRelatedVideos($max_limit = 5) {
    $tags = $this->tags;
    if (!is_array($tags) && !empty($tags)) {
      $tags = array($tags);
    }

    $options['type'] = $this->getType();
    $options['subtype'] = $this->getSubtype();

    if (sizeof($tags)) {
      $total_tags = count($tags);
      $per_tag_limit = (int) ((int) $max_limit / (int) $total_tags);
      $per_tag_limit = ($per_tag_limit) ? $per_tag_limit + 1 : 1;
      foreach ($tags as $tag) {
        if ((real) get_version(true) <= 1.6) {
          $entities[] = get_entities_from_metadata('tags', $tag, $options['type'], $options['subtype'], 0, $per_tag_limit);
        } else {
          $options['metadata_name'] = 'tags';
          $options['metadata_value'] = $tag;
          $entities[] = elgg_get_entities_from_metadata($options);
        }
      }
    }

    if ($entities) {
      foreach ($entities as $videos) {
        foreach ($videos as $video) {
          if ($video->guid != $this->guid) {
            $return[$video->guid] = $video;
          }
        }
      }
    }

    $return = array_chunk($return, $max_limit);
    return $return[0];
  }

  /**
   * deletes a video, override for the parent delete
   *
   * @return boolean
   */
  public function delete() {
    global $CONFIG;

    if ($this->videotype == 'uploaded' && $this->converted == 'no') {
      // delete entity from queue and trash with related media
      $queue_object = new izapQueue();
      $queue_object->delete_from_trash($this->guid, true);
      $queue_object->delete($this->guid, true);
    }

    $imagesrc = $this->imagesrc;
    $filesrc = $this->videofile;
    $ofilesrc = $this->orignalfile;
    $orignal_thumb = $this->orignal_thumb;

    //delete entity from elgg db and corresponding files if exist
    $this->setFilename($imagesrc);
    $image_file = $this->getFilenameOnFilestore();
    file_exists($image_file) && @unlink($image_file);

    $this->setFilename($filesrc);
    $video_file = $this->getFilenameOnFilestore();
    file_exists($video_file) && @unlink($video_file);

    $this->setFilename($ofilesrc);
    $orignal_file = $this->getFilenameOnFilestore();
    file_exists($orignal_file) && @unlink($orignal_file);

    $this->setFilename($orignal_thumb);
    $orignal_thumb_file = $this->getFilenameOnFilestore();
    file_exists($orignal_thumb_file) && @unlink($orignal_thumb_file);

    return delete_entity($this->guid, TRUE);
  }

}
