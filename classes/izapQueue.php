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

define('IN_PROCESS', 1);
define('PENDING', 2);

class izapQueue extends IzapSqlite {

  function __construct() {
    global $CONFIG;
    try {
      parent::__construct($CONFIG->dataroot . 'izap_queue.db');
      $this->setup();
    } catch (PDOException $e) {
      register_error(elgg_echo("izap_videos:error:sqliteDrivers"));
      izapAdminSettings_izap_videos('izapVideoOptions', array('OFFSERVER', 'EMBED'), TRUE);
      izapAdminSettings_izap_videos('izap_cron_time', 'none', TRUE);
    }
  }

  /*
   * Install initial setup in the database
   */

  public function setup() {
    try {
      $this->execute("CREATE TABLE video_queue(
                        guid INTEGER PRIMARY KEY ASC,
                        main_file VARCHAR(255),
                        title VARCHAR(255),
                        url VARCHAR(255),
                        access_id INTEGER,
                        owner_id INTEGER,
                        conversion INTEGER DEFAULT " . PENDING . ",
                        timestamp TIMESTAMP)");
      $this->execute("CREATE TABLE video_trash(
                        guid INTEGER PRIMARY KEY ASC,
                        main_file VARCHAR(255),
                        title VARCHAR(255),
                        url VARCHAR(255),
                        access_id INTEGER,
                        owner_id INTEGER,
                        timestamp TIMESTAMP)");
    } catch (PDOException $e) {
      echo $e->getMessage();
      echo $e->getCode();
    }
  }

  /*
   * Put items in the queue
   */

  public function put(izap_videos $video, $file, $defined_access_id) { 
    return $this->execute("INSERT INTO video_queue (guid, main_file, title, url, access_id, owner_id, timestamp)
      VALUES('" . $video->guid . "',
                                               '" . $file . "',
                                               '" . $video->title . "',
                                               '" . $video->getUrl() . "',
                                               '" . $defined_access_id . "',
                                               '" . $video->owner_guid . "',
                                               strftime('%s','now'))");
  }

  public function get($guid = false) {
    if ($guid) {
      return $this->execute("SELECT * FROM video_queue WHERE guid = {$guid} ORDER BY timestamp");
    } else {
      return $this->execute("SELECT * FROM video_queue ORDER BY timestamp");
    }
  }

  public function count() {
    $select = $this->execute("SELECT count(*) AS count FROM video_queue");
    return (int) $select[0]['count'];
  }

  public function check_process() {
    $select = $this->execute("SELECT count(*) AS count FROM video_queue WHERE conversion = " . IN_PROCESS);
    return (int) $select[0]['count'];
  }

}