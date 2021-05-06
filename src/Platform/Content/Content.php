<?php

/**
 * This file is part of the Codeinfo\LaravelKuaishou.
 *
 * (c) codeinfo <nanye@codeinfo.cn>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Codeinfo\LaravelKuaishou\Platform\Content;

use Codeinfo\LaravelKuaishou\Kernel\Client;

class Content extends Client
{
    protected $baseUri = 'https://open.kuaishou.com';

    /**
     * 发起上传
     *
     * @param string $access_token
     * @return array
     */
    public function startUpload(string $access_token)
    {
        $query = [
            'app_id' => $this->app['config']['app_id'],
            'access_token' => $access_token,
        ];

        $response = $this->httpPostJson($this->baseUri . '/openapi/photo/start_upload', $query);

        return self::resource($response);
    }

    /**
     * 直接上传
     *
     * @param string $endpoint
     * @param string $upload_token
     * @param string $video_path
     * @return array
     */
    public function uploadVideo(string $endpoint, string $upload_token, string $video_path)
    {
        $query = [
            'upload_token' => $upload_token,
        ];

        $response = $this->httpPostUploadVideo('http://' . $endpoint . '/api/upload/multipart', $query, $video_path);

        return self::resource($response);
    }

    /**
     * 发布视频
     *
     * @param string $access_token
     * @param string $upload_token
     * @param string $cover_path 封面图地址
     * @param string $caption 视频标题
     * @return array
     */
    public function publishVideo(string $access_token, string $upload_token, string $cover_path, string $caption)
    {

        $query = [
            'app_id' => $this->app['config']['app_id'],
            'access_token' => $access_token,
            'upload_token' => $upload_token,
        ];

        $multipart = [
            [
                // 封面图 必填 <10mb
                'name' => 'cover',
                'contents' => fopen($cover_path, 'r'),
            ],
            [
                'name' => 'caption',
                'contents' => $caption,
            ],
        ];

        $response = $this->httpPostMultipart(
            $this->baseUri . '/openapi/photo/publish',
            $query,
            $multipart
        );

        return self::resource($response);
    }

    /**
     * 合并操作发布视频
     *
     * @param string $access_token
     * @param string $video_path
     * @param string $cover_path
     * @param string $caption
     * @return array
     */
    public function publishChannel(string $access_token, string $video_path, string $cover_path, string $caption)
    {
        $start_upload = $this->startUpload($access_token);

        if ($start_upload['result'] != 1) {
            throw new \Exception('发起上传失败');
        }

        $upload_video = $this->uploadVideo($start_upload['endpoint'], $start_upload['upload_token'], $video_path);

        if ($upload_video['result'] === 1) {
            return $this->publishVideo($access_token, $start_upload['upload_token'], $cover_path, $caption);
        } else {
            throw new \Exception('上传视频发生错误');
        }
    }

    /**
     * 查询视频列表
     *
     * @param string $access_token
     * @param string $cursor
     * @param integer $count
     * @return array
     */
    public function photoList(string $access_token, string $cursor = null, int $count = null)
    {
        $query = [
            'app_id' => $this->app['config']['app_id'],
            'access_token' => $access_token,
            'cursor' => $cursor,
            'count' => $count,
        ];

        $response = $this->httpGet($this->baseUri . '/openapi/photo/list', array_filter($query));

        return self::resource($response);
    }

    /**
     * 视频信息查询
     *
     * @param string $access_token
     * @param string $photo_id
     * @return array
     */
    public function photoInfo(string $access_token, string $photo_id)
    {
        $query = [
            'app_id' => $this->app['config']['app_id'],
            'access_token' => $access_token,
            'photo_id' => $photo_id,
        ];

        $response = $this->httpGet($this->baseUri . '/openapi/photo/info', $query);

        return self::resource($response);
    }
}
