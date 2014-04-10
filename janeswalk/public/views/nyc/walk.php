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
  <h3 style="font-size: 2.3em; letter-spacing: -1px; margin: 5px 0 15px 0; line-height: 1.2em;"><?=$title?></h3>
  <div style="font-size: 1.2em;">
    <p style="margin-right: 20px;">
      <?php // TODO: don't hard-code the janeswalk.org url, so that JW DBs can be setup elsewhere ?>
      <?php if($thumbnail_url) { ?><img src="http://janeswalk.org/<?=$thumbnail_url?>" class="attachment-thumbnail wp-post-image"><?php } ?>
    </p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;"><?= _n('Date', 'Dates', sizeof($slots)) ?></span>:</strong> <?=$date?></p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;"><?= _n('Time', 'Times', sizeof($slots)) ?></span>:</strong> <?=$time?></p>
    <?php if($wards) { ?>
      <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Borough</span>:</strong> <?= $wards ?></p>
    <?php } ?>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Meeting Place</span>:</strong> <?= $meeting ?></p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Walk Host</span>:</strong> <?=implode(', ', $walk_leaders)?></p>
    <p style="margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Accessibility</span>:</strong> <?=implode(', ', $accessible)?></p>
    <p style="clear: left; margin: 0 0 10px 0;"><strong><span style="text-decoration: underline;">Description</span>:</strong> <?=$longdescription?></p>
    <p></p>
  </div>
</div>
