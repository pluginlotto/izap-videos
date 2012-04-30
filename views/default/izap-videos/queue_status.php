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

$total_queued = $vars['total'];
$queueStatus = $vars['status'];
$queuedVideos = $vars['queue_videos'];
// load lib
IzapBase::loadLib(array(
        'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
        'lib' => 'izap_videos_lib'
));
?>
<div class="elgg-module elgg-module-inline">
  <div class="elgg-head">
    <h3><?php echo elgg_echo('izap_videos:queueStatus') . ': ' . $queueStatus . ' ('.$total_queued.')';?></h3>
  </div>
  <div class="elgg-body">
    <table class="elgg-table-alt">
      <tbody>
        <?php
        if($total_queued > 0):
          $i = 0;
          foreach($queuedVideos as $queuedVideo):
            $extension_length = strlen(IzapBase::getFileExtension($queuedVideo['main_file']));
            $outputPath = substr($queuedVideo['main_file'], 0, '-' . ($extension_length + 1));

            $ORIGNAL_name = basename($queuedVideo['main_file']);
            $ORIGNAL_size = izapFormatBytes(filesize($queuedVideo['main_file']));

            $FLV_name = basename($outputPath . '_c.flv');
            $FLV_size = izapFormatBytes(filesize($outputPath . '_c.flv'));
            ?>
        <tr class="odd <?php echo (!$i && izapIsQueueRunning_izap_videos()) ? 'queue_selected' : ''?>">
          <td>
                <?php echo $ORIGNAL_name . '<br />' . $FLV_name;?>
          </td>
          <td>
                <?php echo $ORIGNAL_size . '<br />' . $FLV_size;?>
          </td>
          <td>
                <?php
                if($queuedVideo['conversion'] != IN_PROCESS) {
                  echo elgg_view('output/confirmlink',array('href'=> IzapBase::getFormAction('reset_queue', GLOBAL_IZAP_VIDEOS_PLUGIN) . '?guid='.$queuedVideo['guid'],'text' => 'X','confirm'=>'Are you sure? It will delete this videos from queue and correspoindingly from db.'));
                }
                ?>
          </td>
        </tr>
            <?php
            $i++;
          endforeach;
        else:
          ?>
        <tr>
          <td>
              <?php echo elgg_echo('izap-videos:queue_empty');?>
          </td>
        </tr>
        <?php
        endif;
        ?>
      </tbody>
    </table>
  </div>
</div>