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

//    $this->obj = $this->getMockBuilder('IzapVideo')
//            ->setMethods(null)
//            ->getMock();
  }

  /**
   * test for saving  elgg_entity
   */
  public function testCorrectOnserverVideo() {

    $izapvideo_obj = new IzapVideo();
    $source_path = dirname(__FILE__) . '/test_video.avi';
    $dest_path = elgg_get_data_path();  //get data folder path    

    $izapvideo_obj->title = 'add new video';
    $izapvideo_obj->description = 'new video add here';
    $izapvideo_obj->owner_guid = 77;
    $process_video = $izapvideo_obj->processOnserverVideo($source_path, $dest_path);

    if ($izapvideo_obj->save()) {
      @unlink($process_video->unlink_tmp_video);
      @unlink($process_video->unlink_tmp_image);
      $this->assertEquals($process_video->thumbnail, 'yes');
      $this->assertEquals($process_video->is_flv, 'yes');
    }
//    if ($this->obj->save()) {
//      $saved_guid = $this->obj->getGUID();
//    }
//
//    unset($this->obj);
//    $saved_object = get_entity($saved_guid);
//    $this->assertEquals($saved_object->guid, $saved_guid);
  }

  /**
   * test for when video not processed
   */
  public function testErrorCasesInOServerVideo() {
    $izapvideo_obj = new IzapVideo();
    $source_path = dirname(__FILE__) . '/test.odt';
    $dest_path = elgg_get_data_path();
    $process_video = $izapvideo_obj->processOnServerVideo($source_path, $dest_path);

    $this->assertEquals($process_video->converted, 'no');
    $this->assertEquals($process_video->is_flv, 'no');
    $this->assertNotEmpty($process_video->message);
    $this->assertEquals($process_video->thumbnail, 'no');
  }

  /**
   * test required parameters for save entity
   */
  
  public function testRequiredParameters() {

    $source_path = dirname($path) . 'test_video.avi';
    $dest_path = elgg_get_data_path();
    $izapvideo_obj = new IzapVideo();
    $izapvideo_obj->title = '';
    $izapvideo_obj->description = '';
    $izapvideo_obj->video_url = '';

    if ($izapvideo_obj->title == '' || $izapvideo_obj->description == '' || $izapvideo_obj->video_url == '') {
       $this->assertEmpty($izapvideo_obj->title,'Title is empty');
       $this->assertEmpty($izapvideo_obj->owner_guid, 'Owner_guid is empty');
       $this->assertEmpty($izapvideo_obj->description, 'Description is empty');
    }
//    else{
//      $izapvideo_obj->owner_guid = 77;
//      $izapvideo_obj->processOnserverVideo($source_path, $dest_path);
//      $this->assertEquals($process_video->thumbnail, 'yes');
//      $this->assertEquals($process_video->is_flv, 'yes');
//    }
  }

}
