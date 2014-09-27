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
  elgg_load_library('elgg:izap_video');
  echo 'here';
  $conversion_failed = getFailedVideos();
  if(sizeof($conversion_failed)){
    $status = elgg_echo('izap-videos:conversion_failed_no');
  }else{
    $status = elgg_echo('izap-videos:conversion_failed_list');
  }
  echo elgg_view(GLOBAL_IZAP_VIDEOS_PLUGIN . '/conversion_failed', array(
      'queue_videos' => getFailedVideos(),
      'status' => $status,
      'total' => count($conversion_failed)
      )
    );
  foreach($conversion_failed as $failed){
    
    
  }

?>
