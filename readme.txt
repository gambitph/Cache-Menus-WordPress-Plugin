=== Plugin Name ===
Contributors: bfintal
Tags: cache, menu, wp_menu, nav_menu, wp_nav_menu, w3, transient, loading speed, optimization, caching
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shave off a few milliseconds on your site loading time

== Description ==

Gambit Cache Menus caches the menus that you use in your front end. This may not be much (an estimate of 18 milliseconds per menu), but if you have large multilevel menus, and a lot of site visitors, then the speed savings may bring a lot of benefits.

Just install and activate the plugin, there are no settings page.

A menu rendered normally takes around 0.02 seconds; activating this plugin reduces this step to 0.002 seconds.

= Features =

* Shaves off 18ms per menu
* Uses the Transient API
* Current menu item highlighting is preserved
* Lightweight
* Cleanly coded plugin

= How Does it Work =

It's all about the Transient API. When a menu is rendered in the front end, the rendered menu is saved as a transient for caching. Future menu renderings use this cached copy afterwards. When the menu is updated, or when menu locations are changed, the transient is forcibly expired to make way for the updated menus.

= This Is Also a Tutorial =

Transients are cool. If you're not familiar with them then we documented the code well so that you can check it out.

There should be a companion tutorial about Transients soon.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit your site and enjoy the wee bit faster page loading time

== Changelog ==

= 1.0 =
First release
