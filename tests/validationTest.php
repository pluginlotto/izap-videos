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
 * Unit test for server side validation
 */	


class validationTest extends PHPUnit_Framework_TestCase {

	protected $obj;

	public function setUp() {
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage));
		$this->obj = $this->getMockForAbstractClass('izapVideo');
		$reflection = new ReflectionClass('izapVideo');
		$method = $reflection->getMethod('initializeAttributes');
		if (method_exists($method, 'setAccessible')) {
			$method->setAccessible(True);
			$method->invokeArgs($this->obj, array());
		}
	}

	/**
	 * @expectedException IzapVideoException
	 * 
	 * @expectedExceptionMessage Please enter the title
	 */
	public function testTile() {
		$this->obj->checkTitle($title = '');
	}

	/**
	 * @expectedException IzapVideoException
	 * 
	 * @expectedExceptionMessage Please enter the video url
	 */
	public function testUrl() {
		$this->obj->checkUrl($url = '');
	}

	/**
	 * @expectedException IzapVideoException
	 * 
	 * @expectedExceptionMessage Please select the video to upload
	 */
	public function testFile() {
		$source_path = '';
		$file = array(
			'name' => 'test_video.avi',
			'tmp_name' => $source_path,
			'size' => '0',
			'error' => '0',
			'type' => 'video/x-msvideo'
		);
		$this->obj->checkFile($file);
	}

}
