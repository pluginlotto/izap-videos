<div>
    <label><?php echo elgg_echo('izap-videos:upload video'); ?></label><br />
    <?php echo elgg_view('input/file', array('name' => 'upload_video')); ?>
    <label id="error"></label>
</div>
<?php c($var);exit; ?>