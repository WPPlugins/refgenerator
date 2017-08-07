=== RefGenerator ===
Contributors: nyamsprod
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ishop%40isaro%2erw&item_name=RefGenerator&item_number=Support%20Open%20Source&no_shipTags
Tags: posts,links,plugin
Requires at least: 2.3.3
Tested up to: 2.8.4
Stable tag: trunk

RefGenerator helps you reference all the external links included in your post

== Description ==

Refgenerator is used to insert at the end of any post the list of all external links used in your post. 
The links are sorted in order of appearance. **Refgenerator is a 2 in 1 plugin** in such a way that it can generate the 
list using 2 differents methods (ie you can use mainly PHP or pure Javascript to generate and sort the list).

* [Support](http://nyams.planbweb.com/contact/)
* [Version History](http://nyams.planbweb.com/blog/refgenerator/)
* [Faq](http://nyams.planbweb.com/blog/2008/01/18/refgenerator-mon-premier-plugin-wordpress/)

== Installation ==

1. Unzip into your `/wp-content/plugins/` directory. If you're uploading it make sure to upload the top-level folder.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit your Refgenerator options (*Options - Refgenerator*) for Wordpress 2.3.x, (*Settings - Refgenerator*) for Wordpress 2.5.x

== Frequently Asked Questions ==

Q: Why doesn't it work for me?

A: The problem is with your Wordpress theme. Either the template does not include `get_header()` in its templates pages or the author of the template forgot to include `wp_header()` in the header.php file and/or `wp_footer` in the footer.php of the Wordpress theme. Be sure to look at the default theme, or other quality themes (eg: standards-compliant XHTML and CSS) to see how they work

Q: I'm using the advanced method but it it does not work for me ?

A: To use the advanced method you must have **a well understanding of html and of the Wordpress Loop** to determine the html `class` and `id` attributes to make the plugin works. If you don't know them because you're not familiar with coding and/or debugging html I shall advise you to use the PHP method.