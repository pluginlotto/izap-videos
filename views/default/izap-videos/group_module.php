<?php

/*
 *    This file is part of izap-videos plugin for Elgg.
 *
 *    izap-videos for Elgg is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    izap-videos for Elgg is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with izap-videos for Elgg.  If not, see <http://www.gnu.org/licenses/>.
 */

$group = elgg_get_page_owner_entity();

if ($group->{GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '_enable'} == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => izap_setHref(array(
		'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
		'action' => 'all',
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
	'href' => izap_setHref(array(
		'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
		'action' => 'add',
		'vars' => array('tab' => (izap_is_onserver_enabled_izap_videos()) ? 'onserver' : 'offserver'),
	)),
	'text' => elgg_echo('izap-videos:add_new'),
	));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('izap-videos:videos_group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
