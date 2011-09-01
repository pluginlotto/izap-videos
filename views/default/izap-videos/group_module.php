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
	$content = '<p>' . elgg_echo('izap-videos:none') . '</p>';
}



$new_link = elgg_view('output/url', array(
	'href' => IzapBase::setHref(array(
                'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                'action' => 'add',
                'vars' => array('tab' =>  (izap_is_onserver_enabled_izap_videos())?'onserver':'offserver'),
            )),
	'text' => elgg_echo('izap-videos:add_new'),
));


echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('izap-videos:videos_group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));