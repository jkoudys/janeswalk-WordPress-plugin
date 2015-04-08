<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   janeswalk
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2015 qaribou
 */

?>
<div class="wrap">
	<h2>
		<img src="<?php echo $logo?>" class="icon32" />
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h2>
	<p>You can set certain Pages on your site so that anything in the path after them links to walks on JanesWalk.org. e.g. loading <code><?php echo get_option('home')?>/my-page/canada/toronto/my-walk</code> would show the walk found at <code>http://janeswalk.org/canada/toronto/my-walk/</code> in your page <code><?php echo get_option('home')?>/my-page</code>. Include a Jane's Walk walk (e.g. by shortname <code>[janeswalk]</code>, or add external media on the page) with no specific walk set in the link on that page, e.g. in <code><?php echo get_option('home')?>/my-page</code>.</p>
	<?php if($permalinks_enabled) { ?>
	<form method="post" action="options.php">
		<?php settings_fields($this->plugin_slug) ?>
		<input type="hidden" name="action" value="update" />
		<table class="form-table">
			<tr>
				<th>
					<label>City walk links go to</label>
				</th>
				<td>
					<?php wp_dropdown_pages(array('name'=>'janeswalk_walkpage', 'show_option_none' => 'No page', 'post_status' => 'publish,private', 'selected' => $janeswalk_walkpage)); ?> 
				</td>
			</tr>
			<tr>
				<th>
					<label>Theme</label>
				</th>
				<td>
					<input type="text" name="janeswalk_map_width" value="<?php echo $janeswalk_theme ?>" />
				</td>
			</tr>

			<tr>
				<th>
					<label>Default Map Dimensions</label>
				</th>
				<td>
					<label>Width: <input type="text" name="janeswalk_map_width" value="<?php echo $janeswalk_map_width ?>" /></label>
					<label>Height: <input type="text" name="janeswalk_map_height" value="<?php echo $janeswalk_map_height ?>" /></label>
				</td>
			</tr>
			<tr><th></th><td>e.g.<code>400px</code>, or <code>100%</code></td></tr>
		</table>
		<?php submit_button() ?>
	</form>
	<?php } else { ?><p>You must enable <a href="options-permalink.php">permalinks</a> first, before using this feature.</p><?php } ?>
</div>
