<?php
/**
* JanesWalk NYC - View that exactly replicates the MAS html
*
* @package   JanesWalk
* @author    Joshua Koudys <josh@qaribou.com>
* @license   GPL-2.0+
* @link      http://janeswalk.org
* @copyright Joshua Koudys, Qaribou
*/
?>
<style>
#mas-janeswalk-walklist thead { height:30px; }
#mas-janeswalk-walklist thead tr { font-weight:bold; }
#mas-janeswalk-walklist td { border-bottom:1px solid #bbb; }
#mas-janeswalk-walklist th {
  position:relative; padding-right: 15px; transition-duration:0.2s; -webkit-transition-duration:0.2s; -moz-transition-duration:0.2s; cursor:pointer; background:orange; }
#mas-janeswalk-walklist th:hover { opacity:0.7 }
#mas-janeswalk-walklist th:after { content: '⇳'; display:table; position:absolute; top:0px; right:3px; }
#mas-janeswalk-walklist .sort:after { content: '▾'; }
#mas-janeswalk-walklist .sort.reverse:after { content: '▴'; }
</style>
<table id="mas-janeswalk-walklist" width="950" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th valign="top" width="8%">Date</th>
      <th valign="top" width="8%">Time</th>
      <th style="padding-left: 10px; padding-right: 10px;" valign="top" width="40%">Walk Name</th>
      <th valign="top" width="6%">Borough</th>
      <th valign="top" width="38%">Meeting Place</th>
    </tr>
  </thead>
  <tbody>
<?php
if(!empty($walks)) {
  foreach($walks as $walk) {
    if(isset($walk['slots'])) {
      foreach(array_slice($walk['slots'], 1) as $slot) {
        $walk['schedule'] = $slot['date'];
        $walk['time'] = $slot['time'];
        array_push($walks, $walk);
      }
    }
  }
  usort($walks, function($b,$a) {
    if($a['schedule'] && $b['schedule']) {
      return strtotime("{$b['schedule']} {$b['time']}") - strtotime("{$a['schedule']} {$a['time']}") ?:
        strcmp($b['title'],$a['title']);
    } else {
      if($a['schedule']) {
        return -1;
      } else if($b['schedule']) {
        return 1;
      }
      return 0;
    }
  } );
?>
<?php
  foreach($walks as $key=>$walk) {
    if($walk['schedule']) {
      $date = date('M j, Y', strtotime($walk['schedule']));
    } else {
      $date = "Open";
    }
    $url = $walkpage ? ($walkpage . parse_url($walk['url'],PHP_URL_PATH)) : $walk['url'];
    $first_marker = $walk['map']['markers'][0];
    $meeting = implode(', ', array_filter(array(trim($first_marker['title']), trim($first_marker['description']))));
?>
    <tr data-janeswalk-sort='<?= $key ?>' data-janeswalk-burough='<?= $walk['wards'] ?>' >
      <td valign="top" width="8%"><?=$date?></td>
      <td valign="top" width="8%"><?=$walk['time']?></td>
      <td style="padding-left: 10px; padding-right: 10px;" valign="top" width="40%"><a href="<?=$url?>" ><?=$walk['title']?></a></td>
      <td valign="top" width="6%"><?= $walk['wards'] ?></td>
      <td valign="top" width="38%"><?= $meeting ?: $walk['short_description'] ?></td>
    </tr>
<?php
  } ?>
  </tbody>
</table>
<?php
}
?>

