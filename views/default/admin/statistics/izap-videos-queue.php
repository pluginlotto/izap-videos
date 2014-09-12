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
  //echo $CONFIG->wwwroot . 'action/' . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/' . 'trigger_queue';
?>

<div id="videoQueue" align="center">
  <img src="<?php echo elgg_get_site_url() . 'mod/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_graphics/queue.gif'; ?>" />
</div>
<p align="right">
  <?php
    echo '[ ';
    echo elgg_view('output/confirmlink', array('href' => getFormAction('trigger_queue', GLOBAL_IZAP_VIDEOS_PAGEHANDLER), 'text' => elgg_echo('izap-videos:re_trigger_queue')));
    echo ' | ';
    echo elgg_view('output/confirmlink', array('href' => getFormAction('reset_queue', GLOBAL_IZAP_VIDEOS_PAGEHANDLER), 'text' => elgg_echo('izap-videos:reset_queue'), 'confirm' => 'Are you sure? It will empty queue and correspoinding videos.'));
    echo ' ]';
  ?>
  <br /><em>Refresh after every 5 seconds.</em>
</p>

<script type="text/javascript">
    function checkQueue() {
      $('#videoQueue').load('<?php echo elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/queue' ?>');
    }
    $(document).ready(function() {
      checkQueue();
      setInterval(checkQueue, 1000);
    });
</script>