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

/**
 * Unit test for offserver video saving process
 */
class offserverTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		// required by ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
	}

	public function testOffserverTest() {
		$tags = "offserver,video";
		$data = array(
			'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
			'title' => 'Barack Obama and Narendra Modi - Joint Briefing from White House',
			'description' => 'Prime Minister Narendra Modi on Tuesday (September 30) arrived at the White House to hold talks with US President Barack Obama. The two are expected to issue a joint statement at the end of the meeting. Both leaders will be discussing issues ranging from manufacturing to sanitation to deepen ties.',
			'access_id' => 2,
			'container_guid' => 77,
			'owner_guid' => 77,
			'videourl' => 'https://www.youtube.com/watch?v=uDYarhCmvfM',
			'videoprocess' => 'offserver',
			'tags' => string_to_tag_array($tags)
		);
		$izap_videos = new IzapVideo();
		$izap_videos->saveVideo($data);
		
		/*
		 * Expected Result
		 */
		$output = new stdClass;
		$output->videourl = 'https://www.youtube.com/watch?v=uDYarhCmvfM';
		$output->videoprocess = 'offserver';
		$output->tags = array('offserver', 'video');
		$output->videothumbnail = 'http://i.ytimg.com/vi/uDYarhCmvfM/1.jpg';
		$output->videosrc = '<iframe width="800" height="500" src="http://www.youtube.com/embed/uDYarhCmvfM?autoplay=1&amp;wmode=transparent" frameborder="0"></iframe>';
		$output->domain = 'https://www.youtube.com/watch?v=uDYarhCmvfM';
		$output->video_type = 'youtube';
		$output->orignal_thumb = 'izap_videos/tmp/original_';
		$output->imagesrc = 'izap_videos/tmp/';
		$output->videotype_site = 'https://www.youtube.com/watch?v=uDYarhCmvfM';
		$output->converted = 'yes';
		$output->filename = 'izap_videos/tmp/original_';

		$output->type = 'object';
		$output->subtype = 'izap_video';
		$output->owner_guid = 77;
		$output->container_guid = 77;
		$output->access_id = 2;
		$output->enabled = 'yes';
		$output->title = 'Barack Obama and Narendra Modi - Joint Briefing from White House';
		$output->description = 'Prime Minister Narendra Modi on Tuesday (September 30) arrived at the White House to hold talks with US President Barack Obama. The two are expected to issue a joint statement at the end of the meeting. Both leaders will be discussing issues ranging from manufacturing to sanitation to deepen ties.';
		
		$this->assertEquals($output->videourl, $izap_videos->videourl);
		$this->assertEquals($output->videoprocess, $izap_videos->videoprocess);
		$this->assertEquals($output->tags, $izap_videos->tags);
		$this->assertEquals($output->videothumbnail, $izap_videos->videothumbnail);
		$this->assertEquals($output->videosrc, $izap_videos->videosrc);
		$this->assertEquals($output->domain, $izap_videos->domain);
		$this->assertEquals($output->video_type, $izap_videos->video_type);
		$this->assertEquals($output->orignal_thumb, $izap_videos->orignal_thumb);
		$this->assertEquals($output->imagesrc, $izap_videos->imagesrc);
		$this->assertEquals($output->videotype_site, $izap_videos->videotype_site);
		$this->assertEquals($output->converted, $izap_videos->converted);
		$this->assertEquals($output->filename, $izap_videos->filename);
		$this->assertEquals($output->type, $izap_videos->type);
		$this->assertEquals($output->subtype, $izap_videos->subtype);
		$this->assertEquals($output->owner_guid, $izap_videos->owner_guid);
		$this->assertEquals($output->container_guid, $izap_videos->container_guid);
		$this->assertEquals($output->enabled, $izap_videos->enabled);
		$this->assertEquals($output->title, $izap_videos->title);
		$this->assertEquals($output->description, $izap_videos->description);
		
	}

}
