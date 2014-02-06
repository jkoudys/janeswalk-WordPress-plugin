<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>
<?php
    $return = "";
    foreach($show as $section) {
      switch($section) {
      case 'title':
?>
  <?php if($json['url']) { ?><a href='<?=$json['url']?>'><?php } ?>
    <h2 class='janeswalk-widget-title'><?=$json['title']?></h2>
  <?php if($json['url']) { ?></a> <?php } ?>
<?php
        break;
      case 'shortdescription':
?>
  <div class='janeswalk-widget-shortdescription'><?=$json['short_description']?></div>
<?php
        break;
      case 'longdescription':
?>
  <div class='janeswalk-widget-longdescription'><?=$json['long_description']?></div>
<?php
        break;
      case 'cityorganizer':
?>
  <p class='janeswalk-widget-cityorganizer'><?=$json['city_organizer']['first_name']?> <?=$json['city_organizer']['last_name']?></p>
<?php
        break;
      default:
        break;
      }
    }
    if(!empty($json['walks'])) {
      foreach($json['walks'] as $walk) {
        foreach($show as $section) {
          switch($section) {
          case "walktitle":
?>
  <h3><?=($walk['url'] ? "<a href='{$walk['url']}'>":'')?><?=$walk['title']?><?=($walk['url'] ?'</a>':'')?></h3>
<?php
            break;
          case "date":
            $scheduled = $walk->time;
            $slots = (Array)$scheduled->slots; 
            if($scheduled->open) { ?>
  <h4 class="available-time"><i class="icon-calendar"></i> Open schedule</h4>
<?php
            } else if(isset($slots[0]['date'])) {
?>
  <h4 class='available-time'><i class='icon-calendar'></i> Next available day: <span class='highlight'><?=$slots[0]["date"]?></span></h4>
<?php
            }
            break;
          case "leaders":
?>
  <h5><?=$walk->team?></h5>
<?php
            break;
          case "description":
            // Load up the return with the data from the walk
?>
  <p style='font-size:1.2em' class='janeswalk-widget-shortdescription'><?=$walk['short_description']?></p>
  <p style='font-size:1.2em' class='janeswalk-widget-longdescription'><?=$walk['long_description']?></p>
<?php
            break; 
          default:
            break;
          }
        }
      }
    }
?>

