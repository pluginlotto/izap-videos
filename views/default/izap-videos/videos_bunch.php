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

$videos = $vars['videos'];
$title_length = 15;
if(isset ($vars['title_length']) && (int)$vars['title_length'] > 0) {
  $title_length = (int)$vars['title_length'];
}
if($videos) {
  if($vars['wrap'] !== FALSE) {
    ?>
 <div class="elgg-module  elgg-module-aside">
      <div class="elgg-head">
        <h3><?php echo elgg_echo($vars['widget_title']); ?></h3></div>
      <div class="elgg-body">
            <?php
          }
          foreach($videos as $video) {
            $title = substr($video->title, 0, $title_length) . ((strlen($video->title) > $title_length) ? '...' : '' );
            ?>
        <div class="video_ajaxed_icon"  title="<?php echo $video->title?>" style="height: 40px; width: 40px;">
          <a href="<?php echo $video->getURL();?>">
                <?php echo $video->getThumb(FALSE, array('width' => 40, 'height' => 40));?>
          </a>
        </div>

        <div class="video_info small_text" title="<?php echo $video->title?>">
          <a href="<?php echo $video->getURL();?>"><b><?php echo $title?></b></a><br />
              <?php
              echo elgg_echo('izap_videos:views') . ':<b>' .$video->getViews() . '</b>';
              ?>
        </div>
        <div class="clearfloat bottom_border"></div>
            <?php
          }
          if($vars['wrap'] !== FALSE) {
            ?>
      </div>
    </div>
    <?php
  }
}