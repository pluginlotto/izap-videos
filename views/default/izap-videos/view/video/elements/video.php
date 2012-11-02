<?php
/**
 * iZAP izap_videos
 *
 * @package Elgg videotizer, by iZAP Web Solutions.
 * @license GNU Public License version 3
 * @Contact iZAP Team "<support@izap.in>"
 * @Founder Tarun Jangra "<tarun@izap.in>"
 * @link http://www.izap.in/
 *
 */

global $IZAPSETTINGS;
$height = ($vars['height']) ? $vars['height'] : 400;
$width = ($vars['width']) ? $vars['width'] : 670;

$unique = md5($vars['video']->guid .'-'. $width . '-' . $height);

$raw_video = IzapBase::setHref(
        array(
        'action' => 'rawvideo',
        'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
        'page_owner' => FALSE,
        'vars' => array(
                $vars['video']->guid, $height, $width
        )
        )
);
?>
<div align="center" <?php echo $playerClass;?> class="contentWrapper <?php echo ($vars['video']->converted != 'yes')?'video_background-top-round':'video_background' ?>" style="height: <?php echo $height?>px;">
  <div id="load_video_<?php echo $unique; ?>">
    <div style="height: <?php echo $height;?>px; width: <?php echo $width?>px;">
      <img src="<?php echo $vars['video']->getThumb(true)?>" alt="<?php echo $vars['video']->getTitle();?>" height="<?php echo $height?>" width="<?php echo $width?>"/>
    </div>
    <div style="position: relative; top: -<?php echo $height?>px;z-index: 0;">
      <a href="<?php echo $raw_video ?>" rel="<?php echo $unique?>" class="izap_ajaxed_thumb">
        <img src="<?php echo $IZAPSETTINGS->graphics;?>trans_play.png" alt="<?php echo elgg_echo('izap_videos:click_to_play')?>" height="<?php echo $height?>" width="<?php echo $width?>"/>
      </a>
    </div>
  </div>
</div>

<?php
if($vars['video']->converted != 'yes'){
    echo '<p class="notConvertedWrapper">'.elgg_echo("izap_videos:alert:not-converted").'</p>';
  }
?>
<div class="clearfloat"></div>
