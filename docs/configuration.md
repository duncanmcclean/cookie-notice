---
title: Configuration
---

During the installation process, you'll have published a configuration file to `config/cookie-notice.php`. The contents of the file look like this:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cookie
    |--------------------------------------------------------------------------
    |
    | It's ironic, but this addon uses cookies to determine which consent groups
    | a user has consented to. Configure the name & expiry of the cookie here.
    |
    */

    'cookie_name' => 'COOKIE_NOTICE',

    'cookie_expiry' => 14,

    /*
    |--------------------------------------------------------------------------
    | Consent Widget
    |--------------------------------------------------------------------------
    |
    | Out of the box, this addon provides a simple consent widget. However, you're
    | free to create your own widget, just specify the view here.
    |
    */

    'widget_view' => 'cookie-notice::widget',

    /*
    |--------------------------------------------------------------------------
    | Consent Groups
    |--------------------------------------------------------------------------
    |
    | Consent groups allow you to give your users the option to which cookies they'd
    | like to enable and which cookies they'd prefer to keep disabled.
    |
    */

    'consent_groups' => [
        [
            'name' => 'Necessary',
            'handle' => 'necessary',
            'enable_by_default' => true,
        ],
        [
            'name' => 'Analytics',
            'handle' => 'analytics',
            'description' => 'These cookies are used to provide us with analytics on which content visitors read, etc.',
            'enable_by_default' => false,
        ],
    ],

];
```

## `cookie_name`

The `cookie_name` config option determines the name of the cookie used to store the user's cookie preferences.

## `cookie_expiry`

The `cookie_expiry` config option determines how long the user's cookie preferences should be kept for before re-prompting for consent.

The default is 14 days.

## `widget_view`

Out of the box, Cookie Notice ships with a simple cookie consent widget. However, you're more than welcome to create your own consent widget. All you need to do is swap out the view here.

You can find out more about building your own widget on the [Customization](/customization) page.

## `consent_groups`

The `consent_groups` config option is an array containing your consent groups. You should change these as needed.

### Supported config options

* `name` - This name will be displayed to the user.
* `handle` - The handle will be used as a unique identifier for this consent group.
* `description` - This description is optional. It'll be displayed to the user, alongside the name.
* `enable_by_default` - This determines whether the consent group will be enabled by default. The user will still be able to disable it if they wish.
