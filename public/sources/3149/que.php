<?php
// ========================================================================
// QUE
// Version 1.0 - Beta 2
// An Inline Quick User Editor
// ------------------------------------------------------------------------
// Written by Alan Wagstaff
// Copyright (C) 2005 Alan Wagstaff
// ========================================================================

// Cludge vB's notice errors
error_reporting(E_ALL & ~E_NOTICE);

// Setup a few important bits and bobs
define("THIS_SCRIPT", "que");

// Include the phrase groups and templates
$phrasegroups = array(
	'cpuser',
	'user',
	'cpglobal',
	'messaging'
	);

$globaltemplates = array(
	'que_changes_saved',	
	'que_confirmation_box',
	'que_confirm_delete',
	'que_edit_form',
	'que_userfield_checkbox_option',
	'que_userfield_optional_input',
	'que_userfield_radio',
	'que_userfield_radio_option',
	'que_userfield_select',
	'que_userfield_select_option',
	'que_userfield_textarea',
	'que_userfield_textbox',
	'que_shell'
	);

require_once('./global.php');
require_once(DIR . '/includes/functions.php');
require_once(DIR . '/includes/functions_misc.php');
require_once(DIR . '/includes/adminfunctions.php');

// ########################################################################
// Start the main script

// ########################################################################
// Setup the permissions for the user

if (!$vbulletin->options['mwaextraadmin4_setting_active'])
{
	// No permission
	print_no_permission();
}

if (!$vbulletin->options['mwaextraadmin4_setting_que_active'])
{
	// No permission
	print_no_permission();
}

if (!($permissions['que'] & $vbulletin->bf_ugp['que']['can_use_que']))
{
	// No permission
	print_no_permission();
}

