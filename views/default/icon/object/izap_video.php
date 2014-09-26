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

  $entity = $vars['entity'];

  $sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar');
// Get size
  if (!in_array($vars['size'], $sizes)) {
    $vars['size'] = "medium";
  }

  $title = $entity->title;
  $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

  $url = $entity->getURL();
  if (isset($vars['href'])) {
    $url = $vars['href'];
  }

  $class = '';
  if (isset($vars['img_class'])) {
    $class = $vars['img_class'];
  }
  if ($entity->imagesrc) {
    $class = "class=\"elgg-photo $class\"";
  } else if ($class) {
    $class = "class=\"$class\"";
  }

  $img_src = $entity->getIconURL($vars['size']);
  $img_src = elgg_format_url($img_src);
  $img = "<img $class src=\"$img_src\" alt=\"$title\" style = 'width:130px;height:100px' />";
  if ($url) {
    $params = array(
      'href' => $url,
      'text' => $img,
      'is_trusted' => true,
    );
    if (isset($vars['link_class'])) {
      $params['class'] = $vars['link_class'];
    }
    echo elgg_view('output/url', $params);
  } else {
    echo $img;
  }
