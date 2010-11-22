=== Geoposty GeoLocation Widgets, Posts and Redirects ===

Contributors: Mobiah, mcinvale
Tags: location, geolocation, geotargeting, geo, ip geolocation, geolocated content, geotargeted content, geomarketing, geocoding, geographic location, geotargeting radius, localized content, location widgets
Requires at least: 2.7
Tested up to: 3.0.1
Stable tag: 0.9.2

Geoposty determines your visitor's location from their ip address, then displays dynamic content that is specific to their area.

== Description ==

Geoposty shows each of your readers information that is unique to their location and relevant to your site.

First, we combine your user's IP address with the world's most accurate database of geolocation IP information.  Next, we use your custom search term/text/point of interest and combine it with reliable APIs from Google and others.  Mix it all together and you get the most relevant, personalized, location-specific content that ever made a WordPress user happy.

The geolocation data used by this plugin--provided by Quova, Inc.--is free until further notice.  Heavy data usage is subject to capping. 

As of this release, you can insert dynamic content items or shortcodes into pages or posts, and control who sees them based on their location or distance from a location. Additionally, there are four sidebar widgets to choose from, and you can have any number or combination of them active on your site at any time.

Features (pages/posts/widgets):
*Insert local weather
*Insert Google map with custom search and size settings
*Insert Shortcodes: These are geo-ip-specific fields that will dynamically populate accurately for your visitor's location, as indicated by their ip address [1]
*Insert localized content
*Location-based display settings: You can customize who sees what content, based on their actual location (state, city, zip, metro area, etc.)
*Radius-based display settings: You can customize who see what content, based on their distance from a location (address) that you indicate.
*Redirect and display settings: You can set up redirects based on the location of your user.

Additional Widget:
*Businesses: Pick your search term and the number of items and your readers will see relevant local business listings.

A note about best practices: GeoPosty is not for spammers or other unsavory folk. We absolutely do not condone using our product to mislead, misinform, abuse, oppress, or otherwise manipulate users. Please do not use our product to make your site spammy. GeoPosty reserves the right to deny service to anyone.

[1] Shortcodes should NOT be used in page titles because they will break your URLs!

== Screenshots ==

1. Geoposty weather widget

== Installation ==

Step 1: Go to GeoPosty.com and enter your name, email, and the domain where you would like to use GeoPosty.
Step 2: Go to GeoPosty.com/download and download the plugin files.
Step 3: Upload the wp-geoposty folder to the `/wp-content/plugins/` directory
Step 4: Activate the plugin through the 'Plugins' menu in WordPress
Step 3: Enter your API code on the GeoPosty Account page.
Step 4: Manage and customize GeoPosty content from your Widgets section, or use shortcodes in your content


== Frequently Asked Questions ==

Q: Where is my GeoPosty api key?
A: Check your email.  If you do not see it there check your spam filter.  If you do not see it there contact us: support@geoposty.com.

Q: Where is the GeoPosty shortcode editor?
A: It is located in the page and post editor.  Look for the globe icon in the wysiwyg editor.

Q: What does a properly formatted GeoPosty redirect url look like?
A: **For the "Visitors to this url:" box, enter only the portion of the address after your domain name.  For example, to redirect visitors from your http://www.yourdomain.com/about/ page, only enter "/about/" into the "Visitors to this url:" box.  

**For the "will be directed to this url:" box, enter the full URL no matter what domain the destination url is on (e.g. "http://www.yourdomain.com/texas/" or "http://www.volvo-north-america.com")

Q: Can I try out GeoPosty on my local machine?
A: Yes. When installing the geoposty plugin on localhost, you will automatically receive a localhost API test key. When you are ready to move geoposty to your real server, just make sure to reinstall Geoposty on your server to obtain the correct API key for your domain.

== Upgrade Notice ==

Version 0.8 is the first full version to be released.  Anyone who tried to download GeoPosty before 8/9/2010 from the repository would not have succeeded as the first version was pulled from the repository immediately for API retooling and other massive power boosts.  If you are one who tried to download before this date, we are sorry for the inconvenience, but if you are reading this we are so glad you persevered this far!  We hope you enjoy this plugin!!!

== Changelog ==

0.4: 28 May 2010

Initial release

0.8: 25 June 2010

    * updated location of API
    * enabled dynamic content in page/post
    * enabled location-based display
    * enabled radius-based display
    * enabled redirect based on user's location
    * enabled usage stats
    * paypal integration for subscriptions
    * notifications re: conflicts with caching plugins

0.9: September 2010

	* enabled compatibility test to run before activation
	* updated filtering features to allow for excluding locations
	* enhanced city identification mechanism

0.9.1 October 2010
	
	* fixed bug caused by saving blank redirects

0.9.2 November 3, 2010

	* enable testing of plugin on a localhost
	* new help section

== License ==

Licensed under the GNU General Public License
Version 3, 29 June 2007
Full text at: http://www.gnu.org/licenses/gpl.txt


== Contact ==

support@geoposty.com
