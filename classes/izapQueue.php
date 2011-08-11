<?php

/**
 * iZAP izap_videos
 *
 * @package Elgg videotizer, by iZAP Web Solutions.
 * @license GNU Public License version 3
 * @Contact iZAP Team "<support@izap.in>"
 * @Founder Tarun Jangra "<tarun@izap.in>"
 * @link http://www.izap.in/
 *
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

  public function restore($guid) {
//    $queue_db = $this->db_connection;
    $item_to_restore = $this->get_from_trash($guid);
    $result = $this->execute("INSERT INTO video_queue (guid, main_file, title, url, access_id, owner_id, timestamp)
                                        VALUES('" . $item_to_restore[0]['guid'] . "',
                                               '" . $item_to_restore[0]['main_file'] . "',
                                               '" . $item_to_restore[0]['title'] . "',
                                               '" . $item_to_restore[0]['url'] . "',
                                               '" . $item_to_restore[0]['access_id'] . "',
                                               '" . $item_to_restore[0]['owner_id'] . "',
                                               strftime('%s','now'))");


    if ($result) {
      return $this->delete_from_trash($guid);
    }

    return $result;
  }

  public function get($guid = false) {
    if ($guid) {
      return $this->execute("SELECT * FROM video_queue WHERE guid = {$guid} ORDER BY timestamp");
    } else {
      return $this->execute("SELECT * FROM video_queue ORDER BY timestamp");
    }
  }

  public function get_from_trash($guid = false) {
    if ($guid) {
      return $this->execute("SELECT * FROM video_trash WHERE guid = {$guid}  ORDER BY timestamp");
    } else {
      return $this->execute("SELECT * FROM video_trash  ORDER BY timestamp");
    }
  }

  public function delete($guid = false, $also_media = false) {
    if ($also_media) {
      $this->delete_related_media($guid);
    }
    return $this->execute((($guid) ? "DELETE FROM video_queue WHERE guid = {$guid}" : 'DELETE FROM video_queue'));
  }

  public function delete_from_trash($guid = false, $also_media = false) {
    $queue_db = $this->db_connection;
    if ($also_media) {
      $this->delete_related_media($guid);
    }
    return $this->execute(($guid) ? "DELETE FROM video_trash WHERE guid = {$guid}" : 'DELETE FROM video_trash');
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

  public function count() {
    $select = $this->execute("SELECT count(*) AS count FROM video_queue");
    return (int) $select[0]['count'];
  }

  public function count_trash() {
    $select = $this->execute("SELECT count(*) AS count FROM video_trash");
    return (int) $select[0]['count'];
  }

  public function change_conversion_flag($guid) {
    $update_sql = "UPDATE video_queue SET conversion = " . IN_PROCESS . "
                            WHERE conversion = " . PENDING . "
                                  AND guid = {$guid}";
    return $this->execute($update_sql);
  }

  public function check_process() {
    $select = $this->execute("SELECT count(*) AS count FROM video_queue WHERE conversion = " . IN_PROCESS);
    return (int) $select[0]['count'];
  }

  /*
   * This function will delete all related media and kill process
   */

  public function delete_related_media($guid) {
    global $CONFIG;
    $item_of_queue = $this->execute("SELECT * FROM video_queue WHERE guid = {$guid}");
    $item_of_trash = $this->execute("SELECT * FROM video_trash WHERE guid = {$guid}");
    $queue_elements = $item_of_queue ? $item_of_queue : $item_of_trash;
    if (!$queue_elements) { // Neither trash nor queue has any element to delete.
      return true;
    }
//  
    $removeChar = -1 * (strlen(end(explode('.', $queue_elements['main_file']))) + 1);
    $tmpVideoFile = substr($queue_elements['main_file'], 0, $removeChar) . '_c.flv';
    //creating path to delete thumb from uploaded filder instead of tmp
    $tmpImageFile = preg_replace("/tmp/", 'izap_videos/uploaded', substr($queue_elements['main_file'], 0, $removeChar) . '_i.png');
    @unlink($queue_elements['main_file']);
    @unlink($tmpVideoFile);
    @unlink($tmpImageFile);
    return true;
  }

}