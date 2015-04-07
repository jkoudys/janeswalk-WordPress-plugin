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

// Helpers
$th = new JanesWalk_ThemeHelper();

// Use an assoc array of closures
$renders = array(
	'title' => function($args) {
		return '<h2 class="janeswalk-widget-title">' . $args['title'] . '</h2>';
	},
	'date' => function($args) {
		$next = DateTime::createFromFormat('U', $args['time'][0], new DateTimeZone('UTC'));
		return (
			'<h4 class="available-time">' .
			'<i class="icon-calendar"></i> ' . __('Next available day') .
			': <span class="highlight">' . $next->format('M j, Y') . '</span>' .
			'</h4>'
		);
	},
	'leaders' => function($args) {
		return (
			'<h3>' . _n('Walk Leader', 'Walk Leaders', count($walk_leaders)) .
			implode(
				', ',
				array_map(
					function($mem) {
						return trim($mem['name-first'] . ' ' . $mem['name-last']);
					},
					$walk_leaders
				)
			) . '</h3>'
		);
	},
	'themes' => function($args) use ($th) {
		$themes = array_filter(
			$args['checkboxes'],
			function($check) {
				return (substr($check, 0, 6) === 'theme-');
			}
		);
		$lis = implode(
			'',
			array_map(
				function($check) use ($th) {
					return '<li>' . $th->getName(substr($key, 6)) . '</li>';
				},
				$themes
			)
		);
		return (
			'<h4>' . __('Themes') . '</h4>' .
			'<ul class="janeswalk-widget-themes">' . $lis . '</ul>'
		);
	},
	'accessibility' => function($args) use ($th) {
		$access = array_filter(
			$args['checkboxes'],
			function($check) {
				return (substr($check, 0, 11) === 'accessible-');
			}
		);
		$lis = implode(
			'',
			array_map(
				function($check) use ($th) {
					return '<li>' . $th->getName(substr($key, 11)) . '</li>';
				},
				$themes
			)
		);
		return (
			'<h4>' . __('Accessibility') . '</h4>' .
			'<ul class="janeswalk-widget-accessibility">' . $lis . '</ul>'
		);
	},
	'description' => function($args) {
		return (
			'<p style="font-size:1.2em" class="janeswalk-widget-shortdescription">' . $shortdescription . '</p>' .
			'<p>' . $longdescription . '</p>'
		);
	},
	'register' => function($args) {
		if (!empty($args['eventbrite'])) {
			return (
				'<a data-eid="' . $args['eventbrite'] . '" href="http://eventbrite.ca/event/' . $eventbrite . '" id="register-btn" class="btn btn-primary btn-large">' .
				__('Register For This Walk') .
				'</a>'
			);
		}
	}
);

// Output the rendered content
return implode(
	'',
	array_map(
		function($section) use ($args) {
			if ($section instanceof Closure) {
				return $section($args);
			}
		},
		$show
	)
);
