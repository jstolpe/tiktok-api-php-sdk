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
namespace TikTok\Post;

// other classes we need to use
use TikTok\TikTok;
use TikTok\Request\Request;

/**
 * Post
 *
 * Content posting.
 *     - Endpoints: 
 *          - /post/publish/video/init/ POST
 *              - Docs: https://developers.tiktok.com/doc/content-posting-api-reference-direct-post/
 *          - /post/publish/inbox/video/init/ POST
 *              - Docs: https://developers.tiktok.com/doc/content-posting-api-reference-upload-video/
 * 
 * @package     tiktok-api-php-sdk
 * @author      Justin Stolpe
 * @link        https://github.com/jstolpe/tiktok-api-php-sdk
 * @license     https://opensource.org/licenses/MIT
 * @version     1.0
 */
class Post extends TikTok {
    /**
     * @const endpoint for the request.
     */
    const ENDPOINT = 'post/publish';

    /**
     * Contructor for instantiating a new object.
     *
     * @param array $config for the class.
     * @return void
     */
    public function __construct( $config ) {
        // call parent for setup
        parent::__construct( $config );
    }

    /**
     * Create draft video.
     *
     * @param array $params params for the POST request.
     * @return response.
     */
    public function draft( $params = array() ) {
        $postParams = array( // parameters for our endpoint
            'endpoint' => '/' . self::ENDPOINT . '/inbox/video/init/',
            'params' => $params
        );

        // post request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }

    /**
     * Post photos.
     *
     * @param array $params params for the POST request.
     * @return response.
     */
    public function photos( $params = array() ) {
        $postParams = array( // parameters for our endpoint
            'endpoint' => '/' . self::ENDPOINT . '/content/init/',
            'params' => $params
        );

        // post request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }

    /**
     * Publish video.
     *
     * @param array $params params for the POST request.
     * @return response.
     */
    public function publish( $params = array() ) {
        $postParams = array( // parameters for our endpoint
            'endpoint' => '/' . self::ENDPOINT . '/video/init/',
            'params' => $params
        );

        // post request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }

    /**
     * Get creator info.
     *
     * @param array $params params for the POST request.
     * @return response.
     */
    public function queryCreatorInfo( $params = array() ) {
        $postParams = array( // parameters for our endpoint
            'endpoint' => '/' . self::ENDPOINT . '/creator_info/query/',
            'params' => $params
        );

        // post request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }

    /**
     * Publish video.
     *
     * @param array $params params for the POST request.
     * @return response.
     */
    public function fetchStatus( $params = array() ) {
        $postParams = array( // parameters for our endpoint
            'endpoint' => '/' . self::ENDPOINT . '/status/fetch/',
            'params' => $params
        );

        // post request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }

    /**
     * Upload file to TikTok Server.
     *
     * @param string $uploadUrl upload_url from publish response.
     * @param array $file params of the file being uploaded.
     * @return response.
     */
    public function uploadFile( $uploadUrl, $file ) {
        // get file size
        $fileSize = fileSize( $file['path'] );

        $headers = array( // set headers for request
            'Content-Range' => 'bytes 0-' . ( $fileSize - 1 ) . '/' . $fileSize,
            'Content-Length' => $fileSize,
            'Content-Type' => $file['mime_type']
        );

        // make request to the api
        $response = $this->sendCustomRequest( $uploadUrl, Request::METHOD_PUT, $headers, $file );

        // return response
        return $response;
    }
}

?>