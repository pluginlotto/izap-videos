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
   * main init function for elgg engine plugin boot 
   * 
   * @global stdClass $IZAPSETTINGS
   * 
   * @version 5.0
   */
  function izap_video_init() {
    global $IZAPSETTINGS;
    $IZAPSETTINGS = new stdClass();
    $IZAPSETTINGS->api_server = 'http://api.pluginlotto.com';
    $IZAPSETTINGS->apiUrl = $IZAPSETTINGS->api_server . '?api_key=' . elgg_get_plugin_setting('izap_api_key', 'izap-videos') . '&domain=' . base64_encode(strtolower($_SERVER['HTTP_HOST']));
    $IZAPSETTINGS->playerPath = elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/player/izap_player.swf';
    $IZAPSETTINGS->graphics = elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/';

    $root = dirname(__FILE__);
    //define path for actions folder
    $action_root = dirname(__FILE__) . '/actions/izap-videos/';
    //register izap-videos plugin lib file
    elgg_register_library('elgg:izap_video', "$root/lib/izap-videos.php");
    //register page handler for particular identifier
    elgg_register_page_handler(GLOBAL_IZAP_VIDEOS_PAGEHANDLER, 'izap_video_page_handler');
    //register page handler for video page
    elgg_register_page_handler('izap_videos_files', 'izap_page_handler_videos_files');
    //register page handler for view videos
    elgg_register_page_handler('izap_view_video', 'izap_view_video_handler');
    elgg_register_entity_type('object', GLOBAL_IZAP_VIDEOS_SUBTYPE);
    //register menu item and set default path to all videos
    $item = new ElggMenuItem('video', elgg_echo('izap_video:Video'), GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/all');
    elgg_register_menu_item('site', $item);
    if (elgg_is_admin_logged_in()) {
      // Add admin menu item 
      elgg_register_admin_menu_item('administer', 'izap-videos-queue', 'statistics');
      elgg_register_admin_menu_item('administer', 'izap-videos-converson-fail', 'statistics');
    }
    elgg_load_library('elgg:izap_video');
    //register action
    elgg_register_action('izap-videos/save', $action_root . 'save.php');
    elgg_register_action(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/delete', $action_root . 'delete.php');
    elgg_register_action(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/trigger_queue', dirname(__FILE__) . '/actions/admin/' . 'trigger_queue.php');
    elgg_register_action(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/reset_queue', dirname(__FILE__) . '/actions/admin/' . 'reset_queue.php');
    //register hook handler
    elgg_register_plugin_hook_handler('unit_test', 'system', 'izap_video_unit_tests');
    elgg_register_plugin_hook_handler('video_unit_test', 'system', 'izap_offserver_unit_tests');
    elgg_register_plugin_hook_handler('entity:url', 'object', 'izap_videos_set_url');
    //register icon handler for thumbnail
    elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'izap_videos_set_icon_url');
    // elgg_register_plugin_hook_handler($action_root, $type, $callback);
    elgg_register_plugin_hook_handler('get_views', 'ecml', 'izap_videos_ecml_view');
    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'izap_videos_owner_block_menu');
    // extend the owner block
    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'izap_owner_block_izap_videos');
    //register video url handler
    elgg_register_entity_url_handler('object', 'izap_video', 'video_url');
    //extend old server stats with current stats
    elgg_extend_view('admin/statistics/server', 'admin/statistics/server_stats');
    elgg_extend_view('page/elements/footer', 'forms/izap-videos/my_javascript');
    elgg_extend_view('css/admin', 'izap-videos/admin_css');
    elgg_extend_view('groups/tool_latest', GLOBAL_IZAP_VIDEOS_PLUGIN . '/group_module');
    if (elgg_get_context() == GLOBAL_IZAP_VIDEOS_PAGEHANDLER) {
      elgg_extend_view('page/elements/footer', 'icon/object/powered_by');
    }
    elgg_register_js('elgg:validate_js', "mod/izap-videos/vendors/validate.js");
    elgg_register_js('elgg:preview_js', "mod/izap-videos/vendors/preview.js");
    elgg_register_js('elgg:player', "mod/izap-videos/views/default/js/mediaelement.js");
    elgg_register_css('elgg:video_css', 'mod/izap-videos/views/default/css/video-js.css');
    elgg_register_css('elgg:preview_css', 'mod/izap-videos/views/default/css/preview.css');
    elgg_register_widget_type('izap_queue_statistics-admin', elgg_echo('izap_queue_statistics-admin:widget_name'), elgg_echo('izap_queue_statistics-admin:widget_description'), 'admin');
    elgg_register_widget_type('izap_fail_conversion_statistics-admin', elgg_echo('izap_fail_conversion_statistics-admin:widget_name'), elgg_echo('izap_fail_conversion_statistics-admin:widget_description'), 'admin');
    elgg_register_widget_type('izap_latest_videos', elgg_echo('izap_latest_videos:widget_name'), elgg_echo('izap_latest_videos:widget_description'), 'profile, dashboard');
    elgg_register_widget_type('izap_my_videos', elgg_echo('izap_my_videos:widget_name'), elgg_echo('izap_my_videos:widget_description'), 'profile, dashboard');
    if(elgg_get_plugin_setting('izap_add_new_video_icon', 'izap-videos') == 'on'){
			elgg_register_event_handler('pagesetup', 'system', 'izap_add_new_video');
		}
  }

  /**
   * get owner's videos
   * 
   * @param string  $hook
   * @param string  $type
   * @param array   $return
   * @param array   $params
   * 
   * @return \ElggMenuItem
   * 
   * @version 5.0
   */
  function izap_owner_block_izap_videos($hook, $type, $return, $params) {
    if ((elgg_instanceof($params['entity'], 'group'))) {
      $url = izap_set_href(array(
        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
        'action' => 'all',
        'full_url' => FALSE
      ));
      $item = new ElggMenuItem(GLOBAL_IZAP_VIDEOS_PAGEHANDLER, elgg_echo('izap-videos:videos_' . $params['entity']->getType()), $url);
      $return[] = $item;
    }
    return $return;
  }

  /**
   * builds default options for video entity fetch
   * 
   * @param array $provided
   * 
   * @return array 
   * 
   * @version 5.0
   */
  function izap_defalut_get_videos_options($provided = array()) {
    $default = array(
      'type' => 'object',
      'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
    );
    return array_merge($default, $provided);
  }

  /**
   * Dispatches izap-video pages.
   * URLs take the form of
   * Get owner videos                                 izap-videos/owner/<username>
   * All izap-video:                                  izap-videos/all
   * User's izap-video:                               izap-videos/owner/<username>
   * Friends' izap-video:                             izap-videos/friends/<username>
   * New post:                                        izap-videos/add/<guid>
   * Edit post:                                       izap-videos/edit/<guid>/<revision>
   * Play pull video:                                 izap-videos/play/<username>/<video guid>/<video friendly title>
   * Get video player:                                izap-videos/viewvideo/<video guid>/<height>/<width>
   * Get queue:                                       izap-videos/queue
   * YouTube upload:                                  izap-videos/upload/<guid>
   * Set next url:                                    izap-videos/next
   * Get queue:                                       izap-videos/queue
   * Get preview data:                                izap-videos/preview
   * Save video after getting respone from youtube:   izap-videos/youtube_response
   * Check Video Status:                              izap-videos/check_video_status/<guid>
   * 
   * Title is ignored
   *
   * @todo no archives for all izap-videos or friends
   *
   * @param string $page
   * 
   * @return boolean
   * 
   * @version 5.0
   */
  function izap_video_page_handler($page) {
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
        
      case 'add':
        if ($page[2] == 'onserver' && izap_is_onserver_enabled_izap_videos() != 'yes') {
          register_error("Currently this service is not available, please try again later");
          forward();
        } elseif ($page[2] == 'youtube' && izap_is_onserver_enabled_izap_videos() != 'youtube') {
          register_error("Currently this service is not available, please try again later");
          forward();
        } elseif ($page[2] == 'offserver' && izap_is_offserver_enabled_izap_videos() != 'yes') {
          register_error("Currently this service is not available, please try again later");
          forward();
        }
        elgg_gatekeeper();
        $params = izap_video_get_page_content_edit($page_type, $page[1], $page[2]);
        break;

      case 'edit':
        elgg_gatekeeper();
        $params = izap_video_get_page_content_edit($page_type, $page[1], $page[2]);
        break;

      case 'all':
        $params = izap_video_get_page_content_list($page[1]);
        break;

      case 'icon':
        $params = izap_videos_read_content($page[1]);
        break;

      case 'play':
        elgg_load_css('elgg:video_css');
        elgg_load_js('elgg:video_js');
        $params = izap_videos_read_content($page[2]);
        $params['filter'] = false;
        break;

      case 'viewvideo':  
        $params = izap_get_video_player($page[1], $page[2], $page[3]);
        break;

      case 'queue':
        $params = izap_get_queue();
        break;

      case 'upload':
        elgg_gatekeeper();
        $params = izap_video_get_page_content_youtube_upload($page_type, $page[1], $page[2]);
        break;

      case 'next':
        elgg_gatekeeper();
        $params = izap_video_get_page_content_youtube_next();
        break;

      case 'preview':
        elgg_gatekeeper();
        izap_preview();
        break;

      case 'youtube_response':
        elgg_gatekeeper();
        izap_youtube_response();
        break;

      case 'check_video_status':
        elgg_gatekeeper();
        izap_check_video_status($page[1]);
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
    echo elgg_view_page($params['title'], $body);
    return true;
  }

  /**
   * return path for unit test
   * 
   * @param string  $hook
   * @param string  $type
   * @param string  $value
   * @param array   $params
   * 
   * @return string
   * 
   * @version 5.0
   */
  function izap_video_unit_tests($hook, $type, $value, $params) {
    $path[] = dirname(__FILE__) . '/tests/IzapVideoTest.php';
    return $path;
  }

  /**
   * return path for offserver unit test
   * 
   * @param string  $hook
   * @param string  $type
   * @param string  $value
   * @param array   $params
   * 
   * @return string
   * 
   * @version 5.0
   */
  function izap_offserver_unit_tests($hook, $type, $value, $params) { 
    $path[] = dirname(__FILE__) . '/tests/VideoUnitTest.php';
    return $path;
  }

  /**
   * return video view url
   * 
   * @param string  $hook
   * @param string  $type
   * @param string  $url
   * @param array   $params
   * 
   * @return url
   * 
   * @version 5.0
   */
  function izap_videos_set_url($hook, $type, $url, $params) {
    $entity = $params['entity'];
    if (elgg_instanceof($entity, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE)) {
      $friendly_title = elgg_get_friendly_title($entity->title);
      return GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/video/{$entity->guid}/$friendly_title";
    }
  }

  /**
   * return icon url for thumbnail
   * 
   * @param string  $hook
   * @param string  $type
   * @param string  $url
   * @param array   $params
   * 
   * @return string
   * 
   * @version 5.0
   */
  function izap_videos_set_icon_url($hook, $type, $url, $params) {
    $file = $params['entity'];
    if (elgg_instanceof($file, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE) && $file->imagesrc) {
      return "mod/izap-videos/thumbnail.php?file_guid=$file->guid";
    } else {
      global $IZAPSETTINGS;
      $url = $IZAPSETTINGS->graphics . 'no_preview.jpg';
      $url = elgg_trigger_plugin_hook('file:icon:url', 'override', $params, $url);
      return $url;
    }
  }

  /**
   * return video menu option for rightbar
   * 
   * @param string  $hook
   * @param string  $type
   * @param string  $return
   * @param array   $params
   * 
   * @return \ElggMenuItem
   * 
   * @version 5.0
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
   * @param array $page
   * 
   * @version 5.0
   */
  function izap_page_handler_videos_files($page) {
    set_input('what', $page[0]);
    set_input('videoID', $page[1]);
    set_input('size', $page[2]);
    elgg_load_library('elgg:izap_video');
    izap_read_video_file();
  }

  /**
   * print given array
   * 
   * @param array $array
   */
  function c($array) {
    echo '<pre>';
    echo '<div style="border:3px solid #000">';
    print_r($array);
    echo '</div>';
    echo '</pre>';
  }

  /**
   * Need for including ZEND
   * 
   * @version 5.0
   */
  $paths = array(
    elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/vendors/',
    '.',
  );
  set_include_path(implode(PATH_SEPARATOR, $paths));

  /**
   * Add link for new video in navigation bar
   * 
   * @version 5.0
   */
  function izap_add_new_video() {
    if (elgg_is_logged_in()) {
      $class = "new_video_icon";
      $text = "<span class='$class'></span>";
      $tooltip = elgg_echo('izap_videos:add');
      $url = GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/add/';
      if (izap_is_onserver_enabled_izap_videos() == 'yes') {
        $url .= elgg_get_logged_in_user_guid() . '/onserver';
      } elseif (izap_is_onserver_enabled_izap_videos() == 'youtube') {
        $url .= elgg_get_logged_in_user_guid() . '/youtube';
      } elseif (izap_is_offserver_enabled_izap_videos() == 'yes') {
        $url .= elgg_get_logged_in_user_guid() . '/offserver';
      } else {
        $url .= elgg_get_logged_in_user_guid() . '/offserver';
      }
      elgg_register_menu_item('topbar', array(
        'name' => 'add_new_video',
        'href' => $url,
        'text' => $text,
        'priority' => 800,
        'title' => $tooltip,
      ));
    }
  }
  
