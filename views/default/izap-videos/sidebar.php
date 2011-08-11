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

$link_addr = '';
echo elgg_view('output/izap_categories',array('plugin_id' => $vars['entity'],'subtype' =>GLOBAL_IZAP_VIDEOS_SUBTYPE));
 
   $tags = elgg_view_tagcloud(array(
        'type' => 'object',
        'subtype' =>GLOBAL_IZAP_VIDEOS_SUBTYPE,
        'limit' => 50
    ));
   if($tags){ ?>


<div class="elgg-module  elgg-module-aside">
    <div class="elgg-head">
        <h3>Tags</h3></div>
    <div class="elgg-body">
      <?php  echo $tags ?>
    </div>
</div>

<?php }?>