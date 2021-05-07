# 快手扩展包

    基于Laravel开发的快手平台扩展包

## 初始化项目

    //发布配置文件
    php artisan vendor:publish

## 初始化开放平台

    use Codeinfo\LaravelKuaishou\Factory;
    $app = Factory::platform(config('kuaishou.platform'))


## 生成手机二维码 线下物料有效期1年 每次请求重新生成

    $app->oauth->qrcode($scope, $redirect_uri);

### getAccessToken 登陆授权

    $app->oauth->code2AccessToken($code)

### getUserInfo 获取用户信息

    $app->oauth->userInfo($access_token);


