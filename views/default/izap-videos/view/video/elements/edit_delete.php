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

if ($vars['video']->canEdit()) {
  $DeleteEdit .= elgg_view("output/confirmlink", array(
          'href' => IzapBase::getFormAction('delete', GLOBAL_IZAP_VIDEOS_PLUGIN) . '?video_id=' . $vars['video']->getGUID(),
          'text' => '<img src="'.$vars['url'].'mod/'.GLOBAL_IZAP_ELGG_BRIDGE.'/_graphics/delete.png" />',                                                               //elgg_echo('delete'),
          'confirm' => elgg_echo('izap_videos:remove'),
      'title' => 'delete'
  ));
  $DeleteEdit .= '&nbsp;&nbsp;';
  if($vars['video']->converted == 'yes') {
    $DeleteEdit .= '<a href="' . IzapBase::setHref(array(
      'action' => 'edit',
      'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
      'page_owner' => $vars['video']->container_username,
        )) . $vars['video']->getGUID() . '" title = "Edit"><img src="'.$vars['url'].'mod/'.GLOBAL_IZAP_ELGG_BRIDGE.'/_graphics/edit.png" />'//elgg_echo('izap_videos:edit')
    . '</a>';
  }else {
    $queue_object = new izapQueue();
    $trash_guid_array = $queue_object->get_from_trash($vars['video']->guid);
    if($trash_guid_array) {
      $DeleteEdit .= elgg_echo("izap_videos:form:izapTrashedVideoMsg");
    }else {
      $DeleteEdit .= elgg_echo("izap_videos:form:izapEditMsg");
    }
  }
}
echo $DeleteEdit;