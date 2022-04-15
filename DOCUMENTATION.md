## Configuration

During installation, you'll publish a configuration file for Cookie Notice to `config/cookie-notice.php`. The contents of said file look like this:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cookie
    |--------------------------------------------------------------------------
    |
    | It's ironic, but this addon uses cookies to store if a user has consented
    | to cookies or not, and which ones they've consented to. Don't worry
    | though, no cookie is stored if the user doesn't consent.
    |
    */

    'cookie_name' => 'COOKIE_NOTICE',

    /*
    |--------------------------------------------------------------------------
    | Consent Groups
    |--------------------------------------------------------------------------
    |
    | Consent groups allow you to give your users the option to which cookies they'd
    | like to enable and which cookies they'd prefer to keep disabled.
    |
    */

    'groups' => [
        'Necessary' => [
            'required' => true,
            'toggle_by_default' => true,
        ],
        'Statistics' => [
            'required' => false,
            'toggle_by_default' => false,
        ],
        'Marketing' => [
            'required' => false,
            'toggle_by_default' => false,
        ],
    ],

];
```

- `cookie_name` defines the name of the cookie you wish to store the users' cookie preferences.
- `groups` is an array of consent groups. Feel free to update them however you want. The key is the name of the group, which will be displayed to the user. `required` defines whether the user is absolutly required to accept cookies for that group. `toggle_by_default` will automatically check the checkbox on the consent notice, however the user will be able to uncheck it if they want.

## Usage

### Displaying the cookie notice

It's simple! Just add this to your site's layout:

```antlers
{{ cookie_notice }}
```

If you need to move Cookie Notice's JavaScript elsewhere on the page, you can do this:

```antlers
{{ cookie_notice:scripts }}
```

### Overriding the cookie notice

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

### If user has given any consent...

If you want to check if the user has given consent to any of your consent groups, you can do this with JavaScript:

```js
if (window.cookieNotice.hasConsent()) {
  // has consented to something
}
```

If you're using [Alpine.js](https://alpinejs.dev/), you may conditionally display elements with `x-show`:

```html
<div x-show="window.cookieNotice.hasConsent()">
  <p>Just so you know, you've consented!</p>
</div>
```

### If user has consented for...

You'll want to make sure that you're only running marketing scripts when the user has consented to the Marketing consent group. To check if a user has consented to a particular group, do this:

```js
if (window.cookieNotice.hasConsent("Marketing")) {
  // marketing scripts
}
```

If you're using [Alpine.js](https://alpinejs.dev/), you may conditionally display elements with `x-show`:

```html
<div x-show="window.cookieNotice.hasConsent('Marketing')">
    <iframe src="https://youtube.com/embed/xxx">
</div>
```
