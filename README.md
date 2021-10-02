<!-- statamic:hide -->

![Banner](./banner.png)

## Cookie Notice

<!-- /statamic:hide -->

Cookie Notice provides a cookie consent notice to visitors of your Statamic site. It includes the ability to toggle consent groups and by default has a clean [TailwindCSS](https://tailwindcss.com) design.

This repository contains the source code for Cookie Notice. Cookie Notice is a commercial addon, to use it in production, you'll need to [purchase a license](https://statamic.com/cookie-notice).

**Disclaimer:** It's your responsibility (as the license holder) to ensure your cookie notice complies with local cookie laws.

### Cookie consent notification

As you'd expect, this addon gives you a lightweight cookie notification. The code for which is fully customisable to meet the design of your site.

### Consent Groups

Cookie Notice has built-in support for consent groups - allowing your users to consent to specific types of cookies (eg. Required, Statistics, Marketing).

### Initialise code with consent

You may run certain bits of code only if the user has given their consent.

## Installation

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

## Documentation

### Configuration

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

### Usage

#### Displaying the cookie notice

It's simple! Just add this to your site's layout (or wherever you want to put it)

```handlebars
{{ cookie_notice }}
```

#### Overriding the cookie notice

If you wish to have some customization over the cookie notice view, maybe for styling purposes, you can publish the view using the below command:

```
php artisan vendor:publish --tag=cookie-notice-views
```

The view will then be published into your `resources/views/vendor` directory. Inside the view, there's a couple of variables that are available to you:

* `domain`
* `cookie_name`
* `groups`
* `csrf_field`
* `csrf_token`
* `current_date`, `now` and `today`
* `site` for the current site
* `sites` for an array of all sites
* And also any globals you have setup...

#### If user has given any consent...

If you want to check if the user has given consent to any of your consent groups, you can do this with JavaScript:

```js
if (window.cookieNotice.hasConsent()) {
    // has consented to something
}
```

#### If user has consented for...

You'll want to make sure that you're only running marketing scripts when the user has consented to the Marketing consent group. To check if a user has consented to a particular group, do this:

```js
if (window.cookieNotice.hasConsent('Marketing')) {
    // marketing scripts
}
```

## Commercial addon

Cookie Notice is a commercial addon - you **must purchase a license** via the [Statamic Marketplace](https://statamic.com/addons/double-three-digital/cookie-notice) to use it in a production environment.

## Security

Only the latest version of Cookie Notice (v5.0) will receive security updates if a vulnerability is found. 

If you discover a security vulnerability, please report it to Duncan straight away, [via email](mailto:security@doublethree.digital). Please don't report security issues through GitHub Issues.

## Official Support

If you're in need of some help with Cookie Notice, [send me an email](mailto:help@doublethree.digital) and I'll do my best to help! (I'll usually respond within a day)

<!-- statamic:hide -->

---

<p>
<a href="https://statamic.com"><img src="https://img.shields.io/badge/Statamic-3.0+-FF269E?style=for-the-badge" alt="Compatible with Statamic v3"></a>
<a href="https://packagist.org/packages/doublethreedigital/cookie-notice/stats"><img src="https://img.shields.io/packagist/v/doublethreedigital/cookie-notice?style=for-the-badge" alt="Cookie Notice on Packagist"></a>
</p>

<!-- /statamic:hide -->
