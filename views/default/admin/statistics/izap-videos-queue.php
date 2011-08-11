<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

global $IZAPSETTINGS;
$action = get_input('action');
switch ($action) {
  case 'reset':
    izapResetQueue_izap_videos();
    forward($vars['url'] . 'pg/videos/adminSettings/' . get_loggedin_user()->username . '?option=queue_status');
    break;

  case 'delete':
    izapEmptyQueue_izap_videos();
    forward($vars['url'] . 'pg/videos/adminSettings/' . get_loggedin_user()->username . '?option=queue_status');
    break;

  default:
    break;
}

?>
<div id="videoQueue" align="center">
  <img src="<?php echo $IZAPSETTINGS->graphics . 'queue.gif'?>" />
</div>
<p align="right">
  <?php
  echo '[ ';
  echo elgg_view('output/confirmlink',array('href'=> IzapBase::getFormAction('trigger_queue', GLOBAL_IZAP_VIDEOS_PLUGIN),'text' => elgg_echo('izap-videos:re_trigger_queue')));
  echo ' | ';
  echo elgg_view('output/confirmlink',array('href'=> IzapBase::getFormAction('reset_queue', GLOBAL_IZAP_VIDEOS_PLUGIN),'text' => elgg_echo('izap-videos:reset_queue'),'confirm'=>'Are you sure? It will empty queue and correspoinding videos.'));
  echo ' ]';
  ?>
  <br /><em>Refresh after every 5 seconds.</em>
</p>
<script type="text/javascript">
  function checkQueue(){
    $('#videoQueue').load('<?php echo $vars['url'] . 'pg/videos/getQueue'?>');
  }
  $(document).ready(function(){
    checkQueue();
    setInterval(checkQueue, 5000);
  });
</script>