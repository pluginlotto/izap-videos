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
      'subtype' => 'izap_video',
      'full_view' => false,
      'no_results' => elgg_echo('izap-videos:none'),
  );

  $current_user = elgg_get_logged_in_user_entity();
  if ($container_guid) {
// access check for closed groups
    elgg_group_gatekeeper();

//  $options['container_guid'] = $container_guid; 
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
  $url = GLOBAL_IZAP_VIDEOS_PLUGIN . '/add/';
  $url .= elgg_get_logged_in_user_guid() . '/onserver';
  elgg_register_menu_item('title', array(
      'name' => elgg_get_friendly_title($title),
      'href' => $url,
      'text' => $title,
      'link_class' => 'elgg-button elgg-button-action',
  ));

  $return['content'] = elgg_list_entities($options);
  return $return;
}

/**
 * Get page components to list of the user's friends' posts.
 * @param type $container_guid
 */
function izap_video_get_page_content_friends($container_guid = NULL) {
  
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
    
  } else {
    elgg_push_breadcrumb(elgg_echo('izap_videos:add'));
    $body_vars = izap_videos_prepare_form_vars(null);

    $form_vars = array('enctype' => 'multipart/form-data');
    $title = elgg_echo('izap-videos:add');
    $content = elgg_view_form('izap-videos/save', $form_vars, $body_vars);
  }

  $return['title'] = $title;
  $return['content'] = $content;
  $return['sidebar'] = $sidebar;

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

function izapAdminSettings_izap_videos($settingName, $values = '', $override = false, $makeArray = false) {
  $send_array = array(
      'name' => $settingName,
      'value' => $values,
      'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
  );

  return pluginSetting($send_array);
}

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

