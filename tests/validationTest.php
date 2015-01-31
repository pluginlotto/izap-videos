<?php

/**
 * Check server side validation work properly or not
 *
 * @author monika
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
