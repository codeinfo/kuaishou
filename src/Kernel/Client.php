<?php

/**
 * This file is part of the Codeinfo\LaravelKuaishou.
 *
 * (c) codeinfo <nanye@codeinfo.cn>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Codeinfo\LaravelKuaishou\Kernel;

use Codeinfo\LaravelKuaishou\Kernel\Http\Request;

class Client extends Request
{
    /**
     * ServiceContainer.
     *
     * @var \Codeinfo\LaravelKuaishou\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * Client constructor.
     *
     * @param \Codeinfo\LaravelKuaishou\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        parent::__construct();

        $this->app = $app;
    }

    /**
     * Http Get.
     *
     * @param string $url
     * @param array $query
     * @return
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * Http Post Json.
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function httpPostJson(string $url, array $query = [], array $json = [])
    {
        return $this->request($url, 'POST', [
            'query' => $query,
            'json' => $json,
        ]);
    }

    /**
     * Http get redirect.
     *
     * @param string $url
     * @return
     */
    public function httpGetRedirect(string $url)
    {
        return $this->request($url, 'GET', [
            'allow_redirects' => [
                'track_redirects' => true,
            ],
        ]);
    }

    /**
     * Http Post Upload video for mp4.
     *
     * @param string $url
     * @param array $query
     * @param string $video_path
     * @return
     */
    public function httpPostUpload($url, array $query, string $video_path)
    {
        $options = [
            'query' => $query,
            'multipart' => [
                [
                    'name' => 'video',
                    'contents' => fopen($video_path, 'r'),
                    'headers' => [
                        'Content-Type' => 'video/mp4',
                    ],
                ],
            ],
        ];

        return $this->request($url, 'POST', $options);
    }

    /**
     * Http Post Upload video for mp4.
     *
     * @param string $url
     * @param array $query
     * @param string $video_path
     * @return
     */
    public function httpPostUploadVideo($url, array $query, string $video_path)
    {
        $options = [
            'query' => $query,
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($video_path, 'rb'),
                    'headers' => [
                        'Content-Type' => 'video/mp4',
                    ],
                ],
            ],
        ];

        return $this->request($url, 'POST', $options);
    }

    /**
     * Http Post Multipart
     *
     * @param string $url
     * @param array $query
     * @param string $video_path
     * @return
     */
    public function httpPostMultipart(string $url, array $query, array $multipart)
    {
        $options = [
            'query' => $query,
            'multipart' => $multipart,
        ];

        return $this->request($url, 'POST', $options);
    }

    /**
     * 返回值处理
     *
     * @param Object $response
     * @return Array
     */
    protected static function resource($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
