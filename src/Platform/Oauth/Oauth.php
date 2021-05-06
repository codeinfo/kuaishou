<?php

/**
 * This file is part of the Codeinfo\LaravelKuaishou.
 *
 * (c) codeinfo <nanye@codeinfo.cn>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Codeinfo\LaravelKuaishou\Platform\Oauth;

use Codeinfo\LaravelKuaishou\Kernel\Client;

class Oauth extends Client
{
    protected $baseUri = 'https://open.kuaishou.com';

    /**
     * 网页登陆后授权模式
     *
     * @param string $scope
     * @param string $redirect_uri
     * @return string $url
     */
    public function authorize(string $scope, string $redirect_uri = null, string $state = null)
    {
        $query = [
            'app_id' => $this->app['config']['app_id'],
            'scope' => $scope,
            'response_type' => 'code',
            'redirect_uri' => is_null($redirect_uri) ? $this->app['config']['redirect_uri'] : $redirect_uri,
            'state' => $state,
        ];

        $url = $this->baseUri . '/oauth2/authorize?';

        foreach ($query as $key => $value) {
            $url .= '&' . $key . '=' . $value;
        }

        return $url;
    }

    /**
     * 获取用户授权token.
     *
     * @param string $code
     * @return array
     */
    public function code2AccessToken(string $code)
    {
        $query = [
            'app_id' => $this->app['config']['app_id'],
            'app_secret' => $this->app['config']['app_secret'],
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];

        $response = $this->httpGet($this->baseUri . '/oauth2/access_token', $query);

        return self::resource($response);
    }

    /**
     * Refresh Access Token
     *
     * @param string $refresh_token
     * @return array
     */
    public function refreshAccessToken(string $refresh_token)
    {
        $query = [
            'app_id' => $this->app['config']['app_id'],
            'app_secret' => $this->app['config']['app_secret'],
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ];

        $response = $this->httpGet($this->baseUri . '/oauth2/refresh_token', $query);

        return self::resource($response);
    }

    /**
     * GetUserInfo
     *
     * @return array
     */
    public function userInfo(string $access_token)
    {
        $query = [
            'app_id' => $this->app['config']['app_id'],
            'access_token' => $access_token,
        ];

        $response = $this->httpGet($this->baseUri . '/openapi/user_info', $query);

        return self::resource($response);
    }
}
