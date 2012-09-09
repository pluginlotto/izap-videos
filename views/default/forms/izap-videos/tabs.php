<?php
/***************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2011. iZAP                  *
 * All rights reserved                             *
 ***************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

if(izap_is_onserver_enabled_izap_videos() === 'yes'){
$tabs['onserver'] = array(
            'title' => elgg_echo('izap-videos:onserver'),
            'url' => IzapBase::setHref(array(
                      'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                      'action' => 'add',
                      'vars' => array('onserver')
                      )),
            'selected' => ($vars['entity']->videoprocess == 'onserver'),
    );
}elseif(izap_is_onserver_enabled_izap_videos() === 'youtube'){
$tabs['onserver'] = array(
            'title' => elgg_echo('izap-videos:onserver'),
            'url' => IzapBase::setHref(array(
                'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                'action' => 'add',
                'page_owner' => elgg_instanceof(elgg_get_page_owner_entity(), 'group') ? elgg_get_page_owner_entity()->username : elgg_get_logged_in_user_entity()->username,
                'vars' => array('tab' => ($onserver = izap_is_onserver_enabled_izap_videos()) ?
                            ($onserver == 'yes')?'onserver':'youtube' :
                            'offserver'),
            )),
            'selected' => ($vars['entity']->videoprocess == 'youtube'),
    );
}

if(izap_is_offserver_enabled_izap_videos()){
    $tabs['offserver'] = array(
            'title' => sprintf(elgg_echo('izap-videos:offserver'),''),
            'url' => IzapBase::setHref(array(
                      'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                      'action' => 'add',
                      'vars' => array('offserver')
                      )),
            'selected' => ($vars['entity']->videoprocess == 'offserver'),
    );
}
  echo elgg_view('navigation/tabs', array('tabs' => $tabs));