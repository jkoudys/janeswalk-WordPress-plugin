<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

?>
<div class="wrap">
  <h2>
    <img src="<?php echo $logo?>" class="icon32" />
    <?php echo esc_html( get_admin_page_title() ); ?>
  </h2>
  <p>You can set certain Pages on your site so that anything in the path after them links to walks on JanesWalk.org. e.g. loading <code><?php echo get_option('home')?>/my-page/canada/toronto/my-walk</code> would show the walk found at <code>http://janeswalk.org/canada/toronto/my-walk/</code> in your page <code><?php echo get_option('home')?>/my-page</code>. Include a Jane's Walk walk (e.g. by shortname <code>[janeswalk]</code>, or add external media on the page/post) with no specific walk set in the link on that page/post, e.g. in <code><?php echo get_option('home')?>/my-page</code>.</p>
<?php if($permalinks_enabled) { ?>
  <form method="post" action="options.php">
    <?php settings_fields($this->plugin_slug) ?>
    <input type="hidden" name="action" value="update" />
    <h3 class="title">City walk-list links are:</h3>
    <table class="form-table">
      <tr>
        <th>
          <label>Page</label>
        </th>
        <td>
          <?php wp_dropdown_pages(array('name'=>'janeswalk_links', 'show_option_none' => 'No page', 'selected' => $janeswalk_links)) ?>
        </td>
      </tr>
    </table>
    <?php submit_button() ?>
  </form>
  <?php } else { ?><p>You must enable <a href="options-permalink.php">permalinks</a> first, before using this feature.</p><?php } ?>
</div>
