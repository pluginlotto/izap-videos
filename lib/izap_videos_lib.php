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
 * checks if the field is null or not
 *
 * @param variable $input any variable
 * @param array $exclude in case if we want to exclude some values
 * @return boolean true if null else false
 */
function izapIsNull_izap_videos($input, $exclude = array()) {
  if (!is_array($input)) {
    $input = array($input);
  }

  if (count($input) >= 1) {
    foreach ($input as $key => $value) {
      if (!in_array($key, $exclude)) {
        //if(is_null($value) || empty($value)){
        if (empty($value)) {
          return TRUE;
        }
      }
    }
  } else {
    return TRUE;
  }

  return FALSE;
}

/**
 * this converts the array into object
 *
 * @param array $array
 * @return object
 */
function izapArrayToObject_izap_videos($array) {
  if (!is_array($array))
    return FALSE;

  $obj = new stdClass();
  foreach ($array as $key => $value) {
    if ($key != '' && $value != '') {
      $obj->$key = $value;
    }
  }

  return $obj;
}

/**
 * sets or gets the private settings for the izap_videos
 *
 * @param string $settingName setting name
 * @param mix $values sting or array of value
 * @param boolean $override if we want to force override the value
 * @param boolean $makeArray if we want the return value in the array
 * @return value array or string
 */
function izapAdminSettings_izap_videos($settingName, $values = '', $override = false, $makeArray = false) {
  $send_array = array(
      'name' => $settingName,
      'value' => $values,
      'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
  );

  return IzapBase::pluginSetting($send_array);
}

/**
 * checks if it is windows
 *
 * @return boolean TRUE if windows else FALSE
 */
