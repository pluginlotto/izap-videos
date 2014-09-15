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
      $tag = "offserver, video,";
      $data = array(
        'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
        'title' => 'Self-Organization: The Secret Sauce for Improving your Scrum team',
        'description' => 'Google Tech Talks September 4, 2008 ABSTRACT High performance depends on the self-organizing capability of teams. Understanding how this works and how to avoid destroying self-organization is a challenge.',
        'access_id' => '2',
        'container_guid' => 77,
        'tags' => string_to_tag_array($tags),
        'videourl' => 'https://www.youtube.com/watch?v=M1q6b9JI2Wc',
        'videoprocess' => 'offserver',
      );
      $izap_videos = new IzapVideo();
      $izap_videos->saveVideo($data);
      

//      include_once (dirname(dirname(dirname(__FILE__)))) . '/izap-videos/actions/izap-videos/offserver.php';
//      include_once (dirname(dirname(dirname(__FILE__)))) . '/izap-videos/lib/izap-videos.php';
      
    }

  }
  