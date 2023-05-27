<?php
/*======================================================================*\
|| #################################################################### ||
|| # psiStats 2009 for vBulletin 3.5.x - 3.8.x by Psionic Vision
|| #################################################################### ||
|| # Copyright ©2009 Anton Kanevsky (ankan925@gmail.com) aka Psionic Vision. All Rights Reserved.
|| # This file may redistributed under GPL license for non-commercial purposes only.
|| #################################################################### ||
\*======================================================================*/

######################################################################################
error_reporting(E_ALL & ~E_NOTICE);

######################################################################################
define('THIS_SCRIPT', 'psistats');

######################################################################################
######################################################################################
######################################################################################

/**
* fetch_percent_color
*
* Takes in this row's amount and total amount, and creates global $color, $percent and $real_percent variables.
*/
function fetch_percent_color($this_count, $total_count)
{
	global $color, $percent, $real_percent;

	if ($total_count > 0)
	{
		$color = rand(1,6);
		$real_percent	= round($this_count / $total_count * 100, 2);
		$percent = ($real_percent >= 70 ? 70 + ($real_percent - 70) / 4 : $real_percent);
	}
	else
	{
		$color = rand(1,6);
		$real_percent = 0;
		$percent = 0;
	}
}

/**
* fetch_sum
*
* Takes in a mysql resources and returns total count from all rows.
*/
function fetch_sum(&$mysql_resource)
{
	global $vbulletin;
	
	$total = 0;
	while ($item = $vbulletin->db->fetch_array($mysql_resource))													
	{
		$total += $item['count'];																		
	}
	
	$vbulletin->db->data_seek($mysql_resource, 0);																	
	return $total;																						
}

######################################################################################
######################################################################################
######################################################################################
			
$phrasegroups = array('psistats'); 																		
$globaltemplates = array('psistats_footer'); 
$specialtemplates = array('userstats');
$actiontemplates = array(
	'home' => array(
		'PSISTATS_HOME'
	),
	'styles' => array(
		'PSISTATS_STYLES',
		'psistats_stylebit'
	),
	'toptf' => array(
		'PSISTATS_TOPTF',
		'psistats_topthreadbit',
		'psistats_topforumbit'
	),
	'display' => array(
		'PSISTATS_DISPLAY',
		'psistats_resolutionbit',
		'psistats_depthbit'
	),
	'regions' => array(
		'PSISTATS_REGIONS',
		'psistats_regionbit',
		'psistats_countrybit'
	),
	'os' => array(
		'PSISTATS_OS',
		'psistats_browserbit',
		'psistats_osbit'
	),
	'refsites' => array(
		'PSISTATS_REFSITES',
		'psistats_refsitebit',
	),
);

######################################################################################
if (!isset($_REQUEST['do']) OR !in_array($_REQUEST['do'], array_keys($actiontemplates)))
{
	$_REQUEST['do'] = 'home';
}

######################################################################################
require_once('./global.php');
######################################################################################

if (trim($vbulletin->options['psistats_ugslimit']))
{
	if (preg_match('/^([0-9]{1,2}[\s]*[,]{0,1}[\s]*)*$/', $vbulletin->options['psistats_ugslimit']))
	{
		eval('$canview = is_member_of($vbulletin->userinfo, ' . $vbulletin->options['psistats_ugslimit'] . ');');
		if (!$canview)
		{
			print_no_permission();
		}
	}
}

######################################################################################
######################################################################################
######################################################################################

