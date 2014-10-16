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

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
ini_set('max_execution_time', 0);
ini_set('memory_limit', ((int) get_plugin_setting('izapMaxFileSize', GLOBAL_IZAP_VIDEOS_PLUGIN) + 100) . 'M');
elgg_load_library('elgg:izap_video');
// only works if started from command line
if ($argc > 1 && $argv[1] == 'izap' && $argv[2] == 'web') {
	izap_get_access_izap_videos(); // get the complete access to the system;
	izap_run_queue_izap_videos();
	izap_remove_access_izap_videos(); // remove the access from the system
}