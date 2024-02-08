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
namespace TikTok\Video;

// other classes we need to use
use TikTok\TikTok;
use TikTok\Request\Params;
use TikTok\Request\Fields;

/**
 * Video
 *
 * Get videos.
 *     - Endpoints: 
 *          - /video/list/ POST
 *              - Docs: https://developers.tiktok.com/doc/tiktok-api-v2-video-list/
 *          - /video/query/ POST
 *              - Docs: https://developers.tiktok.com/doc/tiktok-api-v2-video-query
 * 
 * @package     tiktok-api-php-sdk
 * @author      Justin Stolpe
 * @link        https://github.com/jstolpe/tiktok-api-php-sdk
 * @license     https://opensource.org/licenses/MIT
 * @version     1.0
 */
class Video extends TikTok {
    /**
     * @const endpoint for the request.
     */
    const ENDPOINT = 'video';

    /**
     * @var array $fields a list of all the fields we are requesting to get back.
     */
    protected $fields = array(
        Fields::ID,
        Fields::CREATE_TIME,
        Fields::TITLE,
        Fields::COVER_IMAGE_URL,
        Fields::SHARE_URL,
        Fields::VIDEO_DESCRIPTION,
        Fields::DURATION,
        Fields::HEIGHT,
        Fields::WIDTH,
        Fields::TITLE,
        Fields::EMBED_HTML,
        Fields::EMBED_LINK,
        Fields::LIKE_COUNT,
        Fields::COMMENT_COUNT,
        Fields::SHARE_COUNT,
        Fields::VIEW_COUNT
    );

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
     * Get videos list.
     *
     * @param array $params params for the GET request.
     * @param array $fields fields to get back for the GET request.
     * @return response.
     */
    public function getList( $params = array(), $fields = array() ) {
        // endpoint
        $endpoint = '/' . self::ENDPOINT . '/list/';

        // add on required query params
        $endpoint .= '?' . Params::FIELDS . '=' . Params::commaStringToArray( $fields ? $fields : $this->fields );

        $postParams = array( // parameters for our endpoint
            'endpoint' => $endpoint,
            'params' => $params
        );

        // get request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }

    /**
     * Check videos by ids.
     *
     * @param array $videoIds video ids for the request.
     * @param array $fields fields to get back for the GET request.
     * @return response.
     */
    public function query( $videoIds, $fields = array() ) {
        // endpoint
        $endpoint = '/' . self::ENDPOINT . '/query/';

        // add on required query params
        $endpoint .= '?' . Params::FIELDS . '=' . Params::commaStringToArray( $fields ? $fields : $this->fields );

        $postParams = array( // parameters for our endpoint
            'endpoint' => $endpoint,
            'params' => array(
                Params::FILTERS => json_encode( // need json on the ids
                    array( // ids to look up
                        Params::VIDEO_IDS => $videoIds
                    ) 
                )
            )
        );

        // get request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }
}

?>