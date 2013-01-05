<?php

/* * *************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2011. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

class IzapGYoutube extends IzapGoogle {

    private $request = array(
        'developerkey' => false,
        'tokenhandler' => 'http://gdata.youtube.com/action/GetUploadToken',
        'nexturl' => '',
        'operationurl' => '',
        'homeurl' => '',
        'single_token' => '',
        'metadata' => array());
    private $http_client;
    private $youtube_object = false;

    public function __construct($http_client = false, $developer_key = false) {
        Zend_Loader::loadClass('Zend_Gdata_YouTube');
        $this->setRequestDeveloperKey($developer_key);
        $this->http_client = $http_client;
    }

    /*
     * undefined method calls handling. We are only entertaining getMethods and setMethods.
     */

    public function __call($functionName, $arguments) {
        try {
            if (preg_match('/^getRequest([A-Za-z]+)/', $functionName, $matches)) {
                $value_to_get = strtolower($matches[1]);
                if (!isset($this->request[$value_to_get])) {
                    throw new IzapException(elgg_echo('izap-elgg-bridge:Exception:no_metadata', array($value_to_get)));
                }
                return $this->request[$value_to_get];
            } elseif (preg_match('/^setRequest([A-Za-z]+)/', $functionName, $matches)) {
                $index_to_set = strtolower($matches[1]);
                $this->request[$index_to_set] = $arguments[0];
            } elseif (preg_match('/^getMetadata/', $functionName, $matches)) { // returns whole metadata in term of array
                return $this->request['metadata'];
            } elseif (preg_match('/^get([A-Za-z]+)/', $functionName, $matches)) {
                $value_to_get = strtolower($matches[1]);
                if (!isset($this->request['metadata'][$value_to_get])) {
                    throw new IzapException(sprintf(elgg_echo('izap-elgg-bridge:Exception:no_metadata'), $value_to_get));
                }
                return $this->request['metadata'][$value_to_get];
            } elseif (preg_match('/^set([A-Za-z]+)/', $functionName, $matches)) {
                $index_to_set = strtolower($matches[1]);
                $this->request['metadata'][$index_to_set] = $arguments[0];
            } else {
                throw new IzapException(sprintf(elgg_echo('izap-elgg-bridge:Exception:no_method'), $functionName));
            }
        } catch (IzapException $ze) {
            register_error($ze->getMessage());
        }
    }

    static public function getAuthSubHttpClient($token = false) {
        $next = elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER.'/upload/' . elgg_get_logged_in_user_entity()->username . '/youtube';
        $scope = 'http://gdata.youtube.com';
        $secure = false;
        $session = true;
        if (!isset($_SESSION['YT_TOKEN']) && !$token) {
            return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session);
        } else if (!isset($_SESSION['YT_TOKEN']) && $token) {
            $_SESSION['YT_TOKEN'] = Zend_Gdata_AuthSub::getAuthSubSessionToken($token);
        }
        return new self(Zend_Gdata_AuthSub::getHttpClient($_SESSION['YT_TOKEN']),
                        izapAdminSettings_izap_videos('youtubeDeveloperKey'));
    }

    public function YoutubeObject() {
        if (!$this->youtube_object) {
            $this->youtube_object = new Zend_Gdata_YouTube($this->http_client,
                            'iZAP-video-1.0',
                            null,
                            $this->getRequestDeveloperKey());
        }
        return $this->youtube_object;
    }

    static public function getYoutubeCategories() {
      
      
       $cats = array(
           'Film' => 'Film & Animation',
           'Autos' => 'Autos',
           'Music' =>  'Music',
            'Animals'=>'Pets & Animals',
            'Sports' => 'Sports',
            'Shortmov'=>'Short Movies',
            'Travel'=>'Travel & Events',
            'Games'=>'Gaming',
            'Videoblog'=>'Videoblogging',
            'Comedy'=>'Comedy',
            'Entertainment'=>'Entertainment',
            'News'=>'News & Politics',
            'Howto'=>'Howto & Style',
            'Education'=>'Education',
            'Tech'=>'Science & Technology',
            'Nonprofit'=>'Nonprofits & Activism',
            'Movies'=>'Movies',
            'Movies_anime_animation'=>'Anime/Animation',
            'Movies_action_adventurte'=>'Action/Adventure',
            'Movies_classics'=>'Classics',
            'Movies_comedy'=>'Comedy',
            'Movies_documentary'=>'Documentary',
            'Movies_drama'=>'Drama',
            'Movies_family'=>'Family',
            'Movies_foreign'=>'Foreign',
            'Movies_horror'=>'Horror',
            'Movies_sci_fi_fantasy'=>'Sci-Fi/Fantasy',
            'Movies_thriller'=>'Thriller',
            'Movies_shorts'=>'Shorts',
            'Shows'=>'Shows',
            'Trailers'=>'Trailers');
       
     
        asort($cats);
        return $cats;
    }

}

