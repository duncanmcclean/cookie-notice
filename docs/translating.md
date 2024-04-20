---
title: Translating
---

If you're developing a language other than English, you'll likely want to translate the "strings" in the cookie notice widget.

To do this, you'll need to create a JSON translations file:

1. Create a `lang` directory in the root of your project, if you don't have one already.
2. Create a `{locale}.json` file (obviously replace `{locale}` with your locale)
3. In the file, you may provide key/value pairs for each of the strings you wish to translate:

```json
// lang/de.json

{
  "Accept": "Annehmen"
}
```
