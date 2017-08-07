<?php
/**
   Plugin Name: RefGenerator
   Plugin URI: http://nyams.planbweb.com/blog/refgenerator/
   Description: RefGenerator is a free Wordpress plugin that automatically list and add all your external references included in your post at the end of its content. This plugin is very simple to <a href="options-general.php?page=refgenerator/options.php">configure.</a>. Happy blogging!!
   Author: Nyamagana Butera Ignace Philippe
   Version: 2.1.1
   Author URI: http://nyams.planbweb.com/
 */
if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('refgen', 'wp-content/plugins/' .  dirname(plugin_basename(__FILE__)) . '/langs');
}
//1 - ACTIVATION FUNCTIONS 

//! add default options when the plugin is activated */
function refgenerator_activation(){
	add_option('refgen_post_parent_id','post');
	add_option('refgen_post_content_tag','div');
	add_option('refgen_post_content_class','post-content');
	add_option('refgen_post_links_class','post-links');
	add_option('refgen_method','php');
	add_option('refgen_display_settings','');
	add_option('refgen_display_title','Post external references');
}
register_activation_hook( __FILE__ , 'refgenerator_activation');

//2 - ADMIN FUNCTIONS 

//! function to call the plugin admin page : options-refgenerator.php
add_action('admin_menu','refgenerator_admin');
function refgenerator_admin() {
    add_options_page('RefGenerator', 'RefGenerator', 'manage_options', 'refgenerator/options-refgenerator.php');
}

add_action('admin_head','refgenerator_admin_js');
function refgenerator_admin_js() {
	wp_enqueue_script('jquery');
?>
<script type="text/javascript">
//<![CDATA[
jQuery(function($){
	$("#refgen_method").change(function(){
		if($(this).val() === 'php'){
			$('.refgenhide').slideUp('slow');
		} else {
			$('.refgenhide').slideDown('slow');
		}
	});
	if ($("#refgen_method").val() === 'php') {
		$(".refgenhide").hide();
	}	
	$(':checkbox').click(function(){
		if ($(this).attr('checked') === true) {
			if ($(this).attr('id') !== 'refgen_reset') {
				$('#refgen_reset').attr('checked', false);	
			} else {
				$(':checkbox').attr('checked', function() {return $(this).is('#refgen_reset')});
			}
		}
	});
});
//]]>
</script>
<?php 
}
//3 - TEMPLATE FUNCTIONS

$refgen['method']             = get_option('refgen_method');
$refgen['post_links_class']   = get_option('refgen_post_links_class');
$refgen['display_settings']   = get_option('refgen_display_settings');
$refgen['display_title']      = get_option('refgen_display_title');
$refgen['post_parent_id']     = get_option('refgen_post_parent_id');
$refgen['post_content_tag']   = get_option('refgen_post_content_tag');
$refgen['post_content_class'] = get_option('refgen_post_content_class');
//! add refgen CSS
function refgenerator_css() {
	$css_dir = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css';
?>
<!-- start RefGenerator  -->
<link type="text/css" media="screen" rel="stylesheet" href="<?php echo $css_dir; ?>/screen.css" />
<link type="text/css" media="print"  rel="stylesheet" href="<?php echo $css_dir; ?>/print.css" />
<?php
}

