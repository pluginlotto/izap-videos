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


$options = $vars['options'];
$selectedTab = $vars['selected'];
?>
<div id="elgg_horizontal_tabbed_nav">
  <ul>
    <?php
    foreach ($options as $addOption) {
      ?>
    <li class="<?php echo ($addOption == $selectedTab) ? 'selected' : '';?>">
      <a href="?option=<?php echo $addOption?>">
          <?php echo elgg_echo('izap_videos:addEditForm:' . $addOption);?>
      </a>
    </li>
      <?php
    }
    ?>
  </ul>
</div>