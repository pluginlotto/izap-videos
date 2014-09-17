<?php
class IzapCurl {

  /**
   * The file to read and write cookies to for requests
   *
   * @var string
   * */
  public $cookie_file;

  /**
   * Determines whether or not requests should follow redirects
   *
   * @var boolean
   * */
  public $follow_redirects = true;

  /**
   * An associative array of headers to send along with requests
   *
   * @var array
   * */
  public $headers = array();

  /**
   * An associative array of CURLOPT options to send along with requests
   *
   * @var array
   * */
  public $options = array();

  /**
   * The referer header to send along with requests
   *
   * @var string
   * */
  public $referer;

  /**
   * The user agent to send along with requests
   *
   * @var string
   * */
  public $user_agent;

  /**
   * Stores an error string for the last request if one occurred
   *
   * @var string
   * @access protected
   * */
  protected $error = '';

  /**
   * Stores resource handle for the current CURL request
   *
   * @var resource
   * @access protected
   * */
  protected $request;

  /**
   * Stores auth information
   * 
   * @var array
   * @access private
   */
  private $auth = false;

  /**
   * Initializes a Curl object
   *
   * Sets the $cookie_file to "curl_cookie.txt" in the current directory
   * */
  function __construct($auth = false) {
    $this->cookie_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'curl_cookie.txt';
    $this->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Curl/PHP ' . PHP_VERSION . ' (http://www.pluginlotto.com,http://github.com/pluginlotto/izap-videos)';
    $auth = array('username' => '', 'password' => '');
    if (!$auth) {
      // auth array has username, password
      if(!($auth['username'] && $auth['password'])){
        throw new Exception('Username and Password parameters are required for authentication.');
      }
      $this->auth = array('username' => $auth['username'], 'password' => $auth['password']);
    }
  }

  /**
   * Makes an HTTP DELETE request to the specified $url with an optional array or string of $vars
   *
   * Returns a IzapCurlResponse object if the request was successful, false otherwise
   *
   * @param string $url
   * @param array|string $vars 
   * @return IzapCurlResponse object
   * */
  function delete($url, $vars = array()) {
    return $this->request('DELETE', $url, $vars);
  }

  /**
   * Returns the error string of the current request if one occurred
   *
   * @return string
   * */
  function error() {
    return $this->error;
  }

  /**
   * Makes an HTTP GET request to the specified $url with an optional array or string of $vars
   *
   * Returns a IzapCurlResponse object if the request was successful, false otherwise
   *
   * @param string $url
   * @param array|string $vars 
   * @return IzapCurlResponse
   * */
  function get($url, $vars = array()) {
    if (!empty($vars)) {
      $url .= (stripos($url, '?') !== false) ? '&' : '?';
      $url .= (is_string($vars)) ? $vars : http_build_query($vars, '', '&');
    }
    return $this->request('GET', $url);
  }

  /**
   * Makes an HTTP HEAD request to the specified $url with an optional array or string of $vars
   *
   * Returns a IzapCurlResponse object if the request was successful, false otherwise
   *
   * @param string $url
   * @param array|string $vars
   * @return IzapCurlResponse
   * */
  function head($url, $vars = array()) {
    return $this->request('HEAD', $url, $vars);
  }

  /**
   * Makes an HTTP POST request to the specified $url with an optional array or string of $vars
   *
   * @param string $url
   * @param array|string $vars 
   * @return IzapCurlResponse|boolean
   * */
  function post($url, $vars = array()) {
    return $this->request('POST', $url, $vars);
  }

  /**
   * Makes an HTTP PUT request to the specified $url with an optional array or string of $vars
   *
   * Returns a IzapCurlResponse object if the request was successful, false otherwise
   *
   * @param string $url
   * @param array|string $vars 
   * @return IzapCurlResponse|boolean
   * */
  function put($url, $vars = array()) {
    return $this->request('PUT', $url, $vars);
  }

  /**
   * Makes an HTTP request of the specified $method to a $url with an optional array or string of $vars
   *
   * Returns a IzapCurlResponse object if the request was successful, false otherwise
   *
   * @param string $method
   * @param string $url
   * @param array|string $vars
   * @return IzapCurlResponse|boolean
   * */
  function request($method, $url, $vars = array(), $auth = false) {
    $this->error = '';
    $this->request = curl_init();
    if (is_array($vars))
      $vars = http_build_query($vars, '', '&');

    $this->set_request_method($method);
    $this->set_request_options($url, $vars);
    $this->set_request_headers();

    $response = curl_exec($this->request);

    if ($response) {
      $response = new IzapCurlResponse($response, isset($vars['json']) ? $vars['json'] : false);
    } else {
      $this->error = curl_errno($this->request) . ' - ' . curl_error($this->request);
    }

    curl_close($this->request);

    return $response;
  }

  /**
   * Formats and adds custom headers to the current request
   *
   * @return void
   * @access protected
   * */
  protected function set_request_headers() {
    $headers = array();
    foreach ($this->headers as $key => $value) {
      $headers[] = $key . ': ' . $value;
    }
    curl_setopt($this->request, CURLOPT_HTTPHEADER, $headers);
  }

  /**
   * Set the associated CURL options for a request method
   *
   * @param string $method
   * @return void
   * @access protected
   * */
  protected function set_request_method($method) {
    switch (strtoupper($method)) {
      case 'HEAD':
        curl_setopt($this->request, CURLOPT_NOBODY, true);
        break;
      case 'GET':
        curl_setopt($this->request, CURLOPT_HTTPGET, true);
        break;
      case 'POST':
        curl_setopt($this->request, CURLOPT_POST, true);
        break;
      default:
        curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, $method);
    }
  }

  /**
   * Sets the CURLOPT options for the current request
   *
   * @param string $url
   * @param string $vars
   * @param array|false $auth will contains username,password for basic auth
   * @return void
   * @access protected
   * */
  protected function set_request_options($url, $vars) {
    curl_setopt($this->request, CURLOPT_URL, $url);
    if (!empty($vars))
      curl_setopt($this->request, CURLOPT_POSTFIELDS, $vars);

    # Set some default CURL options
    curl_setopt($this->request, CURLOPT_HEADER, true);
    curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->request, CURLOPT_USERAGENT, $this->user_agent);

    if ($this->auth['username'] && $this->auth['password']) {
      curl_setopt($this->request, CURLOPT_USERPWD, "{$this->auth['username']}:{$this->auth['password']}");
      curl_setopt($s, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    }

    if ($this->cookie_file) {
      curl_setopt($this->request, CURLOPT_COOKIEFILE, $this->cookie_file);
      curl_setopt($this->request, CURLOPT_COOKIEJAR, $this->cookie_file);
    }
    if ($this->follow_redirects)
      curl_setopt($this->request, CURLOPT_FOLLOWLOCATION, true);
    if ($this->referer)
      curl_setopt($this->request, CURLOPT_REFERER, $this->referer);

    # Set any custom CURL options
    foreach ($this->options as $option => $value) {
      curl_setopt($this->request, constant('CURLOPT_' . str_replace('CURLOPT_', '', strtoupper($option))), $value);
    }
  }

}
