<?php
/*======================================================================*\
|| #################################################################### ||
|| # Private Messages Preventor Product      for vBulletin Forums       ||
|| # ---------------------------------------------------------------- # ||
|| #       version 2.0            Coded by : Done                       ||
|| #################################################################### ||
\*======================================================================*/


// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);

// ##################### DEFINE IMPORTANT CONSTANTS #######################
define('CVS_REVISION', '$RCSfile$ - $Revision: 25644 $');

// #################### PRE-CACHE TEMPLATES AND DATA ######################
$phrasegroups = array();
$specialtemplates = array();

// ########################## REQUIRE BACK-END ############################
require_once('./global.php');

// ######################## CHECK ADMIN PERMISSIONS #######################
$pm_preventor_canadmin = explode(" ", $vbulletin->options['pm_preventor_canadmin']);

if (!in_array($vbulletin->userinfo['userid'],$pm_preventor_canadmin))
{
	print_cp_no_permission();
}

// ############################# LOG ACTION ###############################
$vbulletin->input->clean_array_gpc('r', array(
	'pmtextid'			=> TYPE_INT,
	'pmid'				=> TYPE_INT,
	'returnuserid'		=> TYPE_INT,
	'returntosender'	=> TYPE_INT,
	'replacetime'		=> TYPE_INT,
	'pmchoosen'			=> TYPE_ARRAY_UINT,
	'title'          	=> TYPE_NOHTML,
	'message'        	=> TYPE_STR,

));
log_admin_action(!empty($vbulletin->GPC['pmid']) ? ' pmid = ' . $vbulletin->GPC['pmid'] : '');

// ########################################################################
// ######################### START MAIN SCRIPT ############################
// ########################################################################

print_cp_header($vbphrase['pm_preventor_manager']);

function create_raw($userid,$pmid,$fromusername,$username,$title, $dateline = array())
		{
			echo '<tr valign="top" align="center">';
			echo '<td class="alt2" align="right"> <a href="pm_preventor.php?do=view&amp;pmid='.$pmid.'">'.$title.'</a></td>';
			echo '<td class="alt1">'.$fromusername.'</td>';
			echo '<td class="alt2">'.$username.'</td>';
			echo '<td class="alt1">'.$dateline[0].'<br>'.$dateline[1].'</td>';
			echo '<td class="alt2" align="left"> <a href="pm_preventor.php?do=view&amp;pmid='.$pmid.'">'.$vbphrase['view'].'<input type="checkbox" name="pmchoosen['.$pmid.']" checked value="'.$pmid.'" /></td>';
			echo '</tr>';
		}

if (empty($_REQUEST['do']))
{
	$_REQUEST['do'] = 'moderate';
}


