<?php
/**
 * View of a City
 *
 * A City - basically a list of walks for that city. City details are better
 * left up to the WordPress page content itself.
 *
 * @package   janeswalk
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2014 Joshua Koudys, Qaribou
 */

$renders = array(
	'title' => function($args) {
	}
);

// Output the rendered content
// TODO: move this into controller-logic
return implode(
	'',
	array_map(
		function($section) use ($args) {
			return $section($args);
		},
		$show
	)
);

$return = "";
foreach ($show as $section) {
	switch ($section) {
	case 'title':
?>
  <?php if($url) { ?><a href='<?php echo $url ?>'><?php } ?>
	<h2 class='janeswalk-widget-title'><?php echo $title ?></h2>
  <?php if($url) { ?></a> <?php } ?>
<?php
		break;
	case 'shortdescription':
?>
  <div class='janeswalk-widget-shortdescription'><?php echo $short_description ?></div>
<?php
		break;
	case 'longdescription':
?>
  <div class='janeswalk-widget-longdescription'><?php echo $long_description ?></div>
<?php
		break;
	case 'cityorganizer':
?>
  <p class='janeswalk-widget-cityorganizer'><?php echo $city_organizer['first_name'] ?> <?php echo $city_organizer['last_name'] ?></p>
<?php
		break;
	default:
		break;
	}
}
if(!empty($walks)) {
	foreach($walks as $walk) {
		foreach($show as $section) {
			switch($section) {
			case "walktitle":
?>
  <h3><?php echo ($walk['url'] ? "<a href='{$walk['url']}'>":'') ?><?php echo $walk['title'] ?><?php echo ($walk['url'] ?'</a>':'') ?></h3>
<?php
				break;
			case "date":
				$scheduled = $walk->time;
				$slots = (Array) $scheduled->slots; 
				if($scheduled->open) { ?>
  <h4 class="available-time"><i class="icon-calendar"></i> Open schedule</h4>
<?php
				} else if(isset($slots[0]['date'])) {
?>
  <h4 class='available-time'><i class='icon-calendar'></i> Next available day: <span class='highlight'><?php echo $slots[0]["date"] ?></span></h4>
<?php
				}
				break;
			case "leaders":
?>
  <h5><?php echo $walk->team ?></h5>
<?php
				break;
			case "description":
				// Load up the return with the data from the walk
?>
  <p style='font-size:1.2em' class='janeswalk-widget-shortdescription'><?php echo $walk['short_description'] ?></p>
  <p style='font-size:1.2em' class='janeswalk-widget-longdescription'><?php echo $walk['long_description'] ?></p>
<?php
				break; 
			default:
				break;
			}
		}
	}
}
?>

