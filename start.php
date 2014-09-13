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

  define('GLOBAL_IZAP_VIDEOS_PLUGIN', 'izap-videos');
  define('GLOBAL_IZAP_VIDEOS_SUBTYPE', 'izap_video');
  define('GLOBAL_IZAP_VIDEOS_PAGEHANDLER', 'videos');
  define('GLOBAL_IZAP_VIDEOS_CLASS', 'IzapVideo');

  elgg_register_event_handler('init', 'system', 'izap_video_init');

  /**
   * main init function
   */
  function izap_video_init() {
    //Offser Api Key
    global $CONFIG, $IZAPSETTINGS;
    $IZAPSETTINGS = new stdClass();
//    $hey = getOffserverApiKey();
    $IZAPSETTINGS->api_server = 'http://api.pluginlotto.com';
    $IZAPSETTINGS->apiUrl = $IZAPSETTINGS->api_server . '?api_key=' . elgg_get_plugin_setting('izap_api_key', 'izap-videos') . '&domain=' . base64_encode(strtolower($_SERVER['HTTP_HOST']));
    $IZAPSETTINGS->playerPath = elgg_get_site_url() . 'mod/' .GLOBAL_IZAP_VIDEOS_PLUGIN . '/player/izap_player.swf';
    $root = dirname(__FILE__);

    //define path for actions folder
    $action_root = dirname(__FILE__) . '/actions/izap-videos/';

    //register izap-videos plugin lib file
    elgg_register_library('elgg:izap_video', "$root/lib/izap-videos.php");

    //register page handler for particular identifier
    elgg_register_page_handler(GLOBAL_IZAP_VIDEOS_PAGEHANDLER, 'izap_video_page_handler');

    //register page handler for video page
    elgg_register_page_handler('izap_videos_files', 'pageHandler_izap_videos_files');

    //register page handler for view videos
    elgg_register_page_handler('izap_view_video', 'izap_view_video_handler');

    elgg_register_entity_type('object', GLOBAL_IZAP_VIDEOS_SUBTYPE);

    //register menu item and set default path to all videos
    $item = new ElggMenuItem('video', elgg_echo('izap_video:Video'), GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/all');
    elgg_register_menu_item('site', $item);

    if (elgg_is_admin_logged_in()) {
      // Add admin menu item 
      elgg_register_admin_menu_item('administer', 'izap-videos-queue', 'statistics');
    }

    //register action
    elgg_register_action('izap-videos/save', $action_root . 'save.php');
    elgg_register_action(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/delete', $action_root . 'delete.php');
    elgg_register_action(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/trigger_queue', dirname(__FILE__) . '/actions/admin/' . 'trigger_queue.php');
    elgg_register_action(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/reset_queue', dirname(__FILE__) . '/actions/admin/' . 'reset_queue.php');


    //register hook handler
    elgg_register_plugin_hook_handler('unit_test', 'system', 'izap_video_unit_tests');
    //extend css
    elgg_extend_view('css/admin', 'izap-videos/admin_css');

    elgg_register_plugin_hook_handler('entity:url', 'object', 'izap_videos_set_url');


    //register icon handler for thumbnail
    elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'izap_videos_set_icon_url');

    // elgg_register_plugin_hook_handler($action_root, $type, $callback);
    elgg_register_plugin_hook_handler('get_views', 'ecml', 'izap_videos_ecml_view');
    //register video url handler
    elgg_register_entity_url_handler('object', 'izap_video', 'video_url');

    //add_group_tool_option('izap_video_page_handler', elgg_echo('izap-videos:enable_videos'));
    //extend old server stats with current stats
    elgg_extend_view('admin/statistics/server', 'admin/statistics/server_stats');
    elgg_extend_view('page/elements/header', 'forms/izap-videos/my_javascript');

    elgg_register_widget_type('izap-videos', elgg_echo('izap-videos'), elgg_echo('izap-videos:widget:description'));
    elgg_register_widget_type(
      'izap_queue_statistics-admin', elgg_echo('izap_queue_statistics-admin:widget_name'), elgg_echo('izap_queue_statistics-admin:widget_description'), 'admin');

    elgg_register_js('elgg:video_js', "mod/izap-videos/views/default/js/jquery.js");
    elgg_register_js('elgg:player', "mod/izap-videos/views/default/js/mediaelement.js");

    elgg_register_css('elgg:video_css', 'mod/izap-videos/views/default/css/video-js.css');

    //elgg_register_notification_event('object', 'izap_video',array('create'));

    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'izap_videos_owner_block_menu');
  }

  /**
   * Dispatches izap-video pages.
   * URLs take the form of
   *  All izap-video:       izap-videos/all
   *  User's izap-video:    izap-videos/owner/<username>
   *  Friends' izap-video:   izap-videos/friends/<username>
   *  New post:        izap-videos/add/<guid>
   *  Edit post:       izap-videos/edit/<guid>/<revision>
   * Title is ignored
   *
   * @todo no archives for all izap-videos or friends
   *
   * @param array $page
   * @return bool
   */
  function izap_video_page_handler($page) {
    elgg_load_library('elgg:izap_video');

    // push all blogs breadcrumb
    elgg_push_breadcrumb(elgg_echo('izap_video:Video'), GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/all");

    //if no param pass then default is all.
    if (!isset($page[0])) {
      $page[0] = 'all';
    }
    $page_type = $page[0];
    switch ($page_type) {
      case 'owner':
        $user = get_user_by_username($page[1]);
        if (!$user) {
          forward('', '404');
        }
        $params = izap_video_get_page_content_list($user->guid);
        break;
      case 'friends':
        $user = get_user_by_username($page[1]);
        if (!$user) {
          forward('', '404');
        }
        $params = izap_video_get_page_content_friends($user->guid);
        break;
      //add new video
      case 'add':
        elgg_gatekeeper(); //if user is not logged in then redirect user to login page
        $params = izap_video_get_page_content_edit($page_type, $page[1], $page[2]);
        break;
      //edit particular izap-videos 
      case 'edit':
        elgg_gatekeeper();  //if user is not logged in then redirect usre to login page 
        $params = izap_video_get_page_content_edit($page_type, $page[1], $page[2]);
        break;
      //view all iZAP izap-videos
      case 'all':
        $params = izap_video_get_page_content_list();
        break;
      case 'icon':
        $params = izap_videos_read_content($page[1]);
        break;
      case 'video': //full page video
        elgg_load_css('elgg:video_css');
        elgg_load_js('elgg:video_js');
        //  elgg_load_js('elgg:player');
        $params = izap_read_video_file($page[1]);
        $params['filter'] = false;
        break; 
      case 'viewvideo':    //load video by ajax
        $params = getVideoPlayer($page[1], $page[2], $page[3]);
        break;
      case 'queue': //get queue status in admins
        $params = getQueue();
        break;
      default:
        return false;
    }

    //add sidebar 
    if (isset($params['sidebar'])) {
      $params['sidebar'] .= elgg_view('izap-videos/sidebar', array('page' => $page_type));
    } else {
      $params['sidebar'] = elgg_view('izap-videos/sidebar', array('page' => $page_type));
    }

    $body = elgg_view_layout('content', $params);
    // print_r($params);exit;
    echo elgg_view_page($params['title'], $body);
    return true;
  }

  /**
   * run unit test for izap-videos 
   * @param type $hook
   * @param type $type
   * @param type $value
   * @param type $params
   * @return string
   */
  function izap_video_unit_tests($hook, $type, $value, $params) {
    $path[] = dirname(__FILE__) . '/tests/IzapVideoTest.php';
    return $path;
  }

  /**
   * set url for view video
   * @param type $hook
   * @param type $type
   * @param type $url
   * @param type $params
   * @return type
   */
  function izap_videos_set_url($hook, $type, $url, $params) {
    $entity = $params['entity'];
    if (elgg_instanceof($entity, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE)) {
      $friendly_title = elgg_get_friendly_title($entity->title);
      return GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/video/{$entity->guid}/$friendly_title";
    }
  }

  /**
   * set icon for thumbnail
   * @param type $hook
   * @param type $type
   * @param type $url
   * @param type $params
   * @return type
   */
  function izap_videos_set_icon_url($hook, $type, $url, $params) {
    $file = $params['entity'];
    if (elgg_instanceof($file, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE) && $file->imagesrc) {
      if ($file->video_url) {
        return $file->imagesrc;
      } else {
        return "mod/izap-videos/thumbnail.php?file_guid=$file->guid";
      }
    }
  }

  /**
   * 
   * @param type $entity
   * @return type
   */
  function video_url($entity) {
    // $entity = $params['entity']; echo $entity->guid; exit;
    $title = elgg_get_friendly_title($entity->title); //echo elgg_get_site_url() . "izap-videos/video/$entity->guid/$title"; exit;    
    return "mod/izap-videos/video.php?file_guid=$entity->guid";
  }

  /**
   * 
   * @param type $hook
   * @param type $type
   * @param type $url
   * @param type $params
   * @return type
   */
  function izap_videos_ecml_view($hook, $type, $return, $params) {
    $return['object/izap_video'] = elgg_echo('item:object:izap_video');
    return $return;
  }

  /**
   * 
   * @param type $hook
   * @param type $type
   * @param type $return
   * @param type $params
   * @return \ElggMenuItem
   */
  function izap_videos_owner_block_menu($hook, $type, $return, $params) {
    if (elgg_instanceof($params['entity'], 'user')) {
      $url = GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/owner/{$params['entity']->username}";
      $item = new ElggMenuItem('izap_videos', elgg_echo('item:object:izap-videos'), $url);
      $return[] = $item;
    }

    return $return;
  }

  /**
   * 
   * @param type $page
   */
  function pageHandler_izap_videos_files($page) {
    set_input('what', $page[0]);
    set_input('videoID', $page[1]);
    set_input('size', $page[2]);
    elgg_load_library('elgg:izap_video');
    read_video_file();
  }

  function c($array) {
    echo '<pre>';
    echo '<div style="border:3px solid #000">';
    print_r($array);
    echo '</div>';
    echo '</pre>';
  }
  