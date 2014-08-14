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

$Fail_functions = ini_get('disable_functions');
$php_version = phpversion();

$exec = function_exists('exec') ? TRUE : FALSE;
$curl = (extension_loaded('curl')) ? TRUE : FALSE;
$ffmpeg_path = current(explode(' ', izapAdminSettings_izap_videos('izapVideoCommand')));
$pdo_sqlite = (extension_loaded('pdo_sqlite')) ? TRUE : FALSE;

$php_command = exec(izapAdminSettings_izap_videos('izapPhpInterpreter') . ' --version', $output_PHP, $return_value);
if ($return_value === 0) {
  $php = nl2br(implode('', $output_PHP));
}

$ffmpeg_command = exec($ffmpeg_path . ' -version', $output_FFmpeg, $return_var);
if ($return_var === 0) {
  $ffmpeg = nl2br(implode($output_FFmpeg));

  if (!izapIsWin_izap_videos()) { // set the testing videos commands if it is not windows
    $in_video = elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/test/test_video.avi';
    $file_handler = new ElggFile();
    $file_handler->owner_guid = elgg_get_logged_in_user_guid();
    $file_handler->setFilename(GLOBAL_IZAP_VIDEOS_PLUGIN . '/test/test_video.avi');
    $file_handler->open('read');
    $file_handler->write(file_get_contents($in_video));

    $in_video = $file_handler->getFilenameOnFilestore();
    if (!file_exists($in_video)) {
      $in_video = elgg_get_plugins_path() . GLOBAL_IZAP_VIDEOS_PLUGIN . '/test/test_video.avi';
      $file_handler->open('write');
      $file_handler->write(file_get_contents($in_video));
      $in_video = $file_handler->getFilenameOnFilestore();
    }
    $file_handler->close();

    if (file_exists($in_video)) {
      $in_video;
      $outputPath = substr($in_video, 0, -4);
      $out_video = $outputPath . '_c.flv';
      $commands = array(
          'Simple command' => $ffmpeg_path . ' -y -i [inputVideoPath] [outputVideoPath]',
      );
    }
  }
}


$plugin = elgg_get_plugin_from_id('izap-videos');
$max_file_upload = izapReadableSize_izap_videos(ini_get('upload_max_filesize'));
$max_post_size = izapReadableSize_izap_videos(ini_get('post_max_size'));
$max_input_time = ini_get('max_input_time');
$max_execution_time = ini_get('max_execution_time');
$memory_limit = ini_get('memory_limit');
$plugin = elgg_get_plugin_from_id('izap-videos');
?>

<div class="elgg-module elgg-module-inline izap_server_report">
  <div class="elgg-head">
    <h3><?php echo $plugin->getmanifest()->getName(); ?> version: <?php echo $plugin->getmanifest()->getVersion(); ?></h3>
  </div>

  <table>
    <tr class="odd <?php echo ($exec) ? 'ok' : 'not_ok'; ?>">
      <td class="column_one">exec()</td>
      <td><?php echo ($exec) ? 'Success' : 'Fail'; ?></td>
      <td>Required to execute the commands.</td>
    </tr>

    <tr class="odd <?php echo ($curl) ? 'ok' : 'not_ok'; ?>">
      <td class="column_one">cURL support</td>
      <td><?php echo ($curl) ? 'Success' : 'Fail'; ?></td>
      <td>Required for fetching the remote feed.</td>
    </tr>

    <tr class="odd <?php echo ($pdo_sqlite) ? 'ok' : 'not_ok'; ?>">
      <td class="column_one">PDO_SQLITE Support</td>
      <td><?php echo ($pdo_sqlite) ? 'Success' : 'Fail'; ?></td>
      <td>Required to manage que by sqlite database.</td>
    </tr>

    <tr class="odd <?php echo ($ffmpeg) ? 'ok' : 'not_ok'; ?>">
      <td class="column_one">FFmpeg</td>
      <td class="column_one"><?php echo ($ffmpeg) ? 'Success' : 'Fail'; ?></td>
      <td><?php echo $ffmpeg; ?><br />
      </td>
    </tr>

    <tr class="odd <?php echo ($php) ? 'ok' : 'not_ok' ?>">
      <td class="column_one">PHP interpreter test</td>
      <td><?php echo ($php) ? 'Success' : 'Fail'; ?></td>
      <td>
        <?php if (!$php) { ?>
          PHP interpreter not found. <br />
          <em><b>Action</b>: Be sure the provided path is correct in admin settings.</em>
          <?php
        } else {
          echo $php;
        }
        ?>
      </td>
    </tr>

    <tr class="odd ok">
      <td class="column_one">upload_max_filesize</td>
      <td class="column_one"><?php echo $max_file_upload; ?></td>
      <td>The maximum size of files that PHP will accept uploads. Keep it bigger for big files.</td>
    </tr>

    <tr class="odd ok">
      <td class="column_one">post_max_size</td>
      <td class="column_one"><?php echo $max_post_size; ?></td>
      <td>Needs to be a small amount bigger or same, than upload_max_filesize for a file upload to work. Keep it bigger for big files.</td>
    </tr>

    <tr class="odd ok">
      <td class="column_one">max_input_time</td>
      <td class="column_one"><?php echo $max_input_time; ?></td>
      <td>Determines how much time PHP will wait to receive file data. Keep it "0" for bigger file.</td>
    </tr>

    <tr class="odd ok">
      <td class="column_one">max_execution_time</td>
      <td class="column_one"><?php echo $max_execution_time; ?></td>
      <td>This sets the maximum time in seconds a script is allowed to run before it is terminated. Keep it "0" for bigger files.</td>
    </tr>

    <tr class="odd ok">
      <td class="column_one">memory_limit</td>
      <td class="column_one"><?php echo $memory_limit; ?></td>
      <td>This is php main memory limit and it needs to be bigger enough for your bigger file need to process via ffmpeg.</td>
    </tr>
    </tbody>
  </table>
</div>