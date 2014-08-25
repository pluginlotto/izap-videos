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
elgg_register_event_handler('init', 'system', 'izap_video_init');

/**
 * main init function
 */
function izap_video_init() {
    $root = dirname(__FILE__);

    //define path for actions folder
    $action_root = dirname(__FILE__) . '/actions/izap-videos/';

    //register izap-videos plugin lib file
    elgg_register_library('elgg:izap_video', "$root/lib/izap-videos.php");

    //register page handler for particular identifier
    elgg_register_page_handler('izap-videos', 'izap_video_page_handler');

    elgg_register_entity_type('object', 'izap_video');

    //register menu item and set default path to all videos
    $item = new ElggMenuItem('video', elgg_echo('izap_video:Video'), 'izap-videos/all');
    elgg_register_menu_item('site', $item);

    if (elgg_is_admin_logged_in()) {
        // Add admin menu item @todo: can be done automatic loading via bridge
        elgg_register_admin_menu_item('administer', 'izap-videos-queue', 'statistics');
        elgg_register_admin_menu_item('administer', 'izap-videos-conversion_queue', 'statistics');
    }

    //register action
    elgg_register_action('izap-videos/save', $action_root . 'save.php');
    elgg_register_action('izap-videos/delete', $action_root . 'delete.php');
    elgg_register_action('izap-videos/trigger_queue', dirname(__FILE__) . '/actions/admin/' . 'trigger_queue.php');
    elgg_register_action('izap-videos/reset_queue', dirname(__FILE__) . '/actions/admin/' . 'reset_queue.php');
    //register hook handler
    elgg_register_plugin_hook_handler('unit_test', 'system', 'izap_video_unit_tests');
    //extend css
    elgg_extend_view('css/admin', 'izap-videos/admin_css');

    elgg_register_plugin_hook_handler('entity:url', 'object', 'izap_videos_set_url');
    
    //extend old server stats with current stats
    elgg_extend_view('admin/statistics/server', 'admin/statistics/server_stats');

    elgg_register_widget_type('izap-videos', elgg_echo('izap-videos'), elgg_echo('izap-videos:widget:description'));
    elgg_register_widget_type(
            'izap_queue_statistics-admin', elgg_echo('izap_queue_statistics-admin:widget_name'), elgg_echo('izap_queue_statistics-admin:widget_description'), 'admin');
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
    elgg_push_breadcrumb(elgg_echo('izap_video:Video'), "izap-videos/all");

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
            $params = izap_video_get_page_content_edit($page_type, $page[1]);
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
        case 'view':
            $params = izap_videos_read_content($page[1]);
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

function izap_videos_set_url($hook, $type, $url, $params) {
    $entity = $params['entity'];
    if (elgg_instanceof($entity, 'object', 'izap_video')) {
        $friendly_title = elgg_get_friendly_title($entity->title);
        return "izap-videos/view/{$entity->guid}/$friendly_title";
    }
}

