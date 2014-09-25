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

  $upload_video = $_FILES['upload_video'];
  $return_value = $this->processfile($upload_video);

  if (!file_exists($return_value->videofile)) {
    register_error(elgg_echo('izap_videos:error:notUploaded'));
    forward($_SERVER['HTTP_REFERER']);
    exit;
  }
  $this->access_id = 0;
  $this->videotype = $return_value->videotype;
  if ($return_value->videofile) {
    $this->videofile = $return_value->videofile;
  }

  if (empty($_FILES['upload_thumbnail']['name'])) {
    if ($return_value->thumb) { 
      $this->orignal_thumb = $return_value->orignal_thumb;
      $this->imagesrc = $return_value->thumb;
    }
  } else {
    if($_FILES['upload_thumbnail']['error'] == 0) {
      $set_original_thumbnail = $this->get_tmp_path('original_' .$_FILES['upload_thumbnail']['name']);
      $this->setFilename($set_original_thumbnail);
      $this->open("write");
      $this->write(file_get_contents($_FILES['upload_thumbnail']['tmp_name']));

      //set thumbnail size
      $thumbnail = get_resized_image_from_existing_file($this->getFilenameOnFilestore(), 650, 500);
      $set_thumb = $this->get_tmp_path($_FILES['upload_thumbnail']['name']);
      $this->setFilename($set_thumb);
      $this->open("write");
      $this->write($thumbnail);
      $this->imagesrc = $set_thumb;
    }
  }