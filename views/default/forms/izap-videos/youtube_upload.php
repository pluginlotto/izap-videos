<?php
global $IZAPSETTINGS;
$form .= IzapBase::input('file', array(
            'name' => 'attributes[videofile]',
            'input_title' => elgg_echo('izap_videos:addEditForm:videoFile'),
            'id' => 'video_file',
        ));
$form .= '<div id="extra_form">';

$form .= elgg_view('input/hidden', array(
    'name' => 'attributes[videoprocess]',
    'value' => $video->videoprocess,
        ));
$form .= IzapBase::input('hidden', array('name' => 'token', 'value' => $vars['token']));
$form .= IzapBase::input('submit', array(
        'value' => elgg_echo('Upload'),
        'id' => 'submit_button'));
echo elgg_view('input/form', array(
      'action' => $vars['action'],
      'id' => 'izap-video-form',
      'body' => $form,
      'enctype' => 'multipart/form-data'
  ));
?>
<div id="progress_button" style="display: none;">
    <?php echo elgg_echo('izap-videos:do-not-refresh');?><br /><img src="<?php echo $IZAPSETTINGS->graphics?>ajax-loader_black.gif" />
  </div>
 <script type="text/javascript">
    $(document).ready(function() {
      $('#izap-video-form').submit(function() {
        $('#submit_button').hide();
        $('#progress_button').show();
      });
    });
  </script>