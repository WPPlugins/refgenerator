<?php
/*
   Author: Nyamagana Butera Ignace
   Author URI: http://nyams.planbweb.com
   Description: Administrative options for RefGenerator
 */

if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('refgen', 'wp-content/plugins/' .  dirname(plugin_basename(__FILE__)) . '/langs');
}
?>
<div class="wrap">
<?php
//! checking for the right permission
if (function_exists('current_user_can') && !current_user_can('manage_options') ) {
?>
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php _e('RefGenerator Presentation','refgen') ?></h2>
<table class="form-table">
<tr><td><p><?php _e("You do not have the permission rights to edit refgenerator settings",'refgen'); ?></p></td></tr>
</table>
<?php
} else {
	$location = get_option('siteurl') . '/wp-admin/admin.php?page=refgenerator/options-refgenerator.php'; // Form Action URI
	//! delete all the options before deactivating the plugin */
	if (!empty($_POST['remove_plugin'])) {
		$location = get_option('siteurl') . '/wp-admin/plugins.php';
		delete_option('refgen_post_parent_id');
		delete_option('refgen_post_content_tag');
		delete_option('refgen_post_content_class');
		delete_option('refgen_post_links_class');
		delete_option('refgen_method');
		delete_option('refgen_display_settings');
		delete_option('refgen_display_title');
?>
<div id="icon-tools" class="icon32"><br /></div>
<h2><?php _e('RefGenerator Deactivation','refgen') ?></h2>
<form method="get" action="<?php echo $location; ?>">
<table class="form-table">
<tr><td><p><?php _e('You have successfully uninstall RefGenerator settings.','refgen'); ?></p></td></tr>
</table>
<p class="submit"><input type="submit" name="remove_plugin" value="<?php _e('Deinstall RefGenerator', 'refgen') ?> &raquo;" /></p>
</form>
<?php
	} else {
		//check form submission and update options
		if ($_POST['stage']==='process') {
			update_option('refgen_post_parent_id', $_POST['refgen_post_parent_id']);
			update_option('refgen_post_content_tag'  , $_POST['refgen_post_content_tag']);
			update_option('refgen_post_content_class', $_POST['refgen_post_content_class']);
			update_option('refgen_post_links_class', $_POST['refgen_post_links_class']);
			update_option('refgen_method', $_POST['refgen_method']);
			$value = (empty($_POST['refgen_display_reset'])) ? $_POST['refgen_display_settings'] : '';
			update_option('refgen_display_settings',$value);
			update_option('refgen_display_title',$_POST['refgen_display_title']);
		}
		//Get options for form fields
		$refgen_method = stripslashes(get_option('refgen_method'));
		$refgen['display_settings'] = get_option('refgen_display_settings');
?>
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php _e('RefGenerator Presentation','refgen') ?></h2>
<table class="form-table">
<tr>
<td>
<p><?php _e('Welcome to RefGenerator settings center and once again thank you for installing and activating my plugin.RefGenerator will help you provide automatically your post references.','refgen'); ?><br/>
<?php _e('This admin center will help you setup the plugin and check it against your active template ( HTML and CSS files ).','refgen'); ?></p>
<p><?php _e('You may choose between 2 methods to generate the list:', 'refgen' ) ?></p>
<ul>
<li><?php _e("<strong>Simple Mode :</strong> it's the default mode because this one works almost out of the box.<br/><em>Of note,</em> not all the links will be shown ( ie additionals links produce by other plugin may not be shown).",'refgen') ?></li>
<li><?php _e('<strong>Advanced Mode :</strong> if you understand well wordpress template engine and are able update them, this method is for you<br/><em>Of note,</em> all external links will be shown but you need a javascript enable browser to show the final listing.','refgen') ?></li>
</ul>
<p><?php _e("Now that you know everything let's configure the plugin :",'refgen')?></p>
</td>
</tr>
</table>

<div id="icon-plugins" class="icon32"><br /></div>
<h2><?php _e('Set Up RefGenerator','refgen') ?></h2>
<form name="form1" method="post" action="<?php echo $location ?>&amp;updated=true">
<div>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="stage" value="process" />
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
<tr>
<th scope="row" style="vertical-align:top;">
<h3 style="margin:0;"><?php _e('Choose the section title','refgen'); ?></h3>
</th>
<td style="margin-bottom: 0pt; border-bottom-width: 0pt;">
<input type="text" name="refgen_display_title" id="refgen_display_title" value="<?php echo stripslashes(get_option('refgen_display_title')); ?>" size="20" style="width:250px;" />
<em style="font-style:normal;"><?php _e('This title will appear as refgenerator section title in your post.', 'refgen' ) ?></em>
</td>
</tr>
</table>

<table class="form-table">
<tr>
<th scope="row" style="vertical-align:top;"><h3 style="margin:0;"><?php _e('Choose your display method','refgen'); ?></h3></th>
<td>
	<table width="100%" cellspacing="2" cellpadding="5" class="editform">
	<tr valign="top">
	<th colspan="2" scope="row" style="margin-bottom:0; border-bottom-width:0;"><label for="refgen_method"><?php _e('Generated the list using:', 'refgen' ) ?></label>
	<select name="refgen_method" id="refgen_method">
	<option value="php"<?php if($refgen_method!='js'){ echo ' selected="selected"';} ?>><?php _e('the Simple Mode','refgen') ?></option>
	<option value="js"<?php if($refgen_method=='js'){ echo ' selected="selected"';} ?>><?php _e('the Advanced Mode','refgen') ?></option>
	</select>
	</th>
	</tr>
	<tr valign="top">
	<th scope="row" style="margin-bottom:0; border-bottom-width:0; width:50%;"><label for="refgen_post_links_class"><?php _e('CSS class for the references container<sup>*</sup>:', 'refgen' ) ?></label></th>
	<td style="margin-bottom: 0pt; border-bottom-width: 0pt;"><input type="text" name="refgen_post_links_class" id="refgen_post_links_class" value="<?php echo stripslashes(get_option('refgen_post_links_class')); ?>" size="20" /><br/>
	<?php _e("<sup>*</sup> leave this value unchanged if you don't know what to do",'refgen'); ?></td>
	</tr>
	</table>
</td>
</tr>
</table>

<table class="form-table refgenhide">
<tr>
<th scope="row" style="margin-bottom:0; border-bottom-width:0; vertical-align:top;"><h3 style="margin:0"><?php _e('Settings from you template','refgen') ?></h3><em style="text-decoration:none; font-size:.9em; color:red; font-style:normal"><?php _e('Advanced Mode only','refgen'); ?></em></th>
<td style="margin-bottom:0; border-bottom-width:0;">
	<table width="100%" cellspacing="2" cellpadding="5" class="editform">
	<tr valign="top">
	<th scope="row" style="margin-bottom:0; border-bottom-width:0; width:50%;"><label for="refgen_post_parent_id"><?php _e('id attribute for all posts container:', 'refgen' ) ?></label></th>
	<td style="margin-bottom:0; border-bottom-width:0;"><input type="text" name="refgen_post_parent_id" id="refgen_post_parent_id" value="<?php echo stripslashes(get_option('refgen_post_parent_id')) ?>" size="20" /></td>
	</tr>
	<tr valign="top">
	<th scope="row" style="margin-bottom:0; border-bottom-width:0; width:50%;"><label for="refgen_post_content_tag"><?php _e('post content container tag name:', 'refgen' ) ?></label></th>
	<td style="margin-bottom:0; border-bottom-width:0;"><input type="text" name="refgen_post_content_tag" id="refgen_post_content_tag" value="<?php echo stripslashes(get_option('refgen_post_content_tag')); ?>" size="20" /></td>
	</tr>
	<tr valign="top">
	<th scope="row" style="margin-bottom:0; border-bottom-width:0; width:50%;"><label for="refgen_post_content_class"><?php _e('post content container class name:', 'refgen' ) ?></label></th>
	<td style="margin-bottom:0; border-bottom-width:0;"><input type="text" name="refgen_post_content_class" id="refgen_post_content_class" value="<?php echo stripslashes(get_option('refgen_post_content_class')); ?>" size="20" /></td>
	</tr>
	<tr>
	<td colspan="2" style="margin-bottom:0; border-bottom-width:0;"><b><?php _e('If some of these values does not exist for your template, please add them in your template to enable the Advanced Mode.','refgen') ?></b></td>
	</tr>
	</table>
</td>
</tr>
</table>

<table class="form-table">
<tr>
<th scope="row" style="margin-bottom:0; border-bottom-width:0; vertical-align:top;"><h3 style="margin:0"><?php _e('When to display the list','refgen') ?></h3></th>
<td style="margin-bottom:0; border-bottom-width:0;">
<?php _e('RefGenerator list appears at the end of the article. Depending on your theme and audience you may wish to display refgenerator list on :','refgen'); ?><br />
<label><input type="checkbox" name="refgen_display_settings[]" value="is_home"<?php if( is_array($refgen['display_settings']) && in_array('is_home',$refgen['display_settings']) ) echo ' checked="checked"'; ?> /> <?php _e('the home page','refgen'); ?></label><br />
<label><input type="checkbox" name="refgen_display_settings[]" value="is_single"<?php if( is_array($refgen['display_settings']) && in_array('is_single',$refgen['display_settings']) ) echo ' checked="checked"'; ?> /> <?php _e('the single page','refgen'); ?></label><br />
<label><input type="checkbox" name="refgen_display_settings[]" value="is_page"<?php if( is_array($refgen['display_settings']) && in_array('is_page',$refgen['display_settings']) ) echo ' checked="checked"'; ?> /> <?php _e('the static page','refgen'); ?></label><br />
<label><input type="checkbox" name="refgen_display_settings[]" value="is_search"<?php if( is_array($refgen['display_settings']) && in_array('is_search',$refgen['display_settings']) ) echo ' checked="checked"'; ?> /> <?php _e('the search page','refgen'); ?></label><br />
<label><input type="checkbox" name="refgen_display_settings[]" value="is_date"<?php if( is_array($refgen['display_settings']) && in_array('is_date',$refgen['display_settings']) ) echo ' checked="checked"'; ?> /> <?php _e('the archives by date page','refgen'); ?></label><br />
<label><input type="checkbox" name="refgen_display_settings[]" value="is_category"<?php if( is_array($refgen['display_settings']) && in_array('is_category',$refgen['display_settings']) ) echo ' checked="checked"'; ?> /> <?php _e('the category page','refgen'); ?></label><br />
<label><input type="checkbox" name="refgen_display_settings[]" value="is_tag"<?php if( is_array($refgen['display_settings']) && in_array('is_tag',$refgen['display_settings']) ) echo ' checked="checked"'; ?> /> <?php _e('the archives by tag page','refgen'); ?></label><br />
<label><input type="checkbox" name="refgen_display_reset" value="1" id="refgen_reset" />  <?php _e('or on every page','refgen'); ?></label> <strong><?php _e('By choosing this settings you will overide all other settings','refgen'); ?></strong><br />
</td>
</tr>
</table>

<p class="submit">
<input type="hidden" name="page_options" value="refgen_display_settings,refgen_display_reset,refgen_post_parent_id,refgen_post_content_tag,refgen_post_content_class,refgen_method" />
<input type="submit" name="Submit" class="button-primary" value="<?php _e('Update Options', 'refgen') ?> &raquo;" />
</p>
</div>
</form>

<form method="post" action="<?php echo $location; ?>" onsubmit="return confirm('<?php _e('Are you sure you want to\\n- Deinstall RefGenerator\\n- Completely remove all its settings ?','refgen'); ?>');">
<div>
<div id="icon-tools" class="icon32"><br /></div>
<h2><?php _e('RefGenerator Deactivation','refgen') ?></h2>
<table class="form-table">
<tr>
<td style="margin-bottom:0; border-bottom-width:0;">
<strong><?php _e("You will completely remove all RefGenerator settings and be redirected to the plugin interface to de-install RefGenerator from your blog",'refgen'); ?>.</strong>
</td>
</tr>
</table>
<p class="submit"><input type="submit" name="remove_plugin" value="<?php _e('Deinstall RefGenerator', 'refgen') ?> &raquo;" /></p>
</div>
</form>
<?php } 
}
?>
</div>
<div style="clear:both; padding:.3em">&nbsp;</div>
