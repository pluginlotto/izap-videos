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

  global $IZAPSETTINGS;
  $token = get_input('token');
?>
<div>
  <label><?php echo elgg_echo('izap-videos:upload video'); ?></label><br />
  <?php echo elgg_view('input/file', array('name' => 'videofile', 'id' => 'video_file')); ?>
  <label id="error"></label>
</div>
<div>
  <?php echo elgg_view('input/hidden', array('name' => 'token', 'value' => $vars['token'])); ?>
</div>
<?php
  // extendable view for other plugins
  echo elgg_view('izap_upload/form_extension');
?>
<?php echo elgg_view('input/submit', array('value' => 'upload', 'id' => 'submit_button')); ?>

<div id="progress_button" style="display: none;">
  <?php echo elgg_echo('izap-videos:do-not-refresh'); ?><br /><img src="<?php echo $IZAPSETTINGS->graphics ?>ajax-loader_black.gif" />
</div>

