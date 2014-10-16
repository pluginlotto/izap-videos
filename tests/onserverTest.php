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
 *
 */
class onserverTest extends PHPUnit_Framework_TestCase {

	function testOnserver() {
		define('IZAP_VIDEO_UNIT_TEST', True);
		$source_path = elgg_get_data_path() . 'test_video.avi';
		$file = array(
			'name' => 'test_video.avi',
			'tmp_name' => $source_path,
			'size' => '309042',
			'error' => '0',
			'type' => 'video/x-msvideo'
		);

		/*
		 * Delete flv video and thumbnail is exists
		 */
		if (file_exists(elgg_get_data_path() . 'test_video_c.flv')) {
			unlink(elgg_get_data_path() . 'test_video_c.flv');
		}
		if (file_exists(elgg_get_data_path() . 'test_video_i.png')) {
			unlink(elgg_get_data_path() . 'test_video_i.png');
		}

		$izap_video = new IzapVideo();
		$tags = "offserver,video";
		$izap_video->subtype = GLOBAL_IZAP_VIDEOS_SUBTYPE;
		$izap_video->title = 'title : title';
		$izap_video->description = 'description : description';
		$izap_video->owner_guid = 77;
		$izap_video->container_guid = 77;
		$izap_video->access_id = 2;
		$izap_video->videoprocess = 'onserver';
		$izap_video->tags = string_to_tag_array($tags);
		$processed_data = $izap_video->processfile($file);
		$izap_video->videotype = $processed_data->videotype;
		if ($processed_data->videofile) {
			$izap_video->videofile = $processed_data->videofile;
		}
		if ($processed_data->orignal_thumb) {
			$izap_video->orignal_thumb = $processed_data->orignal_thumb;
		}

		/*
		 * Convert video
		 */
		require elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/izap_convert_video.php';
		if (file_exists(elgg_get_data_path() . 'test_video_c.flv') && filesize(elgg_get_data_path() . 'test_video_c.flv') > 0) {
			$izap_video->converted = 'yes';
		} else {
			$izap_video->converted = 'no';
		}

		/*
		 * Expected Result
		 */
		$output = new stdClass;
		$output->videoprocess = 'onserver';
		$output->videotype = 'video/x-msvideo';
		$output->videofile = elgg_get_data_path() . 'test_video.avi';
		$output->tags = array('offserver', 'video');
		$output->orignal_thumb = elgg_get_data_path() . 'test_video_i.png';
		$output->converted = 'yes';
		$output->type = 'object';
		$output->subtype = 'izap_video';
		$output->owner_guid = 77;
		$output->container_guid = 77;
		$output->access_id = 2;
		$output->enabled = 'yes';
		$output->title = 'title : title';
		$output->description = 'description : description';

		/*
		 * Compare with expected result
		 */
		$this->assertEquals($output->videoprocess, $izap_video->videoprocess);
		$this->assertEquals($output->tags, $izap_video->tags);
		$this->assertEquals($output->videotype, $izap_video->videotype);
		$this->assertEquals($output->orignal_thumb, $izap_video->orignal_thumb);
		$this->assertEquals($output->converted, $izap_video->converted);
		$this->assertEquals($output->type, $izap_video->type);
		$this->assertEquals($output->subtype, $izap_video->subtype);
		$this->assertEquals($output->owner_guid, $izap_video->owner_guid);
		$this->assertEquals($output->container_guid, $izap_video->container_guid);
		$this->assertEquals($output->enabled, $izap_video->enabled);
		$this->assertEquals($output->title, $izap_video->title);
		$this->assertEquals($output->description, $izap_video->description);
		$this->assertFileExists(elgg_get_data_path() . 'test_video_c.flv');
		$this->assertFileExists(elgg_get_data_path() . 'test_video_i.png');
	}

}
