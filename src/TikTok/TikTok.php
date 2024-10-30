<?php

/**
 * Copyright 2024 Justin Stolpe.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace TikTok;

// other classes to use
use TikTok\Request\Request;
use TikTok\Request\Curl;

/**
 * TikTok
 *
 * Core functionality for talking to the TikTok API.
 * Developer Docs: https://developers.tiktok.com/doc/overview/
 *
 * @package     tiktok-api-php-sdk
 * @author      Justin Stolpe
 * @link        https://github.com/jstolpe/tiktok-api-php-sdk
 * @license     https://opensource.org/licenses/MIT
 * @version     1.0
 */
class TikTok {
    /**
     * @const string Default Graph API version for requests.
     */
    const DEFAULT_GRAPH_VERSION = 'v2';

    /**
     * @var string $graphVersion the graph version we want to use.
     */
    protected $graphVersion;

    /**
     * @var object $client the client service.
     */
    protected $client;

    /**
     * @var string $accessToken access token to use with requests.
     */
    protected $accessToken;

    /**
     * @var Request $request the request to the api.
     */
    protected $request = '';

    /**
     * @var string $cursor cursor next for more info
     */
    public $cursorNext = '';

    /**
     * Contructor for instantiating a new TikTok object.
     *
     * @param array $config for the class
     * @return void
     */
    public function __construct( $config ) {
        // set our access token
        $this->setAccessToken( isset( $config['access_token'] ) ? $config['access_token'] : '' );

        // instantiate the client
        $this->client = new Curl();

        // set graph version
        $this->graphVersion = isset( $config['graph_version'] ) ? $config['graph_version'] : self::DEFAULT_GRAPH_VERSION;
    }

    /**
     * Sends a GET request returns the result.
     *
     * @param array $params params for the GET request.
     * @return response.
     */
    public function get( $params ) {
        // check for params
        $endpointParams = isset( $params['params'] ) ? $params['params'] : array();

        // perform GET request
        return $this->sendRequest( Request::METHOD_GET, $params['endpoint'], $endpointParams );
    }

    /**
     * Sends a POST request and returns the result.
     *
     * @param array $params params for the POST request.
     * @return response.
     */
    public function post( $params ) {
        // check for params
        $endpointParams = isset( $params['params'] ) ? $params['params'] : array();

        // perform POST request
        return $this->sendRequest( Request::METHOD_POST, $params['endpoint'], $endpointParams );
    }

    /**
     * Send a custom GET request to the API and returns the result.
     *
     * @param string $customUrl the entire url for the request.
     * @param string $requestType type of request.
     * @param array $headers request headers.
     * @param array $file file being uploaded.
     * @return response.
     */
    public function sendCustomRequest( $customUrl, $requestType = Request::METHOD_GET, $headers = array(), $file = array() ) {
        // create our request
        $this->request = new Request( $requestType );

        // set our custom url for the request
        $this->request->setUrl( $this->graphVersion, $customUrl );

        // set headers
        $this->request->setHeaders( $headers );

        // set files
        $this->request->setFile( $file );

        // return the response
        $response = $this->client->send( $this->request );

        // append the request to the response
        $response['debug'] = $this;

        // return the response
        return $response;
    }

    /**
     * Send a request to the API and returns the result.
     *
     * @param string $method HTTP method.
     * @param string $endpoint endpoint for the request.
     * @param string $params parameters for the endpoint.
     * @return response.
     */
    public function sendRequest( $method, $endpoint, $params ) {
        // create our request
        $this->request = new Request( $method, $endpoint, $params, $this->graphVersion, $this->accessToken );

        // send the request to the client for processing
        $response = $this->client->send( $this->request );

        // set cursors
        $this->setCursors( $response );

        // append the request to the response
        $response['debug'] = $this;

        // return the response
        return $response;
    }

    /**
     * Set the access token.
     *
     * @param string $accessToken set the access token.
     * @return void.
     */
    public function setAccessToken( $accessToken ) {
        $this->accessToken = $accessToken;
    }

    /**
     * Set cursor.
     *
     * @param array &$response response from the api.
     * @return void.
     */
    public function setCursors( &$response ) {
        if ( !empty( $response['data']['has_more'] ) ) { // check for has more
            $this->cursorNext = $response['cursor_next'] = $response['data']['cursor'];
        }
    }
}

?>