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

// other classes we need to use
use TikTok\Request\Request;

/**
 * Curl
 *
 * Handle curl functionality for requests.
 * 
 * @package     tiktok-api-php-sdk
 * @author      Justin Stolpe
 * @link        https://github.com/jstolpe/tiktok-api-php-sdk
 * @license     https://opensource.org/licenses/MIT
 * @version     1.0
 */
class Curl {
	/**
     * @var object $curl
     */
    protected $curl;

    /**
     * @var int The curl client error code.
     */
    protected $curlErrorCode = 0;
    
	/**
     * @var string $rawResponse The raw response from the server.
     */
    protected $rawResponse;

    /**
     * Perform a curl call.
     * 
     * @param Request $request
     * @return array The curl response.
     */
    public function send( $request ) {
        $options = array( // curl options for the connection
            CURLOPT_URL => $request->getUrl(),
            CURLOPT_RETURNTRANSFER => true, // Return response as string
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_CAINFO => __DIR__ . '/certs/cacert.pem',
        );

        if ( $request->getMethod() == Request::METHOD_POST ) { // need to add on post fields
            $options[CURLOPT_POSTFIELDS] = $request->getUrlBody();
        }

        if ( $request->getMethod() == Request::METHOD_PUT ) { // needed for put
            $options[CURLOPT_PUT] = true;
        }

        if ( $request->getAccessToken() ) { // pass along access token
            $options[CURLOPT_HTTPHEADER] = array(
                'Authorization: Bearer ' . $request->getAccessToken()
            );
        }

        if ( $request->getHeaders() ) { // we have headers to send
            foreach ( $request->getHeaders() as $headerKey => $headerValue ) { // loop over headers
                // generate header
                $header = $headerKey . ': ' . $headerValue;

                if ( isset( $options[CURLOPT_HTTPHEADER] ) ) { // add on to existing headers
                    $options[CURLOPT_HTTPHEADER][] = $header; 
                } else { // setup new header array
                    $options[CURLOPT_HTTPHEADER] = array(
                        $header
                    );
                }
            }
        }

        if ( $request->getFile() ) { // we have file being uploaded
            // get file info
            $fileInfo = $request->getFile();

            // open the file
            $openFile = fopen( $fileInfo['path'], 'rb' );

            // add file data to curl options
            $options[CURLOPT_INFILE] = $openFile;
            $options[CURLOPT_INFILESIZE] = filesize( $fileInfo['path'] );
        }

        // initialize curl
        $this->curl = curl_init();

        // set the options
        curl_setopt_array( $this->curl, $options );

        // send the request
        $this->rawResponse = curl_exec( $this->curl );

        // close curl connection
        curl_close( $this->curl );

        if ( $request->getFile() ) { // close our file
            fclose( $openFile );
        }

        // return nice json decoded response
        return json_decode( $this->rawResponse, true );
    }
}

?>