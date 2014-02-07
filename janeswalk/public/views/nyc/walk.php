<?php
/**
 * NYC Walks
 *
 * A walk rendered in the same HTML format as the MAS block
 *
 * @package   janeswalk
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2014 Joshua Koudys, Qaribou
 */
?>

<div>
  <h3 style="font-size: 2.3em; letter-spacing: -1px; margin: 5px 0 15px 0; line-height: 1.2em;"><?=$json['title']?></h3>
  <div style="font-size: 1.2em;">
    <p style="margin-right: 20px;">
      <?php // TODO: don't hard-code the janeswalk.org url, so that JW DBs can be setup elsewhere ?>
      <img src="http://janeswalk.org/<?=$json['thumbnail_url']?>" class="attachment-thumbnail wp-post-image">
    </p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Date</span>:</strong> <?=$date?></p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Time</span>:</strong> <?=$time?></p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Walk Host</span>:</strong> <?=implode(', ', $walk_leaders)?></p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Accessibility</span>:</strong> <?=implode(', ', $accessible)?></p>
    <p style="clear: left; margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Description</span>:</strong> <?=$json['longdescription']?></p>
    <p></p>
  </div>
</div>
