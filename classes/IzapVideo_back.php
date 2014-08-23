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

class IzapVideo extends ElggFile {

    protected $format = '.flv';

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes['subtype'] = "izap_video";
    }

    public function __construct($guid = NULL) {
        parent::__construct($guid);
        // set some initial values so that old videos can work
    }

    /**
     * set tmp path for upload video
     */
    public function get_tmp_path($video_name) {
        $setFileName = 'izap_videos/tmp/' . $video_name;
        return $setFileName;
    }

    /**
     * 
     * @param type $file_path input path for ffmpeg processing
     */
    public function processOnserverVideo($source_path, $dest_path) {
        $returnvalue = new stdClass();

        $destination_path = $dest_path . time() . $this->format;
        $file_name = end(explode('/', $destination_path));
        $source_file = end(explode('/', $source_path));

        //tmp file
        $this->setFilename($this->get_tmp_path($source_file));
        $this->open('write');
        $this->write(file_get_contents($source_path));
        $this->tmpfile = $this->getFilenameOnFilestore();
        $returnvalue->tmpFile = $this->getFilenameOnFilestore();

        //ffmpeg command
        exec("ffmpeg -i $source_path $destination_path 2>&1", $out, $err);

        //if file convert suucessful then move tmp file.
        if ($err == 0 && file_exists($destination_path)) {

            
            $this->filename = $source_file;
            $this->converted = 'yes';

            //if file not moved then unlink from tmp folder
            if (!$this->tmpfile) {
                @unlink($destination_path);
            } else {
                //send destination_path back to test file
                $returnvalue->converted = 'yes';
                $returnvalue->unlink_tmp_video = $destination_path;
               // $returnvalue->is_flv = 'yes';
            }
        } else {
            //return if video not converted
            $returnvalue->converted = 'no';
          //  $returnvalue->is_flv = 'no';
            $returnvalue->message = end($out);
        }

//        //get thumbnail 
//        $get_thumbnail = $this->get_thumbnail($source_path, $dest_path);
//
//        if ($get_thumbnail->thumbnail == '') {
//            $returnvalue->thumbnail = 'no';
//        } else {
//            $returnvalue->unlink_tmp_image = $get_thumbnail->thumbnail;
//            $returnvalue->thumbnail = 'yes';
//        }
        return $returnvalue;
    }

    /**
     * get thumbnail from video
     * @param type $source
     * @param string $dest
     * @return string
     */
    public function get_thumbnail($source, $dest) {
        $return_thumbnail = new stdClass();
        $dest = $dest . time() . '.jpg';
        $image_name = end(explode('/', $dest));

        //get thumbnail from video command
        exec("ffmpeg -i $source -vframes 1 -s 160x120 -ss 10 $dest 2>&1", $out, $err);

        //if thumbnail get successful then move tmp file
        if ($err == 0 && file_exists($dest)) {
            //move image from tmp folder
            $this->setFilename('izap_videos/uploaded/image_' . $image_name);
            $this->open('write');
            $this->write(file_get_contents($dest));
            $this->close();
            $this->originalimagename = $image_name;
            $this->tmpimage = $this->getFilenameOnFilestore();

            //if file not moved then unlink from tmp folder
            if (!$this->tmpimage) {
                @unlink($dest);
            } else {
                $return_thumbnail->thumbnail = $dest;
            }
        } else {
            $return_thumbnail->thumbnail = '';
        }
        return $return_thumbnail;
    }

}
