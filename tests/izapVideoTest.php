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

	protected $obj;
	protected $owner_object;

	protected function setUp() {
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
		$this->obj = $this->getMockForAbstractClass('IzapVideo');
		$reflection = new ReflectionClass('IzapVideo');
		$method = $reflection->getMethod('initializeAttributes');
		if (method_exists($method, 'setAccessible')) {
			$method->setAccessible(true);
			$method->invokeArgs($this->obj, array());
		}

		$this->owner_object = $this->getMockForAbstractClass('ElggUser');
		$ownerRefelection = new ReflectionClass('ElggUser');
		$ownerMethod = $ownerRefelection->getMethod('initializeAttributes');
		if (method_exists($ownerMethod, 'setAccessible')) {
			$ownerMethod->setAccessible(true);
			$ownerMethod->invokeArgs($this->owner_object, array());
		}
	}

	public function testDefaultAttributes() {
		$this->assertEquals(null, $this->obj->guid);
		$this->assertEquals('object', $this->obj->type);
		$this->assertEquals('izap_video', $this->obj->subtype);
		$this->assertEquals(elgg_get_logged_in_user_guid(), $this->obj->owner_guid);
		$this->assertEquals(elgg_get_logged_in_user_guid(), $this->obj->container_guid);
		$this->assertEquals(null, $this->obj->site_guid);
		$this->assertEquals(ACCESS_PRIVATE, $this->obj->access_id);
		$this->assertEquals(null, $this->obj->time_created);
		$this->assertEquals(null, $this->obj->time_updated);
		$this->assertEquals(null, $this->obj->last_action);
		$this->assertEquals('yes', $this->obj->enabled);
		$this->assertEquals(null, $this->obj->videourl);
	}

	public function testSettingAndGettingAttribute() {
		$this->obj->subtype = 'izap_videos';
		$this->assertEquals('izap_videos', $this->obj->subtype);
	}

	public function testSettingIntegerAttributes() {
		foreach (array('access_id', 'owner_guid', 'container_guid') as $name) {
			$this->obj->$name = '7';
			$this->assertSame(7, $this->obj->$name);
		}
	}

	public function testSettingUnsettableAttributes() {
		foreach (array('guid', 'time_updated', 'last_action') as $name) {
			$this->obj->$name = 'bar';
			$this->assertNotEquals('bar', $this->obj->$name);
		}
	}

	public function testSimpleGetters() {
		global $IZAPSETTINGS;
		$IZAPSETTINGS->api_server = 'http://api.pluginlotto.com';
		$IZAPSETTINGS->apiUrl = $IZAPSETTINGS->api_server . '?api_key=3a97ba34ce2e15257a9d935e527e290b' . '&domain=' . base64_encode(strtolower('monika.mmela.z'));

		$data = array(
			'title' => 'Barack Obama and Narendra Modi - Joint Briefing from White House',
			'description' => 'Prime Minister Narendra Modi on Tuesday (September 30) arrived at the White House to hold talks with US President Barack Obama. The two are expected to issue a joint statement at the end of the meeting. Both leaders will be discussing issues ranging from manufacturing to sanitation to deepen ties.',
			'access_id' => 2,
			'videourl' => 'https://www.youtube.com/watch?v=uDYarhCmvfM',
			'videoprocess' => 'offserver',
			'owner_guid' => 7,
			'access_id' => 2,
			'time_created' => 233342234
		);

		foreach ($data as $attribute => $attribute_value) {
			$this->obj->$attribute = $attribute_value;
		}

		$this->owner_object->username = 'izap';

		$this->assertEquals($this->obj->getGUID(), $this->obj->guid);
		$this->assertEquals($this->obj->getType(), $this->obj->type);
		$this->assertEquals($this->obj->getSubtype(), $this->obj->subtype);
		$this->assertEquals($this->obj->getOwnerGUID(), $this->obj->owner_guid);
		$this->assertEquals($this->obj->getAccessID(), $this->obj->access_id);
		$this->assertEquals($this->obj->getTimeCreated(), $this->obj->time_created);
		$this->assertEquals($this->obj->getTimeUpdated(), $this->obj->time_updated);
		$this->assertEquals('izap_videos/tmp/temporary_name', $this->obj->getTmpPath('temporary_name'));
		$this->assertEquals('http://localhost/izap_videos/play/izap//barack-obama-and-narendra-modi-joint-briefing-from-white-house', $this->obj->getURL($this->owner_object, 'izap_videos'));
	}

	public function testPluginlottoApiResponse() {
		global $IZAPSETTINGS;
		$IZAPSETTINGS->api_server = 'http://api.pluginlotto.com';
		$IZAPSETTINGS->apiUrl = $IZAPSETTINGS->api_server . '?api_key=3a97ba34ce2e15257a9d935e527e290b' . '&domain=' . base64_encode(strtolower('monika.mmela.z'));

		$data = array(
			'title' => 'Barack Obama and Narendra Modi - Joint Briefing from White House',
			'description' => 'Prime Minister Narendra Modi on Tuesday (September 30) arrived at the White House to hold talks with US President Barack Obama. The two are expected to issue a joint statement at the end of the meeting. Both leaders will be discussing issues ranging from manufacturing to sanitation to deepen ties.',
			'access_id' => 2,
			'videourl' => 'https://www.youtube.com/watch?v=uDYarhCmvfM',
			'videoprocess' => 'offserver',
			'access_id' => 2,
			'time_created' => 233342234
		);
		$result = $this->obj->saveVideo($data);
		
		/*
		 * Expected Output
		 */
		$output = new stdClass;
		$output->videourl = 'https://www.youtube.com/watch?v=uDYarhCmvfM';
		$output->videoprocess = 'offserver';
		$output->videothumbnail = 'http://i.ytimg.com/vi/uDYarhCmvfM/1.jpg';
		$output->videosrc = '<iframe width="800" height="500" src="http://www.youtube.com/embed/uDYarhCmvfM?autoplay=1&amp;wmode=transparent" frameborder="0"></iframe>';
		$output->video_type = 'youtube';
		$output->converted = 'yes';
		$output->type = 'object';
		$output->access_id = 2;
		$output->enabled = 'yes';
		$output->title = 'Barack Obama and Narendra Modi - Joint Briefing from White House';
		$output->description = 'Prime Minister Narendra Modi on Tuesday (September 30) arrived at the White House to hold talks with US President Barack Obama. The two are expected to issue a joint statement at the end of the meeting. Both leaders will be discussing issues ranging from manufacturing to sanitation to deepen ties.';

		$this->assertEquals($output->videourl, $result->videourl);
	  $this->assertEquals($output->videoprocess, $result->videoprocess);
	  $this->assertEquals($output->videothumbnail, $result->videothumbnail);
	  $this->assertEquals($output->videosrc, $result->videosrc);
	  $this->assertEquals($output->video_type, $result->video_type);
	  $this->assertEquals($output->converted, $result->converted);
	  $this->assertEquals($output->type, $result->type);
	  $this->assertEquals($output->owner_guid, $result->owner_guid);
	  $this->assertEquals($output->container_guid, $result->container_guid);
	  $this->assertEquals($output->enabled, $result->enabled);
	  $this->assertEquals($output->title, $result->title);
	  $this->assertEquals($output->description, $result->description);
	}
}
