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

$total_queued = $vars['total']; 
$queueStatus = $vars['status'];
$queuedVideos = $vars['queue_videos'];  
// load lib
elgg_load_library('elgg:izap_video');
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
            $extension_length = strlen(getFileExtension($queuedVideo['main_file'])); 
            $outputPath = substr($queuedVideo['main_file'], 0, '-' . ($extension_length + 1));
           
         
             $ORIGNAL_name = basename($queuedVideo['main_file']); 
            $ORIGNAL_size = izapFormatBytes(filesize($queuedVideo['main_file']));

            $FLV_name = basename($outputPath . '_c.flv'); 
            $FLV_size = izapFormatBytes(filesize($outputPath . '_c.flv'));
            ?>
        <tr class="odd <?php echo (!$i && izap_is_queue_running_izap_videos()) ? 'queue_selected' : ''?>">
          <td>
                <?php echo $ORIGNAL_name . '<br />' . $FLV_name;?>
          </td>
          <td>
                <?php echo $ORIGNAL_size . '<br />' . $FLV_size;?>
          </td>
          <td>
            <?php if($queuedVideo['conversion'] != IN_PROCESS) { 
              echo 'Not Converted';
            }else{
              echo 'Converted';
            } ?>
          </td>
          <td>
                <?php 
                if($queuedVideo['conversion'] != IN_PROCESS) {
                  echo elgg_view('output/confirmlink',array('href'=> getFormAction('reset_queue', GLOBAL_IZAP_VIDEOS_PLUGIN) . '?guid='.$queuedVideo['guid'],'text' => 'X','confirm'=>'Are you sure? It will delete this videos from queue and correspoindingly from db.'));
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