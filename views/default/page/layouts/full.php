<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

?>
<div class="elgg-layout elgg-layout-one-sidebar clearfix" style ="border:1px solid #000">
	<div class="izap-video-body">
	<?php echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/video', array('video' => $vars['izap_video'], 'full_view' => TRUE, 'height' => 500, 'width' => 900));
  ?>
	</div>
  <div class="izap-video-related">
  <div class="izap-video-description">
  <?php echo $vars['content'];
  echo elgg_view_comments($vars['izap_video'], true);
  ?>
  </div>

  <div class="elgg-sidebar elgg-aside" id="elgg-layout-sidebar">

    <?php echo elgg_view('layout/elements/sidebar', $vars);?>
  </div>
</div>
  <div class="clearfloat"></div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#related_videos').load('<?php echo IzapBase::sethref(array(
        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
            'action' => 'load_related_videos',
            'page_owner' => FALSE,
            'vars' => array($vars['izap_video']->guid)
    ));
    ?>');
  });
</script>