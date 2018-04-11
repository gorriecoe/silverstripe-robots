# Silverstripe Robots.txt generation
This module provides simple robots.txt generation for Silverstripe, with various configuration options available.

When a site is not in live mode (such as on a testing domain) it will respectively block the entire domain, ensuring that (at least respectful) search engines will refrain from indexing your test site.

## Installation
Composer is the recommended way of installing SilverStripe modules.
```
composer require gorriecoe/silverstripe-robots
```

## Requirements

- silverstripe/cms ^4.0

## Maintainers

- [Gorrie Coe](https://github.com/gorriecoe)

## Credit

This module is heavily inspired by [silverstripe-robots](https://github.com/tractorcow/silverstripe-robots) by Damian Mooyman

## Configuration

You can add a page or pattern to be blocked by adding it to the disallowedUrls configuration

```yaml
gorriecoe\Robots\Robots:
  disallowed_urls:
    - 'mysecretpage.html'
    - '_private'
    - 'Documents-and-Settings/Ricky/My-Documents/faxes/sent-faxes'
```

Also by default, any page with 'ShowInSearch' set to false will also be excluded. This
can be useful for hiding auxiliary pages like "thanks for signing up", or error pages.

You can turn this off (if you really absolutely think you need to) using the below.

```yaml
gorriecoe\Robots\Robots:
  disallow_unsearchable: false
```

By default the module will check for a sitemap file in `/sitemap.xml`. You can set a custom file location using the below configuration.

```yaml
gorriecoe\Robots\Robots:
  sitemap: '/sitemap_index.xml'
```
For multiple sitemaps.
```yaml
gorriecoe\Robots\Robots:
  sitemap:
      - '/sitemap_index.xml'
      - 'http://www.gstatic.com/s2/sitemap.xml'
```
