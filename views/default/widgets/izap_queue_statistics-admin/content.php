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

$queue = new izapQueue();

?>
<div class="elgg-module elgg-module-inline">
  <div class="elgg-body">
    <table class="elgg-table-alt">
      <tbody>

        <tr class="even">
          <td>
              <?php echo elgg_echo('admin:statistics:izap-videos-queue');?>
          </td>
          <td>
            <?php echo ($queue->check_process() > 0) ? elgg_echo('izap_videos:running') : elgg_echo('izap_videos:notRunning');?>
          </td>
        </tr>
        
        <tr class="odd">
          <td>
            <a href="<?php echo $vars['url']?>pg/admin/statistics/izap-videos-queue">
              <?php echo elgg_echo('izap-videos:total_videos_in_queue');?>
            </a>
          </td>
          <td>
            <?php echo $queue->count();?>
          </td>
        </tr>

        <tr class="even">
          <td>
            <a href="<?php echo $vars['url']?>pg/admin/statistics/izap-videos-trash">
              <?php echo elgg_echo('izap-videos:total_videos_in_trash');?>
            </a>
          </td>
          <td>
            <?php echo $queue->count_trash();?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>