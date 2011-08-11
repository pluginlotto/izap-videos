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

$description = $vars['video']->getDescription(array('max_length'=> 200));
$container_entity = get_entity($vars['video']->container_guid);

//c($vars['video']->getownerentity());
?>

<div class="contentWraper">
  <div class="video_description">
    <div class="owner_icon">
      <?php
      echo elgg_view("profile/icon",array('entity' => $vars['video']->getownerentity(), 'size' => 'small'));
      ?>
    </div>
    <?Php echo elgg_Echo('by')?>
<a href="<?php echo $vars['url']; ?>pg/videos/owner/<?php echo $container_entity->username; ?>">
          <?php echo $container_entity->name; ?>
        </a> &nbsp;
        <?php echo elgg_get_friendly_time($vars['video']->time_created);?>

    <div class="meta_info">
      <p>
        <?php
        echo IzapBase::controlEntityMenu(array('entity' => $vars['video'], 'handler' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER));
        ?>
      </p>
    </div>
    <div class="clearfloat"></div>

    <div class="main_page_total_views">
      <h3>
        <?php echo $vars['video']->getViews();?>
      </h3>
    </div>

    <div class="description">
      <div id="small_desc">
        <?php
        /// description text
        if(strlen($description) > 255) {
          $mini_description = strip_tags($description);
          echo substr($mini_description,0,255);
          echo '... &nbsp;&nbsp;&nbsp;<a href="#fulldesc" onClick="show_full_desc();" class="more_less_link">['.elgg_echo("izap_videos:more").']</a>';
        }else {
          echo $description;
        }
        ?>
      </div>

      <div id="full_desc" style="display:none;">
        <?php
        // description text
        echo $description.' &nbsp;&nbsp;&nbsp;<a href="#small_desc" onClick="hide_full_desc();" class="more_less_link">['.elgg_echo("izap_videos:less").']</a>';
        ?>
      </div>
      <?php
      // tags view
      echo elgg_view('output/tags', array('tags' => $vars['video']->tags));
      echo elgg_view('output/categories', array('entity' => $vars['video']));
      ?>
    </div>
  </div>
</div>