if ($_REQUEST['do'] == 'home')
{
	$c = array(
		'creation_date'			=> 0,											// board creation date
		'creation_fdate'		=> 0,											// board creation date (formatted)
		'creation_dayspassed'	=> 0, 											// days passed since board creation
		'posts_per_day'			=> 0,											// posts per day (average)
		'lastuserid'			=> $vbulletin->userstats['newuserid'],			// last registered user
		'lastusername'			=> $vbulletin->userstats['newusername'],			// last registered user
		'maxrefs'				=> 0,											// maximum referrals
		'maxrefs_userid'		=> 0,											// 	-> userid
		'maxrefs_username'		=> '',											//	-> username
		'users'					=> $vbulletin->userstats['numbermembers'],		// total users
		'staff'					=> 0,											// total staff
		'administrators'		=> 0,											//	-> admins
		'smoderators'			=> 0,											//	-> smods
		'moderators'			=> 0,											//	-> mods
		'registered_today'		=> 0,											// users registered during last 24 hours
		'active_users'			=> 0,											// active users
		'nonactive_users'		=> 0,											// nonactive users
		'activity'				=> 0,											// activity %
		'online'				=> 0,											// online total
		'online_users'			=> 0,											// online members
		'online_guests'			=> 0,											// online guests
		'threads'				=> 0,											// total threads
		'threads_today'			=> 0,											// total threads (24 hrs)
		'posts'					=> 0,											// total posts
		'posts_today'			=> 0,											// total posts (24 hrs)
		'pmtotal'				=> 0,											// total private messages
		'ousercache'			=> array(),										// cache (users online)
	);

	############################################################################################################################	
	if ($vbulletin->options['activememberoptions'] & 2)
	{
		if ($vbulletin->options['activememberdays'])
		{
			$c['active_users'] = $vbulletin->userstats['activemembers'];
			$c['nonactive_users'] = $vbulletin->userstats['numbermembers'] - $vbulletin->userstats['activemembers'];
			$c['activity'] = round($vbulletin->userstats['activemembers'] / $vbulletin->userstats['numbermembers'] * 100, 2);
		}
		else
		{
			$c['active_users'] = $vbulletin->userstats['numbermembers'];
			$c['anonactive_users'] = 0;
			$c['activity'] = 100;
		}
		
		$show['activity_stats'] = true;
	}
	else
	{
		$show['activity_stats'] = false;
	}
	
	############################################################################################################################	
	$getusers = $db->query_read("
		SELECT
			user.username, (user.options & " . $vbulletin->bf_misc_useroptions['invisible'] . ") AS invisible,
			session.userid, session.inforum, session.lastactivity
		FROM " . TABLE_PREFIX . "session AS session
		LEFT JOIN " . TABLE_PREFIX . "user AS user ON(user.userid = session.userid)
		WHERE session.lastactivity > " . (TIMENOW - $vbulletin->options['cookietimeout']) . "
	");
	
	while ($loggedin = $db->fetch_array($getusers))
	{
		if (!$loggedin['userid'])
		{
			$c['online_guests']++;
		}
		else if (!isset($c['ousercache']["$loggedin[userid]"]))
		{
			$c['ousercache']["$loggedin[userid]"] = true;
			
			if (!$loggedin['invisible'] OR $loggedin['userid'] == $vbulletin->userinfo['userid'] OR ($vbulletin->userinfo['permissions']['genericpermissions'] & $vbulletin->bf_ugp_genericpermissions['canseehidden']))
			{
				$c['online_users']++;
			}
		}
	}

	if ($vbulletin->userinfo['userid'])
	{
		$c['online_users'] = ($c['online_users'] == 0 ? 1 : $c['online_users']);
	}
	else
	{
		$c['online_guests'] = ($c['online_guests'] == 0	? 1 : $c['online_guests']);
	}

	$c['online'] = $c['online_users'] + $c['online_guests'];
	
	############################################################################################################################
	$c['registered_today'] = $db->query_first("
		SELECT COUNT(userid) as X
		FROM " . TABLE_PREFIX . "user
		WHERE joindate > " . (TIMENOW - 86400) . "
	");
	$c['registered_today'] = $c['registered_today']['X'];
	
	############################################################################################################################
	$c['pmtotal'] = $db->query_first("
		SELECT SUM(pmtotal) as X
		FROM " . TABLE_PREFIX . "user
	");
	$c['pmtotal'] = $c['pmtotal']['X'];
	
	############################################################################################################################
	$threads = $db->query_first("
		SELECT COUNT(threadid) as X
		FROM " . TABLE_PREFIX . "thread
		WHERE visible = 1
	");
	
	$c['threads'] = $threads['X'];
	$c['threads_today'] = 0;
	
	if ($c['threads'])
	{
		$threads = $db->query_first("
			SELECT COUNT(threadid) as X
			FROM " . TABLE_PREFIX . "thread
			WHERE visible = 1
			AND dateline > " . (TIMENOW - 86400) . "
		");
		
		$c['threads_today'] = $threads['X'];
	}
	
	$db->free_result($threads);
	
	############################################################################################################################
	$posts = $db->query_first("
		SELECT COUNT(postid) as X
		FROM " . TABLE_PREFIX . "post
		WHERE visible = 1
	");
	
	$c['posts'] = $posts['X'];
	$c['posts_today'] = 0;
	
	if ($c['posts'])
	{
		$posts = $db->query_first("
			SELECT COUNT(postid) as X
			FROM " . TABLE_PREFIX . "post
			WHERE visible = 1
			AND dateline > " . (TIMENOW - 86400) . "
		");
		
		$c['posts_today'] = $posts['X'];
	}
	
	$db->free_result($posts);
	
	############################################################################################################################
	$getref = $db->query_first("
		SELECT referrerid, COUNT(referrerid) as referredcount
		FROM " . TABLE_PREFIX . "user
		WHERE referrerid > 0
		GROUP BY referrerid
		ORDER BY referredcount DESC LIMIT 1
	");
	
	if ($getref)
	{
		$getinfo = $db->query_first("
			SELECT username
			FROM " . TABLE_PREFIX . "user
			WHERE userid = " . $getref['referrerid'] . "
			LIMIT 1
		");
		
		if ($getinfo)
		{
			$c['maxrefs'] 			= $getref['referredcount'];
			$c['maxrefs_userid'] 	= $getref['referrerid'];
			$c['maxrefs_username'] 	= $getinfo['username'];
		}
	}
	
	############################################################################################################################
	if ($vbulletin->options['psistats_creationdate'] AND preg_match('#^\d{1,2}/\d{1,2}/\d{4}$#', $vbulletin->options['psistats_creationdate']))
	{
		$getcreationdate = explode('/', $vbulletin->options['psistats_creationdate']);
		$getcurrentdate = getdate(TIMENOW);

		if ($getcreationdate[0] > 0 AND $getcreationdate[0] <= 12 AND $getcreationdate[1] > 0 AND $getcreationdate[1] <= 31 AND $getcreationdate[2] > 1990 AND $getcreationdate[2] <= $getcurrentdate['year'])
		{
			require_once(DIR . '/includes/functions_misc.php');		
			$c['creation_date'] = vbmktime(0, 0, 0, $getcreationdate[0], $getcreationdate[1], $getcreationdate[2]);
		}
	}
	
	if (!$c['creation_date'])
	{
		$getcreationdate = $db->query_first("
			SELECT joindate
			FROM " . TABLE_PREFIX . "user
			ORDER BY joindate ASC LIMIT 1
		");
		
		$c['creation_date'] = $getcreationdate['joindate'];
	}
	
	$c['creation_fdate'] 		= vbdate($vbulletin->options['dateformat'], $c['creation_date']);
	$c['creation_dayspassed'] 	= ceil((TIMENOW - $c['creation_date']) / 86400);
	$c['posts_per_day'] 		= round($c['posts'] / $c['creation_dayspassed'], 2);
	
	############################################################################################################################
	$adminugs = array();
	$smodugs = array();
	
	foreach ($vbulletin->usergroupcache as $usergroupid => $usergroup)
	{
		if ($usergroup['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['cancontrolpanel'])
		{
			$adminugs[] = $usergroupid;
		}
		else if ($usergroup['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['ismoderator'])
		{
			$smodugs[] = $usergroupid;
		}
	}
	
	if (sizeof($adminugs))
	{
		$adminugs_cond = array();
		foreach ($adminugs as $ugid)
		{
			$adminugs_cond[] = "FIND_IN_SET($ugid, membergroupids)";
		}
		
		$getadmins = $db->query_first("
			SELECT COUNT(userid) as X
			FROM " . TABLE_PREFIX . "user
			WHERE usergroupid IN (" . implode(',', $adminugs) . ")
			" . (sizeof($adminugs_cond) ? "OR " . implode(' OR ', $adminugs_cond) : "") . "
		");
		
		$c['administrators'] = $getadmins['X'];
	}
	
	if (sizeof($smodugs))
	{
		$smodugs_cond = array();
		foreach ($smodugs as $ugid)
		{
			$smodugs_cond[] = "FIND_IN_SET($ugid, membergroupids)";
		}
		
		$getsmods = $db->query_first("
			SELECT COUNT(userid) as X
			FROM " . TABLE_PREFIX . "user
			WHERE usergroupid IN (" . implode(',', $smodugs) . ")
			" . (sizeof($smodugs_cond) ? "OR " . implode(' OR ', $smodugs_cond) : "") . "
		");
		
		$c['smoderators'] = $getsmods['X'];
	}
	
	$getmods = $db->query_read("
		SELECT DISTINCT userid
		FROM " . TABLE_PREFIX . "moderator
		WHERE forumid <> -1
	");
	
	$c['moderators'] = $db->num_rows($getmods);
	
	$c['staff'] = $c['administrators'] + $c['smoderators'] + $c['moderators'];
}

######################################################################################
######################################################################################
######################################################################################

if ($_REQUEST['do'] == 'styles')
{
	$total_count = 0;
	$stylecache = array();
	
	// fetch styles
	$getstyles = $db->query_read("
		SELECT styleid, title
		FROM " . TABLE_PREFIX . "style
		WHERE userselect = 1
	");
	
	while ($style = $db->fetch_array($getstyles))
	{
		$stylecache["$style[styleid]"] = $style;
		$stylecache["$style[styleid]"]['count'] = 0;
	}
	
	// fetch users
	$getusers = $db->query_read("
		SELECT IF(styleid > 0, styleid, " . $vbulletin->options['styleid'] . ") as styleid
		FROM " . TABLE_PREFIX . "user
	");
	
	$stylebit = '';
	if ($total_count = $db->num_rows($getusers))
	{
		while ($user = $db->fetch_array($getusers))
		{
			if (isset($stylecache["$user[styleid]"]))
			{
				$stylecache["$user[styleid]"]['count']++;
			}
			else
			{
				$total_count--;
			}
		}
		
		foreach($stylecache as $styleid => $style)
		{
			fetch_percent_color($style['count'], $total_count);		
			eval('$stylebit .= "' . fetch_template('psistats_stylebit') . '";');
		}
	}
}

######################################################################################
######################################################################################
######################################################################################

if ($_REQUEST['do'] == 'toptf')
{
	// top forums by threadreplycount
	$getforums = $db->query_read("
		SELECT forumid, title, threadcount, replycount
		FROM " . TABLE_PREFIX . "forum
		ORDER BY threadcount + replycount DESC
	");
	
	$topforumbit = '';
	if ($db->num_rows($getforums))
	{
		$i = 0;
		
		while ($forum = $db->fetch_array($getforums))
		{
			if (!$vbulletin->options['showprivateforums'] AND !($vbulletin->userinfo['forumpermissions']["$forum[forumid]"] & $vbulletin->bf_ugp_forumpermissions['canview']))
			{
				$limitfids .= ", $forum[forumid]";
				continue;
			}
	
			if ($forum['threadcount'] > 0 OR $forum['replycount'] > 0)
			{
				if (++$i <= $vbulletin->options['psistats_toptflimit'])
				{
					$forum['acount'] = $forum['threadcount'] + $forum['replycount'];
					eval('$topforumbit .= "' . fetch_template('psistats_topforumbit') . '";');
				}
			}
		}
	}

	// top threads by views
	$getthreads = $db->query_read("
		SELECT threadid, title, views
		FROM " . TABLE_PREFIX . "thread
		WHERE visible = 1
		AND forumid NOT IN (0$limitfids)
		ORDER BY views DESC
		LIMIT " . $vbulletin->options['psistats_toptflimit'] . "
	");
	
	$vtopthreadbit = '';
	if ($db->num_rows($getthreads))
	{
		while ($thread = $db->fetch_array($getthreads))
		{
			eval('$vtopthreadbit .= "' . fetch_template('psistats_topthreadbit') . '";');
		}
	}
	
	// top threads by replies
	$getthreads = $db->query_read("
		SELECT threadid, title, replycount
		FROM " . TABLE_PREFIX . "thread
		WHERE visible = 1
		AND replycount > 0
		AND forumid NOT IN (0$limitfids)
		ORDER BY replycount DESC
		LIMIT " . $vbulletin->options['psistats_toptflimit'] . "
	");
	
	$rtopthreadbit = '';
	if ($db->num_rows($getthreads))
	{
		while ($thread = $db->fetch_array($getthreads))
		{
			eval('$rtopthreadbit .= "' . fetch_template('psistats_topthreadbit') . '";');
		}
	}
}

######################################################################################
######################################################################################
######################################################################################

if ($_REQUEST['do'] == 'display')
{
	// fetch resolutions
	$getresolutions = $db->query_read("
		SELECT cdata as resolution, count
		FROM " . TABLE_PREFIX . "psistats_data
		WHERE ctype = 'screenresolution'
		ORDER BY " . ($vbulletin->options['psistats_sortnum'] ? "count DESC" : "resolution ASC") . "
	");
	
	$resolutionbit = '';
	if ($db->num_rows($getresolutions))
	{
		$total_count = fetch_sum($getresolutions);
		while ($resolution = $db->fetch_array($getresolutions))
		{
			fetch_percent_color($resolution['count'], $total_count);		
			eval('$resolutionbit .= "' . fetch_template('psistats_resolutionbit') . '";');
		}
	}
	
	// fetch depths
	$getdepths = $db->query_read("
		SELECT cdata as depth, count
		FROM " . TABLE_PREFIX . "psistats_data
		WHERE ctype = 'screendepth'
		ORDER BY " . ($vbulletin->options['psistats_sortnum'] ? "count DESC" : "depth ASC") . "
	");
	
	$depthbit = '';
	if ($db->num_rows($getdepths))
	{
		$total_count = fetch_sum($getdepths);
		while ($depth = $db->fetch_array($getdepths))
		{
			$depth['depth']				= explode(',', $depth['depth']);
			$depth['bits']				= trim($depth['depth'][0]);
			$depth['colors']			= trim($depth['depth'][1]);
			
			fetch_percent_color($depth['count'], $total_count);
			eval('$depthbit .= "' . fetch_template('psistats_depthbit') . '";');
		}
	}
}

######################################################################################
######################################################################################
######################################################################################

if ($_REQUEST['do'] == 'regions')
{
	$regioncache = array();

	// require back-end files
	require_once(DIR . '/mwaextraadmin4/psistats/database_country.php');

	// fetch countries
	$getcountries = $db->query_read("
		SELECT cdata as code, count 
		FROM " . TABLE_PREFIX . "psistats_data
		WHERE ctype = 'country'
		ORDER BY " . ($vbulletin->options['psistats_sortnum'] ? "count DESC" : "code ASC") . "
	");
	
	$countrybit = $regionbit = '';
	if ($db->num_rows($getcountries))
	{
		$total_count = fetch_sum($getcountries);
		while ($country = $db->fetch_array($getcountries))
		{
			$country['name'] 		= $database_country["$country[code]"][0];
			$country['region'] 		= $database_country["$country[code]"][1];
			$country['capital'] 		= $database_country["$country[code]"][2];
			$country['currency'] 	= $database_country["$country[code]"][3];
			$country['ciacode'] 		= $database_country["$country[code]"][4];
			$country['code'] 		= strtolower($country['code']);
			$country['flagfile']		= (!file_exists(DIR . '/mwaextraadmin4/psistats/database_flag/' . $country['code'] . '.gif')) ? 'noflag' : $country['code'];
			
			$regioncache["$country[region]"] = (isset($regioncache["$country[region]"]) ? $regioncache["$country[region]"] + $country['count'] : $country['count']);
			
			fetch_percent_color($country['count'], $total_count);
			eval('$countrybit .= "' . fetch_template('psistats_countrybit') . '";');
		}
		
		// fetch continents based on countries
		foreach ($regioncache as $region => $count)
		{
			fetch_percent_color($count, $total_count);
			eval('$regionbit .= "' . fetch_template('psistats_regionbit') . '";');
		}
	}
}

######################################################################################
######################################################################################
######################################################################################

if ($_REQUEST['do'] == 'os')
{
	// fetch browsers
	$getbrowsers = $db->query_read("
		SELECT cdata as browserstring, count 
		FROM " . TABLE_PREFIX . "psistats_data
		WHERE ctype = 'browser'
		ORDER BY " . ($vbulletin->options['psistats_sortnum'] ? "count DESC" : "browserstring ASC") . "
	");
	
	$browserbit = '';
	if ($db->num_rows($getbrowsers))
	{
		$total_count = fetch_sum($getbrowsers);
		while ($browser = $db->fetch_array($getbrowsers))
		{
			$browser['browserstring'] = explode(',', $browser['browserstring']);
			$browser['browser'] = $browser['browserstring'][0];
			$browser['version'] = $browser['browserstring'][1];			
			
			$browserimage = strtolower(str_replace(' ', '', $browser['browser']));
		
			if (!file_exists(DIR . '/mwaextraadmin4/psistats/database_agent/' . $browserimage . '.gif'))
			{
				switch ($browser['browser'])
				{
					case 'Mozilla':
					case 'Firefox':
						$browserimage = 'mozilla';
						break;
					default:
						$browserimage = 'nobrowser';
						if (strpos($browser['browser'], 'Bot') !== false)
						{
							$browserimage = 'crawler';
						}
						break;
				}
			}

			fetch_percent_color($browser['count'], $total_count);
			eval('$browserbit .= "' . fetch_template('psistats_browserbit') . '";');
		}
	}
	
	// fetch oses
	$getoses = $db->query_read("
		SELECT cdata as os, count
		FROM " . TABLE_PREFIX . "psistats_data
		WHERE ctype = 'os'
		ORDER BY " . ($vbulletin->options['psistats_sortnum'] ? "count DESC" : "os ASC") . "
	");
	
	$osbit = '';
	if ($db->num_rows($getoses))
	{
		$total_count = fetch_sum($getoses);
		while ($os = $db->fetch_array($getoses))
		{			
			switch ($os['os'])
			{
				case 'Windows 98':
				case 'Windows ME':
				case 'Windows NT':
				case 'Windows 2000':
					$osimage = 'windows';
					break;
				case 'Windows XP':
				case 'Windows Vista':
				case 'Windows 2003 Server':
					$osimage = 'windowsxp';
					break;
				case 'Windows CE':
					$osimage = 'windowsce';
					break;
				default:
					$osimage = strtolower(str_replace(' ', '', $os['os']));					
					if (!file_exists(DIR . '/mwaextraadmin4/psistats/database_os/' . $osimage . '.gif'))
					{
						$osimage = 'noos';
					}
					break;
			}
			
			fetch_percent_color($os['count'], $total_count);
			eval('$osbit .= "' . fetch_template('psistats_osbit') . '";');
		}
	}
}

######################################################################################
######################################################################################
######################################################################################

if ($_REQUEST['do'] == 'refsites')
{
	// fetch referring sites
	$getsites = $db->query_read("
		SELECT cdata as referrer, count
		FROM " . TABLE_PREFIX . "psistats_data
		WHERE ctype = 'referrer'
		AND cmisc = 0
		ORDER BY count DESC
		LIMIT " . $vbulletin->options['psistats_refsitelimit'] . "
	");
	
	$output = '';
	$refsitebit = '';
	$rows = $db->num_rows($getsites);
	
	$vbulletin->input->clean_gpc('r', 'output', TYPE_STR);
	$vbulletin->GPC['output'] = strtoupper($vbulletin->GPC['output']);
	
	switch ($vbulletin->GPC['output'])
	{
		case 'JS':
		{
			$output = 	"function referrer(referrer, count)\r\n" . 
						"{\r\n" . 
						"\tthis.referrer = referrer;\r\n" . 
						"\tthis.count = count;\r\n" . 
						"}\r\n";
						
			$output .= "var referrers = new Array(" . $rows . ");\r\n";
		}
		break;
		case 'XML':
		{
			// set variables
			$expires = TIMENOW + 300;
			$lastmodified = TIMENOW;
			
			// set XML type and nocache headers
			$headers = array(
				'Cache-control: max-age=' . $expires,
				'Expires: ' . gmdate('D, d M Y H:i:s', $expires) . ' GMT',
				'Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT',
				'ETag: "' . md5($lastmodified) . '"',
				'Content-Type: text/xml' . ($stylevar['charset'] != '' ? '; charset=' .  $stylevar['charset'] : ''),
			);
			
			// print out the page header
			$output = '<?xml version="1.0" encoding="' . $stylevar['charset'] . '"?>' . "\r\n";
			require_once(DIR . '/includes/class_xml.php');
			$xml = new vB_XML_Builder($vbulletin);
			$xml->add_group('source');
			$xml->add_tag('url', $vbulletin->options['bburl']);
		}
		break;
		default:
	}
	
	if ($rows > 0)
	{
		$total_count = fetch_sum($getsites);
		while ($site = $db->fetch_array($getsites))
		{
			switch ($vbulletin->GPC['output'])
			{
				case 'JS':
				{
					$site['referrer'] = addslashes_js($site['referrer']);
					$site['count'] = addslashes_js($site['count']);
					$output .= "\treferrers[] = new referrer('$site[referrer]', $site[count]);\r\n";
				}
				break;
				case 'XML':
				{
					$xml->add_group('referrer');
						$xml->add_tag('referrer', $site['referrer']);
						$xml->add_tag('count', $site['count']);
					$xml->close_group('referrer');
				}
				break;
				default:
				{
					fetch_percent_color($site['count'], $total_count);	
					eval('$refsitebit .= "' . fetch_template('psistats_refsitebit') . '";');
				}
			}
		}
	}
	
	switch ($vbulletin->GPC['output'])
	{
		case 'JS':
		{
			exit($output);
		}
		break;
		case 'XML':
		{
			$xml->close_group('source');
			$output .= $xml->output();
			unset($xml);
			exit($output);
		}
		break;
		default:
	}
}

######################################################################################
######################################################################################
######################################################################################

$version = $db->query_first("SELECT version FROM " . TABLE_PREFIX . "product WHERE productid = 'psistats' LIMIT 1");
$version = $version['version'];

$show['referrer_statistics'] = (file_exists(DIR . '/referrers.php') ? true : false);

$navbits = construct_navbits(array(
	'psistats.php'	=>	$vbphrase['psistats_psistats'],
	''				=>	$vbphrase['psistats_' . $_REQUEST['do']]
));
eval('$navbar = "' . fetch_template('navbar') . '";');
eval('$psistats_footer = "' . fetch_template('psistats_footer') . '";');
eval('print_output("' . fetch_template('PSISTATS_' . strtoupper($_REQUEST['do'])) . '");');

/*======================================================================*\
|| #################################################################### ||
|| # psiStats 2009 for vBulletin 3.5.x - 3.8.x by Psionic Vision
|| #################################################################### ||
\*======================================================================*/
?>