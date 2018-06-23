 yaf + composer

# mac本地环境搭建：
```
    brew install nginx
    sudo nginx 
    sudo nginx -s stop|reload

    vim /usr/local/etc/nginx/conf.d/xxx.conf

    server {
            listen       80;
            server_name  local.xxxxx.com;
            root /code_dir/public;
            location /{
                index index.php;
                try_files $uri $uri/ /index.php?$query_string;
           }
           location = /favicon.ico {
                log_not_found off;
                access_log off;
           }
            location ~ \.php$ {
                include /usr/local/etc/nginx/fastcgi.conf;
                fastcgi_intercept_errors on;
                fastcgi_pass   127.0.0.1:9000;
            }

        }

    brew install php71 \
    --without-snmp \
    --without-apache \
    --with-debug \
    --with-fpm \
    --with-intl \
    --with-homebrew-curl \
    --with-homebrew-libxslt \
    --with-homebrew-openssl \
    --with-imap \
    --with-mysql \
    --with-tidy

    /usr/local/Cellar/php\@7.1/7.1.18/bin/pecl install yaf

    brew services start|stop|restart


    进入目录下 composer install
```

# 配置获取
```
// 上传目录
$return_string = Util_Conf::get('db.redis.port');
$return_array = Util_Conf::get('db.redis');

```

# 文件上传
```
// 上传目录
$savePath = Util_Upload('upload', 'path');

// 允许的规则
$allowType = getConfig('upload', 'rule');

$result = parent::upload($allowType, $savePath);
```



# 数据加解密

```
$string = '数据加解密';
$crypt = new Util_CryptAES();
$crypt->set_key(Util_Conf::('setting.CryptAES'));
$crypt->require_pkcs5();

// 加密
$crypt_string = $crypt->encrypt($string);

// 解密
$decrypt_string = $crypt->decrypt($crypt_string); 

echo $crypt_string . ' ' . $decrypt_string; // 1MxgJsgKZKXXhTE8msOKpA== 数据加解密

// 此类还可以配合Java来进行加解密，具体链接可参考 http://www.cnblogs.com/yipu/articles/3871576.html
```


# URL 重写

```
public function _initRoute(Yaf_Dispatcher $dispatcher)
{
    $router = $dispatcher->getRouter();
    $router->addRoute('login', new Yaf_Route_Rewrite(
        '/login$',
        array(
            'module'         => 'Index', // 默认的模块可以省略
            'controller'    => 'Public',
            'action'        => 'login'
        )
    ));
}
```

# 日志记录

```

// 直接记录在以日期开头的文件里，如16_08_24.log
Log_Log::info('this is a log', true, true);

// 加上前缀，prefix_16_08_24.log
Log_Log::info('this is a log', true, true, 'prefix');
```

# 邮件发送

```
https://github.com/swiftmailer/swiftmailer
```

# 请求操作

```
https://github.com/rmccue/Requests
       
```
# 参数验证

```
https://github.com/rakit/validation
```
