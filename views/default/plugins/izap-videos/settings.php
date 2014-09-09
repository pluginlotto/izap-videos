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
      $default = (izapIsWin_izap_videos()) ? '' : '/usr/bin/php';
      $saved_command = elgg_get_plugin_setting('izapPhpInterpreter', 'izap-videos');
      echo elgg_view('input/text', array(
        'name' => 'params[izapPhpInterpreter]',
        'value' => $saved_command ? $saved_command : $default
      ));
    ?>
  </label>
</p>

<p>
  <label>
    <?php echo elgg_echo('izap_videos:adminSettings:izapVideoCommand'); ?>
    <br />
    <?php
      $default = (izapIsWin_izap_videos()) ?
        elgg_get_plugins_path() . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/bin/ffmpeg.exe' . ' -y -i [inputVideoPath] -vcodec libx264 -vpre ' . elgg_get_plugins_path() . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/presets/libx264-hq.ffpreset' . ' -b 300k -bt 300k -ar 22050 -ab 48k -s 400x400 [outputVideoPath]' :
        '/usr/bin/ffmpeg -y -i [inputVideoPath] [outputVideoPath]';
      $saved_command = elgg_get_plugin_setting('izapVideoCommand', 'izap-videos');

      echo elgg_view('input/text', array(
        'name' => 'params[izapVideoCommand]',
        'value' => $saved_command ? $saved_command : $default
      ));
    ?>
  </label>
</p>

<p>
  <label>
    <?php echo elgg_echo('izap_videos:adminSettings:izapVideoThumb'); ?>
    <br />
    <?php
      $default_setting = (izapIsWin_izap_videos()) ?
        elgg_get_plugins_path() . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/bin/ffmpeg.exe' . ' -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]' :
        '/usr/bin/ffmpeg -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]';
      $thumbnail_cmd = elgg_get_plugin_setting('izapVideoThumb', 'izap-videos');
      echo elgg_view('input/text', array(
        'name' => 'params[izapVideoThumb]',
        'value' => $thumbnail_cmd ? $thumbnail_cmd : $default_setting
      ));
    ?>
  </label>
</p>

<div id="youtube-server">
  <label>
    <?php echo elgg_echo('Youtube Developer Key'); ?></label>
  <?php
    $saved_data = elgg_get_plugin_setting('Youtube_Developer_Key', 'izap-videos');
    echo elgg_view('input/text', array(
      'name' => 'params[Youtube_Developer_Key]',
      'value' => $saved_data ? $saved_data : ""
    ));
  ?>
</div>

<div>
  <label><?php echo elgg_echo('izap_videos:adminSettings:onServerVideos'); ?></label>
  <?php
    echo elgg_view('input/radio', array(
      'name' => 'params[Onserver_enabled_izap_videos]',
      'id' => 'onserver',
      'value' => pluginSetting(
        array(
          'name' => 'Onserver_enabled_izap_videos',
          'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
          'value' => 'no'
      )),
      'options' => array(
        elgg_echo('izap-videos:adminSettings:my-server') => 'yes',
        elgg_echo('izap-videos:adminSettings:youtube-server') => 'youtube',
        elgg_echo('izap-videos:adminSettings:disable') => 'no',
      ),
    ));
  ?>
</div>

<!--<div id="onserver_video">
<?php echo elgg_echo('Onserver Video'); ?><br />
<?php
  $ffmpeg_path = exec('ffmpeg -version', $out, $err);
  $onserver_settings = elgg_get_plugin_setting('Onserver_enabled_izap_videos', 'izap-videos');
  if ($onserver_settings == 'yes') {
    ?>
                  <input type="radio" name="params[Onserver_enabled_izap_videos]" value="yes" checked> Yes <br />
                  <input type="radio" name="params[Onserver_enabled_izap_videos]" value= 'no'> No 
  <?php } elseif ($onserver_settings == 'no') { ?>
                  <input type="radio" name="params[Onserver_enabled_izap_videos]" value="yes" > Yes <br />
                  <input type="radio" name="params[Onserver_enabled_izap_videos]" value= 'no' checked > No   
    <?php
  } else {
    if ($err == 0) {
      ?> 
                          <input type="radio" name="params[Onserver_enabled_izap_videos]" value="yes" checked> Yes <br />
    <?php } else { ?>
                          <input type="radio" name="params[Onserver_enabled_izap_videos]" value="yes"> Yes <br />
    <?php } ?>
                  <input type="radio" name="params[Onserver_enabled_izap_videos]" value= 'no'> No 
  <?php } ?>
