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

/**
 * Save offserver videos
 * 
 * @version 5.0
 */
global $IZAPSETTINGS;
$arg = parse_url($IZAPSETTINGS->apiUrl);
$api = explode('&', $arg['query']);
$key = explode('=', $api[0]);
if ($key[1] == '') {
	register_error('Register API Key for offserver video');
	forward(REFERER);
}
$video_data = array(
	'url' => $this->videourl,
	'title' => $this->title,
	'description' => $this->description,
);
$this->saveYouTubeVideoData($video_data);
