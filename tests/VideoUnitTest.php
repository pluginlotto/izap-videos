<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of OffserverTest
   *
   * @author monika
   */

  class VideoUnitTest extends PHPUnit_Framework_TestCase {
//    protected $obj;
//
//    protected function setUp() {
//      // required by ElggEntity when setting the owner/container
//      _elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
//
//      $this->obj = $this->getMockBuilder('IzapVideo')
//        ->setMethods(null)
//        ->getMock();
//    }
    
    public function testOffserverTest() {
      $izap_videos = new IzapVideo();
      $izap_videos->subtype = GLOBAL_IZAP_VIDEOS_SUBTYPE;
      $izap_videos->videourl = 'https://www.youtube.com/watch?v=wEhu57pih5w';
      $izap_videos->owner_guid = 77;
      $izap_videos->access_id = 2;
      
//      include_once (dirname(dirname(dirname(__FILE__)))) . '/izap-videos/actions/izap-videos/offserver.php';
//      include_once (dirname(dirname(dirname(__FILE__)))) . '/izap-videos/lib/izap-videos.php';
      $video_value = input('https://www.youtube.com/watch?v=wEhu57pih5w');
    }

  }
  