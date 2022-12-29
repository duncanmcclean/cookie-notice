---
title: "Example: Checking consent with Alpine.js"
---

If you're using [Alpine.js](https://alpinejs.dev/) & need to conditionally display elements based on consent status, you may do something like this, using the `x-show` directive:

```html
<div x-show="window.cookieNotice.hasConsent('Marketing')">
  <iframe src="https://youtube.com/embed/xxx">
</div>
```
