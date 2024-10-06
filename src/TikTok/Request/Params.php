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
namespace TikTok\Request;

/**
 * Params
 *
 * Functionality and defines for query parameters.
 *
 * @package     tiktok-api-php-sdk
 * @author      Justin Stolpe
 * @link        https://github.com/jstolpe/tiktok-api-php-sdk
 * @license     https://opensource.org/licenses/MIT
 * @version     1.0
 */
class Params {
    /**
     * @const strings of the query parameters.
     */
    const CLIENT_KEY = 'client_key';
    const CLIENT_SECRET = 'client_secret';
    const CODE = 'code';
    const CODE_CHALLENGE = 'code_challenge';
    const GRANT_TYPE = 'grant_type';
    const FIELDS = 'fields'; 
    const FILTERS = 'filters';
    const REDIRECT_URI = 'redirect_uri';
    const RESPONSE_TYPE = 'response_type';
    const SCOPE = 'scope';
    const STATE = 'state';
    const REFRESH_TOKEN = 'refresh_token';
    const VIDEO_IDS = 'video_ids';

    /**
     * Get fields for a request.
     * 
     * @param array $fields list of fields for the request.
     * @return array fields array with comma separated string.
     */
    public static function getFieldsParam( $fields ) {
        return array( // return fields param
            self::FIELDS => self::commaStringToArray( $fields )
        );
    }

    /**
     * Turn array into a comma separated string.
     * 
     * @param array $array elements to be comma separated.
     * @return string comma separated list of fields.
     */
    public static function commaStringToArray( $array = array() ) {
        // imploded string on commas and return
        return implode( ',', $array );
    }
}

?>