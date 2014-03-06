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
<table style="padding: 7px;" width="950" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr style="background: orange; height: 30px;">
<td style="font-weight: bold;" valign="top" width="6%">Date</td>
<td style="font-weight: bold;" valign="top" width="8%">Time</td>
<td style="padding-left: 10px; padding-right: 10px; font-weight: bold;" valign="top" width="40%">Walk Name</td>
<td style="font-weight: bold;" valign="top" width="46%">Meeting Place</td>
</tr>
</tbody>
</table>
<?php
if(!empty($walks)) {
  foreach($walks as $walk) {
    if($walk['schedule']) {
      $date = date('M j, Y', strtotime($walk['schedule']));
    } else {
      $date = "Open";
    }
    $url = $walkpage ? ($walkpage . parse_url($walk['url'],PHP_URL_PATH)) : $walk['url'];
    $first_marker = $walk['map']['markers'][0];
    $meeting = trim("{$first_marker['title']}, {$first_marker['description']}");
?>
<table style="padding: 7px;" width="950" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr data-janeswalk-burough='<?= $walk['wards'] ?>' >
      <td valign="top" width="6%"><?=$date?></td>
      <td valign="top" width="8%"><?=$walk['time']?></td>
      <td style="padding-left: 10px; padding-right: 10px;" valign="top" width="40%"><a href="<?=$url?>" ><?=$walk['title']?></a></td>
      <td valign="top" width="46%"><?= $meeting ?: $walk['short_description'] ?></td>
    </tr>
  </tbody>
</table>
<div height="1" style="border-bottom: 1px solid #B3B3B3;"></div>
<?php
  }
}
?>

