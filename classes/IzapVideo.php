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

  class IzapVideo extends ElggFile {

    protected $format = '.flv';

    protected function initializeAttributes() {
      parent::initializeAttributes();

      $this->attributes['subtype'] = GLOBAL_IZAP_VIDEOS_SUBTYPE;
    }

    public function __construct($guid = NULL) {
      parent::__construct($guid);
// set some initial values so that old videos can work
    }

    /**
     * set tmp path for upload video
     */
    public function get_tmp_path($name) {
      $setFileName = 'izap_videos/tmp/' . $name;
      return $setFileName;
    }

    /**
     * 
     * @param type $file_path input path for ffmpeg processing
     */
    public function processOnserverVideo($source_path, $dest_path) {
// $returnvalue = new stdClass();

      $destination_path = $dest_path . time() . $this->format;
      $file_name = end(explode('/', $destination_path));
      $source_file = end(explode('/', $source_path));

//tmp file
      $this->setFilename($this->get_tmp_path($source_file));
      $this->open('write');
      $this->write(file_get_contents($source_path));
      $this->videofile = $this->getFilenameOnFilestore();
//  $returnvalue->tmpfilepath = $this->getFilenameOnFilestore();
//  return $returnvalue;
    }

    /**
     * process upload file
     * @param type $file
     * @return int
     */
    public function processfile($file) { 
      $returnvalue = new stdClass();

      $filename = $file['name'];
      $tmpname = $file['tmp_name']; 
      $file_err = $file['error'];
      $file_type = $file['type'];
      $file_size = $file['size'];

      if ($file_err > 0) { 
        return 104;
      }

      // if file is of zero size
      if ($file_size == 0) { 
        return 105;
      }
      $returnvalue->videotype = $file_type; 
      $set_video_name = $this->get_tmp_path(time() . $filename); 
      $this->setFilename($set_video_name);
      $this->open("write");
      $this->write(file_get_contents($tmpname));
      $returnvalue->videofile = $this->getFilenameOnFilestore();

      // take snapshot of the video
      $image = new izapConvert($returnvalue->videofile);  
      if ($image->get_thumbnail_from_video()) { 
        $retValues = $image->getValues(TRUE);
        if ($retValues['imagename'] != '' && $retValues['imagecontent'] != '') { 
          $set_original_thumbnail = $this->get_tmp_path('original_' . $retValues['imagename']); 
          $this->setFilename($set_original_thumbnail);
          $this->open("write");
          if ($this->write($retValues['imagecontent'])) {
            $orignal_file_path = $this->getFilenameOnFilestore();

            $thumb = get_resized_image_from_existing_file($orignal_file_path, 120, 90);
            $set_thumb = $this->get_tmp_path($retValues['imagename']);
            $this->setFilename($set_thumb);
            $this->open("write");
            $this->write($thumb);

           // $this->close();
            $returnvalue->orignal_thumb = $set_original_thumbnail;
            $returnvalue->thumb = $set_thumb;
          }
        }
      }
      return $returnvalue;
    }

  }
  