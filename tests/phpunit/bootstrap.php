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
	
$engine = __DIR__.'/../../../../engine';
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
date_default_timezone_set('Asia/Kolkata');

global $CONFIG;
$CONFIG = (object) array(
	'dbprefix' => 'elgg_',
	'boot_complete' => false,
	'dataroot' => __DIR__.'/../data/'
);
require_once "$engine/lib/autoloader.php";

// @todo remove once views service and menu tests no longer need it
function elgg_get_site_url() {
	return 'http://localhost/';
}


// Provide some basic global functions/initialization.
require_once "$engine/lib/elgglib.php";

// This is required by ElggEntity
require_once "$engine/lib/sessions.php";
require_once "$engine/lib/filestore.php";
require_once __DIR__.'/../../lib/izap-videos.php';
elgg_register_library('elgg:izap_video', __DIR__.'/../../lib/izap-videos.php');
require_once __DIR__.'/../../classes/IzapCurl.php';
require_once __DIR__.'/../../classes/IzapCurlResponse.php';
require_once __DIR__.'/../../classes/IzapVideo.php';
require_once __DIR__.'/../../classes/izapConvert.php';
require_once __DIR__.'/../../classes/IzapSqlite.php';
require_once __DIR__.'/../../classes/izapQueue.php';
require_once __DIR__.'/../../classes/IzapVideoException.php';
require_once "$engine/classes/Elgg/Translit.php";


global $DEFAULT_FILE_STORE;
$DEFAULT_FILE_STORE = new ElggDiskFilestore($CONFIG->dataroot);

function elgg_get_friendly_title($title) {
	// titles are often stored HTML encoded
	$title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');

	$title = Elgg_Translit::urlize($title);

	return $title;
}
