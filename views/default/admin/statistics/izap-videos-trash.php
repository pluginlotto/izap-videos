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

IzapBase::loadLib(array(
            'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
            'lib' => 'izap_videos_lib'
        ));

$buggy_videos_object = new izapQueue();
$buggy_videos = $buggy_videos_object->get_from_trash();
//c($buggy_videos);
?>
<form method="post" action="<?php echo IzapBase::getFormAction('recycle_delete', GLOBAL_IZAP_VIDEOS_PLUGIN) ?>">
  <?php
  echo elgg_view('input/securitytoken');
  ?>
  <div class ="trash_settings">
    <table class="elgg-table-alt">
      <tbody>        
        <?php
        if ($buggy_videos):
          foreach ($buggy_videos as $video):
        ?>
            <tr class="odd">
          <?php
            $name = $video['main_file'];
            $size = izapFormatBytes(filesize($video['main_file']));
          ?>
            <td class ="coloum1">
            <?php
            echo '<b>' . elgg_echo('video_name :') . '</b>';
            echo $name;
            echo '<br />';
            echo '<b>' . elgg_echo('size') . '</b>';
            echo $size;
            ?>
          </td><td class = "coloum2">
            <?php
            echo elgg_view('input/radio', array(
                'name' => 'attributes[' . $video['guid'] . '][action]',
                'options' => array(
                    elgg_echo('restore') => 'restore',
                    elgg_echo('delete') => 'delete'
                    )));
            ?>
            <label>
              <?php
              echo elgg_echo('send_message');
              echo elgg_view('input/text', array(
                  'name' => 'attributes[' . $video['guid'] . '][message]',
                  'value' => ''
              ))
              ?>
            </label>
          </td>

        </tr>
        <?php
              endforeach;
              echo IzapBase::input('submit');
              else:
                ?>
        No video available in the trash
                <?php
              endif;
        ?>

        </tbody>
      </table>
        </div>
      </form>

      