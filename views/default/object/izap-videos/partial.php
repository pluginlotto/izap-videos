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

$owner = $vars['video']->getOwnerEntity();
$video_pic = elgg_view('output/url', array(
                'href' =>   $vars['video']->getUrl(),
	'text' => $vars['video']->getThumb(false, array('width' => 80, 'height' => 80, 'alt' => $vars['video']->title), TRUE),
));
$owner_link = elgg_view('output/url', array(
                'href' =>  IzapBase::setHref(array(
                'action' => 'owner',
                'page_owner' => $vars['video']->container_username,
                )),
	'text' => $owner->name,
));

$author_text = elgg_echo('byline', array($owner_link));
$tags = elgg_view('output/tags', array('value' =>  IzapBase::izap_truncate_array($vars['video']->tags, 5)));
$date = elgg_view_friendly_time($vars['video']->time_created);

if ($vars['video']->comments_on) {
	$comments_count = $vars['video']->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $vars['video']->getURL() . '#video-comments',
			'text' => $text,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

$subtitle = "<p>$author_text $date $comments_link</p>";

 $description = strip_tags($vars['video']->getDescription());
 $description = substr($description, 0, 200) . ((strlen($description) > 200) ? '...' : '' );


	$title_link = elgg_view('output/url', array(
		'text' => $vars['video']->getTitle(array('max_length' => 55 , 'mini' => true)),
		'href' => $vars['video']->getURL(),
	));

$params = array(
		'entity' => $vars['video'],
		'metadata' => IzapBase::controlEntityMenu(array('entity' => $vars['video'],'page_owner'=>$vars['video']->container_username, 'handler' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER)),
		'title' => $title_link,
    'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $description,
	);
	$params = $params + $vars;
 $list_body = elgg_view('object/izap-videos/elements/summary', $params);
 	echo elgg_view_image_block($video_pic, $list_body);
  ?>
