---
title: Translating
---

If you need to, you can translate any of the 'strings' in the cookie notice (like 'Accept' and 'Manage Cookies'). In order to do this, you'll need to create a JSON translations file:

1. In your `lang` folder (or `resources/lang` for some sites), create a `{locale}.json` file (replace `{locale}` with the name of the locale you wish to translate - eg `en.json`)
2. You may then set keys & values to represent the default string and the string of your translation.

```json
// lang/de.json

{
  "Accept": "Annehmen"
}
```
