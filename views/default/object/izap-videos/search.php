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


if($vars['video']) {
  $friendlytime = elgg_get_friendly_time($vars['video']->time_created);
  $icon = '<a href="' . $vars['video']->getURL() . '"><img src="'.$vars['video']->getThumb(TRUE).'"></a>';

  $info = elgg_echo('videos') . " : ";
  $info .= '<a href="' . $vars['video']->getURL() . '"  class="screenshot" rel="' . $vars['video']->getThumb(TRUE) . '">' . $vars['video']->getTitle() . '</a>';
  $info .= "<br />";
  $info .= "<a href=\"".IzapBase::setHref(array(
    'context' => 'videos',
    'page_owner' => $vars['entity']->owner_username,
    'action' => 'owner'
  ))."\">{$vars['video']->onwer_name}</a> {$friendlytime}";
  echo elgg_view_listing($icon,$info);
}