<?php
/**
 * Elgg file thumbnail
 *
 * @package ElggFile
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get file GUID
$file_guid = (int) get_input('file_guid', 0);  

// Get file thumbnail size
//$size = get_input('size', 'small');

$file = get_entity($file_guid);
if (!elgg_instanceof($file, 'object', 'izap_video')) {  
	exit;
}

//$simpletype = $file->simpletype;
//
$thumbfile = $file->filename; 
	// Grab the file
	if ($thumbfile && !empty($thumbfile)) { 
		$readfile = new ElggFile();
		$readfile->owner_guid = $file->owner_guid; 
		$readfile->setFilename($thumbfile);
                $contents = $readfile->getFilenameOnFilestore($thumbfile); 
		//$mime = $file->getMimeType();
                               
		$contents = file_get_contents($contents);
 
		// caching images for 10 days
		//header("Content-type: $mime");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("Content-Length: " . strlen($contents));

		echo $contents;
		exit;
	}

