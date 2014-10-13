<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require dirname(dirname(dirname(__FILE__))) . "/izap-videos/classes/IzapVideo.php";

class offserverTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		// required by ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
	}

	public function testOffserverTest() {
		$tags = "offserver,video";
		$data = array(
			'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
			'title' => 'Apple Music Special Event 2005-The iPod Nano Introduction',
			'description' => 'Here we see Steve Jobs introducing the first ever iPod Nano.',
			'access_id' => '2',
			'container_guid' => 77,
			'owner_guid' => 77,
			'videourl' => 'https://www.youtube.com/watch?v=7GRv-kv5XEg',
			'videoprocess' => 'offserver',
			'tags' => string_to_tag_array($tags)
		);
		$izap_videos = new IzapVideo();
		$izap_videos->saveVideo($data);

		$output = new IzapVideo();
		$output->videourl = array('https://www.youtube.com/watch?v=7GRv-kv5XEg');
		$output->videoprocess = array('offserver');
		$output->tags = array('offserver', 'video');
		$output->videothumbnail = array('http://i.ytimg.com/vi/7GRv-kv5XEg/1.jpg');
		$output->videosrc = array('<iframe width="800" height="500" src="http://www.youtube.com/embed/7GRv-kv5XEg?autoplay=1&amp;wmode=transparent" frameborder="0"></iframe>');
		$output->domain = array('https://www.youtube.com/watch?v=7GRv-kv5XEg');
		$output->video_type = array('youtube');
		$output->orignal_thumb = array('izap_videos/tmp/original_');
		$output->imagesrc = array('izap_videos/tmp/');
		$output->videotype_site = array('https://www.youtube.com/watch?v=7GRv-kv5XEg');
		$output->converted = array('yes');
		$output->filename = array('izap_videos/tmp/original_');	
		
		$output->type = 'object';
		$output->subtype = 'izap_video';
		$output->owner_guid = '77';
		$output->container_guid = '77';
		$output->access_id = '2';
		$output->enabled = 'yes';
		$output->title = 'Apple Music Special Event 2005-The iPod Nano Introduction';
		$output->description = 'Here we see Steve Jobs introducing the first ever iPod Nano.';

		$this->assertEquals($output, $izap_videos);
	}

}
