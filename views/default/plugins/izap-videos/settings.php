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
		$default = (izap_is_win_izap_videos()) ? '' : '/usr/bin/php';
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
		$default = (izap_is_win_izap_videos()) ?
			elgg_get_plugins_path() . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/bin/ffmpeg.exe' . ' -y -i [inputVideoPath] -vcodec libx264 -vpre ' . elgg_get_plugins_path() . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/presets/libx264-hq.ffpreset' . ' -b 300k -bt 300k -ar 22050 -ab 48k -s 400x400 [outputVideoPath]' :
			exec("which ffmpeg") . ' -y -i [inputVideoPath] [outputVideoPath]';
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
		$default_setting = (izap_is_win_izap_videos()) ?
			elgg_get_plugins_path() . '' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/ffmpeg/bin/ffmpeg.exe' . ' -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]' :
			exec("which ffmpeg") . ' -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]';
		$thumbnail_cmd = elgg_get_plugin_setting('izapVideoThumb', 'izap-videos');
		echo elgg_view('input/text', array(
			'name' => 'params[izapVideoThumb]',
			'value' => $thumbnail_cmd ? $thumbnail_cmd : $default_setting
		));
		?>
  </label>
</p>

<!--Onserver and Youtube Settings Start Here-->
<div>
  <label><?php echo elgg_echo('izap_videos:adminSettings:onServerVideos'); ?></label>
	<?php
	echo elgg_view('input/radio', array(
		'name' => 'params[Onserver_enabled_izap_videos]',
		'id' => 'onserver',
		'value' => izap_plugin_setting(
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

<?php $onserver_setting = elgg_get_plugin_setting('Onserver_enabled_izap_videos', 'izap-videos'); ?>
<?php if ($onserver_setting == 'yes' || $onserver_setting == 'no') { ?>
	<div id="youtube_key_youtube" style="display: none;">
		<label>
			<?php echo elgg_echo('Youtube Developer Key'); ?></label>
		<?php
		$saved_data = elgg_get_plugin_setting('youtubeDeveloperKey', 'izap-videos');
		echo elgg_view('input/text', array(
			'name' => 'params[youtubeDeveloperKey]',
			'value' => $saved_data ? $saved_data : ""
		));
		?>
	</div>
<?php } elseif ($onserver_setting == 'youtube') { ?>
	<div id="youtube_key_youtube">
		<label>
			<?php echo elgg_echo('Youtube Developer Key'); ?></label>
		<?php
		$saved_data = elgg_get_plugin_setting('youtubeDeveloperKey', 'izap-videos');
		echo elgg_view('input/text', array(
			'name' => 'params[youtubeDeveloperKey]',
			'value' => $saved_data ? $saved_data : ""
		));
		?>
	</div>
<?php } else { ?>
	<div id="youtube_key" style="display: none;">
		<label>
			<?php echo elgg_echo('Offserver API Key'); ?></label>
		<?php
		$saved_data = elgg_get_plugin_setting('izap_api_key', 'izap-videos');
		echo elgg_view('input/text', array(
			'name' => 'params[izap_api_key]',
			'value' => $saved_data ? $saved_data : ""
		));
		?>
	</div>
<?php } ?>
<!--Onserver and Youtube Settings End Here-->

<!--Offserver Settings Start Here-->
<div>
  <label>
		<?php echo elgg_echo('Offserver Video'); ?></label><br />
	<?php
	$offserver_setting = elgg_get_plugin_setting('Offserver_enabled_izap_videos', 'izap-videos');
	if ($offserver_setting == 'yes') {
		?>
		<input type="radio" name="params[Offserver_enabled_izap_videos]" value="yes" id="offserver_enable" checked> Yes <br />
		<input type="radio" name="params[Offserver_enabled_izap_videos]" value= 'no' id="offserver_disable"> No 
	<?php } elseif ($offserver_setting == 'no') { ?>
		<input type="radio" name="params[Offserver_enabled_izap_videos]" value="yes" id="offserver_enable" > Yes <br />
		<input type="radio" name="params[Offserver_enabled_izap_videos]" value= 'no'  id="offserver_disable" checked> No 
	<?php } else { ?>
		<input type="radio" name="params[Offserver_enabled_izap_videos]" value="yes" id="offserver_enable" > Yes <br />
		<input type="radio" name="params[Offserver_enabled_izap_videos]" value= 'no' id="offserver_disable" checked> No 
	<?php } ?>
</div>
<?php if ($offserver_setting == 'yes') { ?>
	<div id="offserver_key_yes">
		<label>
			<?php echo elgg_echo('Offserver API Key'); ?></label>
		<?php
		$saved_data = elgg_get_plugin_setting('izap_api_key', 'izap-videos');
		echo elgg_view('input/text', array(
			'name' => 'params[izap_api_key]',
			'value' => $saved_data ? $saved_data : ""
		));
		?>
	</div>
<?php } elseif ($offserver_setting == 'no') { ?>
	<div id="offserver_key_no" style="display: none;">
		<label>
			<?php echo elgg_echo('Offserver API Key'); ?></label>
		<?php
		$saved_data = elgg_get_plugin_setting('izap_api_key', 'izap-videos');
		echo elgg_view('input/text', array(
			'name' => 'params[izap_api_key]',
			'value' => $saved_data ? $saved_data : ""
		));
		?>
	</div>
<?php } else { ?>
	<div id="offserver_key">
		<label>
			<?php echo elgg_echo('Offserver API Key'); ?></label>
		<?php
		$saved_data = elgg_get_plugin_setting('izap_api_key', 'izap-videos');
		echo elgg_view('input/text', array(
			'name' => 'params[izap_api_key]',
			'value' => $saved_data ? $saved_data : ""
		));
		?>
	</div>
<?php } ?>

<!--Enble/Disable Add new video's icon-->
<div>
  <label>
		<?php echo elgg_echo('Show Add Video Link'); ?></label><br />
	<?php
	$icon_setting = elgg_get_plugin_setting('izap_add_new_video_icon', 'izap-videos');
	if ($icon_setting == 'on') {
		?>
		<input type="radio" name="params[izap_add_new_video_icon]" value="on" id="offserver_enable" checked> On <br />
		<input type="radio" name="params[izap_add_new_video_icon]" value= 'off' id="offserver_disable"> Off 
	<?php } elseif ($icon_setting == 'off') { ?>
		<input type="radio" name="params[izap_add_new_video_icon]" value="on" id="offserver_enable" > On <br />
		<input type="radio" name="params[izap_add_new_video_icon]" value= 'pff'  id="offserver_disable" checked> Off 
	<?php } else { ?>
		<input type="radio" name="params[izap_add_new_video_icon]" value="on" id="offserver_enable" checked> On <br />
		<input type="radio" name="params[izap_add_new_video_icon]" value= 'off' id="offserver_disable"> Off 
	<?php } ?>
</div>
<?php elgg_load_js('elgg:video_settings_js'); ?>
