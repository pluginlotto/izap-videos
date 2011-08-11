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

set_context('izap_mini_list');
$videos = elgg_list_entities(array(
    'type' => 'object',
    'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
    'full_view' => FALSE,
    'pagination' => FALSE,
    'limit' => 5
));

echo elgg_view_module('featured',  elgg_echo('izap_latest_videos:widget_name'), $videos);