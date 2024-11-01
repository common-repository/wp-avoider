=== Plugin Name ===
Contributors: hajonolte
Donate link: https://nolte-imp.de/
Tags: avoid redirects, redirection
Requires at least: 4.6
Tested up to: 5.2
Stable tag: 1.0
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Avoid landing page redirects. This plugin checks which redirections are active and if it finds a double redirection it tries to fix it. Only 4kb in size. 

== Description ==

When optimizing a website, Google� Page Speed Insights or GTMetrix.com can display a poor ranking if there are too many redirects for a URL to the landing page. 

If a visitor enters http://domainname.com in the browser, the visitor is redirected to httpS://domainname.com and finally lands on httpS://www.domainname.com. This double redirection is punished by numerous search engines.

This plugin examines the redirects and then issues a suggestion for a solution. By simply clicking on it, the plugin makes the necessary settings in the .htaccess file - including backup of the existing file.

The plugin itself is only 4kb in size. 



== Installation ==



1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->avoid landing pages redirect screen to configure the plugin



== Frequently Asked Questions ==

= Does this Plugin any filechanges? =

Yes. It changes the .htaccess-file but before doing this it makes a backup.



== Screenshots ==

1. This screen shot shows the demo-webite on https://varvy.com/tools/redirects/ BEFORE installation of WP-AVOIDER
2. WP-AVOIDER investigated the 2 redirects and finally...
3. https://varvy.com/tools/redirects/ shows that everything is fine now.

== Changelog ==

= 1.0 =
* Initial release (October 2019)



