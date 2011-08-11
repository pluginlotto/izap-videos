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

$video = elgg_extract('entity', $vars);
$size = elgg_extract('size', $vars);
$is_ajax = elgg_extract('ajax', $vars);
$image_attr_array = array();
switch ($size) {
  case 'tiny' :
    $image_attr_array['width'] = '16';
    $image_attr_array['height'] = '16';
    break;

  case 'small' :
    $image_attr_array['width'] = '40';
    $image_attr_array['height'] = '40';
    break;

  case 'medium' :
    $image_attr_array['width'] = '80';
    $image_attr_array['height'] = '80';
    break;
}

if($is_ajax === TRUE):
  echo $video->getAjaxedThumb();
else:
  echo $video->getThumb(FALSE, $image_attr_array);
endif;