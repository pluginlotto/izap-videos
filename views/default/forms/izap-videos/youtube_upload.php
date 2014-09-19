<?php 
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
<?php echo elgg_view('input/submit', array('value' => 'upload', 'id' => 'submit_button')); ?>

<!--<div id="progress_button" style="display: none;">
    <?php //echo elgg_echo('izap-videos:do-not-refresh');?><br /><img src="<?php //echo $IZAPSETTINGS->graphics?>ajax-loader_black.gif" />
  </div>
 <script type="text/javascript">
    $(document).ready(function() {
      $('#izap-video-form').submit(function() {
        $('#submit_button').hide();
        $('#progress_button').show();
      });
    });
  </script>-->
    
    
    