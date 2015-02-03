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

$url = GLOBAL_IZAP_VIDEOS_PAGEHANDLER;
$get_type = end(explode('/', current_page_url()));
$container = parse_url(current_page_url());
$container_guid = explode('/', $container['path']);
$second_last_key = key( array_slice( $container_guid, -2, 1, TRUE ) );

if (izap_is_onserver_enabled_izap_videos() == 'yes') {
	$tabs['onserver'] = array(
		'title' => elgg_echo('izap-videos:onserver'),
		'url' => "$url/add/" . $container_guid[$second_last_key] . '/onserver',
		'selected' => ($get_type == 'onserver'),
	);
} elseif (izap_is_onserver_enabled_izap_videos() == 'youtube') {
	$tabs['onserver'] = array(
		'title' => elgg_echo('izap-videos:youtube'),
		'url' => "$url/add/" . $container_guid[$second_last_key] . '/youtube',
		'selected' => ($get_type == 'youtube'),
	);
}
if (izap_is_offserver_enabled_izap_videos() == 'yes') {
	$tabs['offserver'] = array(
		'title' => elgg_echo('izap-videos:offserver'),
		'url' => "$url/add/" . $container_guid[$second_last_key] . '/offserver',
		'selected' => ($get_type == 'offserver')
	);
}
echo elgg_view('navigation/tabs', array('tabs' => $tabs));
