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
$_ffmpeg = explode(' ', elgg_get_plugin_setting('izapVideoCommand', 'izap-videos'));
$ffmpeg_path = exec($_ffmpeg[0] . ' -version', $out, $err);
if ($err == 0) {
	$ffmpeg = $ffmpeg_path;
}

$pdo_sqlite = (extension_loaded('pdo_sqlite')) ? TRUE : FALSE;

$php_command = exec(izap_admin_settings_izap_videos('izapPhpInterpreter') . ' --version', $output_PHP, $return_value);
if ($return_value === 0) {
	$php = nl2br(implode('', $output_PHP));
}

$plugin = elgg_get_plugin_from_id('izap-videos');
$max_file_upload = izap_readable_size_izap_videos(ini_get('upload_max_filesize'));
$max_post_size = izap_readable_size_izap_videos(ini_get('post_max_size'));
$max_input_time = ini_get('max_input_time');
$max_execution_time = ini_get('max_execution_time');
$memory_limit = ini_get('memory_limit');
$plugin = elgg_get_plugin_from_id('izap-videos');
?>

<div class="elgg-module elgg-module-inline ">
  <div class="elgg-head">
    <h3><?php echo $plugin->getmanifest()->getName(); ?> version: <?php echo $plugin->getmanifest()->getVersion(); ?></h3>
  </div>

  <div class="elgg-body">
    <table class="elgg-table-alt">
      <tr class="odd">
        <td class="column_one"><b>exec() :</b></td>
        <td><?php echo ($exec) ? 'Success' : 'Fail'; ?></td>
        <td>Required to execute the commands.</td>
        <td><?php echo ($exec) ? '<input type= "button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979";color:white;>'; ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>cURL support :</b></td>
        <td><?php echo ($curl) ? 'Success' : 'Fail'; ?></td>
        <td>Required for fetching the remote feed.</td>
        <td><?php echo($curl) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>PDO :</b></td>
        <td><?php echo ($pdo_sqlite) ? 'Success' : 'Fail'; ?></td>
        <td>Required to manage que by sqlite database.</td>
        <td><?php echo ($pdo_sqlite) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>FFmpeg :</b></td>
        <td class="column_one"><?php echo ($ffmpeg) ? 'Success' : 'Fail'; ?></td>
        <td><?php echo $ffmpeg; ?><br />
        <td><?php echo($ffmpeg) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
        </td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>PHP interpreter test :</b></td>
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
        <td><?php echo ($php) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>upload_max_filesize :</b></td>
        <td class="column_one"><?php echo $max_file_upload; ?></td>
        <td>The maximum size of files that PHP will accept uploads. Keep it bigger for big files.</td>
        <td><?php echo($max_file_upload) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>post_max_size : </b></td>
        <td class="column_one"><?php echo $max_post_size; ?></td>
        <td>Needs to be a small amount bigger or same, than upload_max_filesize for a file upload to work. Keep it bigger for big files.</td>
        <td><?php echo($max_post_size) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">' ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>max_input_time :</b></td>
        <td class="column_one"><?php echo $max_input_time; ?></td>
        <td>Determines how much time PHP will wait to receive file data. Keep it "0" for bigger file.</td>
        <td><?php echo ($max_input_time) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>max_execution_time :</b></td>
        <td class="column_one"><?php echo $max_execution_time; ?></td>
        <td>This sets the maximum time in seconds a script is allowed to run before it is terminated. Keep it "0" for bigger files.</td>
        <td><?php echo($max_execution_time) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
      </tr>

      <tr class="odd">
        <td class="column_one"><b>memory_limit :</b></td>
        <td class="column_one"><?php echo $memory_limit; ?></td>
        <td>This is php main memory limit and it needs to be bigger enough for your bigger file need to process via ffmpeg.</td>
        <td><?php echo ($memory_limit) ? '<input type="button" value="Success" style="background-color:#97FD79">' : '<input type="button" value ="Fail" style="background-color:#FD7979;color:white;">'; ?></td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
