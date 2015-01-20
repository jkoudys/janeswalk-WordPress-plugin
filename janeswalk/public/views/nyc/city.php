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

if (!empty($walks)) {
    foreach ($walks as $walk) {
        if (isset($walk['slots'])) {
            foreach (array_slice($walk['slots'], 1) as $slot) {
                $walk['schedule'] = $slot['date'];
                $walk['time'] = $slot['time'];
                array_push($walks, $walk);
            }
        }
    }

    // Sort our list of walks for the table
    usort(
        $walks,
        function($b, $a) {
            if ($a['schedule'] && $b['schedule']) {
                // Compare scheduled walks, ie those with a set date
                return strtotime($b['schedule'] . ' ' . $b['time']) - strtotime($a['schedule'] . ' ' . $a['time']) ?:
                    strcmp($b['title'], $a['title']);
            } else {
                // Unscheduled walks display after those with a date set
                if($a['schedule']) {
                    return -1;
                } else if($b['schedule']) {
                    return 1;
                }
                return 0;
            }
        }
    );
} else {
    // Cast a false/null case to an empty array
    $walks = array();
}
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
// Go through all the walks in this city
foreach($walks as $key => $walk) {
    // Format the date or open-scheduled text
    if ($walk['schedule']) {
        $date = date('M j, Y', strtotime($walk['schedule']));
    } else {
        $date = "Open";
    }

    // Build link to the walk's URL. $walkpage is a WP config for page's host
    $url = $walkpage ? ($walkpage . parse_url($walk['url'], PHP_URL_PATH)) : $walk['url'];
    $firstMarker = $walk['map']['markers'][0];
    $meeting = implode(
        ', ',
        array_filter(
            array(
                trim($firstMarker['title']),
                trim($firstMarker['description'])
            )
        )
    );
?>
    <tr data-janeswalk-sort='<?= $key ?>' data-janeswalk-burough='<?= $walk['wards'] ?>' >
      <td valign="top" width="8%"><?= $date ?></td>
      <td valign="top" width="8%"><?= $walk['time'] ?></td>
      <td style="padding-left: 10px; padding-right: 10px;" valign="top" width="40%"><a href="<?= $url ?>" target="_blank" ><?= $walk['title'] ?></a></td>
      <td valign="top" width="6%"><?= $walk['wards'] ?></td>
      <td valign="top" width="38%"><?= $meeting ?: $walk['short_description'] ?></td>
    </tr>
<?php
} ?>
  </tbody>
</table>
