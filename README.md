<!-- statamic:hide -->

![Banner](./banner.png)

## Cookie Notice

<!-- /statamic:hide -->

Cookie Notice provides a cookie consent notice to visitors of your Statamic site. It includes the ability to toggle consent groups and by default has a clean [TailwindCSS](https://tailwindcss.com) design.

This repository contains the source code for Cookie Notice. Cookie Notice is a commercial addon, to use it in production, you'll need to [purchase a license](https://statamic.com/cookie-notice).

**Disclaimer:** It's your responsibility (as the license holder) to ensure your cookie notice complies with local cookie laws.

### Cookie consent notification

As you'd expect, this addon gives you a lightweight cookie notification. The code for which is fully customisable to meet the design of your site.

### Consent Groups

Cookie Notice has built-in support for consent groups - allowing your users to consent to specific types of cookies (eg. Required, Statistics, Marketing).

### Initialise code with consent

You may run certain bits of code only if the user has given their consent.

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

## Documentation

Read the documentation over on the [Statamic Marketplace](https://statamic.com/addons/double-three-digital/cookie-notice/docs).

## Commercial addon

Cookie Notice is a commercial addon - you **must purchase a license** via the [Statamic Marketplace](https://statamic.com/addons/double-three-digital/cookie-notice) to use it in a production environment.

## Security

Only the latest version of Cookie Notice (v5.0) will receive security updates if a vulnerability is found. 

If you discover a security vulnerability, please report it to Duncan straight away, [via email](mailto:security@doublethree.digital). Please don't report security issues through GitHub Issues.

## Official Support

If you're in need of some help with Cookie Notice, [send me an email](mailto:help@doublethree.digital) and I'll do my best to help! (I'll usually respond within a day)

<!-- statamic:hide -->

---

<p>
<a href="https://statamic.com"><img src="https://img.shields.io/badge/Statamic-3.0+-FF269E?style=for-the-badge" alt="Compatible with Statamic v3"></a>
<a href="https://packagist.org/packages/doublethreedigital/cookie-notice/stats"><img src="https://img.shields.io/packagist/v/doublethreedigital/cookie-notice?style=for-the-badge" alt="Cookie Notice on Packagist"></a>
</p>

<!-- /statamic:hide -->
