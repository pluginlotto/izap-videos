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


$elements = IzapBase::input('text', array(
        'input_title' => elgg_echo('izap_videos:addEditForm:videoUrl'),
        'internalname' => 'attributes[_videoUrl]',
        'value' => $vars['loaded_data']->videoUrl,
        'internalid' => 'video_url',
));
$elements .= elgg_view('input/hidden', array(
        'internalname' => 'attributes[videoType]',
        'value' => 'OFFSERVER',
));
echo $elements;
echo '<a href="#" id="view_supported_sites">' . elgg_echo('izap_videos:supported_videos') . '</a>';
?>
<br />
<span id="supported_sites_list" style="display: none;">
  <?php echo izap_get_supported_videos_list(); ?>
  <br />
</span>
<a href="#" id="view_extra_from"><b><?php echo elgg_echo('izap_videos:view_full_form')?></b></a>
<script type="text/javascript">
  $('#view_supported_sites').click(function() {
    $('#supported_sites_list').toggle();
    return false;
  });
</script>