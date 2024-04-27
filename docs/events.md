---
title: Events
---

Whenever the page loads or the user changes their consent preferences, Cookie Notice will dispatch events so you can run the relevant scripts.

> **Note:**
> Make sure your JavaScript is *after* the `{{ cookie_notice:scripts }}` tag.

## `accepted`
**Dispatched when the user provides their consent to a consent group. Also dispatched when loading the page, with the user's existing preferences.**

```html
<script>
window.CookieNotice.on('accepted', (consentGroup) => {
    //
});
</script>
```

## `declined`
**Dispatched when the user removes their consent from a consent group. Also dispatched when loading the page, with the user's existing preferences.**

```html
<script>
window.CookieNotice.on('declined', (consentGroup) => {
    //
});
</script>
```

## `preferences_updated`
**Dispatched when the user updates their consent preferences.**

```html
<script>
window.CookieNotice.on('preferences_updated', (preferences) => {
    //
});
</script>
```




TODO: find somewhere better to put this

just plop your script in the `<head>` with `type="text/plain"` and `data-consent-group="group-handle"` and it'll get executed when the user provides consent.

```html
<script type="text/plain" data-consent-group="analytics">
    console.log('running analytics');
</script>
```
