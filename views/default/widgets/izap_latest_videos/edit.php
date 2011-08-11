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

if (!isset($vars['entity']->videos_to_show)) {
	$vars['entity']->videos_to_show = 4;
}

$params = array(
	'internalname' => 'params[videos_to_show]',
	'value' => $vars['entity']->videos_to_show,
	'options' => range(1, 10),
);
$dropdown = elgg_view('input/dropdown', $params);

?>
<p>
	<?php echo elgg_echo('izap-videos:numbertodisplay'); ?>:
	<?php echo $dropdown; ?>
</p>