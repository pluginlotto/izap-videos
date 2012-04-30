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

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
ini_set('max_execution_time', 0);
ini_set('memory_limit', ((int)get_plugin_setting('izapMaxFileSize', GLOBAL_IZAP_VIDEOS_PLUGIN) + 100) . 'M');
IzapBase::loadLib(array(
  'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
  'lib' => 'izap_videos_lib'
));
// only works if started from command line
if($argc > 1 && $argv[1] == 'izap' && $argv[2] == 'web') {
  izapGetAccess_izap_videos(); // get the complete access to the system;
    izapRunQueue_izap_videos();
  izapRemoveAccess_izap_videos(); // remove the access from the system
}else {
  echo 'Oh You missed the Soup :)';
}
