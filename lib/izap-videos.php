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
  elgg_register_title_button() ;
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
