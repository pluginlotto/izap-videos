<?php
/*
 *    This file is part of izap-videos plugin for Elgg.
 *
 *    izap-videos for Elgg is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    izap-videos for Elgg is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with izap-videos for Elgg.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * object for izap-video
 * @package izap-video 
 */

$full = elgg_extract('full_view', $vars, False);
$izap_video = elgg_extract('entity', $vars, False);
$view_type = end(explode('/', current_page_url()));
$widget_view = get_user_by_username($view_type);
if (!$izap_video) {
	return True;
}

$owner = $izap_video->getOwnerEntity();
if ($izap_video->imagesrc) {
	$icon = elgg_view_entity_icon($izap_video, 'medium');
}

$container = $izap_video->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$description = elgg_get_excerpt($izap_video->description);

$owner_link = elgg_view('output/url', array(
	'href' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER . "/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
	));
$author_text = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($izap_video->time_created);

// The "on" status changes for comments, so best to check for !Off
if ($izap_video->comments_on != 'Off') {
	$comments_count = $izap_video->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER) . '#comments',
			'text' => $text,
			'is_trusted' => true,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}
$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	));
$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}
global $IZAPSETTINGS;
if ($izap_video->converted == 'no') {
	$izap_video->access_id = ACCESS_PRIVATE;
	$izap_video->save();
}
if ($full) {
	$params = array(
		'entity' => $izap_video,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	izap_increase_views($izap_video);
	izap_get_total_views($izap_video);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);
	$text = elgg_view('output/longtext', array('value' => $izap_video->description));
	$get_image = elgg_get_site_url() . 'mod/izap-videos/thumbnail.php?file_guid=' . $izap_video->guid;
	if ($izap_video->videothumbnail) {
		$thumbnail_image = $izap_video->videothumbnail;
		$style = 'height:400px; width: 100%;border-radius:8px;';
	} elseif ($izap_video->imagesrc) {
		$thumbnail_image = $get_image;
		$style = 'height:400px; width: 100%;border-radius:8px;';
	} else {
		$thumbnail_image = $IZAPSETTINGS->graphics . '/no_preview.jpg';
		$style = 'height:400px; width: 670px;background-color:black;border-radius:8px;';
	}

	$get_player_path = elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/viewvideo/' . $izap_video->guid . '/400/670';

	//load video div
	$content = "<div id='load_video_" . $izap_video->guid . "' class='loader'>";
	$content .= '<a href="' . $get_player_path . '" rel="' . $izap_video->guid . '" class = "ajax_load_video">' . '<img src="' . $thumbnail_image . '"  style= "' . $style . '" />';
	$content .= '<img src="' . $IZAPSETTINGS->graphics . 'c-play.png" class="play_icon"/></a>';
	$content .= izap_add_error($izap_video->guid);
	$content .= '</div>';

	$body = " $content $text $summary";

	echo elgg_view('object/elements/full', array(
		'entity' => $izap_video,
		'body' => $body
	));
} elseif ($view_type == 'all') {
	// brief view
	$view_count = izap_get_total_views($izap_video);
	if ($izap_video->videothumbnail) {
		$thumb_path = $izap_video->videothumbnail;
		$path = $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER);
		$file_icon = '<a href="' . $path . '"><img class="elgg-photo " src="' . $thumb_path . '" alt="check it out" style="width:130px;"></a>';
	} else {
		$file_icon = elgg_view_entity_icon($izap_video, 'medium');
	}
	$description_length = strlen($description);
	if ($description_length > 163) {
		$description = substr($description, 0, 160) . "...";
	}

	$description .= "<div class=\"elgg-subtext\"><div class=\"main_page_total_views\">$view_count</div></div>";
	$params = array(
		'entity' => $izap_video,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $description,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	echo elgg_view_image_block($file_icon, $list_body);
} elseif ($container->type == 'group' || $widget_view->type == 'user' || $view_type == 'add') {
	$view_count = izap_get_total_views($izap_video);
	if ($izap_video->videothumbnail) {
		$thumb_path = $izap_video->videothumbnail;
		$path = $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER);
		$file_icon = '<a href="' . $path . '"><img class="elgg-photo " src="' . $thumb_path . '" alt="check it out" style="width:130px;"></a>';
	} else {
		$file_icon = elgg_view_entity_icon($izap_video, 'medium');
	}
	?>
	<div class="elgg-image-block clearfix group_video" >
		<div class="elgg-image ">
			<?php echo $file_icon; ?>
		</div>
		<div class="elgg-body">
			<?php
			$title_length = strlen($izap_video->title);
			if ($container->type == 'group') {
				if ($title_length < 30) {
				?>
				<h3><a href="<?php echo $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER); ?>"><?php echo $izap_video->title ?></a></h3>
				<?php
			} else {
				$title = substr($izap_video->title, 0, 33);
				?> 
				<h3><a href="<?php echo $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER); ?>"><?php echo $title . "..." ?></a></h3>
			<?php } 
			}else{
				if ($title_length < 17) {
				?>
				<h3><a href="<?php echo $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER); ?>"><?php echo $izap_video->title ?></a></h3>
				<?php
			} else {
				$title = substr($izap_video->title, 0, 20);
				?> 
				<h3><a href="<?php echo $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER); ?>"><?php echo $title . "..." ?></a></h3>
			<?php } 
			}
			
			?>
			<?php echo $metadata; ?>
			<?php
			$description_length = strlen($description);
			if ($container->type == 'group') {
				if ($description_length < 87) {
					?>
					<div class="elgg-content"><?php echo $description; ?></div>
					<?php
				} else {
					$description = substr($description, 0, 80);
					?>  
					<div class="elgg-content"><?php echo $description . "..."; ?></div>
				<?php
				}
			} else {
				if ($description_length < 55) {
				?>
				<div class="elgg-content"><?php echo $description; ?></div>
				<?php
			} else {
				$description = substr($description, 0, 57);
				?>  
				<div class="elgg-content"><?php echo $description . "..."; ?></div>
			<?php } 
			}
			?>
			<div class="group-elgg-subtext"><?php echo $subtitle; ?>
				<div class="main_page_total_views total"><?php echo $view_count; ?></div>
			</div>
		</div>
	</div>
	<?php
} else {
	// brief view
	$view_count = izap_get_total_views($izap_video);
	if ($izap_video->videothumbnail) {
		$thumb_path = $izap_video->videothumbnail;
		$path = $izap_video->getURL($owner, GLOBAL_IZAP_VIDEOS_PAGEHANDLER);
		$file_icon = '<a href="' . $path . '"><img class="elgg-photo " src="' . $thumb_path . '" alt="check it out" style="width:130px;"></a>';
	} else {
		$file_icon = elgg_view_entity_icon($izap_video, 'medium');
	}
	$description_length = strlen($description);
	if ($description_length > 163) {
		$description = substr($description, 0, 160) . "...";
	}

	$description .= "<div class=\"elgg-subtext\"><div class=\"main_page_total_views\">$view_count</div></div>";
	$params = array(
		'entity' => $izap_video,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $description,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	echo elgg_view_image_block($file_icon, $list_body);
}
?>

<script type="text/javascript">
	var video_loading_image = '<?php echo $IZAPSETTINGS->graphics . '/ajax-loader_black.gif' ?>';
	var status_url = "<?php echo elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/check_video_status/' . $izap_video->guid; ?>";
</script>
