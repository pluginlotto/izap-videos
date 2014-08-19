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

  public function change_conversion_flag($guid) {
    $update_sql = "UPDATE video_queue SET conversion = " . IN_PROCESS . "
                            WHERE conversion = " . PENDING . "
                                  AND guid = {$guid}";
    return $this->execute($update_sql);
  }

  /*
   * Put queued video in trash
   */

  public function move_to_trash($guid) {
    $item_to_move = $this->get($guid);
    $result = $this->execute("INSERT INTO video_trash (guid, main_file, title, url, access_id, owner_id, timestamp)
                                        VALUES('" . $item_to_move[0]['guid'] . "',
                                               '" . $item_to_move[0]['main_file'] . "',
                                               '" . $item_to_move[0]['title'] . "',
                                               '" . $item_to_move[0]['url'] . "',
                                               '" . $item_to_move[0]['access_id'] . "',
                                               '" . $item_to_move[0]['owner_id'] . "',
                                               strftime('%s','now'))");
    if ($result) {
      return $this->delete($guid);
    }

    return $result;
  }
  /*
   * fetch all records from queue and change their flags to conversion in process.
   */

  public function fetch_videos($limit = 1) {
    $select = $this->execute("SELECT * FROM video_queue WHERE conversion = " . PENDING . " ORDER BY timestamp LIMIT 0, " . $limit . "");
    if (count($select)) {
      foreach ($select as $row) {
        $guid_array[] = $row['guid'];
      }
    }
    return $select;
  }
  
   public function delete($guid = false, $also_media = false) {
    if ($also_media) {
      $this->delete_related_media($guid);
    }
    return $this->execute((($guid) ? "DELETE FROM video_queue WHERE guid = {$guid}" : 'DELETE FROM video_queue'));
  }
}