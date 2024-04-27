---
title: Customization
---

The Cookie Notice addon was built with customization in mind. Although, a cookie consent widget is provided out-of-the-box, you can also build your own widget without any style limitations.

## Custom Widget

To create your own widget, you'll need to create your own view. Here's the boilerplate you need to build your own widget:

```html
<div id="cookie-notice" class="relative" style="z-index: 999">
    <div class="fixed bottom-2 right-2 bg-white p-4">
        <h2>This site uses cookies.</h2>
        <ul>
            {{ consent_groups }}
                <li>
                    <input type="checkbox" name="group-{{ handle }}">
                    <label for="group-{{ handle }}">{{ name }}</label>
                    {{ if description }}
                        <span>{{ description }}</span>
                    {{ /if }}
                </li>
            {{ /consent_groups }}
        </ul>
        <button type="button" data-save-preferences>{{ trans key="Save Preferences" }}</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.CookieNotice.boot(
            document.getElementById('cookie-notice'),
            JSON.parse('{{ config | to_json }}')
        );
    });
</script>
```

Next, update the value of the `widget_view` config option in your `cookie-notice.php` config file:

```php
/*
|--------------------------------------------------------------------------
| Consent Widget
|--------------------------------------------------------------------------
|
| Out of the box, this addon provides a simple consent widget. However, you're
| free to create your own widget, just specify the view here.
|
*/

'widget_view' => 'components.custom_cookie_consent_widget',
```

## "Update Cookie Preferences" button

Out of the box, there's no way for users to update their preferences after initially providing consent.

However, it's very easy to setup. Simply add the `data-show-cookie-notice-widget` attribute to the element you wish to trigger the widget and it'll show.

```html
<button type="button" data-show-cookie-notice-widget>Update cookie preferences</button>
```
