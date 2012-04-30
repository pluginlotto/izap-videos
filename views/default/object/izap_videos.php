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

if(elgg_get_context() == 'search') {
  echo elgg_view('object/'.GLOBAL_IZAP_VIDEOS_PLUGIN.'/search', array('video' => $vars['entity']));
}elseif(elgg_get_context() == 'izap_mini_list') {
  echo elgg_view('object/'.GLOBAL_IZAP_VIDEOS_PLUGIN.'/mini', array('video' => $vars['entity']));
}else {
  if($vars['full']) {
    echo elgg_view('object/'.GLOBAL_IZAP_VIDEOS_PLUGIN.'/full', array_merge($vars, array('video' => $vars['entity'])));
  }else {
    echo elgg_view('object/'.GLOBAL_IZAP_VIDEOS_PLUGIN.'/partial', array('video' => $vars['entity']));
  }
}