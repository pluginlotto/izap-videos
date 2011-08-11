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


$group = elgg_get_page_owner_entity();

if ($group->{GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '_enable'} == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => IzapBase::setHref(array(
      'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
      'action' => 'owner',
    )),
	'text' => elgg_echo('link:view:all'),
));

$header = "<span class=\"groups-widget-viewall\">$all_link</span>";
$header .= '<h3>' . elgg_echo('izap-videos:videos_group') . '</h3>';


elgg_push_context('izap_mini_list');
$options = array(
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities(izap_defalut_get_videos_options($options));
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('blog:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => IzapBase::setHref(array(
      'action' => 'add',
      'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
    )),
	'text' => elgg_echo('izap-videos:add_new'),
));
$content .= "<span class='elgg-widget-more'>$new_link</span>";


$params = array(
	'header' => $header,
	'body' => $content,
	'class' => 'elgg-module-info',
);

echo elgg_view_module('info', '', $content, array('header' => $header));


