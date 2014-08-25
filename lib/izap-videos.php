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
    $url = GLOBAL_IZAP_VIDEOS_PLUGIN . '/add/';

    if (izap_is_onserver_enabled_izap_videos() == 'yes') {
        $url .= elgg_get_logged_in_user_guid() . '/onserver';
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
        $izap_video = get_entity((int) $guid);
        $title = elgg_echo('izap_videos:edit') . ":";
        if (elgg_instanceof($izap_video, 'object', 'izap_video') && $izap_video->canEdit()) {
            $form_vars['entity'] = $izap_video;
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
    $not_converted_videos = get_entities_from_metadata('converted', 'no', 'object', 'izap_videos', 0, 999999);
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
function izapTrigger_izap_videos() {
    $PHPpath = izapGetPhpPath_izap_videos();
    $file_path = elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/izap_convert_video.php';

    if (!izap_is_queue_running_izap_videos()) {
        if (izapIsWin_izap_videos()) {
            pclose(popen("start \"MyProcess\" \"cmd /C " . $PHPpath . " " . $file_path, "r"));
        } else {
            exec($PHPpath . ' ' . $file_path . ' izap web', $output);
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

function izap_save_fileinfo_for_converting_izap_videos($file, $video, $defined_access_id = 2) {
// this will not let save any thing if there is no file to convert
    if (!file_exists($file) || !$video) {
        return false;
    }

    $queue = new izapQueue();
    $r = $queue->put($video, $file, $defined_access_id);
}

/**
 * 
 * @return boolean
 */
function izap_run_queue_izap_videos() {
    $queue_object = new izapQueue();
    $queue = $queue_object->fetch_videos();
    return $queue;
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

function izapConvertVideo_izap_videos($file, $videoId, $videoTitle, $videoUrl, $ownerGuid, $accessId = 2) {

    if (file_exists($file)) {
        $queue_object = new izapQueue();
        // $queue_object->change_conversion_flag($videoId);

        $video = new izapConvert($file);
        $videofile = $video->izap_video_convert();   //if file converted successfully then change flag from pending to processed
     print_R($videofile);
        if ($videofile['error'] > 0) { echo 'if'; exit;
            return $videofile['message'];
        } else { 
            $queue_object->change_conversion_flag($videoId);
        }
    }
}
