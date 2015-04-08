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

// Grab the args specific to the contained walks
$walk_show = array( 'walkdate', 'walkleaders', 'walkdescription' );
// The parts to show for the walk
$walk_show = array_intersect($walk_show, $show);
// Remove those showing from the top-level $show
$show = array_diff($show, $walk_show);

$renders = array(
	'title' => function($args) {
		return (
			'<a href="' . $args['url'] . '">' .
			'<h2 class="janeswalk-widget-title">' . $args['name'] . '</h2>' .
			'</a>'
		);
	},
	'shortdescription' => function($args) {
		return '<div class="janeswalk-widget-shortdescription">' . $args['shortDescription'] . '</div>';
	},
	'longdescription' => function($args) {
		return '<div class="janeswalk-widget-longdescription">' . $args['longDescription'] . '</div>';
	},
	'cityorganizer' => function($args) {
		if ($args['cityOrganizer']) {
			$name = $args['cityOrganizer']['firstName'] . ' ' . $args['cityOrganizer']['lastName'];
			return '<p class="janeswalk-widget-cityorganizer">' . $name . '</p>';
		}
	},
	'walktitle' => function($args) use ($walk_show) {
		return join(
			'',
			array_map(
				function($walk) use ($args, $walk_show) {
					// Output buffer
					$ob = '';
					$ob .= '<h3><a href="' . $walk['url'] . '">' . $walk['title'] . '</a></h3>';

					if ( in_array('walkdate', $walk_show) ) {
						$time = $walk['time'];
						if ($time) {
							// Format the first start time
							if ( ! empty($time['slots']) ) {
								$dt = DateTime::createFromFormat('U', $time['slots'][0][0], new DateTimeZone('UTC'));
								$ob .= '<h4 class="available-time"><i class="icon-calendar"></i> ' . __('Next available day') . ': ' . $dt->format('M j, Y g:i a') . '</h4>';
							}
						}
					}
					if ( in_array('walkleaders', $walk_show) ) {
						$team_names = array_map(
							function($mem) {
								return $mem['name-first'] . ' ' . $mem['name-last'];
							},
							$walk['team']
						);
						$ob .= '<h5>' . implode(', ', $team_names) . '</h5>';
					}
					if ( in_array('walkdescription', $walk_show) ) {
						$ob .= '<p class="janeswalk-widget-shortdescription">' . $walk['shortDescription'] . '</p>';
						$ob .= '<p class="janeswalk-widget-longdescription">' . $walk['longDescription'] . '</p>';
					}

					return $ob;
				},
				(array) $args['walks']
			)
		);
	}
);

// Clean out any walks that aren't in the future
$args['walks'] = array_filter(
	$args['walks'],
	function($walk) {
		// Check that its time is set and later than 2 days ago
		if ( $walk['time'] && $walk['time']['slots'] && $walk['time']['slots'][0] && $walk['time']['slots'][0][0] > (time() - 2 * 24 * 60 * 60) ) {
			return true;
		} else {
			return false;
		}
	}
);
// Sort by start date
usort(
	$args['walks'],
	function($a, $b) {
		$time_a = $a['time']['slots'][0][0];
		$time_b = $b['time']['slots'][0][0];

		// TODO: replace with spaceships, some day
		if ($time_a === $time_b) {
			return 0;
		} else {
			return $time_a < $time_b ? -1 : 1;
		}
	}
);

// Output the rendered content
// TODO: move this into controller-logic
return implode(
	'',
	array_map(
		function($section) use ($args, $renders) {
			$cb = $renders[ $section ];
			return $cb($args);
		},
		$show
	)
);
