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
 * iZAP izap-video php unit test
 * class izap video unit test
 */
class IzapVideoUnitTest extends PHPUnit_Framework_TestCase {

  public function testForm() {
    $data_array = array('title' => '',
        'description' => '');
    $this->assertEquals($data_array, $posted_data = array('title' => '', 'description' => ''));
  }
  
  public function testConnetion(){
   
  }
}