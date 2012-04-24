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

// this page displays the plugin settings content in the admin control panel
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
  <fieldset  class ="izap_admin_fieldset">
    <legend><?php echo elgg_echo('izap_videos:adminSettings:onServerVideos'); ?></legend>
    <p>
      <?php
      echo elgg_view('input/radio', array(
      'name' => 'params[onserver_enabled_izap_videos]',
      'id' => 'onserver',
      'value' => IzapBase::pluginSetting(
              array(
                  'name' => 'onserver_enabled_izap_videos',
                  'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
                   'value' => 'no'
                   )),
      'options' => array(
              elgg_echo('izap-videos:adminSettings:my-server') => 'yes',
              elgg_echo('izap-videos:adminSettings:youtube-server') => 'youtube',
              elgg_echo('izap-videos:adminSettings:disable') => 'no',
      ),
      ));
    ?>
    </p>
    <div id="yes-server">
        <p>
        <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapPhpInterpreter');?>
          <?php
          echo elgg_view('input/text', array(
          'name' => 'params[izapPhpInterpreter]',
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
            'name' => 'params[izapVideoCommand]',
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
          'name' => 'params[izapVideoThumb]',
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
    </div>
    <div id="youtube-server">
      <p>
        <label>
          <?php echo elgg_echo('izap-videos:adminSettings:youtubeUsername');?>
          <br />
          <?php
          echo elgg_view('input/text', array(
            'name' => 'params[youtubeUsername]',
            'value' => izapAdminSettings_izap_videos('youtubeUsername'),
          ));
          ?>
        </label>
      </p>

       <p>
        <label>
          <?php echo elgg_echo('izap-videos:adminSettings:youtubePassword');?>
          <br />
          <?php
          echo elgg_view('input/password', array(
            'name' => 'params[youtubePassword]',
            'value' => izapAdminSettings_izap_videos('youtubePassword'),
          ));
          ?>
        </label>
      </p>
    </div>
      <p>
        <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapBarColor');?>
          <br />
          <?php
          echo elgg_view('input/text', array(
          'name' => 'params[izapBorderColor1]',
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
          'name' => 'params[izapBorderColor2]',
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
          'name' => 'params[izapBorderColor3]',
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
          <?php echo elgg_echo('izap_videos:adminSettings:izapMaxFileSize');?>
          <br />

          <?php
          echo elgg_view('input/text', array(
          'name' => 'params[izapMaxFileSize]',
          'value' => izapAdminSettings_izap_videos('izapMaxFileSize', '5'),
          ));
          ?>
        </label>
      </p>

      <p>
        <?php
        echo '<label>'.elgg_echo('izap_videos:adminSettings:izapTopBarWidget').'</label>';
        echo elgg_view('input/radio', array(
        'name' => 'params[topbar_extend_izap_videos]',
        'align' => 'horizontal',
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
      </p>
  </fieldset>

<fieldset  class ="izap_admin_fieldset">
    <legend><?php echo elgg_echo('izap_videos:adminSettings:offServerVideos'); ?></legend>
    <p>
    <?php
    echo elgg_view('input/radio', array(
    'name' => 'params[offserver_enabled_izap_videos]',
    'value' => IzapBase::pluginSetting(
            array(
              'name' => 'offserver_enabled_izap_videos',
              'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
              'value' => 'yes'
            )),
    'options' => array(
            elgg_echo('izap-videos:adminSettings:enable') => 'yes',
            elgg_echo('izap-videos:adminSettings:disable') => 'no',
    ),
    ));
    ?>
    </p>
  </fieldset>

  <fieldset class ="izap_admin_fieldset">
    <legend><?php echo elgg_echo('izap-videos:adminSettings:general_settings'); ?></legend>

     <p>
      <label><?php echo elgg_echo('izap_videos:adminSettings:izap_display_page');?></label>
      <?php
      echo elgg_view('input/radio', array(
      'name' => 'params[izap_display_page]',
      'align' => 'horizontal',
      'options' => array(
              elgg_echo('izap_videos:adminSettings:izap_default_page') => 'default',
              elgg_echo('izap_videos:adminSettings:izap_full_page') => 'full',
      ),
      'value' => izapAdminSettings_izap_videos('izap_display_page', 'default'),
      ));
      ?>
  </p>

    <p>
    <?php
    echo  '<label>'.elgg_echo('izap_videos:adminSettings:izapKeepOriginal').'</label>';
    echo elgg_view('input/radio', array(
    'name' => 'params[izapKeepOriginal]',
    'align' => 'horizontal',
    'value' => IzapBase::pluginSetting(
            array(
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
  </p>
    <p>
    <?php
    echo '<label>'.elgg_echo('izap_videos:adminSettings:tagcloud_cateogries').'</label>';
    echo elgg_view('input/radio', array(
    'name' => 'params[izapTagCloud_categories]',
    'align' => 'horizontal',
    'value' => IzapBase::pluginSetting(
            array(
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
    </p>
<!--    <p>
    <?php
//    echo '<label>'.elgg_echo('izap_videos:adminSettings:izapGiveUsCredit').'</label>';
//    echo elgg_view('input/radio', array(
//    'name' => 'params[izapGiveUsCredit]',
//    'align' => 'horizontal',
//    'value' => IzapBase::pluginSetting(
//              array(
//              'name' => 'izapGiveUsCredit',
//              'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
//              'value' => 'yes'
//              )),
//    'options' => array(
//            elgg_echo('izap-bridge:yes') => 'yes',
//            elgg_echo('izap-bridge:no') => 'no',
//    ),
//    ));
    ?>
    </p>-->
  </fieldset>
<script type="text/javascript">
  $(document).ready(function(){
  <?php if($on_server_feature = izap_is_onserver_enabled_izap_videos()) {
      echo "$('#".$on_server_feature."-server').show()";
    }else{
      ?>
      $('#yes-server').hide();
      $('#youtube-server').hide();
    <?php
    }
  ?>
    

    $('#onserver-yes').click(function(){
      $('#youtube-server').slideUp('slow');
      $('#'+this.value+'-server').slideDown('slow');
    });
    $('#onserver-youtube').click(function(){
      $('#yes-server').slideUp('slow');
      $('#'+this.value+'-server').slideDown('slow');
    });
  });

</script>