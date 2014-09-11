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
elgg_load_library('elgg:izap_video');
?>

<div id="load_data">
    <?php
  //  echo getQueue();
    ?>
</div>
<div id="videoQueue" align="center">
  <img src="<?php echo elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/_queue_1.gif'; ?>" />
</div>
<div id="trigger_queue">
    <?php
    //$obj = new izapQueue(); $obj->delete();
   //getQueue(); 
//    $queue = izap_run_queue_izap_videos();
//    foreach ($queue as $pending) { 
//        $converted = izapConvertVideo_izap_videos($pending['main_file'], $pending['guid'], $pending['title'], $pending['url'], $pending['owner_id']);
//        //echo $converted;
//    }
    ?>
</div>

<script type="text/javascript">
  function checkQueue(){ 
    $('#videoQueue').load('<?php echo elgg_get_site_url() .GLOBAL_IZAP_VIDEOS_PLUGIN . '/queue'?>');
  }
  $(document).ready(function(){
    checkQueue();
    setInterval(checkQueue, 1000);
  });
</script>

<!--<script>
    $(document).ready(function() {
        setInterval('window.location.reload()', 5000);
    });

</script>-->