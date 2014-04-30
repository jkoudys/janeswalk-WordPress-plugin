<?php 
/**
 * @package Helpers
 * @author Joshua Koudys <jkoudys@gmail.com>
 * @copyright  Copyright (c) 2013
 * @license    http://www.concrete5.org/license/     MIT License
 * Not quite true to its name, this helper is for modelling 'select' type attribute options, including theme and accessible
 */

class JanesWalk_ThemeHelper { 
  private $attributeNameMap;
  private $attributeIconMap;

  public function __construct() {
    $this->attributeIconMap = array(
      'nature-naturelover' => "<i class='icon-bug'></i>",
      'nature-greenthumb' => "<i class='icon-leaf'></i>",
      'nature-petlover' => "<i class='icon-heart'></i>",
      'urban-suburbanexplorer' => "<i class='icon-home'></i>",
      'urban-architecturalenthusiast' => "<i class='icon-building'></i>",
      'urban-moversandshakers' => "<i class='icon-truck'></i>",
      'culture-historybuff' => "<i class='icon-archive'></i>",
      'culture-artist' => "<i class='icon-picture'></i>",
      'culture-aesthete' => "<i class='icon-pencil'></i>",
      'culture-bookworm' => "<i class='icon-book'></i>",
      'culture-foodie' => "<i class='icon-food'></i>",
      'culture-nightowl' => "<i class='icon-glass'></i>",
      'culture-techie' => "<i class='icon-gears'></i>",
      'culture-writer' => "<i class='icon-edit'></i>",
      'civic-activist' => "<i class='icon-bullhorn'></i>",
      'civic-truecitizen' => "<i class='icon-flag-alt'></i>",
      'civic-goodneighbour' => "<i class='icon-group'></i>",

      'urban-sports' => "<i class='icon-trophy'></i>",
      'urban-play' => "<i class='icon-puzzle-piece'></i>",
      'urban-water' => "<i class='icon-tint'></i>",
      'urban-film' => "<i class='icon-facetime-video'></i>",
      'urban-music' => "<i class='icon-music'></i>",
      'civic-international' => "<i class='icon-globe'></i>",
      'civic-military' => "<i class='icon-fighter-jet'></i>",
      'civic-commerce' => "<i class='icon-shopping-cart'></i>",
      'civic-religion' => "<i class='icon-bell'></i>",
      'civic-health' => "<i class='icon-medkit'></i>",
      'civic-nativeissues' => "<i class='icon-sun'></i>",
      'civic-gender' => "<i class='icon-unlock-alt'></i>",
    );
    $this->attributeNameMap = array(
      'nature-naturelover' => 'Nature',
      'nature-greenthumb' => 'Gardening',
      'nature-petlover' => 'Animals',
      'urban-suburbanexplorer' => 'Suburbs',
      'urban-architecturalenthusiast' => 'Architecture',
      'urban-moversandshakers' => 'Transportation',
      'culture-historybuff' => 'Heritage',
      'culture-artist' => 'Art',
      'culture-aesthete' => 'Design',
      'culture-bookworm' => 'Literature',
      'culture-foodie' => 'Food',
      'culture-nightowl' => 'Night Life',
      'culture-techie' => 'Technology',
      'culture-writer' => 'Storytelling',
      'civic-activist' => 'Activism',
      'civic-truecitizen' => 'Citizenry',
      'civic-goodneighbour' => 'Community',

      // 
      'urban-sports' => 'Sports',
      'urban-play' => 'Play',
      'urban-film' => 'Film',
      'urban-water' => 'Water',
      'urban-music' => 'Music',
      'civic-international' => 'International Issues',
      'civic-military' => 'Military',
      'civic-commerce' => 'Commerce',
      'civic-religion' => 'Religion',
      'civic-health' => 'Health',
      'civic-nativeissues' => 'Native Issues',
      'civic-gender' => 'Gender',

      // Accessibility
      'familyfriendly' => 'Family friendly',
      'wheelchair' => 'Wheelchair accessible',
      'dogs' => 'Dogs welcome',
      'strollers' => 'Strollers welcome',
      'bicycles' => 'Bicycles welcome',
      'steephills' => 'Steep hills',
      'uneven' => 'Uneven terrain',
      'busy' => 'Busy sidewalks',
      'bicyclesonly' => 'Bicycles only',
      'lowlight' => 'Low light or nighttime',// Does this work?
      'seniors' => 'Senior Friendly',
    );
  }

  public function getAll($type = 'all') {
    if ($type === 'all') {
      return $this->attributeNameMap;
    }
    if ($type === 'tags') {
      $tags = array();
      foreach ($this->attributeNameMap as $key => $tag) {
        if (preg_match('/\-/', $key)) {
          array_push($tags, $tag);
        }
      }
      return $tags;
    }
    if ($type === 'accessibilities') {
      $accessibilities = array();
      foreach ($this->attributeNameMap as $key => $accessibility) {
        if (!preg_match('/\-/', $key)) {
          array_push($accessibilities, $accessibility);
        }
      }
      return $accessibilities;
    }
  }

  /**
   * Looks up the list of options from the DB
   * This is the only place where themes are 'categorized', which is purely for presentation in the walk create form
   *
   * @param string $type Which type of tag to return (e.g. theme, accessible)
   * @return array
   */ 
  public function getSelectOptions($type = 'all') {
    $options = array(); 
    $satc = new SelectAttributeTypeController(AttributeType::getByHandle('select'));

    if($type === 'all' || $type === 'theme') {
      $satc->setAttributeKey(CollectionAttributeKey::getByHandle('theme'));
      $themeAK = CollectionAttributeKey::getByHandle('theme');
      foreach ($satc->getOptions() as $v) {
        $category = $this->getCategory($v->value);
        $options['theme'][$category][] = array(
          'handle' => $v->value,
          'name' => $this->getName($v->value),
        );
      }
    }
    if($type === 'all' || $type === 'accessibile') {
      $satc->setAttributeKey(CollectionAttributeKey::getByHandle('accessible'));
      foreach ($satc->getOptions() as $v) {
        $options['accessible'][] = array('handle' => $v->value, 'name' => $this->getName($v->value));
      }
    }
    return $options;
  }

  public function getName($handle) {
    return $this->attributeNameMap[(string)$handle] ?: (string)$handle;
  }
  public function getIcon($handle) {
    return $this->attributeIconMap[(string)$handle];
  }
}



