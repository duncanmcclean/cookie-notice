---
title: Installation
---

First, require Cookie Notice as a Composer dependency:

```
composer require doublethreedigital/cookie-notice
```

Next, publish the configuration file and it's assets (CSS & JavaScript):

```
php artisan vendor:publish --tag=cookie-notice-config && php artisan vendor:publish --tag=cookie-notice-assets
```

And finally, add the tag to your site's layout:

```antlers
{{ cookie_notice }}
```

If you need to move Cookie Notice's JavaScript elsewhere on the page, you can do this:

```antlers
{{ cookie_notice:scripts }}
```

## Overriding the cookie notice

If you wish to have some customization over the cookie notice view, maybe for styling purposes, you can publish the view using the below command:

```
php artisan vendor:publish --tag=cookie-notice-views
```

The view will then be published into your `resources/views/vendor` directory. Inside the view, there's a couple of variables that are available to you:

- `domain`
- `cookie_name`
- `groups`
- `csrf_field`
- `csrf_token`
- `current_date`, `now` and `today`
- `site` for the current site
- `sites` for an array of all sites
- And also any globals you have setup...
