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
namespace TikTok\Authentication;

// other classes we need to use
use TikTok\TikTok;
use TikTok\Request\Request;
use TikTok\Request\Params;

/**
 * Authentication
 *
 * Perform authentication.
 *     - Endpoints: 
 *          - Authorization
 *              - Docs: https://developers.tiktok.com/doc/login-kit-web/
 *          - /oauth/token/ (user access) POST
 *              - Docs: https://developers.tiktok.com/doc/oauth-user-access-token-management/
 *          - /oauth/revoke/ POST
 *              - Docs: https://developers.tiktok.com/doc/oauth-user-access-token-management/
 *          - /oauth/token/ (client access) POST
 *              - Docs: https://developers.tiktok.com/doc/client-access-token-management/
 * 
 * @package     tiktok-api-php-sdk
 * @author      Justin Stolpe
 * @link        https://github.com/jstolpe/tiktok-api-php-sdk
 * @license     https://opensource.org/licenses/MIT
 * @version     1.0
 */
class Authentication extends TikTok {
    /**
     * @const string grant_type value for authorization_code.
     */
    const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';

    /**
     * @const string grant_type value for client_credentials.
     */
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';

    /**
     * @const string grant_type value for refresh_token.
     */
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

    /**
     * @const string response_type value for code.
     */
    const RESPONSE_TYPE_CODE = 'code';

    /**
     * @var string $clientKey client key to use with requests.
     */
    protected $clientKey;

    /**
     * @var string $clientSecret client secret to use with requests.
     */
    protected $clientSecret;

    /**
     * Contructor for instantiating a new TikTok authentication object.
     *
     * @param array $config for the class.
     *      'client_key'     => string client key for the TikTok app
     *      'client_secret'  => string client secret for the TikTok app
     * @return void
     */
    public function __construct( $config ) {
        // call parent for setup
        parent::__construct( $config );

        // set client key
        $this->setClientKey( $config['client_key'] );

        // set client secret
        $this->setClientSecret( $config['client_secret'] );
    }

    /**
     * Return the client key.
     *
     * @return string
     */
    public function getClientKey() {
        return $this->clientKey;
    }

    /**
     * Return the client secret.
     *
     * @return string
     */
    public function getClientSecret() {
        return $this->clientSecret;
    }

    /**
     * Set the client key.
     *
     * @param string $clientKey set the client key.
     * @return void
     */
    public function setClientKey( $clientKey ) {
        $this->clientKey = $clientKey;
    }

    /**
     * Set the client secret.
     *
     * @param string $clientSecret set the client secret.
     * @return void
     */
    public function setClientSecret( $clientSecret ) {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Get an access token from the authorization code.
     *
     * @param string $authorizationCode code returned by tiktok along with the redirect uri.
     * @param string $redirectUri uri the user gets sent to after authenticating with TikTok must match redirect uri set in your app.
     * @return object
     */
    public function getAccessTokenFromCode( $authorizationCode, $redirectUri ) {
        return $this->post( array( // make request
            'endpoint' => '/oauth/token/',
            'params' => array( // params required to generate the authorization url
                Params::CLIENT_KEY => $this->getClientKey(),
                Params::CLIENT_SECRET => $this->getClientSecret(),
                Params::CODE => $authorizationCode,
                Params::GRANT_TYPE => self::GRANT_TYPE_AUTHORIZATION_CODE,
                Params::REDIRECT_URI => $redirectUri
            )
        ) );
    }

    /**
     * Get the url for a user to prompt them with the authorization dialog.
     *
     * @param string $redirectUri uri the user gets sent to after authenticating with TikTok.
     * @param string $scope string a comma separated string of authorization scope.
     * @param string $state this gets passed back from TikTok when the use authenticates, optional.
     * @return string the full authentication url
     */
    public function getAuthenticationUrl( $redirectUri, $scope, $state = '' ) {
        $params = array( // params required to generate the authorization url
            Params::CLIENT_KEY => $this->getClientKey(),
            Params::RESPONSE_TYPE => self::RESPONSE_TYPE_CODE,
            Params::REDIRECT_URI => $redirectUri,
            Params::SCOPE => Params::commaStringToArray( $scope ),
            Params::STATE => $state
        );

        // return the login dialog url
        return Request::BASE_AUTHORIZATION_URL . '/' . $this->graphVersion . '/auth/authorize/' . '?' . http_build_query( $params );
    }

   /**
     * Get a client access token.
     *
     * @return object
     */
    public function getClientAccessToken() {
        return $this->post( array( // make request
            'endpoint' => '/oauth/token/',
            'params' => array( // params required to generate the authorization url
                Params::CLIENT_KEY => $this->getClientKey(),
                Params::CLIENT_SECRET => $this->getClientSecret(),
                Params::GRANT_TYPE => self::GRANT_TYPE_CLIENT_CREDENTIALS
            )
        ) );
    } 

    /**
     * Refresh an access token.
     *
     * @param string $accessToken token to refresh.
     * @return object
     */
    public function getRefreshAccessToken( $accessToken ) {
        return $this->post( array( // make request
            'endpoint' => '/oauth/token/',
            'params' => array( // params required to generate the authorization url
                Params::CLIENT_KEY => $this->getClientKey(),
                Params::CLIENT_SECRET => $this->getClientSecret(),
                Params::GRANT_TYPE => self::GRANT_TYPE_REFRESH_TOKEN,
                Params::REFRESH_TOKEN => $accessToken
            )
        ) );
    }

    /**
     * Revoke access.
     *
     * @param string $accessToken token to revoke.
     * @return object
     */
    public function revokeAccessToken( $accessToken ) {
        return $this->post( array( // make request
            'endpoint' => '/oauth/revoke/',
            'params' => array( // params required to generate the authorization url
                Params::CLIENT_KEY => $this->getClientKey(),
                Params::CLIENT_SECRET => $this->getClientSecret(),
                Params::TOKEN => $accessToken
            )
        ) );
    }
}

?>