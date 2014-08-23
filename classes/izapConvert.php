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

class izapConvert {
  
  private $invideo;
  private $outvideo;
  private $outimage;
  private $imagepreview;
  private $values = array();
  private $is_flv = FALSE;

  public $format = 'flv';
  
  public function izapConvert($in = '') {
    $this->invideo = $in;
    $extension_length = strlen(getFileExtension($this->invideo));
    $outputPath = substr($this->invideo, 0, '-' . ($extension_length + 1));
    $this->outvideo =  $outputPath . '_c.' . $this->format;
    $this->outimage = $outputPath . '_i.png';
    $this->imagepreview = $outputPath.'_p.png';
  }
  
  public function izap_video_convert() {

    // check if the file is already flv
    $current_file_type = getFileExtension($this->invideo); 
    if($current_file_type == 'flv') {
     
    } else {
      $videoCommand = izap_get_ffmpeg_videoConvertCommand_izap_videos();
      $videoCommand = str_replace('[inputVideoPath]', $this->invideo, $videoCommand);
      $videoCommand = str_replace('[outputVideoPath]', $this->outvideo, $videoCommand);
      exec($videoCommand, $arr, $ret);

      if(!$ret == 0) {
        $return = array();
        $return['error'] = 1;
        $return['message'] = end($arr);
        $return['completeMessage'] = implode(' ', $arr);

        return $return;
      }
    }
    return end(explode('/', $this->outvideo));
  }
  
  public function getValues($image_only = false) {

    if($this->is_flv) { // if it is flv then return the created array
      return $this->values;
    }

    if(!$image_only) { // if we want the full video values
      $this->values['origname'] = time() . '_' . end(explode('/', $this->invideo));
      $this->values['origcontent'] = file_get_contents($this->invideo);
      $this->values['filename'] = time() . '_' . end(explode('/', $this->outvideo));
      $this->values['filecontent'] = file_get_contents($this->outvideo);
      if($this->values['filecontent'] != '') {
          @unlink($this->invideo);
          @unlink($this->outvideo);
      }
    }else{
      // if only image is needed
      $this->values['imagename'] = time() . '_' . end(explode('/', $this->outimage));
      $this->values['preview'] = time() . '_' . end(explode('/', $this->imagepreview));
      $this->values['imagecontent'] = file_get_contents($this->outimage);
      @unlink($this->outimage);
    }
    return $this->values;
  }
}
