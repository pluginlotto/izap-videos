<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
*/


IzapBase::loadLib(array(
  'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
  'lib' => 'izap_videos_lib'
));
izapTrigger_izap_videos();
system_message(elgg_echo('izap_videos:adminSettings:reset_queue'));
forward(REFERER);
exit;