<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
include __DIR__ . '/../classes/IzapFile.php';
include __DIR__ . '/../classes/IzapObject.php';
include __DIR__ . '/../classes/IzapVideo.php';

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
