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

if(elgg_is_active_plugin('izap-uploadify')) {
  $elements = IzapBase::input('izap-uploadify', array(
          'internalname' => 'attributes[videoFile]',
          'input_title' => elgg_echo('izap_videos:addEditForm:videoFile'),
          'value' => $vars['loaded_data']->videoFile,
          'internalid' => 'video_file',
          'form_id' => 'izap_video_from',
          'redirect_url' => $vars['url'] . 'videos/list/' . elgg_get_logged_in_user_entity()->username,
  ));
}else {
  $elements = IzapBase::input('file', array(
          'internalname' => 'attributes[videoFile]',
          'input_title' => elgg_echo('izap_videos:addEditForm:videoFile'),
          'internalid' => 'video_file',
  ));
}
$elements .= elgg_view('input/hidden', array(
        'internalname' => 'attributes[videoType]',
        'value' => 'ONSERVER',
));
echo $elements;