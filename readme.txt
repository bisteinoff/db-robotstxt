=== DB Robots.txt ===
Contributors: Denis Bisteinov
Donate link: http://seogio.ru
Tags: robots, robots.txt, robots txt, robot, crawler, google, yandex, bing, seo, search engings, indexing
Requires at least: 4.0
Tested up to: 6.2
Stable tag: trunk
License: GPL2

DB Robots.txt automatically creates a virtual file robots.txt including special rules for Google and Yandex. You can also add custom rules for Google, Yandex and any other robots or disable Yandex if you don't need it for search engines optimisation.

== Description ==

DB Robots.txt is an easy (i.e. automated) solution to creating and managing a robots.txt file for your site. It is easy to create a robots.txt without FTP access.

If the plugin detects one or several Sitemap XML files it will include them into robots.txt file.

== Installation ==

1. Upload bisteinoff-robots-txt folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy

== Frequently Asked Questions ==

= Will it conflict with any existing robots.txt file? =

If a physical robots.txt file exists on your site, WordPress won't process any request for one, so there will be no conflict.

= Will this work for sub-folder installations of WordPress? =

Out of the box, no. Because WordPress is in a sub-folder, it won't "know" when someone is requesting the robots.txt file which must be at the root of the site.

== Changelog ==

= 3.2 =
* New option to disable the rules for Yandex
* Design of the Settings page in admin panel

= 3.1 =
* New basic regular rules for Googlebot and Yandex
* Now more possibilities to manage your robots.txt: you can add custom rules for Googlebot, Yandex and other User-agents
* More information about your robots.txt on the settings page

= 3.0 =
* Added a settings page in admin panel for custom rules

= 2.3 =
* Tested with WordPress 6.2.
* The code is optimized
* Added the robots directives for new types of images WebP, Avif

= 2.2 =
* Fixed Sitemap option

= 2.1 =
* Tested with WordPress 5.5.
* Added wp-sitemap.xml

= 2.0 =
* Tested with WordPress 5.0.
* The old Host directive is removed, as no longer supported by Yandex.
* The robots directives are improved and updated.
* Added the robots directives, preventing indexind duplicate links with UTM, Openstat, From, GCLID, YCLID, YMCLID links 

= 1.0 =
* Initial release.