</div>

<div id="youtube_integration">
<?php $youtube_Setting = elgg_get_plugin_setting('Youtube_enabled_izap_videos', 'izap-videos'); ?>
<?php echo elgg_echo('Youtube Integration'); ?><br />
<?php if ($youtube_Setting == 'yes') { ?>
                  <input type="radio" name="params[Youtube_enabled_izap_videos]" value="yes" checked> Yes <br />
                  <input type="radio" name="params[Youtube_enabled_izap_videos]" value= 'no'> No 
  <?php } elseif ($youtube_Setting == 'no') { ?>
                  <input type="radio" name="params[Youtube_enabled_izap_videos]" value="yes"> Yes <br />
                  <input type="radio" name="params[Youtube_enabled_izap_videos]" value= 'no' checked> No 
  <?php } else { ?>
                  <input type="radio" name="params[Youtube_enabled_izap_videos]" value="yes"> Yes <br />
                  <input type="radio" name="params[Youtube_enabled_izap_videos]" value= 'no'> No 
  <?php } ?>
<?php ?>
</div>-->

<div>
  <label>
    <?php echo elgg_echo('Offserver Video'); ?></label><br />
  <?php
    $offserver_setting = elgg_get_plugin_setting('Offserver_enabled_izap_videos', 'izap-videos');
    if ($offserver_setting == 'yes') {
      ?>
      <input type="radio" name="params[Offserver_enabled_izap_videos]" value="yes" checked> Yes <br />
      <input type="radio" name="params[Offserver_enabled_izap_videos]" value= 'no'> No 
    <?php } elseif ($offserver_setting == 'no') { ?>
      <input type="radio" name="params[Offserver_enabled_izap_videos]" value="yes" > Yes <br />
      <input type="radio" name="params[Offserver_enabled_izap_videos]" value= 'no' checked> No 
    <?php } else { ?>
      <input type="radio" name="params[Offserver_enabled_izap_videos]" value="yes" checked> Yes <br />
      <input type="radio" name="params[Offserver_enabled_izap_videos]" value= 'no' > No 
    <?php } ?>
</div>


<script type='text/javascript'>
  $(document).ready(function() {
<?php
  if ($get_server_value = (izap_is_onserver_enabled_izap_videos() == 'youtube')) {
    echo "$('#" . izap_is_onserver_enabled_izap_videos() . "-server').show()";
  } else {
    ?>
        $("#youtube-server").hide();
  <?php }
?>


    $("input:radio[name='params[Onserver_enabled_izap_videos]']").on("click", function() {
      var onserver_value = $("input:radio[name='params[Onserver_enabled_izap_videos]']:checked").val();
      if (onserver_value == 'yes') {
        //jQuery('#youtube_integration input:radio').prop("disabled", true);
        $("#youtube-server").hide();
      } else if (onserver_value == 'youtube') {
        // jQuery('#youtube_integration input:radio').prop("disabled", false);
        $("#youtube-server").show();
      } else if (onserver_value == 'no') {
        $("#youtube-server").hide();
      }
    });

//    $("input:radio[name='params[Youtube_enabled_izap_videos]']").on("click", function() {
//      var youtube_value = $("input:radio[name='params[Youtube_enabled_izap_videos]']:checked").val();
//      if (youtube_value == 'yes') {
//        $("#onserver_video").hide();
//        jQuery('#onserver_video input:radio').prop("disabled", true);
//        $("#youtube_developer_key").show();
//      } else {
//        $("#onserver_video").show();
//        $("#youtube_developer_key").hide();
//        jQuery('#onserver_video input:radio').prop("disabled", false);
//      }
//    });
  });
</script>

<style type='text/css'>
  .izap_admin_fieldset {
    border: 2px solid #DEDEDE;
    margin: 0 0 20px 0;
  }
</style>