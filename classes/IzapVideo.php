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

class IzapVideo extends IzapFile {

  /**
   * 
   * @param type $file_path input path for ffmpeg processing
   */
  public function processOnserverVideo($file_path) {
    
    $form_attributes = array('title' => array(),
        'description' => array(),
        'video_url' => array(),
        'video_type' => array(),
        'video_process' => array(),
        'container_guid' => array(),
        'tags' => array(),
        'comments_on' => array());
  }

}