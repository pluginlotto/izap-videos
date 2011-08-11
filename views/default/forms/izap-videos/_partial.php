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

global $IZAPSETTINGS;
$video = elgg_extract('entity', $vars);
//c($video->categories);exit;
if($video->converted == 'no' && $video->videotype == 'uploaded') {
  $remove_access_id = TRUE;
}
$form_values = IzapBase::getFormValues(array('entity' => $video, 'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN));
if($vars['no_filters'] !== TRUE) {
  if(izap_is_onserver_enabled_izap_videos()) {
    $tabs['onserver'] = array(
            'title' => elgg_echo('izap-videos:onserver'),
            'url' => IzapBase::setHref(array(
            'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
            'action' => 'add',
            'vars' => array('onserver')
            )),
            'selected' => ($vars['selected_option'] == 'onserver'),
    );
  }elseif($vars['selected_option'] == 'onserver') {
    unset ($vars['selected_option']);
  }

  if(izap_is_offserver_enabled_izap_videos()) {
    $tabs['offserver'] = array(
            'title' => elgg_echo('izap-videos:offserver'),
            'url' => IzapBase::setHref(array(
            'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
            'action' => 'add',
            'vars' => array('offserver')
            )),
            'selected' => ($vars['selected_option'] == 'offserver'),
    );
  }elseif($vars['selected_option'] == 'offserver') {
    unset ($vars['selected_option']);

  }
  echo elgg_view('navigation/tabs', array('tabs' => $tabs));
  $form  = elgg_view('forms/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/' . $vars['selected_option']);
}
$form .= '<div id="extra_form">';

$form .= IzapBase::input('file', array(
        'input_title' => elgg_echo('izap_videos:addEditForm:videoImage'),
        'internalname' => 'attributes[videoImage]',
));

$form .= IzapBase::input('text', array(
        'input_title' => elgg_echo('izap_videos:addEditForm:title'),
        'internalname' => 'attributes[title]',
        'value' => $form_values->title,
));

$form .= IzapBase::input('longtext', array(
        'input_title' => elgg_echo('izap_videos:addEditForm:description'),
        'internalname' => 'attributes[description]',
        'value' => $form_values->description,
));

$form .= IzapBase::input('tags', array(
        'input_title' => elgg_echo('izap_videos:addEditForm:tags'),
        'internalname' => 'attributes[tags]',
        'value' => $form_values->tags,
));

$form .= '<label>'.elgg_echo('comments').'</label> ';

$form .= elgg_view('input/dropdown',array(
    'internalname' => 'attributes[comments_on]',
    'value' => $form_values->comments_on,
    'options_values' => array('1' => elgg_echo('izap_videos:on'), '0' => elgg_echo('izap_videos:off'))
));

$form .= '</div>';

$container_guid = elgg_extract('container_guid', $vars, FALSE);
if($container_guid) {
  $form .= elgg_view('input/hidden', array(
          'internalname' => 'attributes[container_guid]',
          'value' => $container_guid,
  ));
}

$form .= elgg_view('input/hidden', array(
        'internalname' => 'attributes[plugin]',
        'value' => GLOBAL_IZAP_VIDEOS_PLUGIN,
));

$form .= elgg_view('input/hidden', array(
        'internalname' => 'attributes[guid]',
        'value' => $form_values->guid,
));

$form .= IzapBase::input((($remove_access_id) ? 'hidden' : 'access'), array(
        'input_title' => elgg_echo('izap_videos:addEditForm:access_id'),
        'internalname' => 'attributes[access_id]',
        'value' => (($form_values->access_id) ? $form_values->access_id : ACCESS_DEFAULT),
));
$form .= elgg_view('input/izap_categories', array('internalname' => 'attributes[categories]','plugin_id' => GLOBAL_IZAP_VIDEOS_PLUGIN, 'value' => $video->categories));

$form .= elgg_view('input/submit', array(
        'value' => elgg_echo('izap_videos:addEditForm:save'),
));
?>
<div class="contentWrapper">
  <?php
  echo elgg_view('input/form', array(
  'action' => IzapBase::getFormAction('add_edit', GLOBAL_IZAP_VIDEOS_PLUGIN),
  'internalid' => 'izap_video_from',
  'body' => $form,
  'enctype' => 'multipart/form-data'
  ));
  ?>
  <div id="progress_button" style="display: none;">
    <?php echo elgg_echo('izap_videos:please_wait');?><img src="<?php echo $IZAPSETTINGS->graphics?>form_submit.gif" />
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#video_form').submit(function() {
        $('#submit_button').hide();
        $('#progress_button').show();
      });
<?php if($vars['selected_option'] == 'offserver' && !(int)$form_values->guid) {?>
    $('#extra_form').hide();
    $('#view_extra_from').click(function(){
      $('#extra_form').toggle();
      return false;
    });
  <?php }?>
    });
  </script>
</div>