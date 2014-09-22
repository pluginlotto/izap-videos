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
   * Elgg izap-video widget
   * @package izap-video
   */
  $max = (int) $vars['entity']->num_display;

  $options = array(
    'type' => 'object',
    'subtype' => 'izap_video',
    'container_guid' => $vars['entity']->owner_guid,
    'limit' => $max,
    'full_view' => FALSE,
    'pagination' => FALSE,
  );
  $content = elgg_list_entities($options);

  echo $content;

  if ($content) {
    $url = "izap_video/owner/" . elgg_get_page_owner_entity()->username;
    $more_link = elgg_view('output/url', array(
      'href' => $url,
      'text' => elgg_echo('more'),
      'is_trusted' => true,
    ));
    echo "<span class=\"elgg-widget-more\">$more_link</span>";
  } else {
    echo elgg_echo('demo:none');
  }
