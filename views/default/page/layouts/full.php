<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
$header = elgg_view('page/layouts/content/header', $vars);
$video = elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/video', array(
        'video' => $vars['izap_video'], 'full_view' => TRUE, 'height' => 500, 'width' => 900));
$content = $vars['content'];
if($vars['izap_video']->comments_on)
$content .= elgg_view_comments($vars['izap_video'], true);
$sidebar = elgg_view('page/layouts/content/sidebar', $params);
$sidebar .= $vars['sidebar'];

echo elgg_view_layout('one_column',array('content' => $header.$video));
echo elgg_view_layout('one_sidebar', array(
    'content' => $content,'sidebar' =>$sidebar));
?>

<script type="text/javascript">
  $(document).ready(function(){
    $('#related_videos').load('<?php
echo IzapBase::setHref(array(
    'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
    'action' => 'load_related_videos',
    'page_owner' => FALSE,
    'vars' => array($vars['izap_video']->guid)
));
?>');
      });
</script>
