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

    public function testOffserverTest() {
      $tag = "offserver, video,";
      $data = array(
        'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
        'title' => 'Self-Organization: The Secret Sauce for Improving your Scrum team',
        'description' => 'Google Tech Talks September 4, 2008 ABSTRACT High performance depends on the self-organizing capability of teams. Understanding how this works and how to avoid destroying self-organization is a challenge.',
        'access_id' => '2',
//        'container_guid' => 77,
        'owner_guid' => 77,
        'tags' => string_to_tag_array($tags),
        'videourl' => 'https://www.youtube.com/watch?v=M1q6b9JI2Wc',
        'videoprocess' => 'offserver',
      );
      $izap_videos = new IzapVideo();
      $video_data = array(
        'url' => $this->videourl,
        'title' => $this->title,
        'description' => $this->description,
      );
      $izap_videos->saveVideo($data);
    }

  }
  