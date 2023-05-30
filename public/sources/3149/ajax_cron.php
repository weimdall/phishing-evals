<?php
/*======================================================================*\
|| ####################################################################
|| # A slightly altered version of the vBulletin cron.php script.
|| # This one does not redirect to clear.gif. Cleans up the return data
|| ####################################################################
\*======================================================================*/

// ####################### SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);
ignore_user_abort(1);
@set_time_limit(0);

// #################### DEFINE IMPORTANT CONSTANTS #######################
define('SKIP_SESSIONCREATE', 1);
define('NOCOOKIES', 1);
define('THIS_SCRIPT', 'cron');

// #################### PRE-CACHE TEMPLATES AND DATA ######################

$phrasegroups = $specialtemplates = $globaltemplates = $actiontemplates = array();

// ######################### REQUIRE BACK-END ############################
require_once './global.php';
require_once DIR . '/includes/functions_cron.php';

// #######################################################################
// ######################## START MAIN SCRIPT ############################
// #######################################################################

($hook = vBulletinHook::fetch_hook('cron_start')) ? eval($hook) : false;

if (!defined('NOSHUTDOWNFUNC') && !$vbulletin->options['crontab'])
{
	vB_Shutdown::add('exec_cron');
}
else
{
	$cronid = NULL;
	if ($vbulletin->options['crontab'] && SAPI_NAME == 'cli')
	{
		$cronid = intval($_SERVER['argv'][1]);
		// if its a negative number or 0 set it to NULL so it just grabs the next task
		if ($cronid < 1)
		{
			$cronid = NULL;
		}
	}

	exec_cron($cronid);
	if (defined('NOSHUTDOWNFUNC'))
	{
		$db->close();
	}
}
?>