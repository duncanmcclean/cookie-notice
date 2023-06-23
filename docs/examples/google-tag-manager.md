---
title: "Example: Google Tag Manager"
---

As you're asking for user's consent first, you should only load in Google Tag Manager after the user has consented to it.

The below example demonstrates what this looks like. It's recommended to hook into both the `loaded` event & the `consented` event so GTM is initialised both upon page load & when the user updates their consent.

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

**Note:** In some EU countries (Austria, Holland, France, Italy), Google Tag Manager / Google Analytics has been declared illegal. If you have users in **any** of these countries, you are breaking the law simply by using it. Look into something like [Fathom Analytics](https://usefathom.com/ref/ZBERDK), a privacy-focused analytics service. You won't even need this addon if it's the only third-party script you're using.
