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

$powered_url = elgg_get_site_url() . "_graphics/powered-by-izap.png";

echo '<div class="mts clearfloat float-alt">';
echo elgg_view('output/url', array(
	'href' => 'http://izap.in',
	'text' => "<img src=\"$powered_url\" alt=\"Powered by Elgg\" width=\"106\" height=\"15\" />",
	'class' => '',
	'is_trusted' => true,
));
echo '</div>';
