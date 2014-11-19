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
 * Save YouTube videos
 * 
 * @version 5.0
 */
$youtube_data = array(
	'title' => $this->title,
	'description' => $this->description,
	'youtube_cats' => $this->youtube_cats,
	'tags' => $this->tags
);
$_SESSION['youtube_attributes'] = $youtube_data;
$video = IzapGYoutube::getAuthSubHttpClient(get_input('token', false));

//get youtube api authorization via users application access.
if (!($video instanceof IzapGYoutube)) {
	forward($video);
} else {
	// if we already have access token for youtube. than redirect user directly
	// on upload page.
	forward(izap_set_href(array(
		'action' => 'upload',
		'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
	)));
}

