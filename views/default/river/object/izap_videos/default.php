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

$object = $vars['item']->getObjectEntity();
$contents = strip_tags($object->description);
$string .= $object->getAjaxedThumb();
if(strlen($contents) > 200) {
  $string .= substr($contents, 0, strpos($contents, ' ', 200)) . "...";
}else {
  $string .= $contents;
}
?>

<?php
$description = '<div class="izap-river">'.$string.'</div><div class="clearfloat"></div>';

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $description,
));