<?php

/* * *************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2011. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

/**
 * Define some globals
 */
define('GLOBAL_IZAP_VIDEOS_PLUGIN', 'izap-videos');
define('GLOBAL_IZAP_VIDEOS_PAGEHANDLER', 'videos');
define('GLOBAL_IZAP_VIDEOS_SUBTYPE', 'izap_videos');
define('GLOBAL_IZAP_VIDEOS_CLASS', 'IzapVideos');
define('GLOBAL_IZAP_VIDEOS_DATAENTRY_ACCESS', 'logged_in');

// Hook the pluugin with the system
if (elgg_is_active_plugin(GLOBAL_IZAP_ELGG_BRIDGE)) {
  elgg_register_event_handler('init', 'system', 'izap_videos_init');
}

/**
 * main init function, that will be hooked
 */
function izap_videos_init() {
  // start plugin
  izap_plugin_init(GLOBAL_IZAP_VIDEOS_PLUGIN);


  global $CONFIG, $IZAPSETTINGS;
  $IZAPSETTINGS = new stdClass();

  $IZAPSETTINGS->filesPath = $CONFIG->wwwroot . 'pg/izap_videos_files/';
  $IZAPSETTINGS->playerPath = $CONFIG->wwwroot . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/player/izap_player.swf';
  $IZAPSETTINGS->api_server = 'http://api.pluginlotto.com/';
  $IZAPSETTINGS->apiUrl = $IZAPSETTINGS->api_server . '?api_key=' . IzapBase::APIKEY() . '&domain=' . base64_encode(strtolower($_SERVER['HTTP_HOST']));
  $IZAPSETTINGS->allowedExtensions = array('avi', 'flv', '3gp', 'mp4', 'wmv', 'mpg', 'mpeg');
  $IZAPSETTINGS->ffmpegPath = $CONFIG->pluginspath . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/bin/ffmpeg.exe';
  $IZAPSETTINGS->ffmpegPresetPath = $CONFIG->pluginspath . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/presets/libx264-hq.ffpreset';
  $IZAPSETTINGS->graphics = $CONFIG->wwwroot . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/';
  $IZAPSETTINGS->ajaxed_video_height = 200;
  $IZAPSETTINGS->ajaxed_video_width = 250;
  $IZAPSETTINGS->on_server = $is_onserver;


  // register pagehandler
  register_page_handler(GLOBAL_IZAP_VIDEOS_PAGEHANDLER, GLOBAL_IZAP_PAGEHANDLER);
  register_page_handler('izap_videos_files', 'pageHandler_izap_videos_files');

  // register menu
  $menu = new ElggMenuItem(
          'izap-videos:videos',
          ucfirst(elgg_echo('izap-videos:videos')),
          IzapBase::setHref(
              array(
                'context' => 'videos',
                'action' => 'all',
                'page_owner' => FALSE,
              )
          )
  );
  elgg_register_menu_item('site', $menu);

  if (elgg_is_admin_logged_in()) {
    // Add admin menu item @todo: can be done automatic loading via bridge
    elgg_register_admin_menu_item('administer', 'izap-videos-queue', 'statistics');
    elgg_register_admin_menu_item('administer', 'izap-videos-trash', 'statistics');
    elgg_register_admin_menu_item('develop', 'izap-videos-server', 'utilities');
  }

  // register widgets @todo this will be done via bridge
  elgg_register_widget_type(
      'izap_latest_videos', elgg_echo('izap_latest_videos:widget_name'), elgg_echo('izap_latest_videos:widget_description'), 'profile, dashboard');

  elgg_register_widget_type(
      'izap_my_videos', elgg_echo('izap_my_videos:widget_name'), elgg_echo('izap_my_videos:widget_description'), 'profile, dashboard');

  elgg_register_widget_type(
      'izap_queue_statistics-admin', elgg_echo('izap_queue_statistics-admin:widget_name'), elgg_echo('izap_queue_statistics-admin:widget_description'), 'admin');

  if (izap_topbar_video_add_icon()) {
    $group_page_owner = false;
    if (preg_match('/(group:[0-9]+)/', $_GET['page'], $matches)) {
      $group_page_owner = $matches[0];
    } elseif (elgg_get_context() == 'groups') {
      if (preg_match('/profile\/([0-9]+)\//', $_GET['page'], $matches)) {
        $group_page_owner = 'group:' . $matches[1];
      }
    }
    elgg_register_menu_item('topbar', array(
      'name' => 'video_top_bar',
      'href' => IzapBase::setHref(array(
        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
        'action' => 'add',
        'page_owner' => ($group_page_owner) ? $group_page_owner : elgg_get_logged_in_user_entity()->username,
        'vars' => array('onserver')
      )),
      'title' => elgg_echo('izap_videos:uploadVideo'),
      'text' => '<img src="' . elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/upload_video.png" />',
    ));
  }

  // extend the group tools
  elgg_register_event_handler('izap', 'link', 'izap_video_link_hook');
  add_group_tool_option(GLOBAL_IZAP_VIDEOS_PAGEHANDLER, elgg_echo('izap-videos:enable_videos'));
  elgg_extend_view('groups/tool_latest', GLOBAL_IZAP_VIDEOS_PLUGIN . '/group_module');


  // extend the owner block
  elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'izap_owner_block_izap_videos');

  // register for the ecml TODO: need more informations yet
  elgg_register_plugin_hook_handler('get_views', 'ecml', 'izap_ecml_views_hook_izap_videos');

  // finally add plugin in search list
  register_entity_type('object', GLOBAL_IZAP_VIDEOS_SUBTYPE);
  elgg_extend_view('index/righthandside', GLOBAL_IZAP_VIDEOS_PLUGIN . '/index_widget');
}

