<?php
/**
* @package RSForm! Pro
* @copyright (C) 2007-2019 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class RsformModelDirectory extends JModelLegacy
{
    protected $fields;
    protected $_db;
    protected $_app;

	/* @var $params Joomla\Registry\Registry */
    public $params;

    protected $validation;

    public function __construct($config = array())
    {
        $this->_app     = JFactory::getApplication();
        $this->_db      = JFactory::getDbo();
        $this->params   = $this->_app->getParams('com_rsform');
        $this->itemid   = $this->getItemid();
        $this->context  = 'com_rsform.directory' . $this->itemid;

        // Check for a valid form
        $this->isValid();

		// For legacy menu items
		$userId	= $this->params->get('userId');
		if ($userId === '0')
		{
			$this->params->set('show_all_submissions', 1);
		}
		elseif ($userId == 'login')
		{
			$this->params->set('show_logged_in_submissions', 1);
		}

        $this->getFields();

        parent::__construct($config);
    }

    /**
     * Check if we are allowed to show content
	 *
	 * @throws Exception
	 *
     */
    public function isValid()
    {
        if (!$this->params->get('enable_directory', 0))
        {
            throw new Exception(JText::_('RSFP_VIEW_DIRECTORY_NOT_ENABLED_FORGOT'));
        }

        // Do we have a valid formId
        $formId = $this->params->get('formId', 0);
        if (empty($formId))
        {
			throw new Exception(JText::sprintf('RSFP_VIEW_DIRECTORY_NO_VALID_FORMID', $formId));
        }

        // Check if the directory exists
		$query = $this->_db->getQuery(true)
			->select($this->_db->qn('formId'))
			->from($this->_db->qn('#__rsform_directory'))
			->where($this->_db->qn('formId') . ' = ' . $this->_db->q($formId));

        if (!$this->_db->setQuery($query)->loadResult())
        {
			throw new Exception(JText::_('RSFP_VIEW_DIRECTORY_NOT_SAVED_YET'));
        }

        return true;
    }

    /**
     *    Get directory fields
     */
    public function getFields()
    {
        if (!is_array($this->fields))
        {
            $this->fields = RSFormProHelper::getDirectoryFields($this->params->get('formId'));
        }

        return $this->fields;
    }

    /**
     *    Submissions query
     */
    public function getListQuery()
    {
        // Get query
        $db = &$this->_db;
        $query = $db->getQuery(true);

        // Get headers
        $fields = $this->getFields();
        $headers = RSFormProHelper::getDirectoryStaticHeaders();

        // Check if it's a search.
        $search = $this->getSearch();

        // If 'Show Only Filtering Result' is enabled, don't create a query unless the user searches for something
        if ($this->params->get('show_filtering_result', 0) && !strlen($search))
		{
			return false;
		}

        // Get the SubmissionId
        $query->select($db->qn('s.SubmissionId'))
            ->from($db->qn('#__rsform_submission_values', 'sv'))
            ->join('left', $db->qn('#__rsform_submissions', 's') . ' ON (' . $db->qn('sv.SubmissionId') . '=' . $db->qn('s.SubmissionId') . ')')
            ->where($db->qn('s.FormId') . '=' . $db->q($this->params->get('formId')))
            ->group($db->qn('s.SubmissionId'))
            ->order($db->qn($this->getListOrder()) . ' ' . $db->escape($this->getListDirn()));

        // Show only confirmed submissions?
        if ($this->params->get('show_confirmed', 0))
        {
            $query->where($db->qn('s.confirmed') . '=' . $db->q(1));
        }

        // Show only submissions for selected language
        if ($lang = $this->params->get('lang', ''))
        {
            $query->where($db->qn('s.Lang') . '=' . $db->q($lang));
        }

        if (!$this->params->get('show_all_submissions'))
		{
			// Check if we need to show only submissions related to UserId.
			$userId = $this->params->get('userId');

			if ($this->params->get('show_logged_in_submissions'))
			{
				// Get only logged in user's submissions
				$user = JFactory::getUser();

				// Do not continue if he's a guest.
				if ($user->guest)
				{
					return false;
				}

				$query->where($db->qn('s.UserId') . '=' . $db->q($user->get('id')));
			}
			elseif ($userId)
			{
				// Show only the submissions of these users
				$userIds = explode(',', $userId);
				$userIds = array_map('intval', $userIds);

				if ($userIds)
				{
					$query->where($db->qn('s.UserId') . ' IN (' . implode(',', $userIds) . ')');
				}
			}
		}

        $needsSelect = array();
		if ($filters = $this->params->get('filter_values', array()))
		{
			$allHaving = array();
			$glue = $this->params->get('filter_glue', 'OR');

			if (is_array($filters) && isset($filters['name']) && is_array($filters['name']))
			{
				for ($i = 0; $i < count($filters['name']); $i++)
				{
					$name = $filters['name'][$i];
					if (isset($filters['operator'][$i]))
					{
						$operator = $filters['operator'][$i];
					}
					else
					{
						continue;
					}

					if (isset($filters['value'][$i]))
					{
						$value = $filters['value'][$i];
					}
					else
					{
						continue;
					}

					if ($this->isValidField($name))
					{
						$needsSelect[] = $name;
						$having = $db->qn($name);

						switch ($operator)
						{
							default:
							case 'is':
								$having .= ' = ' . $db->q($value);
								break;

							case 'is_not':
								$having .= ' != ' . $db->q($value);
								break;

							case 'contains':
								$having .= ' LIKE ' . $db->q('%' . $db->escape($value, true) . '%', false);
								break;

							case 'contains_not':
								$having .= ' NOT LIKE ' . $db->q('%' . $db->escape($value, true) . '%', false);
								break;

							case 'starts':
								$having .= ' LIKE ' . $db->q($db->escape($value, true) . '%', false);
								break;

							case 'starts_not':
								$having .= ' NOT LIKE ' . $db->q($db->escape($value, true) . '%', false);
								break;

							case 'ends':
								$having .= ' LIKE ' . $db->q('%' . $db->escape($value, true), false);
								break;

							case 'ends_not':
								$having .= ' NOT LIKE ' . $db->q('%' . $db->escape($value, true), false);
								break;
						}

						$allHaving[] = $having;
					}
				}
			}

			if ($allHaving)
			{
				$query->having('(' . implode(' ' . $glue . ' ', $allHaving) . ')', 'AND');
			}
		}

        // Iterate through fields to build the query
		if ($fields)
		{
			$allHaving = array();
			foreach ($fields as $field)
			{
				// If the field is viewable or searchable, we need to select() it.
				if ($field->viewable || $field->searchable || in_array($field->FieldName, $needsSelect))
				{
					if ($field->componentId < 0 && isset($headers[$field->componentId]))
					{
						// Static headers.
						// Select the value.
						if ($field->FieldName == 'confirmed')
						{
							// Make sure we display a text instead of 0 and 1.
							$query->select('IF(' . $db->qn('s.confirmed') . ' = ' . $db->q(1) . ', ' . $db->q(JText::_('RSFP_YES')) . ', ' . $db->q(JText::_('RSFP_NO')) . ') AS ' . $db->qn('confirmed'));
						}
						else
						{
							$query->select($db->qn('s.' . $field->FieldName));
						}
					}
					else
					{
						// Dynamic headers.
						// Select the value.
						$query->select('GROUP_CONCAT(IF(' . $db->qn('sv.FieldName') . '=' . $db->q($field->FieldName) . ', ' . $db->qn('sv.FieldValue') . ', NULL)) AS ' . $db->qn($field->FieldName));
					}

					// If we're searching, add the field to the having() query.
					if ($search && $field->searchable)
					{
						// DateSubmitted doesn't play well with LIKE
						if ($field->FieldId == '-1' && preg_match('#([^0-9\-: ])#', $search))
						{
							continue;
						}

						$allHaving[] = $db->qn($field->FieldName) . ' LIKE ' . $db->q('%' . $db->escape($search, true) . '%', false);
					}
				}
			}

			if ($allHaving)
			{
				$query->having('(' . implode(' OR ', $allHaving) . ')', 'AND');
			}
		}

		JFactory::getApplication()->triggerEvent('onRsformAfterManageDirectoriesQueryCreated', array(&$query, $this->params->get('formId')));

        return $query;
    }

    protected function isValidField($name)
	{
		static $fields;

		if ($fields === null)
		{
			$fields = RSFormProHelper::getDirectoryStaticHeaders();
			if ($allFields = RSFormProHelper::getComponents($this->params->get('formId')))
			{
				foreach ($allFields as $field)
				{
					$fields[] = $field->name;
				}
			}
		}

		return in_array($name, $fields);
	}

    public function setGroupConcatLimit()
    {
        $this->_db->setQuery("SET SESSION `group_concat_max_len` = 1000000")->execute();
    }

    /**
     *    Get Submissions
     */
    public function getItems()
    {
        $mainframe = JFactory::getApplication();

        $this->setGroupConcatLimit();

        if ($query = $this->getListQuery())
        {
            $this->_db->setQuery($query, $this->getStart(), $this->getLimit());
            $items = $this->_db->loadObjectList();
        }
        else
        {
            $items = array();
        }

        // small workaround - we need to have only string keys for the items
        foreach ($items as $i => $item)
        {
            $newItem = new stdClass();

            foreach ($item as $key => $value)
            {
                $newItem->{((string)$key)} = $value;
            }

            $items[$i] = $newItem;
        }

        $mainframe->triggerEvent('onRsformAfterManageDirectoriesQuery', array(&$items, $this->params->get('formId')));

        list($multipleSeparator, $uploadFields, $multipleFields, $textareaFields, $secret) = RSFormProHelper::getDirectoryFormProperties($this->params->get('formId'));

        $this->uploadFields = $uploadFields;
        $this->multipleFields = $multipleFields;

        if ($items)
        {
            foreach ($items as $item)
            {
                foreach ($uploadFields as $field)
                {
                    if (!empty($item->{$field}))
                    {
                    	$files = RSFormProHelper::explode($item->{$field});

                    	$values = array();
                    	foreach ($files as $file)
						{
							$values[] = '<a href="' . JRoute::_('index.php?option=com_rsform&task=submissions.viewfile&hash=' . md5($item->SubmissionId . $secret . $field) . '&file=' . md5($file)) . '">' . RSFormProHelper::htmlEscape(basename($file)) . '</a>';
						}
                    	
                        $item->{$field} = implode('<br />', $values);
                    }
                }

                foreach ($multipleFields as $field)
                {
                    if (isset($item->{$field}))
                    {
                        $item->{$field} = str_replace("\n", $multipleSeparator, RSFormProHelper::htmlEscape($item->{$field}));
                    }
                }
            }
        }

        return $items;
    }

    public function getAdditionalUnescaped()
    {
        $unescapedFields = array();

        JFactory::getApplication()->triggerEvent('onRsformBackendManageDirectoriesCreateUnescapedFields', array(array('fields' => & $unescapedFields, 'formId' => $this->params->get('formId'))));

        return $unescapedFields;
    }

    /**
     *    Get directory details
     */
    public function getDirectory()
    {
        static $table;

        if (is_null($table))
        {
            $table = JTable::getInstance('RSForm_Directory', 'Table');
            $table->load($this->params->get('formId', 0));
        }

        return $table;
    }

    public function getTemplate()
    {
        $cid 		= $this->_app->input->getInt('id', 0);
        $format 	= $this->_app->input->get('format');
        $user 		= JFactory::getUser();
        $userId 	= $this->params->get('userId');
        $directory 	= $this->getDirectory();
        $template 	= $directory->ViewLayout;

        if (!$this->params->get('show_logged_in_submissions') && !$this->params->get('show_all_submissions'))
        {
            $userId = explode(',', $userId);
            $userId = array_map('intval', $userId);
        }

        // Grab submission
        require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/submissions.php';
        $submission = RSFormProSubmissionsHelper::getSubmission($cid, false);

        // Submission doesn't exist
        if (!$submission)
        {
            $this->_app->enqueueMessage(JText::sprintf('RSFP_SUBMISSION_DOES_NOT_EXIST', $cid), 'warning');
            return $this->_app->redirect(JRoute::_('index.php?option=com_rsform&view=directory', false));
        }

        // Submission doesn't belong to the configured form ID OR
        // can view only own submissions and not his own OR
        // can view only specified user IDs and this doesn't belong to any of the IDs
        if (($submission->FormId != $this->params->get('formId')) || ($this->params->get('show_logged_in_submissions') && $submission->UserId != $user->get('id')) || (is_array($userId) && !in_array($user->get('id'), $userId)))
        {
            $this->_app->enqueueMessage(JText::sprintf('RSFP_SUBMISSION_NOT_ALLOWED', $cid), 'warning');
            return $this->_app->redirect(JRoute::_('index.php?option=com_rsform&view=directory', false));
        }

        if ($this->params->get('show_confirmed', 0) && !$submission->confirmed)
        {
            $this->_app->enqueueMessage(JText::sprintf('RSFP_SUBMISSION_NOT_CONFIRMED', $cid), 'warning');
            return $this->_app->redirect(JRoute::_('index.php?option=com_rsform&view=directory', false));
        }

        $confirmed = $submission->confirmed ? JText::_('RSFP_YES') : JText::_('RSFP_NO');

        list($replace, $with) = RSFormProHelper::getReplacements($cid);
        $replace = array_merge($replace, array('{global:confirmed}', '{global:lang}'));
        $with = array_merge($with, array($confirmed, $submission->Lang));

        if ($format == 'pdf')
        {
            if (strpos($template, ':path}') !== false)
            {
                $template = str_replace(':path}', ':localpath}', $template);
            }

            $template = str_replace('{sitepath}', JPATH_SITE, $template);
        }
        else
        {
            $template = str_replace('{sitepath}', JUri::root(), $template);
        }

        if (strpos($template, '{/if}') !== false)
        {
            require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/scripting.php';
            RSFormProScripting::compile($template, $replace, $with);
        }

        $detailsLayout = str_replace($replace, $with, $template);
        eval($directory->DetailsScript);

        // Set filename
        $directory->filename = str_replace($replace, $with, $directory->filename);

        return $detailsLayout;
    }

    public function delete($id)
    {
        require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/submissions.php';

        RSFormProSubmissionsHelper::deleteSubmissions($id, true);
    }

	public function save()
	{
		$app        = JFactory::getApplication();
		$db         = JFactory::getDbo();
		$cid    	= $app->input->getInt('id');
        $formId 	= $app->input->getInt('formId');
        $form       = $app->input->post->get('form', array(), 'array');
        $delete     = $app->input->post->get('delete', array(), 'array');
        $static     = $app->input->post->get('formStatic', array(), 'array');
        $files      = $app->input->files->get('form', array(), 'array');
		$validation = RSFormProHelper::validateForm($formId, 'directory', $cid);

		$this->validation =& $validation;

		if (!empty($validation))
		{
			return false;
		}

		$formFields 	= RSFormProHelper::getDirectoryFields($formId);
		$headers 		= RSFormProHelper::getDirectoryStaticHeaders();
		$staticFields   = array();
		$allowed		= array();

		foreach ($formFields as $field)
		{
			if ($field->editable)
			{
				if ($field->componentId < 0 && isset($headers[$field->componentId]))
				{
					$staticFields[] = $field->FieldName;
				}
				else
				{
					$allowed[] = $field->FieldName;
				}
			}
		}

		//Trigger Event - onBeforeDirectorySave
		$this->_app->triggerEvent('onRsformFrontendBeforeDirectorySave', array(array('SubmissionId' => &$cid, 'formId' => $formId, 'post' => &$form)));

		require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/submissions.php';
		$submission = RSFormProSubmissionsHelper::getSubmission($cid);

		// Check if submission exists
		if (!$submission)
		{
			return false;
		}

		list($multipleSeparator, $uploadFields, $multipleFields, $textareaFields, $secret) = RSFormProHelper::getDirectoryFormProperties($this->params->get('formId'));

		// Handle file uploads first
		if ($allowedUploadFields = array_intersect($uploadFields, $allowed))
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/fields/fileupload.php';

			foreach ($allowedUploadFields as $field)
			{
				if ($componentId = RSFormProHelper::getComponentId($field, $formId))
				{
					$data = RSFormProHelper::getComponentProperties($componentId);

					$f = new RSFormProFieldFileUpload(array(
						'formId' 		=> $formId,
						'componentId' 	=> $componentId,
						'data' 			=> $data,
					));

					$multiple = $f->getProperty('MULTIPLE', false);

					// If it's a multiple upload field, append new values by initializing the array with the values from the submission
					if (!empty($submission->values[$field]))
					{
						$form[$field] = RSFormProHelper::explode($submission->values[$field]);
					}
					else
					{
						$form[$field] = array();
					}

					// Remove requested values
					if (!empty($delete[$field]))
					{
						$f->removeHashedValues($form[$field], $delete[$field]);
					}

					// Upload has been successful.
					if ($object = $f->processBeforeStore($submission->SubmissionId, $form, $files, false))
					{
						// Merge new values, otherwise replace them
						if ($multiple)
						{
							$form[$field] = array_merge($form[$field], RSFormProHelper::explode($object->FieldValue));
						}
						else
						{
							$form[$field] = RSFormProHelper::explode($object->FieldValue);
						}
					}
				}
			}
		}

		// Update fields
		if ($allowed)
		{
			foreach ($allowed as $field)
			{
				$value = isset($form[$field]) ? $form[$field] : '';
				if (is_array($value))
				{
					$value = implode("\n", $value);
				}

				// Dynamic field - update value.
				$object = (object) array(
					'FormId' 		=> $formId,
					'SubmissionId' 	=> $cid,
					'FieldName'		=> $field,
					'FieldValue'	=> $value
				);

				if (!isset($submission->values[$field]))
				{
					$db->insertObject('#__rsform_submission_values', $object);
				}
				elseif ($submission->values[$field] !== $value)
				{
					// Update only if we've changed something
					$db->updateObject('#__rsform_submission_values', $object, array('SubmissionId', 'FormId', 'FieldName'));
				}
			}
		}

		$offset = JFactory::getConfig()->get('offset');
		if ($static && $staticFields)
		{
			// Static, update submission
			$query = $db->getQuery(true)
				->update('#__rsform_submissions')
				->where($db->qn('SubmissionId').'='.$db->q($cid));

			foreach ($staticFields as $field)
			{
				if (!isset($static[$field]))
				{
					$static[$field] = '';
				}

				if ($field == 'DateSubmitted')
				{
					$static[$field] = JFactory::getDate($static[$field], $offset)->toSql();
				}

				$query->set($db->qn($field) . '=' . $db->q($static[$field]));
			}

			$db->setQuery($query)->execute();
		}

		// Send emails
		$this->sendEmails($formId, $cid);
		return true;
	}

	public function sendEmails($formId, $SubmissionId)
	{
		$directory = $this->getDirectory();

		$query = $this->_db->getQuery(true)
			->select($this->_db->qn('Lang'))
			->from($this->_db->qn('#__rsform_submissions'))
			->where($this->_db->qn('FormId') . ' = ' . $this->_db->q($formId))
			->where($this->_db->qn('SubmissionId') . ' = ' . $this->_db->q($SubmissionId));

		$lang = $this->_db->setQuery($query)->loadResult();

		list($placeholders,$values) = RSFormProHelper::getReplacements($SubmissionId);

		$query->clear()
			->select('*')
			->from($this->_db->qn('#__rsform_emails'))
			->where($this->_db->qn('type') . ' = ' . $this->_db->q('directory'))
			->where($this->_db->qn('formId') . ' = ' . $this->_db->q($formId))
			->where($this->_db->qn('from') . ' != ' . $this->_db->q(''));

		if ($emails = $this->_db->setQuery($query)->loadObjectList())
		{
			$etranslations = RSFormProHelper::getTranslations('emails', $formId, $lang);

			foreach ($emails as $email)
			{
				foreach (array('fromname', 'subject', 'message', 'replytoname') as $value)
				{
					if (isset($etranslations[$email->id . '.' . $value]))
					{
						$email->{$value} = $etranslations[$email->id . '.' . $value];
					}
				}

				if (empty($email->fromname) || empty($email->subject) || empty($email->message))
				{
					continue;
				}

				$directoryEmail = array(
					'to' 			=> $email->to,
					'cc' 			=> $email->cc,
					'bcc' 			=> $email->bcc,
					'from' 			=> $email->from,
					'replyto' 		=> $email->replyto,
					'replytoName' 	=> $email->replytoname,
					'fromName' 		=> $email->fromname,
					'text' 			=> $email->message,
					'subject' 		=> $email->subject,
					'mode' 			=> $email->mode,
					'files' 		=> array()
				);
				
				eval($directory->EmailsCreatedScript);
				
				// RSForm! Pro Scripting
				// performance check
				if (strpos($directoryEmail['text'], '{/if}') !== false)
				{
					require_once JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/scripting.php';
					RSFormProScripting::compile($directoryEmail['text'], $placeholders, $values);
				}
				
				// Replace placeholders
				$directoryEmail = str_replace($placeholders, $values, $directoryEmail);

				// additional cc
				if (strpos($directoryEmail['cc'], ',') !== false)
				{
					$directoryEmail['cc'] = explode(',', $directoryEmail['cc']);
				}

				// additional bcc
				if (strpos($directoryEmail['bcc'], ',') !== false)
				{
					$directoryEmail['bcc'] = explode(',', $directoryEmail['bcc']);
				}

				//Trigger Event - beforeDirectoryEmail
				$this->_app->triggerEvent('onRsformBeforeDirectoryEmail', array(array('directory' => &$directory, 'placeholders' => &$placeholders, 'values' => &$values, 'submissionId' => $SubmissionId, 'directoryEmail'=>&$directoryEmail)));

				eval($directory->EmailsScript);

				// mail users
				$recipients = explode(',',$directoryEmail['to']);
				if (!empty($recipients))
				{
					foreach ($recipients as $recipient)
					{
						if (!empty($recipient))
						{
							RSFormProHelper::sendMail($directoryEmail['from'], $directoryEmail['fromName'], $recipient, $directoryEmail['subject'], $directoryEmail['text'], $directoryEmail['mode'], !empty($directoryEmail['cc']) ? $directoryEmail['cc'] : null, !empty($directoryEmail['bcc']) ? $directoryEmail['bcc'] : null, $directoryEmail['files'], !empty($directoryEmail['replyto']) ? $directoryEmail['replyto'] : '', !empty($directoryEmail['replytoName']) ? $directoryEmail['replytoName'] : null);
						}
					}
				}
			}
		}
	}

	public function getUploadFields()
	{
		return $this->uploadFields;
	}

	public function getMultipleFields()
	{
		return $this->multipleFields;
	}

	public function getTotal()
	{
		if ($query = $this->getListQuery())
		{
			$this->_db->setQuery($query)->execute();

			return $this->_db->getNumRows();
		}

		return 0;
	}

	public function getPagination()
	{
		return new JPagination($this->getTotal(), $this->getStart(), $this->getLimit());
	}

	public function getStart() {
		static $limitstart;

		if (is_null($limitstart))
		{
			$limitstart	= JFactory::getApplication()->input->get('limitstart', 0, '', 'int');
		}
		return $limitstart;
	}

	public function getLimit()
	{
		static $limit;

		if (is_null($limit))
		{
			$limit = JFactory::getApplication()->input->get('limit', $this->params->get('display_num'), '', 'int');
		}

		return $limit;
	}

	public function getSearch()
	{
		return $this->_app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
	}

	public function getListOrder()
	{
		return $this->_app->getUserStateFromRequest($this->context.'.filter.filter_order', 'filter_order', 'SubmissionId', '');
	}

	public function getListDirn()
	{
		return $this->_app->getUserStateFromRequest($this->context.'.filter.filter_order_Dir', 'filter_order_Dir', 'desc', 'word');
	}

	public function getEditFields()
	{
		$db			= JFactory::getDbo();
		$app		= JFactory::getApplication();
		$return		= array();
		$values		= $app->input->get('form',array(),'array');
		$cid		= $this->_app->input->getInt('id');

		// Load submission
		require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/submissions.php';
		$submission = RSFormProSubmissionsHelper::getSubmission($cid);

		if (!$submission)
		{
			return $return;
		}

		$query = $db->getQuery(true)
			->select($db->qn('MultipleSeparator'))
			->select($db->qn('TextareaNewLines'))
			->from($db->qn('#__rsform_forms'))
			->where($db->qn('FormId') . ' = ' . $db->q($submission->FormId));
		$form = $db->setQuery($query)->loadObject();

		$form->MultipleSeparator = str_replace(array('\n', '\r', '\t'), array("\n", "\r", "\t"), $form->MultipleSeparator);

		$submission->DateSubmitted = JHtml::_('date', $submission->DateSubmitted, 'Y-m-d H:i:s');

		if (is_array($this->validation))
		{
			$validation = $this->validation;
		}
		elseif (!empty($values))
		{
			$validation = RSFormProHelper::validateForm($submission->FormId, 'directory', $cid);
		}
		else
		{
			$validation = array();
		}
		$headers        = RSFormProHelper::getDirectoryStaticHeaders();
		$formFields 	= RSFormProHelper::getDirectoryFields($submission->FormId);

		$query = $db->getQuery(true);
		$query->select($db->qn('ct.ComponentTypeName', 'type'))
			->select($db->qn('c.ComponentId'))
			->select($db->qn('ct.ComponentTypeId'))
			->from($db->qn('#__rsform_components', 'c'))
			->join('left', $db->qn('#__rsform_component_types', 'ct').' ON ('.$db->qn('c.ComponentTypeId').'='.$db->qn('ct.ComponentTypeId').')')
			->where($db->qn('c.FormId').'='.$db->q($submission->FormId))
			->where($db->qn('c.Published').'='.$db->q(1));
		$componentTypes = $db->setQuery($query)->loadObjectList('ComponentId');

		$componentIds = array();
		foreach ($formFields as $formField) {
			if ($formField->FieldId > 0) {
				$componentIds[] = $formField->FieldId;
			}

			// Assign the type
			$formField->type = '';
			if ($formField->FieldId < 0 && isset($headers[$formField->FieldId])) {
				$formField->type = 'static';
			} elseif (isset($componentTypes[$formField->FieldId])) {
				$formField->type = $componentTypes[$formField->FieldId]->type;
			}

			// For convenience...
			$formField->id 		= $formField->FieldId;
			$formField->name 	= $formField->FieldName;
		}

		$properties	= RSFormProHelper::getComponentProperties($componentIds, false);

		// Apply translations based on the current submission's language
		if ($translations = RSFormProHelper::getTranslations('properties', $submission->FormId, $submission->Lang))
		{
			foreach ($translations as $reference_id => $translation)
			{
				list ($componentId, $property) = explode('.', $reference_id, 2);

				if (isset($properties[$componentId][$property]))
				{
					$properties[$componentId][$property] = $translation;
				}
			}
		}

		$YUICalendars = false;
		$jQueryCalendars = false;

		foreach ($formFields as $field)
		{
			if (!$field->editable) {
				continue;
			}

			$invalid		= !empty($validation) && in_array($field->id,$validation) ? ' rsform-error' : '';
			$data			= $field->id > 0 ? $properties[$field->id] : array('NAME' => $field->name);
			$name			= $field->name;
			$new_field		= array(
				RSFORM_DIR_CAPTION      => !empty($data['CAPTION']) ? $data['CAPTION'] : $field->name,
				RSFORM_DIR_INPUT        => '',
				RSFORM_DIR_REQUIRED     => isset($data['REQUIRED']) && $data['REQUIRED'] == 'YES' ? '<strong class="formRequired">(*)</strong>' : '',
				RSFORM_DIR_NAME         => $field->name,
				RSFORM_DIR_DESCRIPTION  => isset($data['DESCRIPTION']) ? $data['DESCRIPTION'] : ''
			);

			if ($invalid)
			{
				if (isset($data['VALIDATIONMESSAGE']))
				{
					$new_field[RSFORM_DIR_VALIDATION] = '<div id="component' . $field->id . '" class="dirError">' . $data['VALIDATIONMESSAGE'] . '</span>';
				}
			}

			if ($field->type != 'static') {
				if (isset($values[$field->name]))
					$value	= $values[$field->name];
				else {
					$value	= isset($submission->values[$field->name]) ? $submission->values[$field->name] : '';
				}
			} else {
				$value = isset($submission->{$field->name}) ? $submission->{$field->name} : '';
			}

			$placeholder = '';
			if (isset($data['PLACEHOLDER']))
			{
				$placeholder = 'placeholder="' . RSFormProHelper::htmlEscape($data['PLACEHOLDER']) . '"';
			}

			switch ($field->type)
			{
				case 'static':
					$new_field[RSFORM_DIR_CAPTION] = JText::_('RSFP_'.$field->name);

					// Show a dropdown for yes/no
					if ($field->name == 'confirmed')
					{
						$options = array(
							JHtml::_('select.option', 0, JText::_('RSFP_NO')),
							JHtml::_('select.option', 1, JText::_('RSFP_YES'))
						);

						$new_field[RSFORM_DIR_INPUT] = JHtml::_('select.genericlist', $options, 'formStatic[confirmed]', null, 'value', 'text', $value);
					}
					else
					{
						$new_field[RSFORM_DIR_INPUT] = '<input class="rs_inp rs_80" type="text" name="formStatic['.$name.']" value="'.RSFormProHelper::htmlEscape($value).'" />';
					}
					break;

				// skip this field for now, no need to edit it
				case 'freeText':
					$new_field[RSFORM_DIR_CAPTION] = '';
					$new_field[RSFORM_DIR_INPUT] = RSFormProHelper::isCode($data['TEXT']);
					break;

				default:
					if (is_array($value))
					{
						$value = implode($form->MultipleSeparator, $value);
					}

					if (strpos($value, "\n") !== false || strpos($value, "\r") !== false)
					{
						$new_field[RSFORM_DIR_INPUT] = '<textarea ' . $placeholder . ' style="width: 95%" class="rs_textarea'.$invalid.'" rows="10" cols="60" name="form['.$name.']">'.RSFormProHelper::htmlEscape($value).'</textarea>';
					}
					else
					{
						$new_field[RSFORM_DIR_INPUT] = '<input class="rs_inp rs_80'.$invalid.'" type="text" name="form['.$name.']" value="'.RSFormProHelper::htmlEscape($value).'" ' . $placeholder . ' />';
					}
					break;

				case 'textArea':
					if (isset($data['WYSIWYG']) && $data['WYSIWYG'] == 'YES')
					{
						$new_field[RSFORM_DIR_INPUT] = RSFormProHelper::WYSIWYG('form['.$name.']', RSFormProHelper::htmlEscape($value), '', 600, 100, 60, 10);
					}
					else
					{
						$new_field[RSFORM_DIR_INPUT] = '<textarea ' . $placeholder . ' style="width: 95%" class="rs_textarea'.$invalid.'" rows="10" cols="60" name="form['.$name.']">'.RSFormProHelper::htmlEscape($value).'</textarea>';
					}
					break;

				case 'checkboxGroup':
				case 'radioGroup':
					$options = array();
					$value = !empty($values) ? $value : RSFormProHelper::explode($value);
					$value = (array) $value;

					if ($field->type === 'checkboxGroup')
					{
						$htmlType = 'checkbox';
						$htmlName = 'form[' . RSFormProHelper::htmlEscape($name) . '][]';
					}
					else
					{
						$htmlName = 'form[' . RSFormProHelper::htmlEscape($name) . ']';
						$htmlType = 'radio';
					}

					require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/fields/fielditem.php';
					require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/fieldmultiple.php';
					$f = new RSFormProFieldMultiple(array(
						'formId' 			=> $submission->FormId,
						'componentId' 		=> $field->id,
						'data' 				=> $data,
						'value' 			=> array('formId' => $submission->FormId, $data['NAME'] => $value),
						'invalid' 			=> in_array($field->id, $validation)
					));

					if ($items = $f->getItems())
					{
						$i = 0;
						foreach ($items as $item)
						{
							$item = new RSFormProFieldItem($item);

							$html = '<label><input type="' . $htmlType . '" ' .
							' name="' . $htmlName . '"' .
							' value="' . RSFormProHelper::htmlEscape($item->value) . '"' .
							' id="' . RSFormProHelper::htmlEscape($name) . $i . '"';

							if ($item->flags['disabled']) {
								$html .= ' disabled="disabled"';
							}

							if (in_array($item->value, $value)) {
								$html .= ' checked="checked"';
							}

							if ($invalid)
							{
								$html .= ' class="' . $invalid . '"';
							}

							$html .= '> ' . $item->label . '</label>';

							$options[] = $html;

							$i++;
						}
					}

					if ($max = (int) $f->getProperty('MAXSELECTIONS'))
					{
						$id = $f->getId();
						RSFormProAssets::addScriptDeclaration("RSFormPro.limitSelections({$submission->FormId}, '{$id}', {$max});");
					}

					$new_field[RSFORM_DIR_INPUT] = '<p>' . implode('</p><p>', $options) . '</p>';

					break;

				case 'selectList':
					$options = array();

					$value = !empty($values) ? $value : RSFormProHelper::explode($value);

					require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/fields/fielditem.php';
					require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/fieldmultiple.php';
					$f = new RSFormProFieldMultiple(array(
						'formId' 			=> $submission->FormId,
						'componentId' 		=> $field->id,
						'data' 				=> $data,
						'value' 			=> array('formId' => $submission->FormId, $data['NAME'] => $value),
						'invalid' 			=> in_array($field->id, $validation)
					));

					if ($items = $f->getItems())
					{
						foreach ($items as $item)
						{
							$item = new RSFormProFieldItem($item);

							if ($item->flags['optgroup']) {
								$options[] = JHtml::_('select.option', '<OPTGROUP>', $item->label, 'value', 'text');
							} elseif ($item->flags['/optgroup']) {
								$options[] = JHtml::_('select.option', '</OPTGROUP>', $item->label, 'value', 'text');
							} else {
								$options[] = JHtml::_('select.option', $item->value, $item->label, 'value', 'text', $item->flags['disabled']);
							}
						}
					}

					$attribs = array();

					if ((int) $data['SIZE'] > 0)
					{
						$attribs[] = 'size="'.(int) $data['SIZE'].'"';
					}

					if ($data['MULTIPLE'] == 'YES')
					{
						$attribs[] = 'multiple="multiple"';
					}

					if ($invalid)
					{
						$attribs[] = 'class="' . $invalid . '"';
					}

					$attribs = implode(' ', $attribs);

					$new_field[RSFORM_DIR_INPUT] = JHtml::_('select.genericlist', $options, 'form['.$name.'][]', $attribs, 'value', 'text', $value);
					break;

				case 'fileUpload':
					if ($value)
					{
						$files = RSFormProHelper::explode($value);
					}
					else
					{
						$files = array();
					}

					$new_field[RSFORM_DIR_INPUT] = '<div>';

					foreach ($files as $file)
					{
						$new_field[RSFORM_DIR_INPUT] .= '<p><button type="button" class="btn btn-secondary btn-small btn-sm" onclick="RSFormProDirectory.clearUpload(\'' . $name . '\', this, \'' . md5($file) . '\');">' . JText::_('COM_RSFORM_CLEAR') . '</button> <span' . ($invalid ? ' class="' . $invalid . '"' : '') . '>' . RSFormProHelper::htmlEscape(basename($file)) . '</span></p>';
					}

					$new_field[RSFORM_DIR_INPUT] .= '</div>';

					$multiple =  !empty($data['MULTIPLE']) && $data['MULTIPLE'] == 'YES';

					$new_field[RSFORM_DIR_INPUT] .= '<input size="45" type="file" name="form['.$name.']' . ($multiple ? '[]' : '') . '" ' . ($multiple ? 'multiple' : '') . ' />';
					break;

				case 'jQueryCalendar':
				case 'calendar':
					if ($field->type === 'jQueryCalendar')
					{
						$jQueryCalendars = true;
					}

					if ($field->type === 'calendar')
					{
						$YUICalendars = true;
					}

					$type = (string) preg_replace('/[^A-Z0-9_\.-]/i', '', strtolower($field->type));
					$type = ltrim($type, '.');
					// For legacy reasons...
					$r = array(
						'ComponentTypeId' => $field->FieldType,
						'Order'			  => isset($data['Order']) ? $data['Order'] : 0
					);

					// Emulate variables
					$out = '';
					$formId = $submission->FormId;
					$val = isset($values[$field->name]) ? $values : $submission->values;
					$data['componentTypeId'] 	= $field->FieldType;
					$data['ComponentTypeName'] 	= $field->type;
					$data['Order'] 				= $field->ordering;

					$app->triggerEvent('onRsformBackendBeforeCreateFrontComponentBody', array(array(
						'out' 			=> &$out,
						'formId' 		=> $submission->FormId,
						'componentId' 	=> $field->componentId,
						'data' 			=> &$data,
						'value' 		=> &$val
					)));

					$config = array(
						'formId' 			=> $formId,
						'componentId' 		=> $field->componentId,
						'data' 				=> $data,
						'value' 			=> $val,
						'invalid' 			=> $invalid,
						'errorClass' 		=> '',
						'fieldErrorClass' 	=> ''
					);

					require_once JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/fields/' . $type . '.php';

					$class = 'RSFormProField' . $type;

					// Create the field
					$fieldClass = new $class($config);

					$out .= $fieldClass->output;

					$app->triggerEvent('onRsformBackendAfterCreateFrontComponentBody', array(array(
						'out' 			=> &$out,
						'formId' 		=> $formId,
						'componentId' 	=> $fieldClass->componentId,
						'data' 			=> $data,
						'value' 		=> $val,
						'r'				=> $r,
						'invalid' 		=> $invalid
					)));

					$new_field[RSFORM_DIR_INPUT] = $out;

					break;
			}

			$return[$field->id] = $new_field;
		}

		if ($YUICalendars || $jQueryCalendars)
		{
			require_once JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/calendar.php';

			// render the YUI Calendars
			if ($YUICalendars)
			{
				$calendar = RSFormProCalendar::getInstance('YUICalendar');
				RSFormProAssets::addScriptDeclaration($calendar->printInlineScript($formId));
			}

			// render the jQuery Calendars
			if ($jQueryCalendars)
			{
				$calendar = RSFormProCalendar::getInstance('jQueryCalendar');
				RSFormProAssets::addScriptDeclaration($calendar->printInlineScript($formId));
			}
		}

		JFactory::getApplication()->triggerEvent('onRsformFrontendGetEditFields', array(&$return, $submission));

		return $return;
	}

	// Get current Itemid
	public function getItemid() {
		if ($active = $this->_app->getMenu()->getActive())
		{
			return $active->id;
		}

		return 0;
	}
}