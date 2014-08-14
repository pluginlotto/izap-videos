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

<p>
  <label>
    <?php echo elgg_echo('izap_videos:adminSettings:izapPhpInterpreter'); ?>
    <?php
    echo elgg_view('input/text', array(
        'name' => 'params[izapPhpInterpreter]',
        'value' => (izapIsWin_izap_videos()) ? '' : '/usr/bin/php'
    ));
    ?>
  </label>
</p>

<p>
<label>
  <?php echo elgg_echo('izap_videos:adminSettings:izapVideoCommand'); ?>
  <br />
  <?php
  echo elgg_view('input/text', array(
      'name' => 'params[izapVideoCommand]',
      'value' => (izapIsWin_izap_videos()) ?
              elgg_get_plugins_path () . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/bin/ffmpeg.exe' . ' -y -i [inputVideoPath] -vcodec libx264 -vpre ' . elgg_get_plugins_path () . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/presets/libx264-hq.ffpreset' . ' -b 300k -bt 300k -ar 22050 -ab 48k -s 400x400 [outputVideoPath]' :
              '/usr/bin/ffmpeg -y -i [inputVideoPath] [outputVideoPath]'
  ));
  ?>
</label>
</p>

<span>
  <?php //echo elgg_echo('izap_videos:adminSettings:info:convert-command'); ?>
</span>

<p>
  <label>
    <?php echo elgg_echo('izap_videos:adminSettings:izapVideoThumb'); ?>
    <br />
    <?php
    echo elgg_view('input/text', array(
        'name' => 'params[izapVideoThumb]',
        'value' =>
        (izapIsWin_izap_videos()) ?
                elgg_get_plugins_path () . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/bin/ffmpeg.exe' . ' -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]' :
                '/usr/bin/ffmpeg -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]'
    ));
    ?>
  </label>
</p>

<div id="onserver_video">
  <?php echo elgg_echo('Onserver Video'); ?>
  <?php
  echo elgg_view('input/radio', array(
      'name' => 'params[Onserver_enabled_izap_videos]',
      'options' => array('Yes' => 'yes', 'No' => 'no')
  ));
  ?>
</div>

<div id="youtube_integration">
  <?php echo elgg_echo('Youtube Integration'); ?>
  <?php
  echo elgg_view('input/radio', array(
      'name' => 'params[Youtube_enabled_izap_videos]',
      'options' => array('Yes' => 'yes', 'No' => 'no')
  ));
  ?>
</div>

<div>
  <?php echo elgg_echo('Offserver Video'); ?><br />
  <?php
  echo elgg_view('input/radio', array(
      'name' => 'params[Offserver_enabled_izap_videos]',
      'options' => array('Yes' => 'yes', 'No' => 'no')
  ));
  ?>
</div>

<script type='text/javascript'>
  $(document).ready(function() {
    $("input:radio[name='params[Onserver_enabled_izap_videos]']").on("click", function() {
      var onserver_value = $("input:radio[name='params[Onserver_enabled_izap_videos]']:checked").val();
      if (onserver_value == 'yes') {
        jQuery('#youtube_integration input:radio').prop("disabled", true);
      } else {
        jQuery('#youtube_integration input:radio').prop("disabled", false);
      }
    });

    $("input:radio[name='params[Youtube_enabled_izap_videos]']").on("click", function() {
      var youtube_value = $("input:radio[name='params[Youtube_enabled_izap_videos]']:checked").val();
      if (youtube_value == 'yes') {
        jQuery('#onserver_video input:radio').prop("disabled", true);
      } else {
        jQuery('#onserver_video input:radio').prop("disabled", false);
      }
    });
  });
</script>