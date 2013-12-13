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

class IzapVideosController extends IzapController {

  public function __construct($page) {
    parent::__construct($page);
    // register default add button
    if (elgg_is_logged_in()) {
      if ($this->action != 'actionAdd' && elgg_get_page_owner_entity()->canWriteToContainer()) {
        $this->addButton(array(
            'menu_name' => 'title',
            'title' => elgg_echo('izap-videos:add_new'),
            'url' => IzapBase::setHref(array(
                'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                'action' => 'add',
                'page_owner' => elgg_instanceof(elgg_get_page_owner_entity(), 'group') ? elgg_get_page_owner_entity()->username : elgg_get_logged_in_user_entity()->username,
                'vars' => array('tab' => ($onserver = izap_is_onserver_enabled_izap_videos()) ?
                            ($onserver == 'yes') ? 'onserver' : 'youtube'  :
                            'offserver'),
            )),
        ));
      }

      $this->addButton(array(
          'menu_name' => 'title',
          'title' => elgg_echo('izap_videos:my_favorites'),
          'url' => IzapBase::setHref(array(
              'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
              'action' => 'favorite',
              'page_owner' => elgg_get_logged_in_user_entity()->username
          )),
      ));
    }

    // load lib
    IzapBase::loadLib(array(
        'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
        'lib' => 'izap_videos_lib'
    ));
    if (IzapBase::pluginSetting(array(
                'name' => 'izapTagCloud_categories',
                'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN
            )) == 'yes') {
      $this->addWidget(GLOBAL_IZAP_VIDEOS_PLUGIN . '/sidebar', array('entity' => GLOBAL_IZAP_VIDEOS_PLUGIN));
    }
  }

  public function actionIndex() {
    $this->actionAll();
  }

  public function actionAll() {
    $this->page_elements['title'] = elgg_echo('izap-videos:all_videos');
    $this->page_elements['filter_context'] = 'all';
    $this->page_elements['content'] = elgg_list_entities(array(
        'full_view' => false,
        'type' => 'object',
        'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
            ));
    $this->drawPage();
  }

  public function actionOwner() {
    $page_owner = elgg_get_page_owner_entity();
    if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
      $this->page_elements['title'] = elgg_echo('izap-videos:my_videos');
      $this->page_elements['filter_context'] = 'mine';
    } else {
      $this->page_elements['title'] = sprintf(elgg_echo('izap-videos:owner_videos'), $page_owner->name);
      $this->page_elements['filter_context'] = 'none';
    }

    if (elgg_instanceof($page_owner, 'group')) {
      $this->page_elements['filter'] = '';
    }
    $this->page_elements['content'] = elgg_list_entities(array(
        'full_view' => false,
        'container_guid' => $page_owner->guid,
        'type' => 'object',
        'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
            ));

