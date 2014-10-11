<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require dirname(dirname(dirname(__FILE__))) . "/izap-videos/classes/IzapVideo.php";

class offserverTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		// required by ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
	}

	public function testOffserverTest() {
		$a=5;
		$b=10;
		assert($a<$b);
		$data = array(
			'subtype' => 'izap_video',
//			'title' => 'Self-Organization: The Secret Sauce for Improving your Scrum team',
//			'description' => 'Google Tech Talks September 4, 2008 ABSTRACT High performance depends on the self-organizing capability of teams. Understanding how this works and how to avoid destroying self-organization is a challenge.',
			'access_id' => '2',
			'container_guid' => 77,
			'owner_guid' => 77,
			'videourl' => 'https://www.youtube.com/watch?v=XeJSXfXep4M',
			'videoprocess' => 'offserver',
		);

		$izap_videos = new IzapVideo();
//		$izap_videos->title = 'titleeeeeeeeeeeeeeeeeeeeeee';
//		$izap_videos->save();
		if($izap_videos->saveVideo($data)){
			echo "+++++++++++++";
		}else{
			echo "-------------";
		}
//	echo "******************";exit;
	}

}
