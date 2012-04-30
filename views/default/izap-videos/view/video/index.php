<?php
/**
 * iZAP izap_videos
 *
 * @package Elgg videotizer, by iZAP Web Solutions.
 * @license GNU Public License version 3
 * @Contact iZAP Team "<support@izap.in>"
 * @Founder Tarun Jangra "<tarun@izap.in>"
 * @link http://www.izap.in/
 *
 */

?>
<div>
  <?php
  echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/video', array_merge($vars, array('video' => $vars['entity'])));
   echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/description', array('video' => $vars['video'],'full_view'=> true));
  // view for other plugins to extend
  echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/extendedPlay');
  if($vars['video']->converted == 'yes' && $vars['video']->comments_on) {
     echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/comments', array('video' => $vars['video']));
  }
  ?>
</div>
<script type="text/javascript">
  function show_full_desc() {
    document.getElementById('small_desc').style.display="none";
    document.getElementById('full_desc').style.display="block";
    return false;
  }

  function hide_full_desc() {
    document.getElementById('small_desc').style.display="block";
    document.getElementById('full_desc').style.display="none";
    return false;
  }
  $(document).ready(function() {
    $('#related_videos').load('<?php echo IzapBase::setHref(array(
        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
            'action' => 'loadrelatedvideos',
            'page_owner' => FALSE,
            'vars' => array($vars['video']->guid)
    ));?>');
  });
</script>