---
title: Events
---

Cookie Notice will emit events when it's initially loaded on a page or where a consent group is consented/revoked by the user. This is the best way of catching whether a user has consented to a specific group or not.

> Note: Your JavaScript will need to come after Cookie Notice's own JavaScript, otherwise it won't work.

## Initial load

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

## Consented to group

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

## Revoked group

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