/**
 * sets page hadler for the thumbs and video
 *
 * @param array $page
 */
function pageHandler_izap_videos_files($page) {
  set_input('what', $page[0]);
  set_input('videoID', $page[1]);
  set_input('size', $page[2]);
  IzapBase::loadLib(array(
    'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    'lib' => 'izap_videos_lib'
  ));
  read_video_file();
}

/**
 * checks if onserver videos are enabled in admin settings
 * @return <type>
 */
function izap_is_onserver_enabled_izap_videos() {
  $settings = IzapBase::pluginSetting(array(
        'name' => 'onserver_enabled_izap_videos',
        'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
      ));

  if ((string) $settings === 'no') {
    return FALSE;
  }

  return $settings;
}

/**
 * checks if offserver videos are enabled in admin settings
 * @return <type>
 */
function izap_is_offserver_enabled_izap_videos() {
  $settings = IzapBase::pluginSetting(array(
        'name' => 'offserver_enabled_izap_videos',
        'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
      ));

  if ((string) $settings === 'no') {
    return FALSE;
  }

  return TRUE;
}

function izap_video_link_hook() {
  if (elgg_get_context() == GLOBAL_IZAP_VIDEOS_PAGEHANDLER) {
    elgg_extend_view('page/elements/footer', 'output/ilink');
    return False;
  }
  return True;
}

function izap_owner_block_izap_videos($hook, $type, $return, $params) {
  if (elgg_instanceof($params['entity'], 'user')
      ||
      (elgg_instanceof($params['entity'], 'group') && $params['entity']->{GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '_enable'} == 'yes')) {

    $url = IzapBase::setHref(array(
          'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
          'action' => 'owner',
          'page_owner' => $parms['entity']->username,
          'full_url' => FALSE
        ));
    $item = new ElggMenuItem(GLOBAL_IZAP_VIDEOS_PAGEHANDLER, elgg_echo('izap-videos:videos_' . $params['entity']->getType()), $url);
    $return[] = $item;
  }
  return $return;
}

function izap_defalut_get_videos_options($provided = array()) {
  $default = array(
    'type' => 'object',
    'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
  );

  return array_merge($default, $provided);
}

function izap_is_video(ElggEntity $entity) {
  return elgg_instanceof($entity, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE, GLOBAL_IZAP_VIDEOS_CLASS);
}

function izap_give_credit() {
  $setting = IzapBase::pluginSetting(array(
        'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
        'name' => 'izapGiveUsCredit',
      ));

  if ($setting !== 'no') {
    return TRUE;
  }

  return FALSE;
}

function izap_topbar_video_add_icon() {
  $admin_setting = (string) IzapBase::pluginSetting(array(
        'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
        'name' => 'topbar_extend_izap_videos'
      ));

  if (elgg_is_logged_in() && izap_is_onserver_enabled_izap_videos() && $admin_setting !== 'no') {
    return TRUE;
  }

  return FALSE;
}

function izap_ecml_views_hook_izap_videos($hook, $entity_type, $return_value, $params) {
  $return_value['object/' . GLOBAL_IZAP_VIDEOS_SUBTYPE] = elgg_echo('izap-videos:videos');

  return $return_value;
}