    $this->drawPage();
  }

  public function actionFriends() {
    $page_owner = elgg_get_page_owner_entity();
    $this->page_elements['title'] = sprintf(elgg_echo('izap-videos:friends_videos'), $page_owner->name);
    $this->page_elements['filter_context'] = 'friends';

    $friends = get_user_friends($page_owner->guid, ELGG_ENTITIES_ANY_VALUE, 999);

    if ($friends) {
      $friends_array = array();
      foreach ($friends as $friend) {
        $friends_array[] = $friend->guid;
      }

      $this->page_elements['content'] = elgg_list_entities(array(
          'full_view' => false,
          'container_guid' => $page_owner->guid,
          'type' => 'object',
          'subtype' => GLOBAL_IZAP_VIDEOS_SUBTYPE,
              ));
    }
    $this->drawPage();
  }

  public function actionFavorite() {
    IzapBase::gatekeeper();
    $page_owner = elgg_get_page_owner_entity();
    $this->page_elements['filter'] = '';
    $this->page_elements['title'] = sprintf(elgg_echo('izap-videos:favorite'), $page_owner->name);
    $this->page_elements['content'] = elgg_list_entities_from_metadata(izap_defalut_get_videos_options(array(
                'full_view' => FALSE,
                'metadata_names' => 'favorited_by',
                'metadata_values' => $page_owner->guid,
            )));

    $this->drawPage();
  }

  protected function addEdit($vars = array()) {
    $this->render('forms/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/' . $vars['selected_option'], $vars);
  }

  //experimental youtube functions
  public function actionUpload() {
    IzapBase::gatekeeper(GLOBAL_IZAP_VIDEOS_DATAENTRY_ACCESS);
    /*
     * Array
      (
      [videoprocess] => youtube
      [container_guid] => 35
      [plugin] => izap-videos
      [guid] =>
      [youtube_cats] => Classics
      [_title] => Let me add new classic video
      [description] =>
      hey, how are you doing.

      [tags] => tag2
      [access_id] => 2
      [comments_on] => 1
      )
     */
    $video = IzapGYoutube::getAuthSubHttpClient(get_input('token', false));
    $yt = $video->YoutubeObject();
    $myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
    $myVideoEntry->setVideoTitle($_SESSION['youtube_attributes']['_title']);
    $myVideoEntry->setVideoDescription($_SESSION['youtube_attributes']['description']);

    // Note that category must be a valid YouTube category
    $myVideoEntry->setVideoCategory($_SESSION['youtube_attributes']['youtube_cats']);
    $myVideoEntry->SetVideoTags($_SESSION['youtube_attributes']['tags']);
    $tokenHandlerUrl = 'http://gdata.youtube.com/action/GetUploadToken';
    try {
      $tokenArray = $yt->getFormUploadToken($myVideoEntry, $tokenHandlerUrl);
    } catch (Exception $e) {

      if (preg_match("/<code>([a-z_]+)<\/code>/", $e->getMessage(), $matches)) {
        register_error('YouTube Error: ' . $matches[1]);
      } else {
        register_error('YouTube Error: ' . $e->getMessage());
      }
      forward(IzapBase::setHref(array(
                  'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                  'action' => 'add',
                  'page_owner' => elgg_instanceof(elgg_get_page_owner_entity(), 'group') ? elgg_get_page_owner_entity()->username : elgg_get_logged_in_user_entity()->username,
                  'vars' => array('tab' => 'youtube'),
              )));
    }
    $this->page_elements['filter'] = '';
    $this->page_elements['title'] = 'Upload video with title: "' . $_SESSION['youtube_attributes']['_title'] . '"';
    $params['token'] = $tokenArray['token'];
    $params['action'] = $tokenArray['url'] . '?nexturl=' . elgg_get_site_url() . 'videos/next';
    $this->render('forms/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/youtube_upload', $params);
  }

  public function actionNext() {

    IzapBase::gatekeeper();
    $is_status = (get_input('status') == 200) ? true : false;
    if (!$is_status) {
      // redirect the user from where he was trying to upload the video.
      register_error("We did not get expected response from YouTube. You might need to provide appropriate youtube category.");
      forward(IzapBase::setHref(array(
                  'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER,
                  'action' => 'add',
                  'page_owner' => elgg_instanceof(elgg_get_page_owner_entity(), 'group') ? elgg_get_page_owner_entity()->username : elgg_get_logged_in_user_entity()->username,
                  'vars' => array('tab' => ($onserver = izap_is_onserver_enabled_izap_videos()) ?
                              ($onserver == 'yes') ? 'onserver' : 'youtube'  :
                              'offserver'),
              )));
      exit;
    }
    $id = get_input('id');
    $pass = '%kdkdhSw*jdksl';
    forward(elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/izap-videos/add_edit?id=' . $id . '&p=' . $pass));
    exit;
  }

  public function actionAdd() {
    IzapBase::gatekeeper(GLOBAL_IZAP_VIDEOS_DATAENTRY_ACCESS);
    //user decided to upload new video So unset old youtube session attributes.
    unset($_SESSION['youtube_attributes']);
    if (!(izap_is_offserver_enabled_izap_videos() ||
            $is_youtube = izap_is_onserver_enabled_izap_videos())) {
      register_error(elgg_echo('izap-videos:message:noAddFeature'));
      forward(IzapBase::setHref(array(
                  'action' => 'all',
                  'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER
              )));
      exit;
    }
    if (!elgg_get_page_owner_entity()->canWriteToContainer()) {
      register_error(elgg_echo('izap-videos:message:noAddFeatureInGroup', array(elgg_get_page_owner_entity()->name)));
      forward(IzapBase::setHref(array(
                  'action' => 'all',
                  'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER
              )));
      exit;
    }

    $this->page_elements['filter'] = false;
    $this->page_elements['title'] = elgg_echo('izap-videos:add_new');
    $video = new IzapVideos();
    $video->container_guid = elgg_get_page_owner_guid();
    //videoType will be updated to "type" in future.
    $video->videoprocess = $this->url_vars[2];
    $this->render('forms/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/' . $this->url_vars[2], array(
        'entity' => $video
    ));
  }

  public function actionEdit() {
    IzapBase::gatekeeper(GLOBAL_IZAP_VIDEOS_DATAENTRY_ACCESS);

    $video = new IzapVideos((int) $this->url_vars[2]);
    if ($video->converted != 'yes') {
      $queue_object = new izapQueue();
      $trash_guid_array = $queue_object->get_from_trash($vars['video']->guid);
      register_error(
              $trash_guid_array ?
                      elgg_echo("izap_videos:form:izapTrashedVideoMsg") :
                      elgg_echo("izap_videos:form:izapEditMsg"));
      forward(IzapBase::setHref(array(
                  'action' => 'all',
                  'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER
              )));
      exit;
    }
    if (!izap_is_video($video) || !$video->canEdit()) {
      forward(IzapBase::setHref(array(
                  'action' => 'all',
                  'context' => GLOBAL_IZAP_VIDEOS_PAGEHANDLER
              )));
      exit;
    }
    $this->page_elements['filter'] = '';
    $this->page_elements['title'] = vsprintf(elgg_echo('izap-videos:editing'), array($video->title));

    $this->render('forms/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/_partial', array(
        'no_filters' => TRUE,
        'entity' => $video
    ));
  }

  public function actionList() {
    // it is same for now
    $this->actionAll();
  }

  public function actionPlay() {
      
    $video = get_entity((int) $this->url_vars[2]);
    if (!elgg_instanceof($video, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE, GLOBAL_IZAP_VIDEOS_CLASS)) {
      forward(IzapBase::setHref(array(
                  'action' => 'all',
              )));
    } 
    //updating view counter
    $video->updateViews();
    $this->page_elements['title'] = $video->title;
    $this->page_elements['filter'] = '';
    $play = IzapBase::pluginSetting(array(
                'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN,
                'name' => 'izap_display_page'
            ));
    $play .='Play';
    $this->$play($video);
    $this->drawPage();
  }

  public function defaultPlay($video) {

    $this->widgets = '';
    $this->addWidget(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/share', array('video' => $video));
    $this->addWidget(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/related', array('video' => $video));
    if (IzapBase::pluginSetting(array(
                'name' => 'izapTagCloud_categories',
                'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN
            )) == 'yes') {
      $this->addWidget(GLOBAL_IZAP_VIDEOS_PLUGIN . '/sidebar', array('entity' => GLOBAL_IZAP_VIDEOS_PLUGIN));
    }
    $this->page_elements['content'] = elgg_view_entity($video, array('full_view' => TRUE));
  }

  public function fullPlay($video) {
    $this->page_layout = 'full';
    $this->widgets = '';
    $this->addWidget(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/share', array('video' => $video));
    $this->addWidget(GLOBAL_IZAP_VIDEOS_PLUGIN . '/view/video/elements/related', array('video' => $video));
    if (IzapBase::pluginSetting(array(
                'name' => 'izapTagCloud_categories',
                'plugin' => GLOBAL_IZAP_VIDEOS_PLUGIN
            )) == 'yes') {
      $this->addWidget(GLOBAL_IZAP_VIDEOS_PLUGIN . '/sidebar', array('entity' => GLOBAL_IZAP_VIDEOS_PLUGIN));
    }
    $this->page_elements['izap_video'] = $video;
    $this->page_elements['content'] = elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN .
            '/view/video/elements/description', array('video' => $video));
  }

  public function actionRawvideo() {
    global $IZAPSETTINGS;

    $video = get_entity($this->url_vars[1]);
    $height = ($this->url_vars[2]) ? $this->url_vars[2] : $IZAPSETTINGS->ajaxed_video_height;
    $width = ($this->url_vars[3]) ? $this->url_vars[3] : $IZAPSETTINGS->ajaxed_video_width;

    if (elgg_instanceof($video, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE, GLOBAL_IZAP_VIDEOS_CLASS)) {
      $player = $video->getPlayer($width, $height, 1);
      echo $player;
    } else {
      echo elgg_echo('izap_videos:ajaxed_videos:error_loading_video');
    }
  }

  public function actionGetQueue() {
    global $CONFIG;

    $queue_status = (izapIsQueueRunning_izap_videos()) ?
            elgg_echo('izap_videos:running') :
            elgg_echo('izap_videos:notRunning');
    $queue_object = new izapQueue();
    echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/queue_status', array(
        'status' => $queue_status,
        'total' => $queue_object->count(),
        'queue_videos' => $queue_object->get(),
            )
    );
  }

  public function actionLoadRelatedVideos() {
    global $CONFIG;
    $video = get_entity($this->url_vars[1]);
    if (!elgg_instanceof($video, 'object', GLOBAL_IZAP_VIDEOS_SUBTYPE, GLOBAL_IZAP_VIDEOS_CLASS)) {
      exit;
    }
    $videos = $video->getRelatedVideos();
    if ($videos) {
      echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/videos_bunch', array('videos' => $videos,
          'widget_title' => elgg_echo('izap_videos:related_videos')));
    }

    $options['metadata_name'] = 'converted';
    $options['metadata_value'] = 'yes';
    $videos = elgg_get_entities_from_metadata(izap_defalut_get_videos_options($options));

    if ($videos) {
      echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/videos_bunch', array('videos' => $videos,
          'widget_title' => elgg_echo('izap_videos:latest')));
    }
  }

}