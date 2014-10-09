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
   * Parses the response from a Curl request into an object containing
   * the response body and an associative array of headers
   *
   * @version 5.0
   * */
  class IzapCurlResponse {

    /**
     * The body of the response without the headers block
     *
     * @var string
     * 
     * @version 5.0
     * */
    public $body = '';

    /**
     * An associative array containing the response's headers
     *
     * @var array
     * 
     * @version 5.0
     * */
    public $headers = array();

    /**
     * Accepts the result of a curl request as a string
     *
     * <code>
     * $response = new CurlResponse(curl_exec($curl_handle));
     * echo $response->body;
     * echo $response->headers['Status'];
     * </code>
     *
     * @param string $response
     * 
     * @version 5.0
     * */
    function __construct($response) {
      # Headers regex
      $pattern = '#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims';

      # Extract headers from response
      preg_match_all($pattern, $response, $matches);
      $headers_string = array_pop($matches[0]);
      $headers = explode("\r\n", str_replace("\r\n\r\n", '', $headers_string));

      # Remove headers from the response body
      $this->body = str_replace($headers_string, '', $response);

      # Extract the version and status from the first header
      $version_and_status = array_shift($headers);
      preg_match('#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#', $version_and_status, $matches);
      $this->headers['Http-Version'] = $matches[1];
      $this->headers['Status-Code'] = $matches[2];
      $this->headers['Status'] = $matches[2] . ' ' . $matches[3];

      # Convert headers into an associative array
      foreach ($headers as $header) {
        preg_match('#(.*?)\:\s(.*)#', $header, $matches);
        $this->headers[$matches[1]] = $matches[2];
      }
    }

    /**
     * Returns the response body
     *
     * <code>
     * $curl = new Curl;
     * $response = $curl->get('google.com');
     * echo $response;  # => echo $response->body;
     * </code>
     *
     * @return string
     * 
     * @version 5.0
     * */
    function __toString() {
      return $this->body;
    }

  }
  