//!function for the PHP method
function php_simple_refgenerator($content) {
	global $refgen;
	$ref_title  = __('reference #', 'refgen');
	$links      = array();
	$userLinks  = array();
	$index      = 1;
	$references = NULL;
	$url = get_bloginfo('url');
	//The script starts here....
	//we extract all links from the content
	if (preg_match_all('|<a(.*)>|U', $content, $L, PREG_SET_ORDER)) {
		foreach ($L as $o) {
			//if this is a link and it's url does not start with the blog url then it is an external link to the blog
			if (preg_match(', href="(.*?)",i', $o[1], $M) && strpos($M[1], $url) === false) {
				if (!in_array($M[1], $userLinks) && !preg_match(',^(javascript:|mailto:|#),i', $M[1])) { 
					$userLinks[]            = $M[1];
					$links[$index]['href']  = $M[1];
					$links[$index]['title'] = ( preg_match(', title="(.*?)",i', $o[1] , $M ) ) ? $M[1]:NULL;
					$links[$index]['lang']  = ( preg_match(', lang="(.*?)",i', $o[1] , $M ) ) ? $M[1]:NULL;
					$index++;
				}
			}
		}
		//references formatting
		if (count($links) > 0) {
			$res   = array();
			$res[] = "<div class=\"{$refgen['post_links_class']}\">\n<h3>" . $refgen['display_title'] . "</h3>\n<ol>";
			foreach ($links as $k => $v) {
				$lang = (!is_null($v['lang'])) ? ' lang="' . $v['lang'] . '"' : '';
				if (is_null($v['title'])) { 
					$v['title'] = $ref_title.$k;
				}
				$res[] = '<li><a href="' . $v['href'] . '"' . $lang . ' rel="nofollow">' . $v['title'] . '</a><br/><small>' . $v['href'] . "</small></li>";
			}
			$res[] = "</ol>\n</div>\n";
			$references = implode("\n", $res);
		}
	}
	return $content.$references;
}

//! associated function used in the PHP method
function js_simple_refgenerator() {
	global $refgen;
?>
<script type="text/javascript">
//<![CDATA[
jQuery(function($){$("div.<?php echo $refgen['post_links_class']?> a").each(function(){$(this).attr('target','_blank');});});
//]]>
</script>
<!-- end RefGenerator -->
<?php
}

function js_advanced_refgenerator() {
	global $refgen;
?>
<script type="text/javascript">
//<![CDATA[
jQuery(function($){
	var a_title = '<?php echo __('reference #', 'refgen'); ?>';
	$('#<?php echo $refgen['post_parent_id'] . ' ' . $refgen['post_content_tag'] . '.' . $refgen['post_content_class']; ?>').each(function () {
		var i = 1, list = [], result = [];
		$(this).find('a').not('a[@href^="<?php bloginfo('url'); ?>"]').each(function() {
			var a = $(this), href = a.attr('href');
			if (jQuery.inArray(href, result) === -1 && /^(javascript:|mailto:|#)/.test(href) === false) {
				var lang  = (a.attr("lang") !== '') ? 'lang="' + a.attr("lang") + '"' : '';
				var title = (a.attr("title") !== '') ? a.attr("title") : a_title + i;
				list.push('<li><a href="' + href + '"' + lang + ' rel="nofollow">' + title + '<\/a><br/><small>' + href + '<\/small><\/li>\n');
				result.push(href);
				i++;
			}
		});
		if (list.length > 0) {
			list.unshift('<div class="<?php echo $refgen['post_links_class']; ?>">\n<h3><?php echo $refgen['display_title']; ?><\/h3>\n<ol>\n');
			list.push('<\/ol>\n<\/div>\n');
			$(this).append(list.join(''));
		}
	});
	$("div.<?php echo $refgen['post_links_class']?> a").each(function(){$(this).attr('target', '_blank');});
});
//]]>
</script>
<!-- end RefGenerator -->
<?php
}

//! function to call depending on your settings ( The Method and In Which Page to display RefGenerator
function refgenerator() {
	global $refgen;
	$refgen_display = false; //if nothing is set...nothing will be shown
	if (!is_array($refgen['display_settings'])) {
		$refgen_display = true;
	} else {
		foreach ($refgen['display_settings'] as $page ){
			if (function_exists($page) && $page()) { 
				$refgen_display = true; 
				break;
			}
		}
	}
	if ($refgen_display === true) {
		wp_enqueue_script('jquery');
		add_action('wp_head', 'refgenerator_css');
		if ($refgen['method'] === 'php') {
			add_action('wp_head', 'js_simple_refgenerator');
			add_filter('the_content', 'php_simple_refgenerator');
		} else {
			add_action('wp_head', 'js_advanced_refgenerator');
		}
	}
}
add_action('get_header', 'refgenerator');
?>
