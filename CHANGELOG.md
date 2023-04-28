# Changelog

## Unreleased

## v8.0.0 (2023-04-28)

### What's new

- Cookie Notice v8 now supports Statamic 4 #61
- Now using Vite to build assets internally #60

### Upgrade guide

1. In your site's `composer.json` file, replace `doublethreedigital/cookie-notice` with `duncanmcclean/cookie-notice`
2. Then, change the addon's version constraint to `^8.0`

## v7.1.1 (2023-01-27)

### What's new

- Statamic 3.4 Support

## v7.1.0 (2023-01-07)

### What's improved

- There's been a few small design changes to the notice
- CSS for the notice are now loaded inline, rather than via an external stylesheet (prevents it from not being displayed with some ad-blockers)

### What's fixed

- Fixed a JavaScript issue when loading the notice for the first time
- Fixed an issue where text on 'Manage Cookies' button sometimes wouldn't show
- Fixed an issue where the CSS file in `public/vendor/cookie-notice` wouldn't be included in your site's Git repository

## v7.0.0 (2022-12-29)

The supported versions of PHP/Statamic/Laravel used alongside this addon have changed, the supported versions are now:

- PHP 8.1 & 8.2
- Statamic 3.3
- Laravel 8

## v6.0.2 (2022-10-14)

### What's improved

- You can now click the buttons on the cookie notice widget while the page is still loading #55 #56

## v6.0.1 (2022-10-10)

### What's fixed

- Fixed an issue for new users providing consent

## v6.0.0 (2022-09-26)

### What's new

- Cookie Notice now has events! You can listen to them to detect consent changes, then trigger the relevant third-party scripts. #54

## v5.2.0 (2022-06-15)

### What's new

- The Cookie Notice view is 'translatable' out-of-the-box now #51 #52

## v5.1.0 (2022-02-26)

### What's new

- Statamic 3.3 support

### Breaking changes

- Dropped support for Statamic 3.0 and Statamic 3.1

## v5.0.8 (2022-01-22)

### What's new

- The JavaScript code can be included in a different place than the Notice itself #48 #49

## v5.0.7 (2021-08-24)

### What's fixed

- Fixed issue with `hasConsent` if 'cookie notice cookie' does not exist #45

## v5.0.6 (2021-08-20)

### What's fixed

- Fixed TailwindCSS classes being purged incorrectly #44

## v5.0.5 (2021-08-19)

### What's new

- Support for [Statamic 3.2](https://statamic.com/blog/statamic-3.2-beta)

## v5.0.4 (2021-07-11)

### What's fixed

- Fixed bug in new update script I added to display warnings when updating to v5.

## v5.0.3 (2021-07-17)

### What's new

- Added a `z-index` to the cookie notice wrapper (hopefully fixes #41)

## v5.0.4 (2021-07-11)

### What's fixed

- Fixed bug in new update script I added to display warnings when updating to v5.

## v5.0.3 (2021-07-11)

### What's new

- When upgrading to v5, you'll now get warnings about breaking changes if they affect you.

## v5.0.2 (2021-07-11)

### What's fixed

- Fixed issue with CSS not being compiled properly

## v5.0.1 (2021-07-11)

This release resolves an issue in the publish workflow of the previous release. Please review [v5.0.0](https://github.com/duncanmcclean/cookie-notice/releases/tag/v5.0.0) for the changelog.

## v5.0.0 (2021-07-11)

**⚠️ This update contains breaking changes.**

Cookie Notice is now compatible with [Static Caching](https://statamic.dev/static-caching#content)!

### What's new

- Now compatible with static caching #40

### Breaking changes

**Assets**

All users of this addon will need to re-publish Cookie Notice's assets after updating.

```
php artisan vendor:publish --tag=cookie-notice-assets
```

**Antlers view**

If you've chosen to publish Cookie Notices views so you can edit them (they'll exist in `resources/views/vendor`), you will need to re-publish the views and make your changes again. Changes had to be made to support static caching.

```
php artisan vendor:publish --tag=cookie-notice-views
```

**Checking for consent**

Additionally, if you're checking for a user's consent anywhere, you'll need to do this using JavaScript instead of the Antlers tags.

_Before_

```handlebars
{{ if {cookie_notice:hasConsented} }}
    <!-- has consented to something -->
{{ /if }}

{{ if {cookie_notice:hasConsented group='Marketing'} }}
    <!-- marketing scripts -->
{{ /if }}
```

_Now_

```js
if (window.cookieNotice.hasConsent()) {
  // has consented to something
}

if (window.cookieNotice.hasConsent("Marketing")) {
  // marketing scripts
}
```

## v4.0.1

### What's improved

- The cookie notice will no longer show in Live Preview #42

## v4.0.0

- Refactored view to use Antlers, instead of Blade
- Allow for using globals inside the cookie notice view #27

---

This release _shouldn't_ be a breaking change if you're coming from v3. However, I've marked it as such in case there are any unintended side affects.

You should be able to continue using your Blade cookie notice views for as long as you like. Or if you like, you could switch it around for Antlers. It really doesn't matter which one you use.
