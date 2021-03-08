![Screenshot](https://raw.githubusercontent.com/doublethreedigital/cookie-notice/master/screenshot.png)

# Cookie Notice

This addon provides a cookie consent notice to visitors of your Statamic site. It includes the ability to toggle consent groups and by default has a clean [TailwindCSS](https://tailwindcss.com) design.

**Disclaimer:** I'm (Duncan) not responsible if this cookie notice addon isn't compliant with local/international regulations.

Although the code for this addon is open-source, you need to purchase a license from the Statamic Marketplace to use it on [a public domain](https://statamic.dev/licensing#public-domains).

## Installation

1. Install via Composer - `composer require doublethreedigital/cookie-notice`
2. Publish the Cookie Notice config and it's assets - `php artisan vendor:publish --tag=cookie-notice-config && php artisan vendor:publish --tag=cookie-notice-assets`
3. Add the notice to your site's layout `{{ cookie_notice }}`

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

* `cookie_name` defines the name of the cookie you wish to store the users' cookie preferences.
* `groups` is an array of consent groups. Feel free to update them however you want. The key is the name of the group, which will be displayed to the user. `required` defines whether the user is absolutly required to accept cookies for that group. `toggle_by_default` will automatically check the checkbox on the consent notice, however the user will be able to uncheck it if they want.

## Usage

### Displaying the cookie notice

It's simple! Just add this to your site's layout (or wherever you want to put it)

```handlebars
{{ cookie_notice }}
```

### Overriding the cookie notice

If you wish to have some customization over the cookie notice view, maybe for styling purposes, you can publish the view using the below command:

```
php artisan vendor:publish --tag=cookie-notice-views
```

The view will then be published into your `resources/views/vendor` directory. Inside the view, there's a couple of variables that are available to you:

* `endpoint`
* `cookie`
* `has_consented` (alias of `hasConsented`)
* `groups`
* `csrf_field`
* `csrf_token`
* `current_date`, `now` and `today`
* `site` for the current site
* `sites` for an array of all sites
* And also any globals you have setup...

### If user has given any consent...

If you want to check if the user has given consent to any of your consent groups, you can do this:

```handlebars
{{ if {cookie_notice:hasConsented} }}
    <!-- has consented to something -->
{{ /if }}
```

### If user has consented for...

You'll want to make sure that you're only running marketing scripts when the user has consented to the Marketing consent group. To check if a user has consented to a particular group, do this:

```handlebars
{{ if {cookie_notice:hasConsented group='Marketing'} }}
    <!-- marketing scripts -->
{{ /if }}
```

## Support
For developer support or any other questions related to this addon, please [get in touch](mailto:hello@doublethree.digital).
