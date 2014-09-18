<?php

  /*
   *    This file is part of izap-videos plugin for Elgg.
   *
   *    izap-videos for Elgg is free software: you can redistribute it and/or modify
   *    it under the terms of the GNU General Public License as published by
   *    the Free Software Foundation, either version 2 of the License, or
   *    (at your option) any later version.
   *
   *    izap-videos for Elgg is distributed in the hope that it will be useful,
   *    but WITHOUT ANY WARRANTY; without even the implied warranty of
   *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   *    GNU General Public License for more details.
   *
   *    You should have received a copy of the GNU General Public License
   *    along with izap-videos for Elgg.  If not, see <http://www.gnu.org/licenses/>.
   */

  /**
   * Get page components to list a user's or all izap-videos.
   * @param type $container_guid
   */
  function izap_video_get_page_content_list($container_guid = NULL) {

    $return = array();
    $return['filter_context'] = $container_guid ? 'mine' : 'all';
    $options = array(
      'type' => 'object',
      'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
      'full_view' => false,
      'no_results' => elgg_echo('izap-videos:none'),
    );

    $current_user = elgg_get_logged_in_user_entity();
    if ($container_guid) {
// access check for closed groups
      elgg_group_gatekeeper();

      $options['container_guid'] = $container_guid;
      $container = get_entity($container_guid);
      if (!$container) {
        
      }

      $return['title'] = elgg_echo('izap-videos:title:user_videos', array($container->name));

      $crumbs_title = $container->name;
      elgg_push_breadcrumb($crumbs_title);

      if ($current_user && ($container_guid == $current_user->guid)) {
        $return['filter_context'] = 'mine';
      } else if (elgg_instanceof($container, 'group')) {
        $return['filter'] = false;
      } else {
// do not show button or select a tab when viewing someone else's posts
        $return['filter_context'] = 'none';
      }
    } else {
      $return['filter_context'] = 'all';
      $return['title'] = elgg_echo('izap-videos:title:all_videos');
      elgg_pop_breadcrumb();
      elgg_push_breadcrumb(elgg_echo('izap-videos'));
    }

    $title = 'Add New Video';
    $url = GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/add/';

    if (izap_is_onserver_enabled_izap_videos() == 'yes') {
      $url .= elgg_get_logged_in_user_guid() . '/onserver';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    } elseif (izap_is_onserver_enabled_izap_videos() == 'youtube') {
      $url .= elgg_get_logged_in_user_guid() . '/youtube';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    } elseif (izap_is_offserver_enabled_izap_videos() == 'yes') {
      $url .= elgg_get_logged_in_user_guid() . '/offserver';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    } else {
      $url .= elgg_get_logged_in_user_guid() . '/offserver';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    }
//    else {
//      $url = 'izap-videos/all';
//      register_error(elgg_echo('izap-videos:message:noAddFeature'));
//      //forward($url);
//    }

    $return['content'] = elgg_list_entities($options);
    return $return;
  }

  /**
   * Get page components to list of the user's friends' posts.
   * @param type $container_guid
   */
  function izap_video_get_page_content_friends($user_guid = NULL) {
    $user = get_user($user_guid);
    if (!$user) {
      forward(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/all');
    }

    $return = array();

    $return['filter_context'] = 'friends';
    $return['title'] = elgg_echo('izap-videos:title:friends');

    $crumbs_title = $user->name;
    elgg_push_breadcrumb($crumbs_title, GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/owner/{$user->username}");
    elgg_push_breadcrumb(elgg_echo('friends'));

    $title = 'Add New Video';
    $url = GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/add/';

    if (izap_is_onserver_enabled_izap_videos() == 'yes') {
      $url .= elgg_get_logged_in_user_guid() . '/onserver';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    } elseif (izap_is_onserver_enabled_izap_videos() == 'youtube') {
      $url .= elgg_get_logged_in_user_guid() . '/youtube';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    } elseif (izap_is_offserver_enabled_izap_videos() == 'yes') {
      $url .= elgg_get_logged_in_user_guid() . '/offserver';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    } else {
      $url .= elgg_get_logged_in_user_guid() . '/offserver';
      elgg_register_menu_item('title', array(
        'name' => elgg_get_friendly_title($title),
        'href' => $url,
        'text' => $title,
        'link_class' => 'elgg-button elgg-button-action',
      ));
    }

    $options = array(
      'type' => 'object',
      'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
      'full_view' => false,
      'relationship' => 'friend',
      'relationship_guid' => $user_guid,
      'relationship_join_on' => 'container_guid',
      'no_results' => elgg_echo('izap-videos:none'),
    );

    $return['content'] = elgg_list_entities_from_relationship($options);

    return $return;
  }

  /**
   * Get page components to edit/create a izap-video post.
   * @param type $page
   * @param type $guid
   * @param type $revision
   */
  function izap_video_get_page_content_edit($page, $guid = 0, $revision = NULL) {

    $return = array(
      'filter' => '',
    );

    $form_vars = array();
    $sidebar = '';
    if ($page == 'edit') {
      $izap_video = get_entity((int) $guid);
      $title = elgg_echo('izap_videos:edit') . ":";
      if (elgg_instanceof($izap_video, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE) && $izap_video->canEdit()) {
        $form_vars['entity'] = $izap_video;
        $form_vars['name'] = "video_upload";
        $title .= ucwords($izap_video->title);

        $body_vars = izap_videos_prepare_form_vars($izap_video, $revision);
        elgg_push_breadcrumb($izap_video->title, $izap_video->getURL());
        elgg_push_breadcrumb(elgg_echo('edit'));
        $content = elgg_view_form('izap-videos/save', $form_vars, $body_vars);
      }
    } else {
      elgg_push_breadcrumb(elgg_echo('izap_videos:add'));
      $body_vars = izap_videos_prepare_form_vars(null);

      $form_vars = array('enctype' => 'multipart/form-data', 'name' => 'video_upload');
      $title = elgg_echo('izap-videos:add');
      $content = elgg_view_form('izap-videos/save', $form_vars, $body_vars);
    }

    $return['title'] = $title;
    $return['content'] = $content;
    $return['sidebar'] = $sidebar;

    return $return;
  }

  /**
   * Get page components to upload youtube video.
   * @param type $page
   * @param type $guid
   * @param type $revision
   */
  function izap_video_get_page_content_youtube_upload($page, $guid = 0, $revision = NULL) {
    $return = array(
      'filter' => '',
    );
//      $form_vars = array();
//      $sidebar = '';
    $video = IzapGYoutube::getAuthSubHttpClient(get_input('token', false));
    $yt = $video->YoutubeObject();
    $myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
    $myVideoEntry->setVideoTitle($_SESSION['youtube_attributes']['title']);
    $myVideoEntry->setVideoDescription($_SESSION['youtube_attributes']['description']);

    // Note that category must be a valid YouTube category
    $myVideoEntry->setVideoCategory($_SESSION['youtube_attributes']['youtube_cats']);
    $myVideoEntry->SetVideoTags($_SESSION['youtube_attributes']['tags']);
    $tokenHandlerUrl = 'http://gdata.youtube.com/action/GetUploadToken';
    try {
      $tokenArray = $yt->getFormUploadToken($myVideoEntry, $tokenHandlerUrl);
    } catch (Exception $e) {
      echo "catch";
      exit;
      if (preg_match("/<code>([a-z_]+)<\/code>/", $e->getMessage(), $matches)) {
        register_error('YouTube Error: ' . $matches[1]);
      } else {
        register_error('YouTube Error: ' . $e->getMessage());
      }
      forward(setHref(array(
        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
        'action' => 'add',
        'page_owner' => elgg_get_logged_in_user_guid(),
        'vars' => array('tab' => 'youtube'),
      )));
    }
    $params['token'] = $tokenArray['token'];
    $params['action'] = $tokenArray['url'] . '?nexturl=' . elgg_get_site_url() . 'videos/next';
    elgg_push_breadcrumb(elgg_echo('upload'));
    $body_vars = izap_videos_prepare_form_vars($params);

    $form_vars = array('enctype' => 'multipart/form-data', 'name' => 'video_upload');
    $title = elgg_echo('Upload video with title: "' . $_SESSION['youtube_attributes']['title'] . '"');
    $content = elgg_view_form('izap-videos/youtube_upload', $form_vars, $body_vars);
//      $return['title'] = $title;
    $return['content'] = $content;
//      $return['sidebar'] = $sidebar;

    return $return;
  }

  /**
   * show particular saved entity
   * @param type $guid
   * @return type
   */
  function izap_videos_read_content($guid = null) {
    $return = array();
    $izap_video = get_entity($guid);

    $return['title'] = ucwords($izap_video->title);
    $return['content'] = elgg_view_entity($izap_video, array('full_view' => true));

    if ($izap_video->comments_on != 'Off') {
      $return['content'] .= elgg_view_comments($izap_video);
    }
    return $return;
  }

  function izap_read_video_file($guid = null) {
    $entity = get_entity($guid);

    if (!elgg_instanceof($entity, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE)) {
      exit;
    }
    $return = array();
    $return['title'] = ucwords($entity->title);
    $return['content'] = elgg_view_entity($entity, array('full_view' => true));

    if ($izap_video->comments_on != 'Off') {
      $return['content'] .= elgg_view_comments($entity);
    }
    return $return;
  }

  /**
   * Pull together izap-video variables for the save form
   * @param type $post
   * @param type $revision
   */
  function izap_videos_prepare_form_vars($post = NULL, $revision = NULL) {

// input names => defaults
    $values = array(
      'title' => NULL,
      'description' => NULL,
      'access_id' => ACCESS_DEFAULT,
      'comments_on' => 'On',
      'tags' => NULL,
      'container_guid' => NULL,
      'guid' => NULL,
      'video_url' => NULL
    );

    if ($post) {
      foreach (array_keys($values) as $field) {
        if (isset($post->$field)) {
          $values[$field] = $post->$field;
        }
      }
    }

    if (elgg_is_sticky_form('izap_videos')) {
      $sticky_values = elgg_get_sticky_values('izap_videos');
      foreach ($sticky_values as $key => $value) {
        $values[$key] = $value;
      }
    }

    elgg_clear_sticky_form('izap_videos');

    return $values;
  }

  /**
   * check whether operating sysytem is window 
   * @return boolean
   */
  function izapIsWin_izap_videos() {
    if (strtolower(PHP_OS) == 'winnt') {
      return true;
    } else {
      return false;
    }
  }

  /**
   * check upload filesize
   * @param type $inputSize
   * @return string
   */
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
   * 
   * @param type $settingName
   * @param type $values
   * @param type $override
   * @param type $makeArray
   * @return type
   */
  function izapAdminSettings_izap_videos($settingName, $values = '', $override = false, $makeArray = false) {
    $send_array = array(
      'name' => $settingName,
      'value' => $values,
      'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    );

    return pluginSetting($send_array);
  }

  /**
   * 
   * @param type $supplied_array
   * @return boolean
   */
  function pluginSetting($supplied_array) {
    $default = array(
      'override' => FALSE,
      'make_array' => FALSE,
    );

    $input = array_merge($default, (array) $supplied_array);
// get old values
    $old_value = elgg_get_plugin_setting($input['name'], $input['plugin']);

//make new value
    if (is_array($input['value'])) {
      $new_value = implode('|', $input['value']);
    } else {
      $new_value = $input['value'];
    }

    if ((!(bool) $old_value && !empty($new_value)) || $input['override']) {
      if (!elgg_set_plugin_setting($input['name'], $new_value, $input['plugin'])) {
        return FALSE;
      } else {
        $return = $new_value;
      }
    }

    if ((bool) $old_value !== FALSE) {
      $old_array = explode('|', $old_value);
      if (count($old_array) > 1) {
        $return = $old_array;
      } else {
        $return = $old_value;
      }
    }

    if (!is_array($return) && $input['make_array'] && (bool) $return) {
      $new_return_val[] = $return;
      $return = $new_return_val;
    }

    return $return;
  }

  /**
   * checks if onserver videos are enabled in admin settings
   * @return <type>
   */
  function izap_is_onserver_enabled_izap_videos() {
    $settings = pluginSetting(array(
      'name' => 'onserver_enabled_izap_videos',
      'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    ));

    if ((string) $settings === 'no') {
      return FALSE;
    }

    return $settings;
  }

  /**
   * check whether offserver videos are enabled in admin settings
   */
  function izap_is_offserver_enabled_izap_videos() {
    $setting = pluginSetting(array(
      'name' => 'Offserver_enabled_izap_videos',
      'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    ));
    if ((string) $setting === 'no') {
      return false;
    }
    return $setting;
  }

  /**
   * resets queue
   *
   * @return boolean
   */
  function izapResetQueue_izap_videos() {
    return izapAdminSettings_izap_videos('isQueueRunning', 'no', true);
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
   * gets the not converted videos
   *
   * @return boolean or entites
   */
  function izapGetNotConvertedVideos_izap_videos() {
    $not_converted_videos = get_entities_from_metadata('converted', 'no', 'object', 'izap_video', 0, 999999);
    if ($not_converted_videos) {
      return $not_converted_videos;
    }

    return false;
  }

  /**
   * 
   * @global type $CONFIG
   * @param type $file
   * @param type $plugin
   * @return type
   */
  function getFormAction($file, $plugin) {
    global $CONFIG;
    return $CONFIG->wwwroot . 'action/' . $plugin . '/' . $file;
  }

  /**
   * this function triggers the queue
   *
   * @global <type> $CONFIG
   */
  function izap_trigger_video_queue() {
    $PHPpath = izapGetPhpPath_izap_videos();
    $file_path = elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/izap_convert_video.php';

    if (!izap_is_queue_running_izap_videos()) {
      if (izapIsWin_izap_videos()) {
        pclose(popen("start \"MyProcess\" \"cmd /C " . $PHPpath . " " . $file_path, "r"));
      } else {
        exec($PHPpath . ' ' . $file_path . ' izap web > /dev/null 2>&1 &', $output);
      }
    }
  }

  /**
   * this function checks if the queue is running or not
   *
   * @return boolean true if yes or false if no
   */
  function izap_is_queue_running_izap_videos() {
    $queue_object = new izapQueue();

    $numberof_process = $queue_object->check_process();
    if ($numberof_process > 0) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * gives the file's extension if file found
   * @param string $filename
   * @return mixed file extension if found else false
   */
  function getFileExtension($filename) {
    if (empty($filename)) {
      return false;
    }

    return strtolower(end(explode('.', $filename)));
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
   * grants the access
   *
   * @param <type> $functionName
   */
  function izapGetAccess_izap_videos() {
    izap_access_override(array('status' => true));
  }

  /**
   * remove access
   *
   * @global global $CONFIG
   * @param string $functionName
   */
  function izapRemoveAccess_izap_videos() {
    izap_access_override(array('status' => false));
  }

  function izap_access_override($params = array()) {
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
    return true;
  }

  function getQueue() {
    global $CONFIG;
    $queue_status = (izap_is_queue_running_izap_videos()) ?
      elgg_echo('izap_videos:running') :
      elgg_echo('izap_videos:notRunning');
    $queue_object = new izapQueue();

    echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/queue_status', array(
      'status' => $queue_status,
      'total' => $queue_object->count(),
      'queue_videos' => $queue_object->get(),
      )
    );
    exit;
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

  function izap_save_fileinfo_for_converting_izap_videos($file, $video, $defined_access_id = 2, $izapvideo) {
// this will not let save any thing if there is no file to convert
    if (!file_exists($file) || !$video) {
      return false;
    }
    $queue = new izapQueue();
    $queue->put($video, $file, $defined_access_id, $izapvideo->getURL());

    //set state processing for video
    $izapvideo->converted = 'in_processing';
    //run queue
    izap_trigger_video_queue();
  }

  /**
   * 
   * @return boolean
   */
  function izap_run_queue_izap_videos() {
    $queue_object = new izapQueue();
    $queue = $queue_object->fetch_videos();

    if (is_array($queue)) {
      foreach ($queue as $pending) {
        $converted = izapConvertVideo_izap_videos($pending['main_file'], $pending['guid'], $pending['title'], $pending['url'], $pending['owner_id']);

        if ($converted['error']) {
          $queue_object->move_to_trash($pending['guid']);
        } else {
          $queue_object->delete($pending['guid']);
        }
      }

      if ($queue_object->count() > 0) {
        izap_run_queue_izap_videos();
      }
    }
    return true;
  }

  /**
   * this function gives the FFmpeg video converting command
   *
   * @return string path
   */
  function izap_get_ffmpeg_videoConvertCommand_izap_videos() {
    $path = elgg_get_plugin_setting('izapVideoCommand', GLOBAL_IZAP_VIDEOS_PLUGIN);
    //  $path = pluginSetting(array('plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN, 'name' => 'izapVideoCommand'));
    $path = html_entity_decode($path);
    if (!$path)
      $path = '';
    return $path;
  }

  /**
   * get thumbanil from uploaded video
   * @return string
   */
  function izap_get_ffmpeg_thumbnailCommand() {
    $path = elgg_get_plugin_setting('izapVideoThumb', GLOBAL_IZAP_VIDEOS_PLUGIN);
    $path = html_entity_decode($path);
    if (!$path)
      $path = '';
    return $path;
  }

  /**
   * 
   * @param type $file
   * @param type $videoId
   * @param type $videoTitle
   * @param type $videoUrl
   * @param type $ownerGuid
   * @param type $accessId
   * @return type
   */
  function izapConvertVideo_izap_videos($file, $videoId, $videoTitle, $videoUrl, $ownerGuid, $accessId = 2) {

    if (file_exists($file)) {
      $queue_object = new izapQueue();
      $video = new izapConvert($file);
      $videofile = $video->izap_video_convert();   //if file converted successfully then change flag from pending to processed

      if (!is_array($videofile)) {
        $queue_object->change_conversion_flag($videoId);
        return $videofile;
      } else {
        $err_message = $videofile['message'];
      }
    } else {
      $err_message = elgg_echo('izap_videos:file not found');
    }
    if (isset($err_message)) {
      $return = array('error' => true, 'message' => $err_message);
    }
    return $return;
  }

  /**
   * read video file content
   */
  function read_video_file() {
    $guid = (int) get_input('videoID');
    $entity = get_entity($guid);
    //echo $entity->videofile; exit;
    //  $izapqueue_obj = new izapQueue();
    //   $get_converted_video = $izapqueue_obj->get_converted_video($guid);

    if (!elgg_instanceof($entity, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE)) {
      exit;
    }

    if ($entity->videofile) {
      $get_video_name = end(explode('/', $entity->videofile));
      $izapvideo_obj = new IzapVideo;
      $set_video_name = $izapvideo_obj->get_tmp_path($get_video_name);
      if (getFileExtension($set_video_name) == 'flv') {
        $set_video_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $set_video_name) . '.flv';
      } else {
        $set_video_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $set_video_name) . '_c.flv';
      }
//          $set_video_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $set_video_name) . '_c.flv';
      // echo $set_video_name; exit;
      $elggfile_obj = new ElggFile;
      $elggfile_obj->owner_guid = $entity->owner_guid;
      $elggfile_obj->setFilename($set_video_name);

//echo file_exists($elggfile_obj->getFilenameOnFilestore())?"true":"false"; exit;
//echo mime_content_type($elggfile_obj->getFilenameOnFilestore()); exit;
      if (file_exists($elggfile_obj->getFilenameOnFilestore())) {
        $contents = $elggfile_obj->grabFile();
      }
      //echo $contents; exit;
      $content_type = 'video/x-flv';

      header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
      header("Pragma: public", true);
      header("Cache-Control: public", true);
      header("Content-Length: " . strlen($contents));
      header("Content-type: {$content_type}", true);

      echo $contents;
      exit;
    }
  }

  /**
   * load video via ajax
   * @param type $guid
   */
  function getVideoPlayer($guid, $height, $width) {
    global $IZAPSETTINGS;
    $entity = get_entity($guid);
    $video_src = elgg_get_site_url() . 'izap_videos_files/file/' . $guid . '/' . elgg_get_friendly_title($entity->title) . '.flv';
    $player_path = $IZAPSETTINGS->playerPath;
    $image_path = elgg_get_site_url() . 'mod/izap-videos/thumbnail.php?file_guid=' . $guid;
    if (getFileExtension($entity->videofile) == 'flv') {
      $get_flv_file = file_exists(preg_replace('/\\.[^.\\s]{3,4}$/', '', $entity->videofile) . '.flv') ? "true" : "false";
    } else {
      $get_flv_file = file_exists(preg_replace('/\\.[^.\\s]{3,4}$/', '', $entity->videofile) . '_c.flv') ? "true" : "false";
    }
    if ($entity->videourl) {
      if (elgg_instanceof($entity, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE, GLOBAL_IZAP_VIDEOS_CLASS)) {
        $content = izapGetReplacedHeightWidth_izap_videos($height, $width, $entity->videosrc);
      } else {
        echo elgg_echo('izap_videos:ajaxed_videos:error_loading_video');
      }
    } else {
      if ($get_flv_file == 'true') {
        $content = "
           <object width='" . $width . "' height= '" . $height . "' id='flvPlayer'>
            <param name='allowFullScreen' value='true'>
            <param name='wmode' value='transparent'>
            <param name='allowScriptAccess' value='always'>
            <param name='movie' value='" . $player_path . "?movie=" . $video_src . "&volume=30&autoload=on&autoplay=on&vTitle=" . $entity->title . "&showTitle=yes' >
            <embed src='" . $player_path . "?movie=" . $video_src . "&volume=30&autoload=on&autoplay=on&vTitle=" . $entity->title . "&showTitle=yes' width='100' height='100' allowFullScreen='true' type='application/x-shockwave-flash' allowScriptAccess='always' wmode='transparent'>
           </object>";
      } else {
        //echo '<p class="notConvertedWrapper" style="background-color:height:400px; black;radius:8px;">' . '</p>';
        $content = '<div align="center" class="contentWrapper video_background-top-round" style="height: "' . $height . 'px";">
             <div align="left" id="no_video" style="height:"' . $height . 'px";background-color: black;border-radius:8px;">Video is queued up for conversion.</div>
       </div>';
      }
    }
    echo $content;
    exit;
  }

  /*
   * Get Offserver Api Key
   */

  function getOffserverApiKey() {
    return elgg_get_plugin_setting('izap_api_key', 'izap-videos');
  }

  function input($video_data = array()) { 
    global $IZAPSETTINGS;
    $url = $IZAPSETTINGS->apiUrl . '&url=' . urlencode($video_data['url']);
    $curl = new IzapCurl();
    $raw_contents = $curl->get($url)->body;
    $returnObject = json_decode($raw_contents);
    if ($returnObject == NULL || $returnObject == FALSE) {
      register_error(elgg_echo('izap_videos:no_response_from_server'));
      forward($_SERVER['HTTP_REFERER']);
      exit;
    }
    // We are not supporting this url.
    if (!$returnObject || empty($returnObject->embed_code)) {
      return $returnObject;
    }
    $obj = new stdClass;
    $obj->title = $video_data['title'] ? $video_data['title'] : $returnObject->title;
    $obj->description = $video_data['description'] ? $video_data['description'] : $returnObject->description;
    $obj->videothumbnail = $returnObject->thumb_url;
    $obj->videosrc = $returnObject->embed_code;
    $obj->videotags = $returnObject->tags;
    $obj->domain = $returnObject->url;
    $obj->filename = time() . '_' . basename($obj->videothumbnail);
    $obj->filecontent = $curl->get($obj->videothumbnail)->body;
    $obj->type = $returnObject->type;
    return $obj;
  }

  function izapGetReplacedHeightWidth_izap_videos($newHeight, $newWidth, $object) {
    $videodiv = preg_replace('/width=["\']\d+["\']/', 'width="' . $newWidth . '"', $object);
    $videodiv = preg_replace('/width:\d+/', 'width:' . $newWidth, $videodiv);
    $videodiv = preg_replace('/height=["\']\d+["\']/', 'height="' . $newHeight . '"', $videodiv);
    $videodiv = preg_replace('/height:\d+/', 'height:' . $newHeight, $videodiv);
    return $videodiv;
  }

  function getYoutubeCategories() {

    $cats = array(
      'Film' => 'Film & Animation',
      'Autos' => 'Autos',
      'Music' => 'Music',
      'Animals' => 'Pets & Animals',
      'Sports' => 'Sports',
      'Travel' => 'Travel & Events',
      'Games' => 'Gaming',
      'Comedy' => 'Comedy',
      'Entertainment' => 'Entertainment',
      'News' => 'News & Politics',
      'Howto' => 'Howto & Style',
      'Education' => 'Education',
      'Tech' => 'Science & Technology',
      'Nonprofit' => 'Nonprofits & Activism',
      'Movies' => 'Movies',
      'Movies_anime_animation' => 'Anime/Animation',
      'Movies_classics' => 'Classics',
      'Movies_comedy' => 'Comedy Movies',
      'Movies_documentary' => 'Documentary',
      'Movies_drama' => 'Drama',
      'Movies_family' => 'Family',
      'Movies_foreign' => 'Foreign',
      'Movies_horror' => 'Horror',
      'Movies_sci_fi_fantasy' => 'Sci-Fi/Fantasy',
      'Movies_thriller' => 'Thriller',
      'Movies_shorts' => 'Shorts',
      'Shows' => 'Shows',
      'Trailers' => 'Trailers');


    asort($cats);
    return $cats;
  }

  function preview() {
//    echo json_encode(array('test'=>'name'));exit;
    $video_url = array(
      'url' => $_POST['url']
    );
    $videoValues = input($video_url);
    $video_data = array(
      'title' => $videoValues->title,
      'description' => $videoValues->description,
      'thumbnail' => $videoValues->videothumbnail
    );
    echo json_encode($video_data);exit;
  }

  function setHref($input = array()) {
    global $CONFIG;

    /**
     * Default Params
     */
    $default = array(
      'trailing_slash' => TRUE,
      'full_url' => TRUE,
    );
    $params = array_merge($default, $input);

    // start url array
    $url_array = array();
    //$url_array[] = 'pg';

    if ($params['context']) {
      $url_array[] = $params['context'];
    } else {
      $url_array[] = elgg_get_context();
    }

    // set which page to call
    $url_array[] = $params['action'];

    // check to set the page owner
    if ($params['page_owner'] !== FALSE) {
      if (isset($params['page_owner'])) {
        $url_array[] = $params['page_owner'];
      } elseif (elgg_get_logged_in_user_guid()) {
        $url_array[] = elgg_get_logged_in_user_guid();
      } elseif (elgg_is_logged_in()) {
        $url_array[] = elgg_get_logged_in_user_guid();
      }
    }

    if (is_array($params['vars']) && sizeof($params['vars'])) {
      foreach ($params['vars'] as $var) {
        $url_array[] = filter_var($var);
      }
    }

    // short circuit for empty values
    foreach ($url_array as $value) {
      if (!empty($value)) {
        $final_array[] = $value;
      }
    }

    // create URL
    $final_url = implode('/', $final_array);

    if ($params['full_url']) {
      $final_url = $CONFIG->wwwroot . $final_url;
    }
    // check for trailing_slash
//      if ($params['trailing_slash']) {
//          $final_url .= '/';
//      }
//c($final_url);exit;
    return $final_url;
  }
  