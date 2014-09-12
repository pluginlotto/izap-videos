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

  class IzapVideoTest extends PHPUnit_Framework_TestCase {

    protected $obj;

    protected function setUp() {
      // required by ElggEntity when setting the owner/container
      _elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));

      $this->obj = $this->getMockBuilder('IzapVideo')
        ->setMethods(null)
        ->getMock();
    }

    /**
     * test for saving  elgg_entity
     */
    public function testCorrectOnserverVideo() {

     // $izapvideo_obj = new IzapVideo();
      $source_path = dirname(__FILE__) . '/test_video.avi';
      $dest_path = elgg_get_data_path();  //get data folder path    

      $this->obj->subtype = GLOBAL_IZAP_VIDEOS_SUBTYPE;
      $this->obj->title = 'add new video';
      $this->obj->description = 'new video add here';
      $this->obj->owner_guid = 77;
      $this->obj->access_id = 2;

      $file = array('name' => 'test_video.avi', 'tmp_name' => $source_path, 'size' => '309042' , 'error' => '0','type' => 'video/x-msvideo');
      $data = $this->obj->processfile($file);
       
      //$process_video = $izapvideo_obj->processOnserverVideo($source_path, $dest_path);

//      if ($izapvideo_obj->save()) {
//        $saved_guid = $izapvideo_obj->getGUID();
//      }
//      unset($izapvideo_obj);
//      $saved_object = get_entity($saved_guid);
//
//      $tmppath = $saved_object->videofile;
//      var_dump($tmppath);
//      echo (file_exists($saved_object->videofile)) ? 'true' : 'false';
//      exit;

//print_r($saved_object); exit;
    //  $this->assertEquals($saved_object->guid, $saved_guid);
    }

  }
  