// ###################### Start allow #######################
if ($_REQUEST['do'] == 'allow')
{

if(empty($_POST['pmchoosen']))
	{
		$vbulletin->url = 'pm_preventor.php?';
		eval(print_standard_redirect('pm_messagesent'));
	}

$where = (is_array($_POST['pmchoosen']) ? "IN (" . implode(',', array_keys($_POST['pmchoosen'])) . ")" : "= " . $_POST['pmchoosen']);

$pm_users = $db->query_read("SELECT pm.userid, pm.pmid, pmtext.pmtextid 
FROM " . TABLE_PREFIX . "pm as pm
LEFT JOIN " . TABLE_PREFIX . "pmtext as pmtext ON ( pmtext.pmtextid = pm.pmtextid )
WHERE pm.pmid " . $where);


while($pm_user = $db->fetch_array($pm_users))
	{

	if($vbulletin->GPC['replacetime'])
		{
			$db->query_write("UPDATE " . TABLE_PREFIX . "pmtext	SET dateline = '" . TIMENOW . "' WHERE pmtextid = " . $pm_user['pmtextid'] );
		}

	if($vbulletin->GPC['returntosender'])
		{
			$return = " ,userid = " . $vbulletin->GPC['returnuserid'];
		}

	if($vbulletin->GPC['message'])
		{
				$db->query_write("UPDATE " . TABLE_PREFIX . "pmtext	SET message = '" . $vbulletin->GPC['message'] . "' WHERE pmtextid = " . $pm_user['pmtextid'] );
		}

	if($vbulletin->GPC['title'])
		{
				$db->query_write("UPDATE " . TABLE_PREFIX . "pmtext	SET title = '" . $vbulletin->GPC['title'] . "' WHERE pmtextid = " . $pm_user['pmtextid'] );
		}

		$db->query_write("UPDATE " . TABLE_PREFIX . "pm	SET folderid = 0".$return." WHERE pmid " . $where );

		$db->query_write("UPDATE " . TABLE_PREFIX . "user SET pmunread = pmunread + 1 WHERE userid = $pm_user[userid]");
	}	
		define('CP_REDIRECT', 'pm_preventor.php?');
		print_stop_message('modpm_private_messages_allowed_sucessfully');
}

// ###################### Start view #######################
if ($_REQUEST['do'] == 'view')
{
	if(empty($vbulletin->GPC['pmid']))
		{
			$vbulletin->url = 'pm_preventor.php?';
			eval(print_standard_redirect('pm_messagesent'));
		}

	$pm = $db->query_read("
		SELECT
			pm.userid, pm.pmid, pm.pmtextid, pmtext.*,user.username
		FROM " . TABLE_PREFIX . "pm AS pm
		LEFT JOIN " . TABLE_PREFIX . "pmtext AS pmtext ON(pmtext.pmtextid = pm.pmtextid)
		LEFT JOIN " . TABLE_PREFIX . "user AS user ON(user.userid = pm.userid)
		WHERE pm.pmid = " . $vbulletin->GPC['pmid'] . "
	");

while($pm_preventor=$db->fetch_array($pm))
	{
	require_once(DIR . '/includes/class_bbcode.php');
      $bbcode_parser =& new vB_BbCodeParser($vbulletin, fetch_tag_list());
      $pm_preventor['message'] = $bbcode_parser->parse($pm_preventor['message'], 0, 0,1);
	echo"  <form name=\"update\" method=post action=\"\">
	<input type=\"hidden\" name=\"adminhash\" value=\"" . ADMINHASH . "\" />\n
	<input type=\"hidden\" name=\"securitytoken\" value=\"" . $vbulletin->userinfo['securitytoken'] . "\" />\n
		<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\" class=\"tborder\" id=\"cpform_table\">
		<tr>
			<td class=\"tcat\" align=\"center\" colspan=\"2\"><b>". $vbphrase['pm_preventor_manager'] ."</b></td>
		</tr>
		<tr>
			<td class=\"tcat\" align=\"right\" colspan=\"2\">".$vbphrase['pm_preventor_from']." : <input readonly=\"readonly\" type=\"text\" name=\"recipients\" value=\"".$pm_preventor['fromusername']. "\"></td>
		</tr>
		<tr>
			<td class=\"tcat\" align=\"right\" colspan=\"2\">".$vbphrase['pm_preventor_to']." : <input readonly=\"readonly\" type=\"text\" name=\"recipients\" value=\"".$pm_preventor['username']. "\"></td>
		</tr>
		<tr>
			<td class=\"tcat\" align=\"right\">".$vbphrase['title']." : <input type=\"text\" name=\"title\" value=\"".$pm_preventor['title']."\"></td>
		</tr>
		<tr>
			<td class=\"tcat\" align=\"right\" colspan=\"2\">".$vbphrase['message']."</td>
		</tr>
		<tr>
			<td class=\"alt2\" colspan=\"2\" align=\"center\">$pm_preventor[message]</td>
		</tr>
		<tr>
			<td class=\"tcat\" align=\"right\" colspan=\"2\" nowrap>".$vbphrase['pm_preventor_replacetime']." : <input type=\"checkbox\" name=\"replacetime\" value=\"1\"></td>
		</tr>
		<tr>
			<td class=\"tcat\" align=\"center\" colspan=\"2\">
			
		<input type=\"submit\" class=\"button\" value=\"".$vbphrase['pm_preventor_allow']."\" title=\"\" tabindex=\"1\" onclick=\"update.action='pm_preventor.php?do=allow'; return true;\"/>
		<input type=\"submit\" class=\"button\" value=\"".$vbphrase['pm_preventor_remove']."\" title=\"\" tabindex=\"1\" onclick=\"update.action='pm_preventor.php?do=remove'; return true;\"/>

		</td>
		</tr>
		</table>
		<input type=\"hidden\" name='pmchoosen' value='".$pm_preventor[pmid]."'>
		<input type=\"hidden\" value=\"" . $pm_preventor['fromuserid'] . "\" name=\"returnuserid\" />


	  </form>";
	}

}

// ###################### Start Remove #######################

if ($_REQUEST['do'] == 'remove')
{
	if(empty($_POST['pmchoosen']))
		{
			$vbulletin->url = 'pm_preventor.php?';
			eval(print_standard_redirect('pm_messagesent'));
		}

	$postpmid = (is_array($_POST['pmchoosen']) ? implode("," , $_POST['pmchoosen']) : $_POST['pmchoosen']);

	print_form_header('pm_preventor', 'kill');
	construct_hidden_code('pmid', $postpmid );
	print_table_header($vbphrase['confirm_deletion']);
	print_description_row($vbphrase['are_you_sure_you_want_to_delete_this_message']);
	print_submit_row($vbphrase['yes'], '', 2, $vbphrase['no']);

}

// ###################### Start Kill #######################

if ($_REQUEST['do'] == 'kill')
{
	if(empty($_POST['pmid']))
		{
			$vbulletin->url = 'pm_preventor.php?';
			eval(print_standard_redirect('pm_messagesent'));
		}

	$db->query_write("DELETE FROM " . TABLE_PREFIX . "pm
			WHERE pmid IN (" . $_POST['pmid'] . ")");

	define('CP_REDIRECT', 'pm_preventor.php?do=moderate');
	print_stop_message('messages_deletetd_successfully'); 
}

// ###################### Start modify #######################
if ($_REQUEST['do'] == 'moderate')
{

$replace = false;

$pm = $db->query_read("
		SELECT
			pmid, pmtextid, userid
		FROM " . TABLE_PREFIX . "pm 
		WHERE pm.folderid = -2
	");

	?>
	<form action="pm_preventor.php" method="post" name="cpform" id="cpform">
<?
		echo "<input type=\"hidden\" name=\"adminhash\" value=\"" . ADMINHASH . "\" />\n";
		echo "<input type=\"hidden\" name=\"securitytoken\" value=\"" . $vbulletin->userinfo['securitytoken'] . "\" />\n";
?>
	<table cellpadding="4" cellspacing="0" border="0" align="center" width="90%" class="tborder" id="cpform_table">
	<tr>
		<td class="tcat" align="center" colspan="5"><b><?php echo $vbphrase['pm_preventor_manager']; ?></b></td>
	</tr>

	<tr valign="top" align="center">
		<td class="thead" align="right"><?php echo $vbphrase['title']; ?></td>
		<td class="thead"><?php echo $vbphrase['pm_preventor_from']; ?></td>
		<td class="thead"><?php echo $vbphrase['pm_preventor_to']; ?></td>
		<td class="thead"><?php echo $vbphrase['date']; ?></td>
		<td class="thead" align="left"><?php echo $vbphrase['pm_preventor_pick']; ?></td>
	</tr>
<?
	while ($pm_preventor = $db->fetch_array($pm))
	{
		$replace = true;
		$pm_user = $db->query_first_slave("
			SELECT
				username
			FROM " . TABLE_PREFIX . "user
			WHERE userid = $pm_preventor[userid]
		");

		$pm_text = $db->query_first_slave("
			SELECT
				title,fromusername,dateline
			FROM " . TABLE_PREFIX . "pmtext
			WHERE pmtextid = $pm_preventor[pmtextid]
		");

$date = array(
	vbdate($vbulletin->options['dateformat'],$pm_text['dateline'],true),
	vbdate("h:i A",$pm_text['dateline'])
	);
		create_raw($pm_preventor['userid'], $pm_preventor['pmid'], $pm_text['fromusername'], $pm_user['username'], $pm_text['title'],$date );

	}
if($replace)
	{
	?>
		<tr>
			<td class="tfoot" colspan="5" align="center">
			<?php echo $vbphrase['pm_preventor_replacetime']." "; ?> <input type="checkbox" value="1" name="replacetime" />
			</td>
		</tr>
	<?
	}
	?>
		<tr>
			<td class="tfoot" colspan="5" align="center">
			<input type="submit" class="button" value="<?php echo $vbphrase['pm_preventor_allow']; ?>" title="" tabindex="1" onclick="cpform.action='pm_preventor.php?do=allow'; return true;"/>
			<input type="submit" class="button" value="<?php echo $vbphrase['pm_preventor_remove']; ?>" title="" tabindex="1" onclick="cpform.action='pm_preventor.php?do=remove'; return true;"/>
		 </td>
		</tr>

		</table>

		</form>
	<?
	}

?>