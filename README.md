#PHP 文件管理器 PHP File Manager

---

## 部署方法 Installation

* 获取源码 Getting the project

```shell
git clone https://github.com/Vivi1838/fileManage.git
```

* web服务器虚拟主机 Setting up the webserver

如果在本地部署，修改本地的hosts记录。

例如windows中，编辑....

Ubuntu中编辑/etc/hosts
```shell
sudo nano /etc/hosts
```
加入
```shell
127.0.0.1     filemanager
```


OSX中编辑/etc/hosts
```shell
sudo vim /etc/hosts
```
加入
```shell
127.0.0.1     filemanager
```

如果是从公网访问，将域名解析新的A记录到本地主机的IP地址。

配置好访问域名后，如果使用Apache2.4
```shell
<VirtualHost *:80>
        DocumentRoot "/path/to/fileManage"
        ServerName filemanager
        <Directory "/path/to/fileManage">
                Options FollowSymLinks Multiviews
                MultiviewsMatch Any
                AllowOverride All
                Require all granted
        </Directory>
</VirtualHost>
```
如果有域名，请将ServerName替换为您的域名，不过不推荐直接在公网访问，如果必须公网访问建议添加apache权限验证

Nginx 1.9
```shell
server {
        listen 80;

        root /path/to/fileManage;
        index index.php index.html index.htm;

        server_name filemanager;

        location / { 
                try_files $uri $uri/ /index.php?$args;
        }

        error_page 404 /404.html;

        error_page 500 502 503 504 /50x.html;
        location = /50x.html {
                root /usr/share/nginx/html;
        }
        
        location ~ .php$ {
                try_files $uri =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                #php-fpm配置需要根据本机情况调整
                fastcgi_pass 127.0.0.1:9100;
                fastcgi_index index.php;
                fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
                include fastcgi_params;
        }

        location ~ /\.ht {
                deny all;
        }

        location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
                expires 24h;
                log_not_found off;
        }
}

```

* 程序配置 Setting up the project
