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
$queue = new izapQueue();
?>
<div class="elgg-module elgg-module-inline">
  <div class="elgg-body">
    <table class="elgg-table-alt">
      <tbody>
        <tr class="even">
          <td>
						<?php echo elgg_echo('admin:statistics:izap-videos-queue'); ?>
          </td>
          <td>
						<?php echo ($queue->check_process() > 0) ? elgg_echo('izap_videos:running') : elgg_echo('izap_videos:notRunning'); ?>
          </td>
        </tr>        
        <tr class="odd">
          <td>
            <a href="<?php echo $vars['url'] ?>pg/admin/statistics/izap-videos-queue">
							<?php echo elgg_echo('izap-videos:total_videos_in_queue'); ?>
            </a>
          </td>
          <td>
						<?php echo $queue->count(); ?>
          </td>
        </tr>
        <tr class="even">
          <td>
            <a href="<?php echo $vars['url'] ?>pg/admin/statistics/izap-videos-trash">
							<?php echo elgg_echo('izap-videos:total_videos_in_trash'); ?>
            </a>
          </td>
          <td>
						<?php echo $queue->count_trash(); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>