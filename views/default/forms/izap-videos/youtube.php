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
?>

<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2011. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

echo elgg_view('forms/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/tabs', $vars);
global $IZAPSETTINGS;
$video = elgg_extract('entity', $vars);
$form_values = IzapBase::getFormValues(array('entity' => $video, 'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN));


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

//Edit form and onserver, title must be mandatory.
$form .= IzapBase::input('text', array(
            'input_title' => elgg_echo('izap_videos:addEditForm:title'),
            'name' => 'attributes[_title]',
            'value' => $form_values->title,
        ));

$form .= IzapBase::input('longtext', array(
            'input_title' => elgg_echo('izap_videos:addEditForm:description'),
            'name' => 'attributes[description]',
            'value' => $form_values->description,
        ));

$form .= IzapBase::input('tags', array(
            'input_title' => elgg_echo('izap_videos:addEditForm:tags'),
            'name' => 'attributes[tags]',
            'value' => $form_values->tags,
        ));


$form .= '</div>';


$form .= IzapBase::input('hidden', array(
            'name' => 'attributes[container_guid]',
            'value' => $video->container_guid,
                ), true);

$form .= IzapBase::input('hidden', array(
            'name' => 'attributes[plugin]',
            'value' => GLOBAL_IZAP_VIDEOS_PLUGIN,
                ), true);

$form .= IzapBase::input('hidden', array(
            'name' => 'attributes[guid]',
            'value' => $form_values->guid,
                ), true);

$form .= IzapBase::input((($video->converted == 'no' && $video->videotype == 'uploaded') ? 'hidden' : 'access'), array(
            'input_title' => elgg_echo('izap_videos:addEditForm:access_id'),
            'name' => 'attributes[access_id]',
            'value' => ACCESS_DEFAULT,
        ));

$form .= IzapBase::input('dropdown', array(
            'input_title' => elgg_echo('izap-elgg-bridge:comments'),
            'name' => 'attributes[comments_on]',
            'value' => $form_values->comments_on,
            'options_values' => array('1' => elgg_echo('izap_videos:on'), '0' => elgg_echo('izap_videos:off'))
        ));


$form .= elgg_view('input/izap_categories', array('name' => 'attributes[categories]', 'plugin_id' => GLOBAL_IZAP_VIDEOS_PLUGIN, 'value' => $video->categories));
$form .= elgg_view('input/categories', $vars);

$form .= elgg_view('input/submit', array(
    'value' => elgg_echo('izap_videos:addEditForm:save'),
    'id' => 'submit_button'
        ));
?>
<div class="contentWrapper">
  <?php
  echo elgg_view('input/form', array(
      'action' => $vars['action'],
      'id' => 'izap-video-form',
      'body' => $form,
      'enctype' => 'multipart/form-data'
  ));
  ?>
  <div id="progress_button" style="display: none;">
    <?php echo elgg_echo('izap-videos:do-not-refresh'); ?><br /><img src="<?php echo $IZAPSETTINGS->graphics ?>ajax-loader_black.gif" />
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#izap-video-form').submit(function() {
        $('#submit_button').hide();
        $('#progress_button').show();
      });
<?php if ($video->videoprocess == 'offserver' && $video->isNewRecord()) { ?>
      $('#view_supported_sites').click(function() {
        $('#supported_sites_list').toggle();
        return false;
      });
      $('#extra_form').hide();
      $('#view_extra_from').click(function(){
        $('#extra_form').toggle();
        return false;
      });
<?php } ?>
  });
  </script>
</div>