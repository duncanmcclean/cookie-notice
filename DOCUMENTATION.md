## Installation

First, install the package using composer.

```shell script
composer require doublethreedigital/statamic-cookie-notice
```

Then publish the addon's configuration file:

```shell script
php artisan vendor:publish --tag=config
```

Then place the cookie notice tag into your layout.

```antlers
{{ cookie_notice }}
```

## Settings

After you've published the addons configuration file, you should find the Cookie Notice config file at `config/cookie-notice.php`. In here you'll be able to change the way the Cookie Notice addon works.

### Notice Text

```php
/**
    * Notice text
    *
    * This is the text that will be displayed in the cookie notice.
*/

'text' => 'Your experience on this site will be improved by allowing cookies.',
```

If you'd like to provide your own cookie notice text, update the text in this setting.

### Location

```php
/**
    * Location
    *
    * This is the location on the screen where the notice will be displayed.
    * You can either choose from 'top' or 'bottom'.
*/

'location' => 'bottom',
```

You can change the location of the cookie notice to either the `top` or `bottom` of the user's screen.

### Cookie Name

```php
/**
    * Cookie Name
    *
    * This is the name of the cookie that is used to store that the user
    * has accepted the cookie notice.
*/

'cookie_name' => 'cookie_notice',
```

If you'd like to change the cookie name away from `cookie_notice` to whatever you want, here's where you do that.

### Disable Styles

```php
/**
    * Disable default styles
    *
    * This is where you toggle on or off the default cookie notice styles.
*/

'disable_styles' => false,
```

If you would prefer to use your own styles instead of ours, you can change `false` to `true`.
