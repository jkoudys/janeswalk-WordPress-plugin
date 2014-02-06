<?php
/**
 * View of a Walk
 *
 * A simple list-view of a walk, suitable for most blogs
 *
 * @package   janeswalk
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2014 Joshua Koudys, Qaribou
 */
?>

<?php
foreach(explode(" ", $show) as $section) {
  switch($section) {
  case "title": ?>
  <h2 class='janeswalk-widget-title'><?=$json['title']?></h2>
<?php
    break;
  case "date":
    if(array_key_exists('open',$scheduled) && $scheduled['open']) { ?>
  <h4 class="available-time"><i class="icon-calendar"></i> Open schedule</h4>
<?php
    } else if(isset($slots[0]['date'])) {
?>
  <h4 class='available-time'><i class='icon-calendar'></i> Next available day: <span class='highlight'><?=$slots[0]['date']?></span></h4>
<?php
    }
    break;
  case "leaders": ?>
    <h3><?='Walk Leader' . (sizeof($walk_leaders) === 1 ? ': ' : 's: ') .
      implode(', ', array_map(function($mem){ return "{$mem['name-first']} {$mem['name-last']}"; }, $walk_leaders)); ?></h3>
<?php
    break;
  case "themes": ?>
  <h4>Themes</h4>
    <ul class='janeswalk-widget-themes'>
<?php
    foreach($json['checkboxes'] as $key=>$theme) {
      if(substr($key, 0, 6) == "theme-") {
?>
      <li data-key='$key'><?=$th->getName(substr($key,6))?></li>
<?php
      }
    } ?>
    </ul>
<?php
    break; 
  case "accessibility": ?>
    <h4>Accessibility</h4>
    <ul class='janeswalk-widget-accessibility'>
<?php
    foreach($json['checkboxes'] as $key=>$theme) {
      if(substr($key, 0, 11) == "accessible-") { 
?>
    <li><?=$th->getName(substr($key,11))?></li>
<?php
      }
    }
?>
    </ul>
<?php
    break; 
  case "description":
?>
    <p style='font-size:1.2em' class='janeswalk-widget-shortdescription'><?=$json['shortdescription']?></p><p><?=$json['longdescription']?></p>
<?php
    break; 
  case "register":
    if(!empty($eid)) {
?>
    <a data-eid="<?=$eid?>" href="<?="http://eventbrite.ca/event/" . $eid ?>" id="register-btn" class="btn btn-primary btn-large">Register For This Walk</a>
<?php
    }
    break; 
  default:
?>
    <p>Warning: show '<?=$section?>' not recognized.</p>
<?php
    break;
  }
}
?>
