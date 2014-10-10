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

/**
 * Main class for YouTube developer setup
 * 
 * @version 5.0
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

	/**
	 * @param string|False  $http_client
	 * @param string|False  $developer_key
	 * 
	 * @version 5.0
	 */
	public function __construct($http_client = false, $developer_key = false) {
		Zend_Loader::loadClass('Zend_Gdata_YouTube');
		$this->setRequestDeveloperKey($developer_key);
		$this->http_client = $http_client;
	}

	/**
	 * Undefined method calls handling. We are only entertaining getMethods and setMethods.
	 * 
	 * @param string  $functionName
	 * @param array   $arguments
	 * 
	 * @return array
	 * 
	 * @throws IzapException
	 * 
	 * @version 5.0
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

	/**
	 * Return authenticated http client
	 * 
	 * @param string  $token
	 * 
	 * @return \self
	 * 
	 * @version 5.0
	 */
	static public function getAuthSubHttpClient($token = false) {
		$next = elgg_get_site_url() . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/upload/' . elgg_get_logged_in_user_entity()->username . '/youtube';
		$scope = 'http://gdata.youtube.com';
		$secure = false;
		$session = true;
		if (!isset($_SESSION['YT_TOKEN']) && !$token) {
			return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session);
		} else if (!isset($_SESSION['YT_TOKEN']) && $token) {
			$_SESSION['YT_TOKEN'] = Zend_Gdata_AuthSub::getAuthSubSessionToken($token);
		}
		return new self(Zend_Gdata_AuthSub::getHttpClient($_SESSION['YT_TOKEN']), izapAdminSettings_izap_videos('youtubeDeveloperKey'));
	}

	/**
	 * Return YouTube object
	 * 
	 * @return array
	 * 
	 * @version 5.0
	 */
	public function YoutubeObject() {
		if (!$this->youtube_object) {
			$this->youtube_object = new Zend_Gdata_YouTube($this->http_client, 'iZAP-video-1.0', null, $this->getRequestDeveloperKey());
		}
		return $this->youtube_object;
	}

}
