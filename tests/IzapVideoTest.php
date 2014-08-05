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

  protected function setUp() {
    // required by ElggEntity when setting the owner/container
    _elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
  }

  public function testOnserverVideo() {       
    $test_array = array(
        'title' => '',
        'description' => '',
        'access_id' => '',
        'container_guid' => '',
        'tags' => '',
        'videourl' => '',
        'videotype' => '',
        'videoprocess' => '',
        'comments_on' => '',
        'categories' => ''
    );
    
    $izapvideo_obj = new IzapVideo();
    $izapvideo_obj->title = '';
    $izapvideo_obj->description = '';
    $izapvideo_obj->access_id = '';
    $izapvideo_obj->container_guid = '';
    $izapvideo_obj->tags = '';
    $izapvideo_obj->videourl = '';
    $izapvideo_obj->videotype = '';
    $izapvideo_obj->videoprocess = '';
    $izapvideo_obj->comments_on = '';
    $izapvideo_obj->categories = '';
  //  $izapvideo_obj->processOnserverVideo($file_path);
    if($izapvideo_obj->save()) {
      $saved_guid = $izapvideo_obj->getGUID();
    }
    unset ($izapvideo_obj);
    $saved_object = get_entity($saved_guid);
    print_r($saved_object); exit;
    $this->assertEquals($saved_object, $saved_guid);
//    $this->assertEquals($elgg_obj, $actual_data_array);
  }

}
