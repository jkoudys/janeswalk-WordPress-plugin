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
if(!empty($json['walks'])) {
  foreach($json['walks'] as $walk) {
    if($walk['schedule']) {
      $date = date('M j, Y', strtotime($walk['schedule']));
    } else {
      $date = "Open";
    }
?>
<table style="padding: 7px;" width="950" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td valign="top" width="6%"><?=$date?></td>
      <td valign="top" width="8%"><?=$walk['time']?></td>
      <td style="padding-left: 10px; padding-right: 10px;" valign="top" width="40%"><a href="<?=$walk['url']?>" ><?=$walk['title']?></a></td>
      <td valign="top" width="46%"><?=$walk['short_description']?></td>
    </tr>
  </tbody>
</table>
<div height="1" style="border-bottom: 1px solid #B3B3B3;"></div>
<?php
  }
}
?>
