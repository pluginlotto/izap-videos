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

  $total_fail = $vars['total'];
  $failStatus = $vars['status'];
  $failedVideos = $vars['queue_videos'];
  elgg_load_library('elgg:izap_video');
?>

<div class="elgg-module elgg-module-inline">
  <div class="elgg-head">
    <h3><?php echo elgg_echo('izap-videos:conversion_failed') . ': ' . $failStatus . ' (' . $total_fail . ')'; ?></h3>
  </div>
  <div class="elgg-body">
    <table class="elgg-table-alt">
      <tbody>
        <?php
          if ($total_fail > 0):
            $i = 0;
            $count = 1;
            foreach ($failedVideos as $failedVideo):
              $owner = get_entity($failedVideo->owner_guid);
              ?>
              <tr class="odd <?php echo (!$i && izap_is_queue_running_izap_videos()) ? 'queue_selected' : '' ?>">
                <td> <?php echo $count; ?></td>
                <td> <?php echo end(explode('/', $failedVideo->videofile)); ?></td>
                <td><?php echo 'Not Converted'; ?></td>
                <td><?php echo $owner->username; ?></td>
              </tr>
              <?php
              $i++;
              $count++;
            endforeach;
          else:
            ?>
            <tr>
              <td><?php echo elgg_echo('izap-videos:conversion_failed_no'); ?></td>
            </tr>
        <?php
          endif;
        ?>
      </tbody>
    </table>
  </div>
</div>