// Setup permissions for the various options
$show['edit_username'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_username']) ? (true) : (false);
$show['edit_password'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_password']) ? (true) : (false);
$show['edit_email'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_email']) ? (true) : (false);

$show['edit_profile'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_profile']) ? (true) : (false);
$show['edit_custom_profile'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_custom_profile']) ? (true) : (false);
$show['edit_im'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_im']) ? (true) : (false);
$show['edit_usergroups'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_usergroups']) ? (true) : (false);
$show['edit_options'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_edit_options']) ? (true) : (false);

$show['delete_account'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_delete_account']) ? (true) : (false);
$show['delete_avatar'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_delete_avatar']) ? (true) : (false);
$show['delete_picture'] = ($permissions['que'] & $vbulletin->bf_ugp['que']['can_delete_picture']) ? (true) : (false);

if (($show['delete_account']) or ($show['delete_avatar']) or ($show['delete_picture']))
{
	$show['delete_buttons'] = true;
}

if (($show['edit_username']) or ($show['edit_password']) or ($show['edit_email']))
{
	$show['edit_account'] = true;
}

// Set a default action
if (empty($_REQUEST['do']))
{
	$_REQUEST['do'] = 'edit';
}

// ########################################################################
// Editing a user - display the form

if ($_GET['do'] == 'edit')
{

	$vbulletin->input->clean_array_gpc('g', array(
		'userid' => TYPE_UINT
	));

	if (!$vbulletin->GPC['userid'])
	{
		// ERROR: No user id specified
		eval(standard_error(fetch_error('que_no_userid_specified')));
	}

	// Fetch the users details
	$user = $db->query_first("
		SELECT user.*, userfield.*, usertextfield.signature
		FROM " . TABLE_PREFIX . "user AS user
		LEFT JOIN " . TABLE_PREFIX . "userfield AS userfield ON (userfield.userid = user.userid)
		LEFT JOIN " . TABLE_PREFIX . "usertextfield AS usertextfield ON (usertextfield.userid = user.userid)
		WHERE user.userid = " . $vbulletin->GPC['userid']
	);

	// Check for a valid userid
	if (!$user['userid'])
	{
		// ERROR: Invalid user id specified
		eval(standard_error(fetch_error('que_invalid_userid_specified')));
	}

	// Merge the options with the users details
	$getoptions = convert_bits_to_array($user['options'], $vbulletin->bf_misc_useroptions);
	$user = array_merge($user, $getoptions);

	// Convert auto subscribe choice if -1 to 99
	$user['autosubscribe'] = ($user['autosubscribe'] == -1) ? (99) : ($user['autosubscribe']);

	// get threaded mode options
	if ($user['threadedmode'] == 1 OR $user['threadedmode'] == 2)
	{
		$threaddisplaymode = $user['threadedmode'];
	}
	else
	{
		if ($user['postorder'] == 0)
		{
			$threaddisplaymode = 0;
		}
		else
		{
			$threaddisplaymode = 3;
		}
	}

	// Fill in the users birthday boxes
	if ($user['birthday'])
	{
		$birthday = explode('-', $user['birthday']);
		$user['dob_month'] = ($birthday[0] ? $birthday[0] : 0);
		$user['dob_day'] = 	 ($birthday[1] ? $birthday[1] : '');
		$user['dob_year'] =  ($birthday[2] != '0000' ? $birthday[2] : '');
	}

	// =======================================================
	// Create the dropdown boxes

	// Language
	$languages_a = array('0' => $vbphrase['use_forum_default']) + fetch_language_titles_array('', false);
	foreach ($languages_a AS $langid => $langname)
	{
		$selected = ($langid == $user['languageid']) ? (' selected="selected"') : ('');
		$dropdown['language'] .= "<option value=\"$langid\" $selected>$langname</option>\n";
	}

	// Cusom User Title
	$options = array(0 => $vbphrase['no'], 2 => $vbphrase['user_set'], 1 => $vbphrase['admin_set_html_allowed']);
	foreach ($options AS $key => $val)
	{
		$selected = ($key == $user['customtitle']) ? (' selected="selected"') : ('');
		$dropdown['custom_user_title'] .= "<option value=\"$key\" $selected>$val</option>\n";
	}

	// Months
	$months = array(
		0 => '- - - -',
		1 => $vbphrase['january'],
		2 => $vbphrase['february'],
		3 => $vbphrase['march'],
		4 => $vbphrase['april'],
		5 => $vbphrase['may'],
		6 => $vbphrase['june'],
		7 => $vbphrase['july'],
		8 => $vbphrase['august'],
		9 => $vbphrase['september'],
		10 => $vbphrase['october'],
		11 => $vbphrase['november'],
		12 => $vbphrase['december']
	);

	foreach ($months AS $key => $month)
	{
		$selected = ($key == $user['dob_month']) ? (' selected="selected"') : ('');
		$dropdown['dob_months'] .= "<option value=\"$key\"$selected>$month</option>\n";
	}

	// Usergroups
	foreach ($vbulletin->usergroupcache AS $id => $usergroup)
	{
		$selected = ($id == $user['usergroupid']) ? (' selected="selected"') : ('');
		$dropdown['primary_usergroup'] .= "<option value=\"$id\"$selected>$usergroup[title]</option>\n";
	}

	// Styles
	$styles_q = "SELECT styleid, title
		FROM " . TABLE_PREFIX . "style
		WHERE userselect = 1
		ORDER BY title";

	$styles_r = $vbulletin->db->query_read($styles_q);

	$selected = ($user['styleid'] == 0) ? ('selected="selected"') : ('');
	$dropdown['style'] = "<option value=\"0\" $selected>$vbphrase[use_forum_default]</option>\n";

	while ($styles_a = $vbulletin->db->fetch_array($styles_r))
	{
		$selected = ($user['styleid'] == $styles_a['styleid']) ? ('selected="selected"') : ('');
		$dropdown['style'] .= "<option value=\"$styles_a[styleid]\" $selected>$styles_a[title]</option>\n";
	}


	// =======================================================
	// Create the check boxes

	// Usergroups
	$usergroupids = explode(',', $user['membergroupids']);
	
	foreach ($vbulletin->usergroupcache AS $id => $usergroup)
	{
		$checked = (in_array($id, $usergroupids)) ? (' checked="checked"') : ('');
		$checkboxes['additional_usergroups'] .= "<label for=\"$usergroup[title]\" title=\"usergroupid: $id\"><input type=\"checkbox\" name=\"form[membergroupids][$id]\" id=\"$id\" value=\"$id\"$checked>$usergroup[title]</label><br />\n";
	}


	// =======================================================
	// Setup the Yes/No boxes

	$optselected['recieve_admin_email'][$user['adminemail']] = 'checked="checked"';
	$optselected['receive_other_emails'][$user['showemail']] = 'checked="checked"';
	$optselected['invisible'][$user['invisible']] = 'checked="checked"';
	$optselected['allow_vcard'][$user['showvcard']] = 'checked="checked"';
	$optselected['receive_pm'][$user['receivepm']] = 'checked="checked"';
	$optselected['pm_notification'][$user['emailonpm']] = 'checked="checked"';
	$optselected['pm_popup'][$user['pmpopup']] = 'checked="checked"';
	$optselected['display_sig'][$user['showsignatures']] = 'checked="checked"';
	$optselected['display_avatars'][$user['showavatars']] = 'checked="checked"';
	$optselected['display_images'][$user['showimages']] = 'checked="checked"';
	$optselected['editor'][$user['showvbcode']] = 'checked="checked"';
	$optselected['auto_thread_sub'][$user['autosubscribe']] = 'checked="checked"';
	$optselected['thread_display_mode'][$threaddisplaymode] = 'checked="checked"';

	// =======================================================
	// Custom Profile Fields

	$profilefields_q = "SELECT profilefieldid, type, data, optional
		FROM " . TABLE_PREFIX . "profilefield 
		ORDER by displayorder";

	$profilefields_r = $vbulletin->db->query_read($profilefields_q);

	$alt_class = 'alt1';

	while ($profilefields_a = $vbulletin->db->fetch_array($profilefields_r))
	{

		// Alternate between alt1 and alt2 when displaying the fieldsvb
		$alt_class = ($alt_class == 'alt1') ? ('alt2') : ('alt1');

		// Set the field name and value
		$profilefieldname = 'field' . $profilefields_a['profilefieldid'];
		$profilefieldvalue = $user['field' . $profilefields_a['profilefieldid']];

		// Get the optional data for radio / check / select boxes
		$data = unserialize($profilefields_a['data']);

		// Check for the type of field to display
		switch ($profilefields_a['type'])
		{
			case 'input':
			{
				// regular text box
				eval('$custom_profile_fields .= "' . fetch_template('que_userfield_textbox') . '";');
				break;
			}

			case 'textarea':
			{
				// Textarea
				eval('$custom_profile_fields .= "' . fetch_template('que_userfield_textarea') . '";');
				break;
			}

			case 'radio':
			{
				// Radio buttons
				$data = unserialize($profilefields_a['data']);
				unset($radiobits);
				unset($checkmatch);

				foreach ($data as $id => $option)
				{
					$id++;
					
					if ($option == $user['field' . $profilefields_a['profilefieldid']])
					{
						$checked = 'checked="checked"';
						$checkmatch = true;
					}
					else
					{
						$checked = '';
					}

					eval('$radiobits .= "' . fetch_template('que_userfield_radio_option') . '";');
				}

				// Check for optional field
				if ($profilefields_a['optional'])
				{
					$optionalvalue = (!$checkmatch) ? ($user['field' . $profilefields_a['profilefieldid']]) : ('');
					$profilefieldname_opt = $profilefieldname . '_opt';

					eval('$optionalinput = "' . fetch_template('que_userfield_optional_input') . '";');
				}

				eval('$custom_profile_fields .= "' . fetch_template('que_userfield_radio') . '";');

				break;
			}

			case 'select':
			case 'select_multiple':
			{
				// Select dropdown
				unset($selectbits);
				unset($checkmatch);

				//$selectfieldname = ($profilefields_a['type'] == 'select_multiple')

				if ($profilefields_a['type'] == 'select_multiple')
				{
					$selectmultiple = 'multiple="multiple"';
					$selectfieldname = "form[customprofile][$profilefieldname][]";
				}
				else
				{
					$selectmultiple = '';
					$selectfieldname = "form[customprofile][$profilefieldname]";
				}					
				
				foreach ($data as $key => $val)
				{
					($profilefields_a['type'] == 'select' ? $key++ : false);
					
					if (($val == $user['field' . $profilefields_a['profilefieldid']]) and ($profilefields_a['type'] == 'select'))
					{
						$selected = 'selected="selected"';
						$checkmatch = true;
					}
					else if ($user['field' . $profilefields_a['profilefieldid']] & pow(2, $key))
					{
						$selected = 'selected="selected"';
						$checkmatch = true;
					}						
					else
					{
						$selected = '';
					}

					($profilefields_a['type'] == 'select_multiple' ? $key++ : false);
					
					eval('$selectbits .= "' . fetch_template('que_userfield_select_option') . '";');
				}

				// Check for optional field
				if ($profilefields_a['optional'])
				{
					$optionalvalue = (!$checkmatch) ? ($optionalvalue = $user['field' . $profilefields_a['profilefieldid']]) : ('');
					$profilefieldname_opt = $profilefieldname . '_opt';

					eval('$optionalinput = "' . fetch_template('que_userfield_optional_input') . '";');
				}

				eval('$custom_profile_fields .= "' . fetch_template('que_userfield_select') . '";');

				break;
			}

			case 'checkbox':
			{
				// Checkboxes
				$data = unserialize($profilefields_a['data']);
				unset($radiobits);

				foreach ($data as $id => $option)
				{
					if ($user['field' . $profilefields_a['profilefieldid']] & pow(2, $id))
					{
						$checked = 'checked="checked"';
						$checkmatch = true;
					}
					else
					{
						$checked = '';
					}

					$id++;
					
					eval('$radiobits .= "' . fetch_template('que_userfield_checkbox_option') . '";');
				}

				eval('$custom_profile_fields .= "' . fetch_template('que_userfield_radio') . '";');

				break;
			}

		}

	}

	// Setup the page ready for output
	$pagetitle = $vbphrase['edit_user'] . ': ' . $user['username'];
	$templatename = 'que_edit_form';

}


// ########################################################################
// Commit the changes to the users account

if ($_POST['do'] == 'commit')
{

	$vbulletin->input->clean_array_gpc('p', array(
		'userid' => TYPE_UINT
		));

	if (!$vbulletin->GPC['userid'])
	{
		// ERROR: No userid specified
		eval(standard_error(fetch_error('que_no_userid_specified')));
	}

/* Leave this in?
	// Check that they're not editing their own account
	if ($vbulletin->userinfo['userid'] == $vbulletin->GPC['userid'])
	{
		print_no_permission();
	}
*/

	// Check that they're not editing a non-editable user
	$noedit = explode(',', $vbulletin->config['SpecialUsers']['undeletableusers']);
	if (in_array($vbulletin->GPC['userid'], $noedit))
	{
		// ERROR: Uneditable user
		eval(standard_error(fetch_error('user_is_protected_from_alteration_by_undeletableusers_var')));
	}

	// ==================================================================
	// Delete Users Account
	if (isset($_POST['delete_account']))
	{

		// Check permissions
		if (!$show['delete_account'])
		{
			print_no_permission();
		}

		// Check to see if the deletion hase been confirmed
		if ($_POST['state'] == 'confirmed')
		{
			// Do the account deletion
			$info = fetch_userinfo($vbulletin->GPC['userid']);
			if ($info['userid'] == $vbulletin->GPC['userid'])
			{
				$userdm =& datamanager_init('User', $vbulletin, ERRTYPE_CP);
				$userdm->set_existing($info);
				$userdm->delete();
				unset($userdm);

				// Log
				log_admin_action('Deleted Account: ' . $vbulletin->GPC['userid']);

				// Fill in the template vars
				$confirmation_title = $vbphrase['que_account_deleted'];
				$confirmation_message = construct_phrase($vbphrase['que_x_account_deleted_sucessfully'], $info['username']);

				$pagetitle = $vbphrase['que_account_deleted'];
				$templatename = 'que_confirmation_box';
			}
			else
			{
				// ERROR: Invalid user id specified
				eval(standard_error(fetch_error('que_invalid_userid_specified')));
			}

		}
		else
		{

			// Display confirmation box

			// Fetch the username
			$username_q = "SELECT `username` FROM `" . TABLE_PREFIX . "user` WHERE `userid` = " . $vbulletin->GPC['userid'] . " LIMIT 1";
			$username_a = $vbulletin->db->query_first($username_q);

			// Fill in the template blanks
			$userid = $vbulletin->GPC['userid'];
			$buttonname = 'delete_account';

			$confirm_delete_title = construct_phrase($vbphrase['que_delete_x_user_account'], $username_a['username']);
			$confirm_delete_message = $vbphrase['que_confirm_delete_account'];	

			// Set the template
			$pagetitle = $confirm_delete_title;
			$templatename = 'que_confirm_delete';

		}

	}
	
	// ==================================================================
	// Delete Users Avatar
	if (isset($_POST['delete_avatar']))
	{

		// Check permissions
		if (!$show['delete_avatar'])
		{
			print_no_permission();
		}

		// Check to see if the deletion hase been confirmed
		if ($_POST['state'] == 'confirmed')
		{
			// Delete the avatar
			$info = fetch_userinfo($vbulletin->GPC['userid']);
			if ($info['userid'] == $vbulletin->GPC['userid'])
			{
				$vbulletin->GPC['avatarid'] = 0;
				$userpic =& datamanager_init('Userpic_Avatar', $vbulletin, ERRTYPE_CP, 'userpic');
				$userpic->condition = "userid = " . $info['userid'];
				$userpic->delete();

				// Update the user account
				$userdata =& datamanager_init('User', $vbulletin, ERRTYPE_CP);
				$userdata->set_existing($info);
				$userdata->set('avatarid', 0);
				$userdata->save();

				// Log
				log_admin_action('Deleted Avatar for User ID: ' . $vbulletin->GPC['userid']);

				// Fill in the template vars
				$confirmation_title = $vbphrase['que_avatar_deleted'];
				$confirmation_message = construct_phrase($vbphrase['que_x_avatar_deleted_sucessfully'], $info['username']);

				$pagetitle = $vbphrase['que_avatar_deleted'];
				$templatename = 'que_confirmation_box';
			}
			else
			{
				// ERROR: Invalid user id specified
				eval(standard_error(fetch_error('que_invalid_userid_specified')));
			}

		}
		else
		{
			// Display the confirmation box

			// Fetch the username
			$username_q = "SELECT `username` FROM `" . TABLE_PREFIX . "user` WHERE `userid` = " . $vbulletin->GPC['userid'] . " LIMIT 1";
			$username_a = $vbulletin->db->query_first($username_q);

			// Fill in the template blanks
			$userid = $vbulletin->GPC['userid'];
			$buttonname = 'delete_avatar';

			$confirm_delete_title = construct_phrase($vbphrase['que_delete_x_avatar'], $username_a['username']);
			$confirm_delete_message = $vbphrase['que_confirm_delete_avatar'];	

			// Set the template
			$pagetitle = $confirm_delete_title;
			$templatename = 'que_confirm_delete';
		}

	}

	// ==================================================================
	// Delete Profile Picture
	if (isset($_POST['delete_profile_pic']))
	{

		// Check permissions
		if (!$show['delete_picture'])
		{
			print_no_permission();
		}

		// Check to see if the deletion hase been confirmed
		if ($_POST['state'] == 'confirmed')
		{
			// Delete the picture
			$info = fetch_userinfo($vbulletin->GPC['userid']);
			if ($info['userid'] == $vbulletin->GPC['userid'])
			{
				$userpic =& datamanager_init('Userpic_Profilepic', $vbulletin, ERRTYPE_CP, 'userpic');
				$userpic->condition = "userid = " . $info['userid'];
				$userpic->delete();

				// Log
				log_admin_action('Deleted Profile Pic for User ID: ' . $vbulletin->GPC['userid']);

				// Fill in the template vars
				$confirmation_title = $vbphrase['que_profile_picture_deleted'];
				$confirmation_message = construct_phrase($vbphrase['que_x_profile_picture_deleted_sucessfully'], $info['username']);

				$pagetitle = $vbphrase['que_profile_picture_deleted'];
				$templatename = 'que_confirmation_box';
			}
			else
			{
				// ERROR: Invalid user id specified
				eval(standard_error(fetch_error('que_invalid_userid_specified')));
			}

		}
		else
		{
			// Display confirmation box

			// Fetch the username
			$username_q = "SELECT `username` FROM `" . TABLE_PREFIX . "user` WHERE `userid` = " . $vbulletin->GPC['userid'] . " LIMIT 1";
			$username_a = $vbulletin->db->query_first($username_q);

			// Fill in the template blanks
			$userid = $vbulletin->GPC['userid'];
			$buttonname = 'delete_profile_pic';

			$confirm_delete_title = construct_phrase($vbphrase['que_delete_x_profile_picture'], $username_a['username']);
			$confirm_delete_message = $vbphrase['que_confirm_delete_picture'];	

			// Set the template
			$pagetitle = $confirm_delete_title;
			$templatename = 'que_confirm_delete';
		}

	}

	// ==================================================================
	// Commit changes to the account
	if (isset($_POST['save']))
	{

		$vbulletin->input->clean_array_gpc('p', array(
			'form' => TYPE_ARRAY
			));

		// Setup the datamanger
		$user =& datamanager_init('User', $vbulletin, ERRTYPE_CP);
		$user->adminoverride = true;

		// Fetch the users existing details
		$info = fetch_userinfo($vbulletin->GPC['userid']);
		$user->set_existing($info);
		if ($user->existing['userid'] != $vbulletin->GPC['userid'])
		{
			// ERROR: Invalid user id specified
			eval(standard_error(fetch_error('que_invalid_userid_specified')));
		}

		// Username
		if ($show['edit_username'])
		{
			$user->set('username', $vbulletin->GPC['form']['username']);

			/* Duplicate key errors - added to the bugs-to-fix list
			// If Marco's Username Management mod is installed...
			if (($vbulletin->products['mh_unm']) and ($vbulletin->GPC['form']['username'] != $user->existing['username']))
			{
				// ...save the username change to the history table
				$username_history_q = "INSERT INTO `" . TABLE_PREFIX . "mh_unm_history` 
						(`userid`, `dateline`, `oldusername`, `changedbyuserid`)
					VALUES (
						" . $vbulletin->db->sql_prepare($user->existing['userid']) . ",
						" . $vbulletin->db->sql_prepare(time()) . ",
						" . $vbulletin->db->sql_prepare($user->existing['username']) . ",
						" . $vbulletin->db->sql_prepare($vbulletin->userinfo['userid']) . "
						)";

				$vbulletin->db->query_write($username_history_q);
			}
			*/
		}

		// Password
		if (($show['edit_password']) and ($vbulletin->GPC['form']['password'] != ''))
		{
			$user->set('password', $vbulletin->GPC['form']['password']);
		}

		// E-Mail
		if ($show['edit_email'])
		{
			$user->set('email', $vbulletin->GPC['form']['email']);
		}

		// Profile
		if ($show['edit_profile'])
		{
			$user->set('languageid', $vbulletin->GPC['form']['language']);
			$user->set('homepage', $vbulletin->GPC['form']['homepage']);
			$user->set('birthday', $vbulletin->GPC['form']['birthday']);
			$user->set('signature', $vbulletin->GPC['form']['signature']);

			// Custom User Title
			$user->set_usertitle($vbulletin->GPC['form']['user_title'],
				($vbulletin->GPC['form']['custom_user_title']) ? (false) : (true),
				$vbulletin->usergroupcache[$vbulletin->GPC['form']['primary_usergroup']],
				true,
				($vbulletin->GPC['form']['customtitle'] == 1) ? (true) : (false)
				);
		}

//echo '<pre>'; die(print_r($vbulletin->GPC['form']));
		
		// Custom Profile Fields
		if ($show['edit_custom_profile'])
		{
			$user->set_userfields($vbulletin->GPC['form']['customprofile'], false, 'admin');
		}

		// Instant Messengers
		if ($show['edit_im'])
		{
			$user->set('icq', $vbulletin->GPC['form']['icq']);
			$user->set('aim', $vbulletin->GPC['form']['aim']);
			$user->set('msn', $vbulletin->GPC['form']['msn']);
			$user->set('yahoo', $vbulletin->GPC['form']['yim']);

			// 3.5.2+ only
			if ($user->validfields['skype'])
			{
				$user->set('skype', $vbulletin->GPC['form']['skype']);
			}
		}

		// Usergroups
		if ($show['edit_usergroups'])
		{
			$user->set('usergroupid', $vbulletin->GPC['form']['primary_usergroup']);
			$user->set('membergroupids', $vbulletin->GPC['form']['membergroupids']);
		}

		// Options
		if ($show['edit_options'])
		{
			$user->set_bitfield('options', 'adminemail', $vbulletin->GPC['form']['option_recieve_admin_email']);
			$user->set_bitfield('options', 'showemail', $vbulletin->GPC['form']['option_receive_other_emails']);
			$user->set_bitfield('options', 'invisible', $vbulletin->GPC['form']['option_invisible']);
			$user->set_bitfield('options', 'showvcard', $vbulletin->GPC['form']['option_allow_vcard']);
			$user->set_bitfield('options', 'receivepm', $vbulletin->GPC['form']['option_receive_pm']);
			$user->set_bitfield('options', 'emailonpm', $vbulletin->GPC['form']['option_pm_notification']);
			$user->set_bitfield('options', 'showsignatures', $vbulletin->GPC['form']['option_display_sig']);
			$user->set_bitfield('options', 'showavatars', $vbulletin->GPC['form']['option_display_avatars']);
			$user->set_bitfield('options', 'showimages', $vbulletin->GPC['form']['option_display_images']);

			$user->set('pmpopup', $vbulletin->GPC['form']['option_pm_popup']);
			$user->set('autosubscribe', $vbulletin->GPC['form']['option_auto_thread_sub']);
			$user->set('threadedmode', $vbulletin->GPC['form']['option_thread_display_mode']);
			$user->set('showvbcode', $vbulletin->GPC['form']['option_editor']);
			$user->set('styleid', $vbulletin->GPC['form']['option_style']);
		}

		// Save the account
		$user->save();

		// Log
		log_admin_action('Edited User ID: ' . $vbulletin->GPC['userid']);

		// Setup the template
		$pagetitle = $vbphrase['que_user_account_changes_saved'];
		$templatename = 'que_changes_saved';

	}

}

eval('$html = "' . fetch_template($templatename) . '";');
eval('print_output("' . fetch_template('que_shell') . '");');

?>