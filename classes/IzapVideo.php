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

    public function saveVideo($data = array()) {
      foreach ($data as $key => $value) {
        $this->$key = $value;
      }
      // mark it as new vidoe if guid is not there yet
      if ($this->guid == 0) {
        $new = true;
      }
      if ($this->videoprocess == 'offserver' || $this->videoprocess == 'onserver' || $this->videoprocess == 'youtube') {
        switch ($this->videoprocess) {
          case 'offserver':
            include_once (dirname(dirname(__FILE__)) . '/actions/izap-videos/offserver.php');
            $saved = $this->save();
            break;
          case 'youtube':
            include_once (dirname(dirname(__FILE__)) . '/actions/izap-videos/youtube.php');
            forward(REFERRER);
            break;
          case 'onserver':
            include_once (dirname(dirname(__FILE__)) . '/actions/izap-videos/onserver.php');
            //before start converting
            $this->converted = 'no';
            if ($saved = $this->save()) {
              $get_guid = $this->getGUID();
              $get_entity = get_entity($get_guid);
              if (file_exists($get_entity->videofile)) {
                $this->videosrc = elgg_get_site_url() . 'izap_videos_files/file/' . $get_entity->guid . '/' . elgg_get_friendly_title($get_entity->title) . '.flv';
                if (getFileExtension($get_entity->videofile) != 'flv') {
                  izap_save_fileinfo_for_converting_izap_videos($get_entity->videofile, $get_entity, $get_entity->access_id, $this);
                }
                //change access id to submit by user after converting video
                $this->access_id = $data['access_id'];
                $saved = $this->save();
              }
            }
            break;
        }
        //create river if new entity
        if ($new == true) {
          elgg_create_river_item(array(
            'view' => 'river/object/izap_video/create',
            'action_type' => 'create',
            'subject_guid' => elgg_get_logged_in_user_guid(),
            'object_guid' => $this->getGUID(),
          ));
        }
        elgg_clear_sticky_form('izap_videos');
        system_messages(elgg_echo('izap-videos:Save:success'));
      } else {
        $saved = $this->save();
      }
      return $saved;
    }

    /**
     * process upload file
     * @param type $file
     * @return int
     */
    public function processfile($file) {
      $returnvalue = new stdClass();

      $filename = str_replace(' ', '_', $file['name']);
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

      // take snapshot from video
      $image = new izapConvert($returnvalue->videofile);
      if ($image->get_thumbnail_from_video()) {
        $retValues = $image->getValues(TRUE);
        if ($retValues['imagename'] != '' && $retValues['imagecontent'] != '') {
          $set_original_thumbnail = $this->get_tmp_path('original_' . $retValues['imagename']);
          $this->setFilename($set_original_thumbnail);
          $this->open("write");
          if ($this->write($retValues['imagecontent'])) {
            $orignal_file_path = $this->getFilenameOnFilestore();

            $thumb = get_resized_image_from_existing_file($orignal_file_path, 650, 500);
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

    public function getURL() {
      $owner = $this->getOwnerEntity();
      return elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/play/' . $owner['username'] . '/' . $this->guid . '/' . elgg_get_friendly_title($this->title);
    }

    public function saveYouTubeVideoData($url) {
      $videoValues = input($url, $this);
      $this->orignal_thumb = $this->get_tmp_path('original_' . $this->filename);
      $this->imagesrc = $this->get_tmp_path($this->filename);
      $this->videotype_site = $this->domain;
      $this->converted = 'yes';
      $this->setFilename($this->orignal_thumb);
      $this->open("write");
    }

  }
  