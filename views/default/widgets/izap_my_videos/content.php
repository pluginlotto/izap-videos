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

$limit = $vars['entity']->videos_to_show;
$limit = ($limit) ? $limit : 5;

elgg_set_context('izap_mini_list');
echo elgg_list_entities_from_metadata(izap_defalut_get_videos_options(array(
  'limit' => $limit,
  'full_view' => FALSE,
  'metadata_name' => 'converted',
  'metadata_value' => 'yes',
  'pagination' => FALSE,
  'container_guid' => elgg_get_logged_in_user_guid(),
)));