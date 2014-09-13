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
//echo dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php'; exit;
  include dirname(dirname(dirname(__FILE__))) . '/engine/start.php';
  class IzapVideoTest extends PHPUnit_Framework_TestCase {

    protected $obj;

    protected function setUp() {
      // required by ElggEntity when setting the owner/container
      _elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));

      $this->obj = $this->getMockBuilder('IzapVideo')
        ->setMethods(null)
        ->getMock();
    }

    /**
     * test 
     */
    public function testThumbnailFromValidVideoFormat() {
      // $izapvideo_obj = new IzapVideo();
      $source_path = dirname(__FILE__) . '/test_video.avi';

      $this->obj->subtype = GLOBAL_IZAP_VIDEOS_SUBTYPE;
      $this->obj->title = 'add new video';
      $this->obj->description = 'new video add here';
      $this->obj->owner_guid = 77;
      $this->obj->access_id = 2;
      $this->obj->videotype = 'video/x-msvideo';

      $file = array('name' => 'test_video.avi', 'tmp_name' => $source_path, 'size' => '309042', 'error' => '0', 'type' => 'video/x-msvideo');
      $processed_data = $this->obj->processfile($file);

      $image_path = preg_replace('/\\.[^.\\s]{3,4}$/', '', $processed_data->videofile) . '_i.png';
      $this->assertFileExists($image_path);
      $this->assertEquals($this->obj->videotype, $processed_data->videotype);
      $this->assertNotEmpty($processed_data->orignal_thumb);
    }

    public function testVideoConverterCommand() {
      $converterCommand = izap_get_ffmpeg_videoConvertCommand_izap_videos();
      $actual_dbcommand = elgg_get_plugin_setting('izapVideoCommand', GLOBAL_IZAP_VIDEOS_PLUGIN);
      $this->assertEquals($converterCommand, $actual_dbcommand);
    }

    public function testThumbnailCommand() {
      $thumbnail_cmd = izap_get_ffmpeg_thumbnailCommand();
      $actual_dbcmd = elgg_get_plugin_setting('izapVideoThumb', GLOBAL_IZAP_VIDEOS_PLUGIN);
      $this->assertEquals($actual_dbcmd, $thumbnail_cmd);
    }

    public function testValidVideoTypeConversion() {
      $file = dirname(__FILE__) . '/test_video.avi';
      $izapconvert_obj = new izapConvert($file);
      $videofile = $izapconvert_obj->izap_video_convert();
      $this->assertFileExists(dirname(__FILE__) . '/test_video_c.flv');
    }

    public function testInvalidVideoFormat() {
      $file = dirname(__FILE__) . '/test.odt';
      $izapconvert_obj = new izapConvert($file);
      $videofile = $izapconvert_obj->izap_video_convert();
      $this->assertNotEmpty($videofile['error']);
    }

    public function testPhpInterpreterPath() {
      $phppath = izapGetPhpPath_izap_videos();
      $actual_path = elgg_get_plugin_setting('izapPhpInterpreter', GLOBAL_IZAP_VIDEOS_PLUGIN);
      $this->assertEquals($actual_path, $phppath);
    }

    public function testSaveEntity() {
      $izapvideo = new IzapVideo();
      $izapvideo->title = 'Add new video';
      $izapvideo->description = 'video';
      //    $izapvideo->owner_guid = 77;
      $izapvideo->access_id = 2;
      $izapvideo->subtype = GLOBAL_IZAP_VIDEOS_SUBTYPE;

      $md_name = 'test_metadata_name_' . rand();
      $md_value = 'test_metadata_value_' . rand();

      $izapvideo->$md_name = $md_value;
      $izapvideo->save();

      $options = array(
        'type' => 'object',
        'subtype' => $izapvideo->subtype,
        'metadata_names' => $md_name,
        'metadata_values' => $md_value
      );

      $entities = elgg_get_entities_from_metadata($options); 
      $this->assertEquals(count($entities), 1);

      foreach ($entities as $entity) { 
        $this->assertEquals($entity->getGUID(), $izapvideo->getGUID());
        $this->assertEquals($entity->$md_name, $md_value);
      }

       $izapvideo->delete();
    }

    public function testQueue() {
      $izapvideo = new IzapVideo();
      $izapvideo->title = 'Add new video';
      $izapvideo->owner_guid = 77;
      $izapvideo->guid = '';
      $izapvideo->save();
      // $guid = $izapvideo->getGUID();

      $izapqueue = new izapQueue();
      $file = dirname(__FILE__) . '/test_video.avi';
      $izapqueue->put($izapvideo, $file, 2);

      $count = $izapqueue->get();
      print_r($count);
      exit;
      //   $this->assertNotEmpty($izapqueue->get($guid));
    }

  }
  