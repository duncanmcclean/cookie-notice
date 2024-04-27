---
title: Installation
---

## System Requirements

* PHP 8.1 and above
* Statamic 5
* Laravel 10 and above

## Install

1. Require Cookie Notice as a Composer dependency:

```
composer require duncanmcclean/cookie-notice
```

2. Publish the addon's config file and it's frontend assets (CSS and JavaScript:)

```
php artisan vendor:publish --tag=cookie-notice-config && php artisan vendor:publish --tag=cookie-notice-assets
```

3. Finally, add the Cookie Notice's tags to your layout. The `cookie_notice:scripts` tag should be in the `<head>` and the `cookie_notice` tag should be the top of the `<body>`

```antlers
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ title }} — {{ settings:site_name }}</title>
        <link rel="stylesheet" href="{{ mix src='css/site.css' }}">
        {{ cookie_notice:scripts }} {{# [tl! add] #}}
    </head>
    <body>
        {{ cookie_notice:widget }} {{# [tl! add] #}}
        <script src="{{ mix src='/js/site.js' }}"></script>
    </body>
</html>
```
