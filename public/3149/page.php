<?php
// ####################### SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);

// #################### DEFINE IMPORTANT CONSTANTS #######################
define('THIS_SCRIPT', 'page');

// ################### PRE-CACHE TEMPLATES AND DATA ######################
// get special phrase groups
$phrasegroups = array();

// get special data templates from the datastore
$specialtemplates = array(
	'smiliecache',
	'bbcodecache'
);

// pre-cache templates used by all actions
$globaltemplates = array(
		'shell_blank',
		'mwaextraadmin4_pagesbit',
		'bbcode_code',
		'bbcode_html',
		'bbcode_php',
		'bbcode_quote'
);

// pre-cache templates used by specific actions
$actiontemplates = array();

// ######################### REQUIRE BACK-END ############################
require_once('./global.php');

// #######################################################################
// ######################## START MAIN SCRIPT ############################
// #######################################################################
if ($_REQUEST['do'])

{	if(($vbulletin->options['pages_run']) AND ($vbulletin->options['mwaextraadmin4_setting_active']))
	{
		// CONFIRM GROUP
		$pages = $db->query_first("SELECT * FROM " . TABLE_PREFIX . "pages_default WHERE varname = '" . $db->escape_string($_REQUEST['do']) . "'");
		$groupsid  = explode(',', $pages['groupids']);
		if (in_array($vbulletin->userinfo['usergroupid'], $groupsid))
		{
			// INSERT INTO
			$db->query_write("
			INSERT INTO " . TABLE_PREFIX . "pages_visitor(pagesid, visitors, ip, dateline)
			VALUES (" . $pages['pageid'] . ", " . $vbulletin->userinfo['userid'] . ", '" . IPADDRESS . "', " . TIMENOW . ")
			");
			$db->query_write("
			UPDATE " . TABLE_PREFIX . "pages_default
			SET countvisit = countvisit + 1
			WHERE pageid = " . $pages['pageid']
			);

			if ($pages['bbcode'])
			{
				require_once(DIR . '/includes/class_bbcode.php');
				$bbcode_parser =& new vB_BbCodeParser($vbulletin, fetch_tag_list());
				$pages['text'] = $bbcode_parser->parse($pages['text']);
			}

			// CODE HTML
			eval('$html = "' . fetch_template('mwaextraadmin4_pagesbit') . '";');

            // SHOW PAGE
			$navbits[''] = $pages['title'];
			$navbits = construct_navbits($navbits);
			eval('$navbar = "' . fetch_template('navbar') . '";');
			eval('print_output("' . fetch_template('shell_blank') . '");');
		}
		else
		{			print_no_permission();
		}
	}
	else
	{		print_no_permission();
	}
}
else
{	exec_header_redirect($vbulletin->options['forumhome'] . '.php');
}

?>