<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of onserverTest
 *
 * @author monika
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
		$converted = izapConvertVideo_izap_videos($izap_video->videofile, '', $izap_video->title, '', $izap_video->owner_guid);
		if (file_exists(elgg_get_data_path() . $converted) && filesize(elgg_get_data_path() . $converted) > 0) {
			if (is_array($converted) && $converted['error']) {
				$izap_video->converted = 'no';
			} else {
				$izap_video->converted = 'yes';
			}
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
	}
}
