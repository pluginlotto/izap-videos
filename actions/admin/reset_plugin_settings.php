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
$plugin = elgg_get_plugin_from_id(GLOBAL_IZAP_VIDEOS_PLUGIN);
$izap_plugin = new IzapPluginSettings();
if($izap_plugin->unsetAllSettings(&$plugin)) {
  system_message(elgg_echo('izap_videos:success:adminSettingsReset'));
}else {
  register_error(elgg_echo('izap_videos:error:adminSettingsReset'));
}
forward(REFERER);