function izapIsWin_izap_videos() {
  if (strtolower(PHP_OS) == 'winnt') {
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
 * gets the video add options from the admin settings
 *
 * @return array
 */
function izapGetVideoOptions_izap_videos() {
  $videoOptions = izapAdminSettings_izap_videos('izapVideoOptions', '', FALSE, TRUE);
  return $videoOptions;
}

/**
 * this function will check that is the given id is of izap_videos
 *
 * @param int $videoId video id
 * @return video entity or FALSE
 */
function izapVideoCheck_izap_videos($videoId, $canEditCheck = FALSE) {
  $videoId = (int) $videoId;
  if ($videoId) {
    $video = get_entity($videoId);

    if ($video && $canEditCheck && !$video->canEdit()) {
      forward();
    }

    if ($video && ($video instanceof IzapVideos)) {
      return $video;
    }
  }

  // if it reaches here then certainly send back
  forward();
}

/**
 * this function saves the entry for futher processing
 * @param string $file main filepath
 * @param int $videoId video guid
 * @param int $ownerGuid owner guid
 * @param int $accessId access id to be used after completion of encoding of video
 */
function izapSaveFileInfoForConverting_izap_videos($file, $video, $defined_access_id = 2) {
// this will not let save any thing if there is no file to convert
  if (!file_exists($file) || !$video) {
    return FALSE;
  }
  $queue = new izapQueue();
  $queue->put($video, $file, $defined_access_id);
  //izapRunQueue_izap_videos();
  izapTrigger_izap_videos();
}

/**
 * this function triggers the queue
 *
 * @global <type> $CONFIG
 */
function izapTrigger_izap_videos() {
  global $CONFIG;
  $PHPpath = izapGetPhpPath_izap_videos();
  $file_path = elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/izap_convert_video.php';
  if (!izapIsQueueRunning_izap_videos()) {
    if (izapIsWin_izap_videos ()) {
      pclose(popen("start \"MyProcess\" \"cmd /C " . $PHPpath . " " . $file_path, "r"));
    } else {
      exec($PHPpath . ' ' . $file_path . ' izap web > /dev/null 2>&1 &', $output);
    }
  }
}

/**
 * this function gives the path of PHP
 *
 * @return string path
 */
function izapGetPhpPath_izap_videos() {
  $path = izapAdminSettings_izap_videos('izapPhpInterpreter');
  $path = html_entity_decode($path);
  if (!$path)
    $path = '';
  return $path;
}

/**
 * this function checks if the queue is running or not
 *
 * @return boolean TRUE if yes or FALSE if no
 */
function izapIsQueueRunning_izap_videos() {
  // check for *nix machine. For windows, it is under development
  $queue_object = new izapQueue();

  $numberof_process = $queue_object->check_process();
  if ($numberof_process > 0) {
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
 * resets queue
 *
 * @return boolean
 */
function izapResetQueue_izap_videos() {
  return izapAdminSettings_izap_videos('isQueueRunning', 'no', TRUE);
}

/**
 * clears queue and resets it
 *
 * @return boolean
 */
function izapEmptyQueue_izap_videos() {
  $pending_videos = izapGetNotConvertedVideos_izap_videos();
  if ($pending_videos) {
    foreach ($pending_videos as $video) {
      $video->delete();
    }
  }

  return izapResetQueue_izap_videos();
}

/**
 * grants the access
 *
 * @param <type> $functionName
 */
function izapGetAccess_izap_videos() {
  izap_access_override(array('status' => TRUE));
}

/**
 * remove access
 *
 * @global global $CONFIG
 * @param string $functionName
 */
function izapRemoveAccess_izap_videos() {
  izap_access_override(array('status' => FALSE));
}

function izap_access_override($params=array()) {
  global $CONFIG;

  if ($params['status']) {
    $func = "elgg_register_plugin_hook_handler";
  } else {
    $func = "elgg_unregister_plugin_hook_handler";
  }

  $func_name = "izapGetAccessForAll_izap_videos";

  $func("premissions_check", "all", $func_name, 9999);
  $func("container_permissions_check", "all", $func_name, 9999);
  $func("permissions_check:metadata", "all", $func_name, 9999);
}

/**
 * elgg hook to override permission check of entities (izap_videos, izapVideoQueue, izap_recycle_bin)
 *
 * @param <type> $hook
 * @param <type> $entity_type
 * @param <type> $returnvalue
 * @param <type> $params
 * @return <type>
 */
function izapGetAccessForAll_izap_videos($hook, $entity_type, $returnvalue, $params) {
  return TRUE;
}

function izapRunQueue_izap_videos() {
  $queue_object = new izapQueue();
  $queue = $queue_object->fetch_videos();
  if (is_array($queue)) {
    foreach ($queue as $pending) {
      $converted = izapConvertVideo_izap_videos($pending['main_file'], $pending['guid'], $pending['title'], $pending['url'], $pending['owner_id']);
      if ($converted['error']) {
        $result = $queue_object->move_to_trash($pending['guid']);
      } else {
        $queue_object->delete($pending['guid']);
      }
      izap_update_all_defined_access_id($pending['guid'], $pending['access_id']);
    }

    // recheck if there is new video in the queue
    if ($queue_object->count() > 0) {
      izapRunQueue_izap_videos();
    }
  }
  return true;
}

/**
 * this function gets the site admin
 *
 * @param boolean $guid if only guid is required
 * @return mix depends on the input and result
 */
function izapGetSiteAdmin_izap_videos($guid = FALSE) {
  $admin = get_entities_from_metadata('admin', 1, 'user', '', 0, 1, 0);
  if ($admin[0]->admin || $admin[0]->siteadmin) {
    if ($guid)
      return $admin[0]->guid;
    else
      return $admin[0];
  }
  return FALSE;
}

/**
 * this function copies the files from one location to another
 *
 * @param int $sourceOwnerGuid guid of the file owner
 * @param string $sourceFile source file location
 * @param int $destinationOwnerGuid guid of new file owner, if not given then takes loggedin user id
 * @param string $destinationFile destination location, if blank then same as source
 */
function izapCopyFiles_izap_videos($sourceOwnerGuid, $sourceFile, $destinationOwnerGuid = 0, $destinationFile = '') {
  $filehandler = new ElggFile();

  $filehandler->owner_guid = $sourceOwnerGuid;
  $filehandler->setFilename($sourceFile);
  $filehandler->open('read');
  $sourceFileContents = $filehandler->grabFile();

  if ($destinationFile == '')
    $destinationFile = $sourceFile;

  if (!$destinationOwnerGuid)
    $destinationOwnerGuid = elgg_get_logged_in_user_guid();

  $filehandler->owner_guid = $destinationOwnerGuid;
  $filehandler->setFilename($destinationFile);
  $filehandler->open('write');
  $filehandler->write($sourceFileContents);

  $filehandler->close();
}

/**
 * this function get all the videos for a user or all users
 *
 * @param int $ownerGuid id of the user to get videos for
 * @param boolean $count Do u want the total or videos ? :)
 * @return videos or false
 */
function izapGetAllVideos_izap_videos($ownerGuid = 0, $count = FALSE, $izapVideoType = 'object', $izapSubtype = 'izap_videos') {
  $videos = get_entities($izapVideoType, $izapSubtype, $ownerGuid, '', 0);
  return $videos;
}

/**
 * wraps the string to given number of words
 *
 * @param string $string string to wrap
 * @param integer $length max length of sting
 * @return sting $string wrapped sting
 */
function izapWordWrap_izap_videos($string, $length = 300, $add_ending = false) {
  if (strlen($string) <= $length) {
    $string = $string; //do nothing
  } else {
    $string = strip_tags($string);
    $string = wordwrap(str_replace("\n", "", $string), $length);
    $string = substr($string, 0, strpos($string, "\n"));

    if ($add_ending) {
      $string .= '...';
    }
  }

  return $string;
}

/**
 * this function will tell if the admin wants to include the index page widget
 *
 * @return boolean true for yes and false for no
 */
function izapIncludeIndexWidget_izap_videos() {
  $var = izapAdminSettings_izap_videos('izapIndexPageWidget', 'YES');

  if ($var == 'NO')
    return FALSE;

  return TRUE;
}

/**
 * manages the url for embeding the videos
 *
 * @param string $text all text
 * @return string
 */
function izapParseUrls_izap_videos($text) {
  return preg_replace_callback('/[^movie=](?<!=["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\!\(\)]+)/i',
          create_function(
                  '$matches',
                  '$url = $matches[1];
        $urltext = str_replace("/", "/<wbr />", $url);
        return "<a href=\"$url\" style=\"text-decoration:underline;\">$urltext</a>";
      '
          ), $text);
}

/**
 * gets the not converted videos
 *
 * @return boolean or entites
 */
function izapGetNotConvertedVideos_izap_videos() {
  $not_converted_videos = get_entities_from_metadata('converted', 'no', 'object', 'izap_videos', 0, 999999);
  if ($not_converted_videos) {
    return $not_converted_videos;
  }

  return FALSE;
}

function izapReadableSize_izap_videos($inputSize) {
  if (strpos($inputSize, 'M'))
    return $inputSize . 'B';

  $outputSize = $inputSize / 1024;
  if ($outputSize < 1024) {
    $outputSize = number_format($outputSize, 2);
    $outputSize .= ' KB';
  } else {
    $outputSize = $outputSize / 1024;
    if ($outputSize < 1024) {
      $outputSize = number_format($outputSize, 2);
      $outputSize .= ' MB';
    } else {
      $outputSize = $outputSize / 1024;
      $outputSize = number_format($outputSize, 2);
      $outputSize .= ' GB';
    }
  }
  return $outputSize;
}

/**
 * this will upgrade you old izap_videos plugin to this version.
 *
 * @global <type> $CONFIG
 */
function izapSetup_izap_videos() {
  global $CONFIG;
  add_subtype('object', 'izap_videos', 'IzapVideos');
  datalist_set('izap_videos_version', '3.55b');
}

/**
 * a quick way to convert bytes to a more readable format
 * http://in3.php.net/manual/en/function.filesize.php#91477
 *
 * @param integer $bytes size in bytes
 * @param integer $precision
 * @return string
 */
function izapFormatBytes($bytes, $precision = 2) {
  $units = array('B', 'KB', 'MB', 'GB', 'TB');

  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);

  $bytes /= pow(1024, $pow);

  return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * counts the queued videos
 * @return integer
 */
function izap_count_queue() {
  $queue_object = new izapQueue();
  return $queue_object->count();
}

function izap_get_video_name_prefix() {
  global $CONFIG;

  $domain = get_site_domain($CONFIG->site_guid);
  $domain = preg_replace('/[^A-Za-z0-9]+/', '_', $domain);

  return $domain . '_izap_videos_';
}

//Hack to correct the access id of the uploaded video.

function izap_update_all_defined_access_id($entity_guid, $accessId = ACCESS_PUBLIC) {
  global $CONFIG;
  // update metadata
  $query = 'UPDATE ' . $CONFIG->dbprefix . 'metadata SET access_id = ' . $accessId . ' WHERE entity_guid = ' . $entity_guid;
  $query = update_data($query);
  if (!$query) {
    return FALSE;
  }
  $query = 'UPDATE ' . $CONFIG->dbprefix . 'entities SET access_id = ' . $accessId . ' WHERE guid = ' . $entity_guid;
  update_data($query);
  return $query;
}

function izap_is_my_favorited($video) {
  $users = (array) $video->favorited_by;
  $key = array_search(elgg_get_logged_in_user_guid(), $users);
  if ($key !== FALSE) {
    return TRUE;
  }

  return FALSE;
}

function izap_remove_favorited($video, $user_guid = 0) {
  $users = (array) $video->favorited_by;

  if (!$user_guid) {
    $user_guid = elgg_get_logged_in_user_guid();
  }

  $key = array_search($user_guid, $users);

  if ($key !== FALSE) {
    unset($users[$key]);
  }

  izapGetAccess_izap_videos();
  $video->favorited_by = array_unique($users);
  izapRemoveAccess_izap_videos();

  return TRUE;
}

function izap_get_supported_videos_list($link = true) {
  global $IZAPSETTINGS;
  $ch = new IzapCurl($IZAPSETTINGS->api_server . 'supported_sites.php');
  $data = $ch->exec();

  $array = unserialize($data);

  foreach ($array as $title => $href) {
    $string[] = ($link)?'<a href="' . $href . '" title="' . $title . '" target="_blank">' . $title . '</a>':$title;
  }
  if($link)
    $return = '(' . elgg_echo('izap_videos:total') . ': ' . count($array) . ') ' . implode(', ', $string);
  else
    $return = implode(', ', $string);
  return $return;
}

/**
 * reads the file from the data source
 */
function read_video_file() {
  $guid = get_input("id");


  if (!$guid)
    $guid = current(explode('.', get_input("file")));

// if nothing is found yet..
  if (!$guid) {
    $guid = get_input('videoID');
  }

  $what = get_input("what");
  $izap_videos = izapVideoCheck_izap_videos($guid);


  if ($izap_videos) {
    // check what is needed
    if ($what == 'image') {
      if (get_input('size') == 'orignal') {
        $filename = $izap_videos->orignal_thumb;
        if ($filename == '') {
          $filename = $izap_videos->imagesrc;
        }
      } else {
        $filename = $izap_videos->imagesrc;
      }
    } elseif (!isset($what) || empty($what) || $what == 'file') {
      $filename = $izap_videos->videofile;
    }

    // only works if there is some file name
    if ($filename != '') {
      $fileHandler = new ElggFile();
      $fileHandler->owner_guid = $izap_videos->owner_guid;
      $fileHandler->setFilename($filename);
      if (file_exists($fileHandler->getFilenameOnFilestore()))
        $contents = $fileHandler->grabFile();
    }

    if ((string) $contents == '') {
      $contents = file_get_contents(elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/no-pic.png');
    }

    $fileName = end(explode('/', $filename));
    $header_array = array();
    if ($what == 'image') {
      $header_array['content_type'] = 'image/jpeg';
    } elseif (!isset($what) || empty($what) || $what == 'file') {
      $header_array['content_type'] = 'application/x-flv';
    }
    $header_array['file_name'] = $fileName;
    $header_array['filemtime'] = filemtime($fileHandler->getFilenameOnFilestore());
    IzapBase::cacheHeaders($header_array);
    echo $contents;
  }
  exit;
}

/**
 * this function gives the FFmpeg video converting command
 *
 * @return string path
 */
function izapGetFfmpegVideoConvertCommand_izap_videos() {
  $path = IzapBase::pluginSetting(array('plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN, 'name' => 'izapVideoCommand'));
  $path = html_entity_decode($path);
  if (!$path)
    $path = '';
  return $path;
}

/**
 * this function gives the FFmpeg video image command
 *
 * @return string path
 */
function izapGetFfmpegVideoImageCommand_izap_videos() {
  $path = IzapBase::pluginSetting(array('name' => 'izapVideoThumb', 'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN));
  $path = html_entity_decode($path);
  if (!$path)
    $path = '';
  return $path;
}

/**
 * this fucntion actually converts the video
 * @param string $file file loacation
 * @param int $videoId video guid
 * @param int $ownerGuid video owner guid
 * @param int $accessId access id
 * @return boolean
 */
function izapConvertVideo_izap_videos($file, $videoId, $videoTitle, $videoUrl, $ownerGuid, $accessId = 2) {
  global $CONFIG;
  $return = FALSE;

  // works only if we have the input file
  if (file_exists($file)) {
    // now convert video
    //
    // Need to set flag for the file going in the conversion.
    $queue_object = new izapQueue();
    $queue_object->change_conversion_flag($videoId);

    $video = new izapConvert($file);
    $videofile = $video->izap_video_convert();

    // check if every this is ok

    if (!is_array($videofile)) {
      // if every thing is ok then get back values to save
      $file_values = $video->getValues();
      $izap_videofile = 'izap_videos/uploaded/' . $file_values['filename'];
      $izap_origfile = 'izap_videos/uploaded/' . $file_values['origname'];
      $izap_videos = new IzapVideos($videoId);
      $izap_videos->setFilename($izap_videofile);
      $izap_videos->open("write");
      $izap_videos->write($file_values['filecontent']);

      //check if you do not want to keep original file
      if (IzapBase::pluginSetting(array('name' => 'izapKeepOriginal', 'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN)) == 'YES') {
        $izap_videos->setFilename($izap_origfile);
        $izap_videos->open("write");
        $izap_videos->write($file_values['origcontent']);
      }

      $izap_videos->converted = 'yes';
      $izap_videos->videofile = $izap_videofile;
      $izap_videos->orignalfile = $izap_origfile;
      notify_user($ownerGuid,
              $CONFIG->site_guid,
              elgg_echo('izap_videos:notifySub:videoConverted'),
              sprintf(elgg_echo('izap_videos:notifyMsg:videoConverted'), $izap_videos->getUrl())
      );
      return true;
    } else {
      $errorReason = (string)$videofile['message'];
    }
  } else {
    $errorReason = elgg_echo('izap_videos:fileNotFound');
  }
  $adminGuid = izapGetSiteAdmin_izap_videos(TRUE);

  // notify admin
  notify_user($adminGuid,
          $CONFIG->site_guid,
          elgg_echo('izap_videos:notifySub:videoNotConverted'),
          sprintf(elgg_echo('izap_videos:notifyAdminMsg:videoNotConverted'), $errorReason)
  );

  if (isset($errorReason)) {
    $return = array('error' => TRUE, 'reason' => $errorReason);
  }
  
  return $return;
}

/**
 * this returns the array of supported videos for uploading
 *
 * @global <type> $CONFIG
 * @return array array of supported videos
 */
function izapGetSupportingVideoFormats_izap_videos() {
  global $IZAPSETTINGS;

  foreach ($IZAPSETTINGS->allowedExtensions as $formats) {
    $supportedFormats[] = $formats;
  }

  asort($supportedFormats);
  return $supportedFormats;
}

/**
 * returns the file name, that ffmpeg can operate
 *
 * @param string $fileName file name
 * @return string all formated file name
 */
function izapGetFriendlyFileName_izap_videos($fileName) {
  global $CONFIG;

  $new_name .= izap_get_video_name_prefix();
  $new_name .= time() . '_';
  $new_name .= preg_replace('/[^A-Za-z0-9\.]+/', '_', $fileName);
  return $new_name;
}

/**
 * this function checks the supported videos
 * @global <type> $CONFIG
 * @param string $videoFileName video name with extension
 * @return boolean TRUE if supported else FALSE
 */
function izapSupportedVideos_izap_videos($videoFileName) {
  global $IZAPSETTINGS;
  $supportedFormats = $IZAPSETTINGS->allowedExtensions;
  $extension = IzapBase::getFileExtension($videoFileName);
  if (in_array($extension, $supportedFormats))
    return TRUE;

  return FALSE;
}

/**
 * this function will check the max upload limit for file
 *
 * @param integer $fileSize in Mb
 * @return boolean true if everything is ok else false
 */
function izapCheckFileSize_izap_videos($fileSize) {
  $maxFileSize = (int) izapAdminSettings_izap_videos('izapMaxFileSize');
  $maxSizeInBytes = $maxFileSize * 1024 * 1024;

  if ($fileSize > $maxSizeInBytes)
    return FALSE;

  return TRUE;
}

/**
 * changes the height and width of the video player
 *
 * @param integer $newHeight height
 * @param integer $newWidth width
 * @param string $object video player
 * @return HTML video player
 */
function izapGetReplacedHeightWidth_izap_videos($newHeight, $newWidth, $object) {
  $videodiv = preg_replace('/width=["\']\d+["\']/', 'width="' . $newWidth . '"', $object);
  $videodiv = preg_replace('/width:\d+/', 'width:' . $newWidth, $videodiv);
  $videodiv = preg_replace('/height=["\']\d+["\']/', 'height="' . $newHeight . '"', $videodiv);
  $videodiv = preg_replace('/height:\d+/', 'height:' . $newHeight, $videodiv);
  return $videodiv;
}
