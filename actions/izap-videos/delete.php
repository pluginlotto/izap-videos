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
   * delete izap-video entity
	 * 
	 * @version 5.0
   */
  $guid = get_input('guid');
  $izap_video = get_entity($guid);

  if (elgg_instanceof($izap_video, 'object', 'izap_video') && $izap_video->canEdit()) {
    $container = get_entity($izap_video->container_guid); //print_r($container); exit;
    if ($izap_video->delete()) {
      system_message(elgg_echo('izap_videos:deleted'));
      if (elgg_instanceof($container, 'group')) {
        forward(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/group/$container->guid/all");
      } else {
        forward(GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/owner/$container->username");
      }
    }
  }

  register_error(elgg_echo("izap_videos:delete:failed"));
  forward(REFERER);
  