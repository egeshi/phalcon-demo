# Project Setup

##### Prerequisites: nginx, git, NPM, composer (optional), redis

1. Clone project `$ git clone git@github.com:egeshi/phalcon-demo.git`
2. Install composer locally if none is setup as global as described [here](https://getcomposer.org/download/)
3. Set `PHALCON_ENV` or `APP_ENV` environment variable to `development` value
3. Set `NODE_ENV` environment variable to `development` value
4. `$ mv composer.phar composer`
5. Run `$ ./composer update` to generate autoloader classes, install Bower/NPM dependencies and Phalcon Devtools needed for IDE.

Nginx config

```
server {
    listen      80;
    server_name phalcon.dev.lan;
    root        /srv/phalcon.custom/app/public;
    index       index.php;
    charset     utf-8;

    location / {
        if (-f $request_filename) {
            break;
        }

        if (!-e $request_filename) {
            rewrite ^(.+)$ /index.php?_url=$1 last;
            break;
        }
    }

    location ~ \.php$ {
            try_files $uri =404;
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/phalcon_error.log;
    access_log /var/log/nginx/phalcon.log;
}
```

