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
IzapBase::loadLib(array(
        'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
        'lib' => 'izap_videos_lib'
));

echo elgg_view('output/confirmlink', array(
'href' => IzapBase::getFormAction('reset_plugin_settings', GLOBAL_IZAP_VIDEOS_PLUGIN),
'text' => elgg_echo('izap_videos:adminSettings:resetSettings'),
'class' => 'izapResetSettings',
)) . '<br />';
?>
<br />
<div id="izap_admin_settings_form">
  <fieldset  class ="izap-admin-group">
    <?php
    echo '<legend>' . elgg_echo('izap_videos:adminSettings:offServerVideos') . '</legend>';
    echo elgg_view('input/radio', array(
    'internalname' => 'params[offserver_enabled_izap_videos]',
    'value' => IzapBase::pluginSetting(array(
    'name' => 'offserver_enabled_izap_videos',
    'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    'value' => 'yes'
    )),
    'options' => array(
            elgg_echo('izap-bridge:enable') => 'yes',
            elgg_echo('izap-bridge:disable') => 'no',
    ),
    ));
    ?>
  </fieldset>
  <br />

  <fieldset  class ="izap-admin-group">
    <?php
    echo '<legend>' . elgg_echo('izap_videos:adminSettings:onServerVideos') . '</legend>';
    echo elgg_view('input/radio', array(
    'internalname' => 'params[onserver_enabled_izap_videos]',
    'value' => IzapBase::pluginSetting(array(
    'name' => 'onserver_enabled_izap_videos',
    'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    'value' => 'no'
    )),
    'options' => array(
            elgg_echo('izap-bridge:enable') => 'yes',
            elgg_echo('izap-bridge:disable') => 'no',
    ),
    ));
    ?>
  </fieldset>
  <br />

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izapPhpInterpreter');?>
      <br />
      <?php
      echo elgg_view('input/text', array(
      'internalname' => 'params[izapPhpInterpreter]',
      'value' => izapAdminSettings_izap_videos('izapPhpInterpreter', (izapIsWin_izap_videos()) ? '' : '/usr/bin/php'),
      ));
      ?>
    </label>
  </p>

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izapVideoCommand');?>
      <br />
      <?php
      echo elgg_view('input/text', array(
      'internalname' => 'params[izapVideoCommand]',
      'value' => izapAdminSettings_izap_videos(
      'izapVideoCommand',
      (izapIsWin_izap_videos()) ?
      $IZAPSETTINGS->ffmpegPath . ' -y -i [inputVideoPath] -vcodec libx264 -vpre '.$IZAPSETTINGS->ffmpegPresetPath.' -b 300k -bt 300k -ar 22050 -ab 48k -s 400x400 [outputVideoPath]'
      :
      '/usr/bin/ffmpeg -y -i [inputVideoPath] [outputVideoPath]'
      ),
      ));
      ?>
    </label>
    <br />
    <span class="izap_info_text">
      <?php echo elgg_echo('izap_videos:adminSettings:info:convert-command');?>
    </span>
  </p>

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izapVideoThumb');?>
      <br />
      <?php
      echo elgg_view('input/text', array(
      'internalname' => 'params[izapVideoThumb]',
      'value' => izapAdminSettings_izap_videos(
      'izapVideoThumb',
      (izapIsWin_izap_videos()) ?
      $IZAPSETTINGS->ffmpegPath . ' -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]'
      :
      '/usr/bin/ffmpeg -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]'
      ),
      ));
      ?>
    </label>
  </p>

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izapBarColor');?>
      <br />
      <?php
      echo elgg_view('input/text', array(
      'internalname' => 'params[izapBorderColor1]',
      'value' => izapAdminSettings_izap_videos('izapBorderColor1'),
      ));
      ?>
    </label>
    <br />
    <span class="izap_info_text">
      <?php echo elgg_echo('izap_videos:adminSettings:info:bg-color');?>
    </span>
  </p>

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izapTextColor');?>
      <br />
      <?php
      echo elgg_view('input/text', array(
      'internalname' => 'params[izapBorderColor2]',
      'value' => izapAdminSettings_izap_videos('izapBorderColor2'),
      ));
      ?>
    </label>
    <br />
    <span class="izap_info_text">
      <?php echo elgg_echo('izap_videos:adminSettings:info:bg-color');?>
    </span>
  </p>

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izapButtoncolor');?>
      <br />

      <?php
      echo elgg_view('input/text', array(
      'internalname' => 'params[izapBorderColor3]',
      'value' => izapAdminSettings_izap_videos('izapBorderColor3'),
      ));
      ?>
    </label>
    <br />
    <span class="izap_info_text">
      <?php echo elgg_echo('izap_videos:adminSettings:info:bg-color');?>
    </span>
  </p>

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izap_display_page');?>
      <br />
      <?php
      echo elgg_view('input/radio', array(
      'internalname' => 'params[izap_display_page]',
      'options' => array(
              elgg_echo('izap_videos:adminSettings:izap_default_page') => 'default',
              elgg_echo('izap_videos:adminSettings:izap_full_page') => 'full',
      ),
      'value' => izapAdminSettings_izap_videos('izap_display_page', 'default'),
      ));
      ?>
    </label>
  </p>

  <p>
    <label>
      <?php echo elgg_echo('izap_videos:adminSettings:izapMaxFileSize');?>
      <br />

      <?php
      echo elgg_view('input/text', array(
      'internalname' => 'params[izapMaxFileSize]',
      'value' => izapAdminSettings_izap_videos('izapMaxFileSize', '5'),
      ));
      ?>
    </label>
  </p>

  <fieldset class ="izap-admin-group">
    <?php
    echo '<legend>' . elgg_echo('izap_videos:adminSettings:izapKeepOriginal') . '</legend>';
    echo elgg_view('input/radio', array(
    'internalname' => 'params[izapKeepOriginal]',
    'value' => IzapBase::pluginSetting(array(
      'name' => 'izapKeepOriginal',
      'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
      'value' => 'yes'
    )),
    'options' => array(
            elgg_echo('izap-bridge:yes') => 'yes',
            elgg_echo('izap-bridge:no') => 'no',
    ),
    ));
    ?>
  </fieldset>
  <br />

  <fieldset  class ="izap-admin-group">
    <?php
    echo '<legend>' . elgg_echo('izap_videos:adminSettings:izapTopBarWidget') . '</legend>';
    echo elgg_view('input/radio', array(
    'internalname' => 'params[topbar_extend_izap_videos]',
    'value' => IzapBase::pluginSetting(array(
    'name' => 'topbar_extend_izap_videos',
    'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    'value' => 'yes'
    )),
    'options' => array(
            elgg_echo('izap-bridge:yes') => 'yes',
            elgg_echo('izap-bridge:no') => 'no',
    ),
    ));
    ?>
  </fieldset>
  <br />

  <fieldset  class ="izap-admin-group">
    <?php
    echo '<legend>' . elgg_echo('izap_videos:adminSettings:tagcloud_cateogries') . '</legend>';
    echo elgg_view('input/radio', array(
    'internalname' => 'params[izapTagCloud_categories]',
    'value' => IzapBase::pluginSetting(array(
    'name' => 'izapTagCloud_categories',
    'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    'value' => 'yes'
    )),
    'options' => array(
            elgg_echo('izap-bridge:yes') => 'yes',
            elgg_echo('izap-bridge:no') => 'no',
    ),
    ));
    ?>
  </fieldset>
  <br />

  <fieldset  class ="izap-admin-group">
    <?php
    echo '<legend>' . elgg_echo('izap_videos:adminSettings:izapGiveUsCredit') . '</legend>';
    echo elgg_view('input/radio', array(
    'internalname' => 'params[izapGiveUsCredit]',
    'value' => IzapBase::pluginSetting(array(
    'name' => 'izapGiveUsCredit',
    'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
    'value' => 'yes'
    )),
    'options' => array(
            elgg_echo('izap-bridge:yes') => 'yes',
            elgg_echo('izap-bridge:no') => 'no',
    ),
    ));
    ?>
  </fieldset>
  <br />
</div>