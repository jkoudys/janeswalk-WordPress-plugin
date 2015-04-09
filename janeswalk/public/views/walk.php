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
		$next = DateTime::createFromFormat('U', $args['time']['slots'][0][0], new DateTimeZone('UTC'));
		return (
			'<h4 class="available-time">' .
			'<i class="icon-calendar"></i> ' . __('Next available day') .
			': <span class="highlight">' . $next->format('M j, Y g:i a') . '</span>' .
			'</h4>'
		);
	},
	'leaders' => function($args) {
		return (
			'<h3>' . _n('Walk Leader', 'Walk Leaders', count($args['walk_leaders'])) . ': ' .
			implode(
				', ',
                $args['walk_leaders']
			) . '</h3>'
		);
	},
	'themes' => function($args) use ($th) {
		$lis = implode(
			'',
			array_map(
				function($check) use ($th) {
					return '<li>' . $check . '</li>';
				},
				$args['themes']
			)
		);
		return (
			'<h4>' . __('Themes') . '</h4>' .
			'<ul class="janeswalk-widget-themes">' . $lis . '</ul>'
		);
	},
	'accessibility' => function($args) use ($th) {
		$lis = implode(
			'',
			array_map(
				function($check) {
					return '<li>' . $check . '</li>';
				},
				$args['accessible']
			)
		);
		return (
			'<h4>' . __('Accessibility') . '</h4>' .
			'<ul class="janeswalk-widget-accessibility">' . $lis . '</ul>'
		);
	},
	'description' => function($args) {
		return (
			'<p style="font-size:1.2em" class="janeswalk-widget-shortdescription">' . $args['shortDescription'] . '</p>' .
			'<p>' . $args['longDescription'] . '</p>'
		);
	},
	'register' => function($args) {
		if ( ! empty($args['eventbrite']) ) {
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
		function($section) use ($args, $renders) {
			$cb = $renders[$section];
			if ( $cb instanceof Closure ) {
				return $cb($args);
			}
		},
		$show
	)
);
