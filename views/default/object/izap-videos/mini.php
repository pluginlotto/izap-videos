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
$video = elgg_extract('video', $vars);
if(izap_is_video($video)):
  $tags = $video->tags;
  $tags_keys = array_rand($tags, 2);
  foreach($tags_keys as $key):
    $tags_array[] = $tags[$key];
  endforeach;
  $icon = elgg_view_entity_icon($video, 'small');
  $icon = sprintf('<a href="'.$video->getURL().'">%s</a>', $icon);

  $title = substr($video->title, 0, 25) . '....';
  $title = sprintf('<a href="'.$video->getURL().'" title="%s">%s</a>', $video->title, $title);

  $info = elgg_view('page/components/summary', array(
          'entity' => $video,
          'tags' => FALSE,
          'title' => $title,
          'subtitle' =>  elgg_get_friendly_time($video->time_created) . ' ' .elgg_echo('by') . ' ' . elgg_view('output/url', array(
                  'text' => $video->container_name,
                  'href' => IzapBase::setHref(
                          array(
                              'action' => 'owner',
                              'page_owner' => $video->container_username,
                              'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                              )),
          )) .  ' ' . elgg_echo('izap_videos:views') . ':<b>' .$video->getViews() . '</b>',
  ));
  echo elgg_view_image_block($icon, $info);
endif;