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

### Events

Cookie Notice will emit events when it's initially loaded on a page or where a consent group is consented/revoked by the user. This is the best way of catching whether a user has consented to a specific group or not.

> Note: Your JavaScript will need to come after Cookie Notice's own JavaScript, otherwise it won't work.

#### Initial load

The `loaded` event will be emitted once Cookie Notice has loaded. It'll give you an array containing the consent groups the user has consented to. You'll probably only need to check the `slug`:

```html
<script>
  cookieNotice.on('loaded', (groups) => {
      if (groups.find(group) => group.slug === 'group_statistics') {
          // Load Google Analytics... (or something else...)
      }

      if (groups.find(group) => group.slug === 'group_marketing') {
          // Load Facebook Pixel... (or something else...)
      }
  })
</script>
```

#### Consented to group

The `consented` event will be emitted when the user has consented to a consent group. It'll give you an object containing information on the group the user has consented to.

```html
<script>
  cookieNotice.on("consented", (group) => {
    if (group.slug === "group_marketing") {
      // Load Facebook Pixel...
    }

    // ...
  });
</script>
```

#### Revoked group

The `revoked` event will be emitted when the user has revoked consent for a consent group. It'll give you an object containing information on the group the user has revoked.

```html
<script>
  cookieNotice.on("revoked", (group) => {
    if (group.slug === "group_marketing") {
      // Stop Facebook Pixel from tracking the user...
    }

    // ...
  });
</script>
```

### Examples

#### Checking consent with Alpine.js

If you're using [Alpine.js](https://alpinejs.dev/) & need to conditionally display elements based on consent status, you may do something like this, using the `x-show` directive:

```html
<div x-show="window.cookieNotice.hasConsent('Marketing')">
  <iframe src="https://youtube.com/embed/xxx">
</div>
```

#### Google Tag Manager

As you're asking for user's consent first, you should only load in Google Tag Manager after the user has consented to it.

The below example demonstrates what thi looks like. It's recommended to hook into both the `loaded` event & the `consented` event so GTM is initialised both upon page load & when the user updates their consent.

```html
<script
  async
  src="https://www.googletagmanager.com/gtag/js?id=G-SOMETHING"
></script>

<script>
  gtag("js", new Date());
  gtag("config", "G-SOMETHING");

  function enableGoogleTagManager() {
      gtag("consent", "update", { analytics_storage: "granted" });

      let gtmBody = document.createElement("noscript");
      gtmBody.setAttribute("id", "gtm-noscript");

      let gtmIframe = document.createElement("iframe");
      gtmIframe.setAttribute(
        "src",
        "https://www.googletagmanager.com/ns.html?id=GTM-SOMETHING"
      );
      gtmIframe.setAttribute("height", 0);
      gtmIframe.setAttribute("width", 0);
      gtmIframe.setAttribute("style", "display:none;visibility:hidden");

      gtmBody.appendChild(gtmIframe);
      document.body.prepend(gtmBody);
  }

  cookieNotice.on('loaded', (groups) => {
      if (groups.find(group) => group.slug === 'group_statistics') {
          enableGoogleTagManager();
      }
  });

  cookieNotice.on("consented", (group) => {
    if (group.slug === "group_statistics") {
      enableGoogleTagManager();
    }
  });

  cookieNotice.on("revoked", (group) => {
    if (group.slug === "group_statistics") {
      // Revoke Google Tag Manager...
    }
  });
</script>
```

> **Note:** In some EU countries (Austria, Holland, France, Italy), Google Tag Manager / Google Analytics has been declared illegal. If you have users in **any** of these countries, you are breaking the law simply by using it. Look into something like [Fathom Analytics](https://usefathom.com/ref/ZBERDK), a privacy-focused analytics service. You won't even need this addon if it's the only third-party script you're using.

### Translating

If you need to, you can translate any of the 'strings' in the cookie notice (like 'Accept' and 'Manage Cookies'). In order to do this, you'll need to create a JSON translations file:

1. In your `lang` folder (or `resources/lang` for some sites), create a `{locale}.json` file (replace `{locale}` with the name of the locale you wish to translate - eg `en.json`)
2. You may then set keys & values to represent the default string and the string of your translation.

```json
// lang/de.json

{
  "Accept": "Annehmen"
}
```
