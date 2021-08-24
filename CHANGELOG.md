# Changelog

## Unreleased

## v5.0.7 (2021-08-24)

### What's fixed

* Fixed issue with `hasConsent` if 'cookie notice cookie' does not exist #45

## v5.0.6 (2021-08-20)

### What's fixed

* Fixed TailwindCSS classes being purged incorrectly #44

## v5.0.5 (2021-08-19)

### What's new

* Support for [Statamic 3.2](https://statamic.com/blog/statamic-3.2-beta)

## v5.0.4 (2021-07-11)

### What's fixed

* Fixed bug in new update script I added to display warnings when updating to v5.

## v5.0.3 (2021-07-17)

### What's new

* Added a `z-index` to the cookie notice wrapper (hopefully fixes #41)

## v5.0.4 (2021-07-11)

### What's fixed

* Fixed bug in new update script I added to display warnings when updating to v5.

## v5.0.3 (2021-07-11)

### What's new

* When upgrading to v5, you'll now get warnings about breaking changes if they affect you.

## v5.0.2 (2021-07-11)

### What's fixed

* Fixed issue with CSS not being compiled properly

## v5.0.1 (2021-07-11)

This release resolves an issue in the publish workflow of the previous release. Please review [v5.0.0](https://github.com/doublethreedigital/cookie-notice/releases/tag/v5.0.0) for the changelog.

## v5.0.0 (2021-07-11)

**⚠️ This update contains breaking changes.**

Cookie Notice is now compatible with [Static Caching](https://statamic.dev/static-caching#content)!

### What's new

* Now compatible with static caching #40

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

*Before*
```handlebars
{{ if {cookie_notice:hasConsented} }}
    <!-- has consented to something -->
{{ /if }}

{{ if {cookie_notice:hasConsented group='Marketing'} }}
    <!-- marketing scripts -->
{{ /if }}
```

*Now*
```js
if (window.cookieNotice.hasConsent()) {
    // has consented to something
}

if (window.cookieNotice.hasConsent('Marketing')) {
    // marketing scripts
}
```

## v4.0.1

### What's improved

* The cookie notice will no longer show in Live Preview #42

## v4.0.0

* Refactored view to use Antlers, instead of Blade
* Allow for using globals inside the cookie notice view #27

---

This release *shouldn't* be a breaking change if you're coming from v3. However, I've marked it as such in case there are any unintended side affects.

You should be able to continue using your Blade cookie notice views for as long as you like. Or if you like, you could switch it around for Antlers. It really doesn't matter which one you use.
