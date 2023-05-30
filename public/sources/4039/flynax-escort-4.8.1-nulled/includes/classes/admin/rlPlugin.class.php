<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.8.1
 *  
 *  PRODUCT: Escort Classifieds
 *  DOMAIN: testdomain.com
 *  FILE: RLPLUGIN.CLASS.PHP
 *  
 *  The software is a commercial product delivered under single, non-exclusive,
 *  non-transferable license for one domain or IP address. Therefore distribution,
 *  sale or transfer of the file in whole or in part without permission of Flynax
 *  respective owners is considered to be illegal and breach of Flynax License End
 *  User Agreement.
 *  
 *  You are not allowed to remove this information from the file without permission
 *  of Flynax respective owners.
 *  
 *  Flynax Classifieds Software 2020 | All copyrights reserved.
 *  
 *  http://www.flynax.com/
 ******************************************************************************/

use Flynax\Interfaces\PluginInterface;
use Flynax\Classes\PluginManager;
use Symfony\Component\Filesystem\Exception\IOException;
use Flynax\Utils\Archive;

/**
 * xajax fallback class
 *
 * @todo - Remove this class once the $_response object usage will be remove
 *         from plugins installation (AndroidConnect and iFlynaxConnect)
 *
 * @since 4.8.1
 */
class xajaxFallback
{
    /**
     * js codes
     * @var array
     */
    private $jsCode = [];

    /**
     * Collect js code
     *
     * @param  string $code - js code
     */
    public function script($code = '')
    {
        if (!$code) {
            return;
        }

        $this->jsCode[] = $code;
    }

    /**
     * Get collected js code
     *
     * @return array - js code array
     */
    public function get()
    {
        return $this->jsCode;
    }
}

/**
 * SMARTY fallback class
 *
 * @todo - Remove this class once the $rlSmarty object usage will be remove
 *         from all plugins installation (weatherForeacast)
 *
 * @since 4.8.1
 */
class smartyFallback
{
    public function register_function() {}
    public function assign_by_ref() {}
    public function assign() {}
    public function display() {}
    public function fetch() {}
}

class rlPlugin
{
    public $inTag;
    public $level = 0;
    public $attributes;

    public $key;
    public $title;
    public $description;
    public $version;
    public $uninstall;
    public $hooks;
    public $phrases;
    public $configGroup;
    public $configs;
    public $blocks;
    public $aBlocks;
    public $pages;
    public $emails;
    public $files;
    public $notice;
    public $controller;

    public $updates;
    public $notices;
    public $controllerUpdate;

    public $noVersionTag = false;

    /**
     * Install plugin
     *
     * @param string  $key        - Plugin key
     * @param boolean $remoteMode - Remote installation mode
     **/
    public function ajaxInstall($key = false, $remoteMode = false)
    {
        global $rlLang, $languages, $rlDb, $reefless, $lang, $rlDebug;

        if (!$key) {
            return false;
        }

        // Create xajax fallback class
        global $_response;
        $_response = new xajaxFallback();

        // Create SMARTY fallback class
        global $rlSmarty;
        $rlSmarty = new smartyFallback();

        $this->noVersionTag = true;

        $out = [];

        if ($reefless->checkSessionExpire() === false) {
            return array(
                'status' => 'REDIRECT',
                'data' => 'session_expired'
            );
        }

        $path_to_install = RL_PLUGINS . $key . RL_DS . 'install.xml';

        if (is_readable($path_to_install)) {
            require_once RL_LIBS . 'saxyParser' . RL_DS . 'xml_saxy_parser.php';

            $rlParser = new SAXY_Parser();
            $rlParser->xml_set_element_handler(array($this, 'startElement'), array($this, 'endElement'));
            $rlParser->xml_set_character_data_handler(array($this, 'charData'));
            $rlParser->xml_set_comment_handler(array($this, 'commentElement'));

            // Parse xml file
            $rlParser->parse(file_get_contents($path_to_install));

            // Check compatibility with current version of the software
            if (!$this->checkCompatibilityByVersion($this->compatible)) {
                return array(
                    'status' => 'ERROR',
                    'message' => $rlLang->getSystem('plugin_not_compatible_notice')
                );
            }

            $plugin = array(
                'Key'         => $this->key,
                'Class'       => $this->uninstall['class'] ?: '',
                'Name'        => $this->title,
                'Description' => $this->description,
                'Version'     => $this->version,
                'Status'      => 'approval',
                'Install'     => 1,
                'Controller'  => $this->controller,
                'Uninstall'   => $this->uninstall['code'],
                'Files'       => serialize($this->files),
            );

            // Install plugin
            if ($rlDb->insertOne($plugin, 'plugins')) {
                // Install language's phrases
                $phrases = $this->phrases;

                foreach ($languages as $language) {
                    $locales[$language['Code']] = $locale = $this->getLanguagePhrases($language['Code'], $this->key);

                    if ($phrases) {
                        foreach ($phrases as $phrase) {
                            if ($phrase['Module'] == 'ext') {
                                $phrase['Module'] = 'admin';
                                $phrase['JS']     = '1';
                            }

                            $lang_keys[] = array(
                                'Code'       => $language['Code'],
                                'Module'     => $phrase['Module'],
                                'JS'         => $phrase['JS'],
                                'Target_key' => $phrase['Target_key'],
                                'Key'        => $phrase['Key'],
                                'Value'      => $locale[$phrase['Key']] ?: $phrase['Value'],
                                'Plugin'     => $this->key,
                                'Status'     => 'approval',
                            );
                        }
                    }
                }

                // Install hooks
                $hooks = $this->hooks;
                if (!empty($hooks)) {
                    $rlDb->insert($hooks, 'hooks');
                }

                // Install configs
                $cGroup = $configGroup = $this->configGroup;
                if (!empty($configGroup)) {
                    $cg_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}config_groups` LIMIT 1");
                    unset($cGroup['Name']);
                    $cGroup['Position'] = $cg_max_poss['max'] + 1;

                    $rlDb->insertOne($cGroup, 'config_groups');
                    $group_id = $rlDb->insertID();

                    // Add config group phrases
                    foreach ($languages as $language) {
                        $locale    = $locales[$language['Code']];
                        $group_key = 'config_groups+name+' . $configGroup['Key'];

                        $lang_keys[] = array(
                            'Code'       => $language['Code'],
                            'Module'     => 'admin',
                            'Key'        => $group_key,
                            'Value'      => $locale[$group_key] ?: $configGroup['Name'],
                            'Plugin'     => $this->key,
                            'Target_key' => 'settings',
                            'Status'     => 'approval',
                        );
                    }
                }
                $group_id = empty($group_id) ? 0 : $group_id;

                $configs = $this->configs;
                if (!empty($configs)) {
                    foreach ($languages as $language) {
                        $locale = $locales[$language['Code']];

                        foreach ($configs as $conf) {
                            $name_key = 'config+name+' . $conf['Key'];
                            $lang_keys[] = array(
                                'Code'       => $language['Code'],
                                'Module'     => 'admin',
                                'Key'        => $name_key,
                                'Value'      => $locale[$name_key] ?: $conf['Name'],
                                'Plugin'     => $this->key,
                                'Target_key' => 'settings',
                                'Status'     => 'approval',
                            );

                            if (!empty($conf['Description'])) {
                                $des_key = 'config+des+' . $conf['Key'];
                                $lang_keys[] = array(
                                    'Code'       => $language['Code'],
                                    'Module'     => 'admin',
                                    'Key'        => $des_key,
                                    'Value'      => $locale[$des_key] ?: $conf['Description'],
                                    'Plugin'     => $this->key,
                                    'Target_key' => 'settings',
                                    'Status'     => 'approval',
                                );
                            }
                        }
                    }

                    foreach ($configs as $key => $value) {
                        $position = $key;

                        if ($configs[$key]['Group']) {
                            $max_pos = $rlDb->getRow("SELECT MAX(`Position`) AS `Max` FROM `{db_prefix}config` WHERE `Group_ID` = '{$configs[$key]['Group']}' LIMIT 1");
                            $position = $max_pos['Max'] + $key;
                        }

                        $configs[$key]['Position'] = $position;
                        $configs[$key]['Group_ID'] = !$group_id ? $configs[$key]['Group'] : $group_id;
                        unset($configs[$key]['Name']);
                        unset($configs[$key]['Description']);
                        unset($configs[$key]['Group']);
                        unset($configs[$key]['Version']);
                    }
                    $rlDb->insert($configs, 'config');
                }

                // Install blocks
                $blocks = $this->blocks;
                if (!empty($blocks)) {
                    foreach ($blocks as $block_key => &$block) {
                        $block_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}blocks` LIMIT 1");
                        $block['Position'] = $block_max_poss['max'] + 1;

                        if (in_array(strtolower($block['Type']), array('html', 'php', 'smarty'))) {
                            foreach ($languages as $language) {
                                $locale = $locales[$language['Code']];

                                // Add name phrases
                                $block_key = 'blocks+name+' . $block['Key'];
                                $lang_keys[] = array(
                                    'Code'       => $language['Code'],
                                    'Module'     => 'box',
                                    'Key'        => $block_key,
                                    'Value'      => $locale[$block_key] ?: $block['Name'],
                                    'Plugin'     => $this->key,
                                    'Target_key' => $block['Key'],
                                    'Status'     => 'avtive',
                                );

                                if (strtolower($block['Type']) == 'html') {
                                    $block_content_key = 'blocks+content+' . $block['Key'];
                                    $lang_keys[] = array(
                                        'Code'       => $language['Code'],
                                        'Module'     => 'common',
                                        'Key'        => $block_content_key,
                                        'Value'      => $locale[$block_content_key] ?: $block['Content'],
                                        'Plugin'     => $this->key,
                                        'Target_key' => $block['Key'],
                                        'Status'     => 'avtive',
                                    );
                                    unset($block['Content']);
                                }
                            }

                            unset($block['Name']);
                            unset($block['Version']);
                        } else {
                            unset($blocks[$block_key]);
                        }
                    }
                    $rlDb->insert($blocks, 'blocks');
                }

                // install admin panel blocks
                $aBlocks = $this->aBlocks;
                if ($aBlocks) {
                    if ($remoteMode) {
                        require_once RL_LIBS . 'smarty' . RL_DS . 'Smarty.class.php';
                        $reefless->loadClass('Smarty');
                    }

                    foreach ($aBlocks as $key => $value) {
                        $aBlock_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}admin_blocks` WHERE `Column` = 'column{$value['Column']}' LIMIT 1");
                        $aBlocks[$key]['Position'] = $aBlock_max_poss['max'] + 1;

                        // add name phrases
                        foreach ($languages as $language) {
                            $locale   = $locales[$language['Code']];
                            $name_key =  'admin_blocks+name+' . $value['Key'];

                            $lang_keys[] = array(
                                'Code'       => $language['Code'],
                                'Module'     => 'admin',
                                'Key'        => $name_key,
                                'Value'      => $locale[$name_key] ?: $value['Name'],
                                'Plugin'     => $this->key,
                                'Target_key' => 'home',
                                'Status'     => 'active',
                            );
                        }

                        $aBlocks[$key]['name'] = $aBlocks[$key]['Name'];
                        $aBlocks[$key]['Column'] = 'column' . $aBlocks[$key]['Column'];

                        unset($aBlocks[$key]['Name']);
                        unset($aBlocks[$key]['name']);
                        unset($aBlocks[$key]['Version']);

                        // Append new block
                        if ($remoteMode) {
                            $rlSmarty->assign('block', $aBlocks[$key]);

                            $tpl = 'blocks' . RL_DS . 'homeDragDrop_block.tpl';
                            $out['html'][] = array(
                                'code' => $rlSmarty->fetch($tpl, null, null, false),
                                'box'  => $value['Column'],
                                'ajax' => $value['Ajax']
                            );
                        }
                    }
                    $rlDb->insert($aBlocks, 'admin_blocks');
                }

                // Install pages
                $pages = $this->pages;
                if (!empty($pages)) {
                    foreach ($pages as $key => $value) {
                        $page_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}pages` LIMIT 1");
                        $pages[$key]['Position'] = $page_max_poss['max'] + 1;

                        if (in_array($pages[$key]['Page_type'], array('system', 'static', 'external'))) {
                            // Add name phrases
                            foreach ($languages as $language) {
                                $locale   = $locales[$language['Code']];
                                $name_key = 'pages+name+' . $pages[$key]['Key'];

                                $lang_keys[] = array(
                                    'Code'   => $language['Code'],
                                    'Module' => 'common',
                                    'Key'    => $name_key,
                                    'Value'  => $locale[$name_key] ?: $pages[$key]['Name'],
                                    'Plugin' => $this->key,
                                    'Status' => 'active',
                                );
                                $lang_keys[] = array(
                                    'Code'   => $language['Code'],
                                    'Module' => 'frontEnd',
                                    'Key'    => 'pages+title+' . $pages[$key]['Key'],
                                    'Value'  => $locale[$name_key] ?: $pages[$key]['Name'],
                                    'Plugin' => $this->key,
                                    'Status' => 'active',
                                );

                                if ($pages[$key]['Page_type'] == 'static') {
                                    $content_key = 'pages+content+' . $pages[$key]['Key'];
                                    $lang_keys[] = array(
                                        'Code'       => $language['Code'],
                                        'Module'     => 'frontEnd',
                                        'Key'        => $content_key,
                                        'Value'      => $locale[$content_key] ?: $pages[$key]['Content'],
                                        'Plugin'     => $this->key,
                                        'Target_key' => $pages[$key]['Page_type'] == 'static' ? 'static' : $pages[$key]['Controller'],
                                        'Status'     => 'active',
                                    );
                                }
                            }

                            switch ($pages[$key]['Page_type']) {
                                case 'system':
                                    $pages[$key]['Controller'] = $pages[$key]['Controller'];
                                    break;
                                case 'static':
                                    $pages[$key]['Controller'] = 'static';
                                    break;
                                case 'external':
                                    $pages[$key]['Controller'] = $pages[$key]['Content'];
                                    break;
                            }
                            unset($pages[$key]['Name']);
                            unset($pages[$key]['Content']);
                            unset($pages[$key]['Version']);
                        } else {
                            unset($pages[$key]);
                        }
                    }
                    $rlDb->insert($pages, 'pages');
                }

                // Install email templates
                $emails = $this->emails;
                if (!empty($emails)) {
                    foreach ($emails as $key => $value) {
                        $email_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}email_templates` LIMIT 1");
                        $emails[$key]['Position'] = $email_max_poss['max'] + 1;

                        // add name phrases
                        foreach ($languages as $language) {
                            $locale      = $locales[$language['Code']];
                            $subject_key = 'email_templates+subject+' . $emails[$key]['Key'];
                            $body_key    = 'email_templates+body+' . $emails[$key]['Key'];

                            $lang_keys[] = array(
                                'Code'   => $language['Code'],
                                'Module' => 'email_tpl',
                                'Key'    => $subject_key,
                                'Value'  => $locale[$subject_key] ?: $emails[$key]['Subject'],
                                'Plugin' => $this->key,
                                'Status' => 'active',
                            );
                            $lang_keys[] = array(
                                'Code'   => $language['Code'],
                                'Module' => 'email_tpl',
                                'Key'    => $body_key,
                                'Value'  => $locale[$body_key] ?: $emails[$key]['Body'],
                                'Plugin' => $this->key,
                                'Status' => 'active',
                            );
                        }
                        unset($emails[$key]['Subject']);
                        unset($emails[$key]['Body']);
                        unset($emails[$key]['Version']);
                    }
                    $rlDb->insert($emails, 'email_templates');
                }

                // Add phrases
                if (!empty($lang_keys)) {
                    $rlDb->insert($lang_keys, 'lang_keys');
                }

                /**
                 * @since 4.7.0 - Using PluginManager class instead of the internal method
                 * @since 4.6.0
                 */
                try {
                    $instance = PluginManager::getPluginInstance($this->key, $this->install['class']);

                    if ($instance && $instance instanceof PluginInterface) {
                        $instance->install();
                    } elseif ($this->install['code'] !== '') {
                        @eval($this->install['code']);
                    }
                } catch (Exception $e) {
                    $rlDebug->logger($e->getMessage());
                }

                // Collect custom js code
                $out['js'] = $_response->get();

                // Check plugin files exist
                $files = $this->files;
                $files_exist = true;

                foreach ($files as $file) {
                    $file = str_replace(array('\\', '/'), array(RL_DS, RL_DS), $file);

                    if (!is_readable(RL_PLUGINS . $this->key . RL_DS . $file)) {
                        $files_exist = false;
                        $missed_files .= '/plugins/' . $this->key . '/<b>' . $file . '</b><br />';
                    }
                }

                // Activate plugin
                if ($files_exist === true) {
                    $tables = array('lang_keys', 'hooks', 'blocks', 'admin_blocks', 'pages', 'email_templates');

                    foreach ($tables as $table) {
                        unset($update);
                        $update = array(
                            'fields' => array(
                                'Status' => 'active',
                            ),
                            'where'  => array(
                                'Plugin' => $this->key,
                            ),
                        );
                        $rlDb->updateOne($update, $table);
                    }

                    unset($update);
                    $update = array(
                        'fields' => array(
                            'Status' => 'active',
                        ),
                        'where'  => array(
                            'Key' => $this->key,
                        ),
                    );
                    $rlDb->updateOne($update, 'plugins');

                    if ($this->notice || is_array($this->notices)) {
                        $post_notice = is_array($this->notices) ? $this->notices[0]['Content'] : $this->notice;
                        $post_install_notice = "<br /><b>" . $lang['notice'] . ":</b> " . $post_notice;
                    }
                    $out['notice'] = $rlLang->getSystem('notice_plugin_installed') . $post_install_notice;

                    // Define menu item data
                    if ($this->controller) {
                        $out['menu'] = [
                            'key' => $this->key,
                            'controller' => $this->controller,
                            'title' => $this->title,
                        ];
                    }
                } else {
                    return array(
                        'status' => 'ERROR',
                        'message' => str_replace('{files}', "<br />" . $missed_files, $rlLang->getSystem('plugin_files_missed'))
                    );
                }
            } else {
                return array(
                    'status' => 'ERROR',
                    'message' => $rlLang->getSystem('plugin_download_deny')
                );
                $rlDebug->logger("Can not install plugin (" . $this->title . "), insert command failed");
            }
        } else {
            return array(
                'status' => 'ERROR',
                'message' => $rlLang->getSystem('install_not_found')
            );
        }

        if ($remoteMode) {
            $out['phrase'] = array(
                'remote_progress_installation_completed' => $rlLang->getSystem('remote_progress_installation_completed'),
                'no_new_plugins' => $rlLang->getSystem('no_new_plugins')
            );
        }

        $out['status'] = 'OK';

        return $out;
    }

    /**
     * Update plugin
     *
     * @param string  $pluginKey  - Plugin key
     * @param boolian $remoteMode - Remote mode
     **/
    public function ajaxUpdate($pluginKey = false, $remoteMode = false)
    {
        global $rlLang, $languages, $rlDb, $reefless, $lang, $rlDebug;

        if (!$pluginKey) {
            return false;
        }

        // Create xajax fallback class
        global $_response;
        $_response = new xajaxFallback();

        // Create SMARTY fallback class
        global $rlSmarty;
        $rlSmarty = new smartyFallback();

        $out = [];

        if ($reefless->checkSessionExpire() === false) {
            return array(
                'status' => 'REDIRECT',
                'data' => 'session_expired'
            );
        }

        $current_version = $rlDb->getOne('Version', "`Key` = '{$pluginKey}'", 'plugins');

        $plugin_dir = RL_UPLOAD . $pluginKey . RL_DS;
        $path_to_update = $plugin_dir . 'install.xml';

        if (is_readable($path_to_update)) {
            require_once RL_LIBS . 'saxyParser' . RL_DS . 'xml_saxy_parser.php';

            $rlParser = new SAXY_Parser();
            $rlParser->xml_set_element_handler(array($this, 'startElement'), array($this, 'endElement'));
            $rlParser->xml_set_character_data_handler(array($this, 'charData'));
            $rlParser->xml_set_comment_handler(array($this, 'commentElement'));

            // Parse xml file
            $rlParser->parse(file_get_contents($path_to_update));

            // Check compatibility with current version of the software
            if (!$this->checkCompatibilityByVersion($this->compatible)) {
                 return array(
                    'status' => 'ERROR',
                    'message' => $rlLang->getSystem('plugin_not_compatible_notice')
                );
            }

            // Check custom changes in the plugin
            if ($rlDb->getOne('Custom', "`Key` = '{$pluginKey}'", 'plugins') === '1') {
                return [
                    'status'  => 'ERROR',
                    'message' => $rlLang->getSystem('deny_update_custom_plugin'),
                    'js'      => [
                        "$('#update_area, #search_area').slideUp('fast');",
                        "typeof xajax_getPluginsLog === 'function' ? xajax_getPluginsLog() : null;",
                    ],
                ];
            }

            $plugin = array(
                'fields' => array(
                    'Name'        => $this->title,
                    'Class'       => $this->uninstall['class'] ?: '',
                    'Description' => $this->description,
                    'Version'     => $this->version,
                    'Controller'  => $this->controller,
                    'Uninstall'   => $this->uninstall['code'],
                    'Files'       => serialize($this->files),
                ),
                'where'  => array(
                    'Key' => $this->key,
                ),
            );

            // Update plugin
            foreach ($this->updates as $update_index => $update_item) {
                $success = true;

                if (version_compare($update_item['Version'], $current_version) > 0) {
                    $lang_keys_insert = array();
                    $lang_keys_update = array();

                    $configs_insert = array();
                    $configs_update = array();

                    $update_item['Files'] = rtrim('install.xml,i18n/,' . $update_item['Files'], ',');

                    // Copy plugin files
                    foreach (explode(',', $update_item['Files']) as $update_file) {
                        $file_to_copy = trim($update_file);
                        $file_source = $plugin_dir . $file_to_copy;
                        $error_message = '';

                        // Skip 'i18n' directory if there is not changes in
                        if ($file_to_copy == 'i18n/' && !file_exists($file_source)) {
                            continue;
                        }

                        if (!file_exists($file_source)) {
                            $error_message = "The '/tmp/upload/{$pluginKey}/{$file_to_copy}' does not exist.";
                        } elseif (!is_writable($plugin_dir)) {
                            $error_message = "The '/plugins/{$pluginKey}/' directory is not writable.";
                        }

                        if ($error_message) {
                            $rlDebug->logger("Plugin updating: {$error_message}");
                            $success = false;
                            break;
                        }

                        $destination = RL_PLUGINS . $pluginKey . RL_DS . $file_to_copy;
                        $catchExceptionFunc = function (IOException $e) use (&$success, $pluginKey) {
                            $rlDebug->logger("
                                Plugin updating: Thrown exception '{$e->getMessage()}' in {$pluginKey} plugin.
                            ");
                            $success = false;
                        };
                        $options = ['override' => true];

                        $filesystem = new \Flynax\Component\Filesystem();
                        $filesystem->copyTo($file_source, $destination, $catchExceptionFunc, $options);
                    }

                    if ($success) {
                        // Update phrases
                        $phrases = $this->phrases;

                        foreach ($languages as $language) {
                            $locales[$language['Code']] = $locale = $this->getLanguagePhrases($language['Code'], $this->key);

                            if ($phrases) {
                                foreach ($phrases as $phrase) {
                                    if (version_compare($phrase['Version'], $update_item['Version'], '!=')) {
                                        continue;
                                    }

                                    if ($phrase['Module'] == 'ext') {
                                        $phrase['Module'] = 'admin';
                                        $phrase['JS']     = '1';
                                    }

                                    $phrase_value = $locale[$phrase['Key']] ?: $phrase['Value'];
                                    $exist_phrase = $rlDb->fetch(
                                        array('Modified', 'Value'),
                                        array(
                                            'Key'  => $phrase['Key'],
                                            'Code' => $language['Code']
                                        ),
                                        null, 1, 'lang_keys', 'row'
                                    );

                                    if ($exist_phrase) {
                                        // Update
                                        if ($language['Code'] == $GLOBALS['config']['lang']
                                            || (
                                                $language['Code'] != $GLOBALS['config']['lang']
                                                && !$exist_phrase['Modified']
                                                && $exist_phrase['Value'] != $phrase_value
                                            )
                                        ) {
                                            $lang_keys_update[] = array(
                                                'fields' => array(
                                                    'Module'     => $phrase['Module'],
                                                    'Value'      => $phrase_value,
                                                    'JS'         => $phrase['JS'],
                                                    'Target_key' => $phrase['Target_key']
                                                ),
                                                'where'  => array(
                                                    'Code' => $language['Code'],
                                                    'Key'  => $phrase['Key'],
                                                ),
                                            );
                                        }
                                    } else {
                                        // Insert
                                        $lang_keys_insert[] = array(
                                            'Code'       => $language['Code'],
                                            'Module'     => $phrase['Module'],
                                            'Key'        => $phrase['Key'],
                                            'Value'      => $phrase_value,
                                            'Plugin'     => $this->key,
                                            'JS'         => $phrase['JS'],
                                            'Target_key' => $phrase['Target_key'],
                                            'Status'     => 'active',
                                        );
                                    }
                                }
                            }
                        }

                        // Update hooks
                        $hooks = $this->hooks;
                        if (!empty($hooks)) {
                            foreach ($hooks as $key => $value) {
                                if (version_compare($value['Version'], $update_item['Version']) == 0) {
                                    $options = '';
                                    $where = array(
                                        'Name' => $value['Name'],
                                        'Plugin' => $this->key
                                    );

                                    if ($value['Class']) {
                                        $common_class = strval($this->class);
                                        $options = "AND (`Class` = '' OR `Class` IN ('{$value['Class']}','{$common_class}'))";
                                    }

                                    if ($hook_data = $rlDb->fetch(['ID', 'Class'], $where, $options, 1, 'hooks', 'row')) {
                                        $where['Class'] = $hook_data['Class'];

                                        $hook_update = array(
                                            'fields' => array(
                                                'Class' => $value['Class'],
                                                'Code'  => $value['Code'],
                                            ),
                                            'where'  => $where
                                        );

                                        $rlDb->updateOne($hook_update, 'hooks');
                                    } else {
                                        $hook_insert = $value;
                                        unset($hook_insert['Version']);
                                        $hook_insert['Status'] = 'active';

                                        $rlDb->insert($hook_insert, 'hooks');
                                    }
                                }
                            }
                        }

                        // Update configs' group
                        $cGroup = $configGroup = $this->configGroup;
                        if (!empty($configGroup)) {
                            if (version_compare($configGroup['Version'], $update_item['Version']) == 0) {
                                if ($rlDb->getOne('ID', "`Key` = '{$configGroup['Key']}' AND `Plugin` = '" . $this->key . "'", 'config_groups')) {
                                    foreach ($languages as $language) {
                                        $locale    = $locales[$language['Code']];
                                        $group_key = 'config_groups+name+' . $configGroup['Key'];

                                        $lang_keys_update[] = array(
                                            'fields' => array(
                                                'Value'      => $locale[$group_key] ?: $configGroup['Name'],
                                                'Target_key' => 'settings',
                                            ),
                                            'where'  => array(
                                                'Code' => $language['Code'],
                                                'Key'  => $group_key,
                                            ),
                                        );
                                    }
                                } else {
                                    $cg_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}config_groups` LIMIT 1");
                                    unset($cGroup['Name']);
                                    unset($cGroup['Version']);
                                    $cGroup['Position'] = $cg_max_poss['max'] + 1;

                                    $rlDb->insertOne($cGroup, 'config_groups');
                                    $group_id = $rlDb->insertID();

                                    foreach ($languages as $language) {
                                        $locale    = $locales[$language['Code']];
                                        $group_key = 'config_groups+name+' . $configGroup['Key'];

                                        $lang_keys_insert[] = array(
                                            'Code'       => $language['Code'],
                                            'Module'     => 'admin',
                                            'Key'        => $group_key,
                                            'Value'      => $locale[$group_key] ?: $configGroup['Name'],
                                            'Plugin'     => $this->key,
                                            'Target_key' => 'settings',
                                            'Status'     => 'active',
                                        );
                                    }
                                }
                            }
                        }

                        $group_id = empty($group_id) ? 0 : $group_id;

                        // Update configs
                        $configs = $this->configs;
                        if (!empty($configs)) {
                            foreach ($configs as $key => $value) {
                                $name_key = 'config+name+' . $value['Key'];
                                $des_key  = 'config+des+' . $value['Key'];

                                if (version_compare($value['Version'], $update_item['Version'], '!=')) {
                                    continue;
                                }

                                if ($rlDb->getOne('ID', "`Key` = '{$value['Key']}' AND `Plugin` = '" . $this->key . "'", 'config')) {
                                    // Update
                                    $configs_update[] = array(
                                        'fields' => array(
                                            'Default'   => $value['Default'],
                                            'Values'    => $value['Values'],
                                            'Type'      => $value['Type'],
                                            'Data_type' => $value['Data_type'],
                                        ),
                                        'where'  => array(
                                            'Key'    => $value['Key'],
                                            'Plugin' => $this->key,
                                        ),
                                    );

                                    foreach ($languages as $language) {
                                        $locale       = $locales[$language['Code']];
                                        $phrase_name  = $locale[$name_key] ?: $value['Name'];
                                        $exist_phrase = $rlDb->fetch(
                                            array('Modified', 'Value'),
                                            array(
                                                'Key'  => $name_key,
                                                'Code' => $language['Code']
                                            ),
                                            null, 1, 'lang_keys', 'row'
                                        );

                                        if ($exist_phrase) {
                                            if ($language['Code'] == $GLOBALS['config']['lang']
                                                || (
                                                    $language['Code'] != $GLOBALS['config']['lang']
                                                    && !$exist_phrase['Modified']
                                                    && $exist_phrase['Value'] != $phrase_name
                                                )
                                            ) {
                                                $lang_keys_update[] = array(
                                                    'fields' => array(
                                                        'Value'      => $phrase_name,
                                                        'Target_key' => 'settings',
                                                    ),
                                                    'where'  => array(
                                                        'Code' => $language['Code'],
                                                        'Key'  => $name_key,
                                                    ),
                                                );
                                            }
                                        } else {
                                            $lang_keys_insert[] = array(
                                                'Code'       => $language['Code'],
                                                'Module'     => 'admin',
                                                'Key'        => $name_key,
                                                'Value'      => $phrase_name,
                                                'Plugin'     => $this->key,
                                                'Target_key' => 'settings',
                                                'Status'     => 'active',
                                            );
                                        }

                                        if (!empty($value['Description'])) {
                                            if (!$rlDb->getOne('ID', "`Key` = 'config+des+{$value['Key']}' AND `Code` = '{$language['Code']}'", 'lang_keys')) {
                                                $lang_keys_insert[] = array(
                                                    'Code'       => $language['Code'],
                                                    'Module'     => 'admin',
                                                    'Key'        => 'config+des+' . $value['Key'],
                                                    'Value'      => $value['Description'],
                                                    'Plugin'     => $this->key,
                                                    'Target_key' => 'settings',
                                                    'Status'     => 'active',
                                                );
                                            }
                                        }
                                    }
                                } else {
                                    // Insert
                                    foreach ($languages as $language) {
                                        $locale = $locales[$language['Code']];

                                        $lang_keys_insert[] = array(
                                            'Code'       => $language['Code'],
                                            'Module'     => 'admin',
                                            'Key'        => $name_key,
                                            'Value'      => $locale[$name_key] ?: $value['Name'],
                                            'Plugin'     => $this->key,
                                            'Target_key' => 'settings',
                                            'Status'     => 'active',
                                        );

                                        if (!empty($value['Description'])) {
                                            $lang_keys_insert[] = array(
                                                'Code'       => $language['Code'],
                                                'Module'     => 'admin',
                                                'Key'        => $des_key,
                                                'Value'      => $locale[$des_key] ?: $value['Description'],
                                                'Plugin'     => $this->key,
                                                'Target_key' => 'settings',
                                                'Status'     => 'active',
                                            );
                                        }
                                    }
                                    $position = $key;

                                    if ($configs[$key]['Group']) {
                                        $max_pos = $rlDb->getRow("SELECT MAX(`Position`) AS `Max` FROM `{db_prefix}config` WHERE `Group_ID` = '{$value['Group']}' LIMIT 1");
                                        $position = $max_pos['Max'] + $key;
                                    }

                                    if ($configGroup['Key']) {
                                        $group_id = $rlDb->getOne('ID', "`Key` = '{$configGroup['Key']}' AND `Plugin` = '" . $this->key . "'", 'config_groups');
                                    }

                                    $configs_insert[] = array(
                                        'Group_ID'  => !$group_id ? $value['Group'] : $group_id,
                                        'Position'  => $position,
                                        'Key'       => $value['Key'],
                                        'Default'   => $value['Default'],
                                        'Values'    => $value['Values'],
                                        'Type'      => $value['Type'],
                                        'Data_type' => $value['Data_type'],
                                        'Plugin'    => $this->key,
                                    );
                                }
                            }

                            if (!empty($configs_update)) {
                                $rlDb->update($configs_update, 'config');
                            }

                            if (!empty($configs_insert)) {
                                $rlDb->insert($configs_insert, 'config');
                            }
                        }

                        // Update blocks
                        $blocks = $this->blocks;
                        if (!empty($blocks)) {
                            foreach ($blocks as $key => $value) {
                                if (version_compare($value['Version'], $update_item['Version'], '!=')) {
                                    continue;
                                }

                                if (in_array(strtolower($value['Type']), array('html', 'php', 'smarty'))) {
                                    if ($rlDb->getOne('ID', "`Key` = '{$value['Key']}' AND `Plugin` = '" . $this->key . "'", 'blocks')) {
                                        // Update
                                        $block_update = array(
                                            'fields' => array(
                                                'Type'     => $value['Type'],
                                                'Content'  => $value['Content'],
                                                'Readonly' => $value['Readonly'],
                                            ),
                                            'where'  => array(
                                                'Key'    => $value['Key'],
                                                'Plugin' => $this->key,
                                            ),
                                        );

                                        if (strtolower($value['Type']) == 'html') {
                                            unset($block_update['fields']['Content']);
                                        }

                                        $rlDb->updateOne($block_update, 'blocks');
                                    } else {
                                        $block_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}blocks` LIMIT 1");
                                        $blocks[$key]['Position'] = $block_max_poss['max'] + 1;

                                        $name_key    = 'blocks+name+' . $value['Key'];
                                        $content_key = 'blocks+content+' . $value['Key'];

                                        // Add name phrases
                                        foreach ($languages as $language) {
                                            $locale = $locales[$language['Code']];

                                            $lang_keys_insert[] = array(
                                                'Code'       => $language['Code'],
                                                'Module'     => 'box',
                                                'Key'        => $name_key,
                                                'Value'      => $locale[$name_key] ?: $value['Name'],
                                                'Plugin'     => $this->key,
                                                'Target_key' => $value['Key'],
                                                'Status'     => 'active',
                                            );

                                            if (strtolower($value['Type']) == 'html') {
                                                $lang_keys_insert[] = array(
                                                    'Code'       => $language['Code'],
                                                    'Module'     => 'box',
                                                    'Key'        => $content_key,
                                                    'Value'      => $locale[$content_key] ?: $value['Content'],
                                                    'Plugin'     => $this->key,
                                                    'Target_key' => $value['Key'],
                                                    'Status'     => 'active',
                                                );
                                            }
                                        }

                                        if (strtolower($value['Type']) == 'html') {
                                            unset($blocks[$key]['Content']);
                                        }
                                        unset($blocks[$key]['Name']);
                                        unset($blocks[$key]['Version']);
                                        $blocks[$key]['Status'] = 'active';

                                        $rlDb->insertOne($blocks[$key], 'blocks');
                                    }
                                }
                            }
                        }

                        // Update admin panel blocks
                        $aBlocks = $this->aBlocks;
                        if ($aBlocks) {
                            if ($remoteMode) {
                                require_once RL_LIBS . 'smarty' . RL_DS . 'Smarty.class.php';
                                $reefless->loadClass('Smarty');
                            }

                            foreach ($aBlocks as $key => $value) {
                                if (version_compare($value['Version'], $update_item['Version'], '!=')) {
                                    continue;
                                }

                                if ($rlDb->getOne('ID', "`Key` = '{$value['Key']}' AND `Plugin` = '" . $this->key . "'", 'admin_blocks')) {
                                    // Update
                                    $aBlock_update = array(
                                        'fields' => array(
                                            'Ajax'    => $value['Ajax'],
                                            'Content' => $value['Content'],
                                            'Fixed'   => $value['Fixed'],
                                        ),
                                        'where'  => array(
                                            'Key'    => $value['Key'],
                                            'Plugin' => $this->key,
                                        ),
                                    );

                                    $rlDb->updateOne($aBlock_update, 'admin_blocks');
                                } else {
                                    $aBlock_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}admin_blocks` WHERE `Column` = 'column{$value['Column']}' LIMIT 1");
                                    $aBlocks[$key]['Position'] = $aBlock_max_poss['max'] + 1;

                                    // Add name phrases
                                    foreach ($languages as $lkey => $lval) {
                                        $lang_keys_insert[] = array(
                                            'Code'       => $lval['Code'],
                                            'Module'     => 'admin',
                                            'Key'        => 'admin_blocks+name+' . $value['Key'],
                                            'Value'      => $value['Name'],
                                            'Plugin'     => $this->key,
                                            'Target_key' => 'home',
                                            'Status'     => 'active',
                                        );
                                    }

                                    $aBlocks[$key]['name'] = $aBlocks[$key]['Name'];
                                    $aBlocks[$key]['Column'] = 'column' . $aBlocks[$key]['Column'];
                                    $aBlocks[$key]['Status'] = 'active';
                                    unset($aBlocks[$key]['Name']);
                                    unset($aBlocks[$key]['name']);
                                    unset($aBlocks[$key]['Version']);

                                    $rlDb->insertOne($aBlocks[$key], 'admin_blocks');

                                    // Append new block
                                    if ($remoteMode) {
                                        $rlSmarty->assign('block', $aBlocks[$key]);

                                        $tpl = 'blocks' . RL_DS . 'homeDragDrop_block.tpl';
                                        $out['html'][] = array(
                                            'code' => $rlSmarty->fetch($tpl, null, null, false),
                                            'box'  => $value['Column'],
                                            'ajax' => $value['Ajax']
                                        );
                                    }
                                }
                            }
                        }

                        // Update pages
                        $pages = $this->pages;
                        if (!empty($pages)) {
                            foreach ($pages as $key => $value) {
                                $name_key    = 'pages+name+' . $value['Key'];
                                $content_key = 'pages+content+' . $value['Key'];

                                if (in_array($value['Page_type'], array('system', 'static', 'external'))) {
                                    if (version_compare($value['Version'], $update_item['Version'], '!=')) {
                                        continue;
                                    }

                                    if ($rlDb->getOne('ID', "`Key` = '{$value['Key']}' AND `Plugin` = '" . $this->key . "'", 'pages')) {
                                        $page_update = array(
                                            'fields' => array(
                                                'Page_type'  => $value['Page_type'],
                                                'Get_vars'   => $value['Get_vars'],
                                                'Controller' => $value['Controller'],
                                                'Deny'       => $value['Deny'],
                                                'Tpl'        => $value['Tpl'],
                                                'Readonly'   => $value['Readonly'],
                                            ),
                                            'where'  => array(
                                                'Key'    => $key['Key'],
                                                'Plugin' => $this->key,
                                            ),
                                        );

                                        $rlDb->updateOne($page_update, 'pages');
                                    } else {
                                        $page_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}pages` LIMIT 1");
                                        $pages[$key]['Position'] = $page_max_poss['max'] + 1;

                                        // Add name phrases
                                        foreach ($languages as $language) {
                                            $locale = $locales[$language['Code']];

                                            $lang_keys_insert[] = array(
                                                'Code'   => $language['Code'],
                                                'Module' => 'common',
                                                'Key'    => $name_key,
                                                'Value'  => $locale[$name_key] ?: $value['Name'],
                                                'Plugin' => $this->key,
                                                'Status' => 'active',
                                            );

                                            $lang_keys_insert[] = array(
                                                'Code'   => $language['Code'],
                                                'Module' => 'frontEnd',
                                                'Key'    => 'pages+title+' . $value['Key'],
                                                'Value'  => $locale[$name_key] ?: $value['Name'],
                                                'Plugin' => $this->key,
                                                'Status' => 'active',
                                            );

                                            if ($value['Page_type'] == 'static') {
                                                $lang_keys_insert[] = array(
                                                    'Code'       => $language['Code'],
                                                    'Module'     => 'frontEnd',
                                                    'Key'        => $content_key,
                                                    'Value'      => $locale[$content_key] ?: $value['Content'],
                                                    'Plugin'     => $this->key,
                                                    'Target_key' => $value['Page_type'] == 'static' ? 'static' : $pages[$key]['Controller'],
                                                    'Status'     => 'active',
                                                );
                                            }
                                        }

                                        switch ($value['Page_type']) {
                                            case 'system':
                                                $pages[$key]['Controller'] = $pages[$key]['Controller'];
                                                break;
                                            case 'static':
                                                $pages[$key]['Controller'] = 'static';
                                                break;
                                            case 'external':
                                                $pages[$key]['Controller'] = $pages[$key]['Content'];
                                                break;
                                        }

                                        unset($pages[$key]['Name']);
                                        unset($pages[$key]['Content']);
                                        unset($pages[$key]['Version']);
                                        $pages[$key]['status'] = 'active';

                                        $rlDb->insertOne($pages[$key], 'pages');
                                    }
                                }
                            }
                        }

                        // Update email templates
                        $emails = $this->emails;
                        if (!empty($emails)) {
                            foreach ($emails as $key => $value) {
                                if (version_compare($value['Version'], $update_item['Version'], '!=')) {
                                    continue;
                                }

                                $subject_key = 'email_templates+subject+' . $value['Key'];
                                $body_key    = 'email_templates+body+' . $value['Key'];

                                if (!$rlDb->getOne('ID', "`Key` = '{$value['Key']}' AND `Plugin` = '" . $this->key . "'", 'email_templates')) {
                                    $email_max_poss = $rlDb->getRow("SELECT MAX(`Position`) AS `max` FROM `{db_prefix}email_templates` LIMIT 1");
                                    $emails[$key]['Position'] = $email_max_poss['max'] + 1;

                                    foreach ($languages as $language) {
                                        $locale = $locales[$language['Code']];

                                        $lang_keys_insert[] = array(
                                            'Code'   => $language['Code'],
                                            'Module' => 'email_tpl',
                                            'Key'    => $subject_key,
                                            'Value'  => $locale[$subject_key] ?: $value['Subject'],
                                            'Plugin' => $this->key,
                                            'Status' => 'active',
                                        );
                                        $lang_keys_insert[] = array(
                                            'Code'   => $language['Code'],
                                            'Module' => 'email_tpl',
                                            'Key'    => $body_key,
                                            'Value'  => $locale[$body_key] ?: $value['Body'],
                                            'Plugin' => $this->key,
                                            'Status' => 'active',
                                        );
                                    }
                                    unset($emails[$key]['Subject']);
                                    unset($emails[$key]['Body']);
                                    unset($emails[$key]['Version']);
                                    $emails[$key]['Status'] = 'active';

                                    $rlDb->insertOne($emails[$key], 'email_templates');
                                }
                            }
                        }

                        /**
                         * @since 4.7.0 - Using PluginManager class instead of the internal method
                         * @since 4.6.0
                         */
                        try {
                            $instance = PluginManager::getPluginInstance($this->key, $update_item['Class']);

                            if ($instance && $instance instanceof PluginInterface) {
                                $instance->update($update_item['Version']);
                            } elseif ($update_item['Code'] !== '') {
                                @eval($update_item['Code']);
                            }
                        } catch (Exception $e) {
                            $rlDebug->logger($e->getMessage());
                        }

                        // Collect custom js code
                        $out['js'] = array_merge((array) $out['js'], $_response->get());

                        // Add phrases
                        if (!empty($lang_keys_insert)) {
                            $rlDb->insert($lang_keys_insert, 'lang_keys');
                        }

                        // Update phrases
                        if (!empty($lang_keys_update)) {
                            $rlDb->update($lang_keys_update, 'lang_keys');
                        }

                        $plugin_version_update = array(
                            'fields' => array(
                                'Version' => $update_item['Version'],
                            ),
                            'where'  => array(
                                'Key' => $this->key,
                            ),
                        );

                        $rlDb->updateOne($plugin_version_update, 'plugins');
                    }
                }
            }

            // Delete unzipped plugin from TMP
            $reefless->deleteDirectory(RL_UPLOAD . $this->key . RL_DS);

            if ($success && $rlDb->updateOne($plugin, 'plugins')) {
                $update_notice = $rlLang->getSystem('plugin_updated');

                // Print notices
                if (!empty($this->notices)) {
                    foreach ($this->notices as $key => $value) {
                        if (version_compare($value['Version'], $current_version) > 0) {
                            $plugin_update_notice .= '<li style="list-style:initial"><b>' . $lang['notice'];
                            $plugin_update_notice .= " ({$lang['version']} {$value['Version']}):</b> ";
                            $plugin_update_notice .= $value['Content'] . "</li>";
                        }
                    }
                    $update_notice .= $plugin_update_notice
                    ? "<br /><br /><ul>" . $plugin_update_notice . "</ul>"
                    : "";
                }

                $out['notice'] = $update_notice;

                // Define menu item data
                if ($this->controller && version_compare($this->controllerUpdate, $current_version) > 0) {
                    $out['menu'] = [
                        'key' => $this->key,
                        'controller' => $this->controller,
                        'title' => $this->title,
                    ];
                }
            } else {
                $rlDebug->logger("Cannot update plugin (" . $this->title . "), success variable returned FALSE.");
                return array(
                    'status' => 'ERROR',
                    'message' => $rlLang->getSystem('install_fail_files_upload')
                );
            }
        } else {
            $rlDebug->logger("Cannot update plugin (" . $this->title . "), '{$path_to_update}' does not found.");
            return array(
                'status' => 'ERROR',
                'message' => $rlLang->getSystem('install_not_found')
            );
        }

        if ($remoteMode) {
            $out['phrase'] = array(
                'remote_progress_update_completed' => $rlLang->getSystem('remote_progress_update_completed')
            );
        }

        $out['status'] = 'OK';

        return $out;
    }

    public function startElement($parser, $name, $attributes)
    {
        $this->level++;
        $this->inTag = $name;
        $this->attributes = $attributes;

        if ($this->inTag == 'plugin' && isset($attributes['name'])) {
            $this->key = $attributes['name'];
        }

        $this->path[] = $name;
    }

    public function endElement($parser, $name)
    {
        $this->level--;
    }

    public function charData($parser, $text)
    {
        switch ($this->inTag) {
            case 'hook':
                $_class = strval($this->attributes['class'] ?: $this->class);

                $this->hooks[] = array(
                    'Name'    => $this->attributes['name'],
                    'Class'   => $_class,
                    'Version' => $this->attributes['version'],
                    'Code'    => empty($_class) ? $text : '',
                    'Plugin'  => $this->key,
                    'Status'  => 'approval',
                );

                if ($this->noVersionTag) {
                    $itemIndex = count($this->hooks) - 1;
                    unset($this->hooks[$itemIndex]['Version']);
                }
                break;

            case 'phrase':
                $this->phrases[] = array(
                    'Key'        => $this->attributes['key'],
                    'Version'    => $this->attributes['version'],
                    'Module'     => $this->attributes['module'],
                    'JS'         => $this->attributes['js'] ? '1' : '0',
                    'Target_key' => $this->attributes['target'],
                    'Value'      => $text,
                );
                break;

            case 'configs':
                $this->configGroup = array(
                    'Key'     => $this->attributes['key'],
                    'Version' => $this->attributes['version'],
                    'Name'    => $this->attributes['name'],
                    'Plugin'  => $this->key,
                );

                if ($this->noVersionTag) {
                    unset($this->configGroup['Version']);
                }
                break;

            case 'config':
                $this->configs[] = array(
                    'Key'         => $this->attributes['key'],
                    'Version'     => $this->attributes['version'],
                    'Group'       => $this->attributes['group'],
                    'Name'        => $this->attributes['name'],
                    'Description' => $this->attributes['description'],
                    'Default'     => $text,
                    'Values'      => $this->attributes['values'],
                    'Type'        => $this->attributes['type'],
                    'Data_type'   => $this->attributes['validate'],
                    'Plugin'      => $this->key,
                );
                break;

            case 'block':
                $this->blocks[] = array(
                    'Key'      => $this->attributes['key'],
                    'Version'  => $this->attributes['version'],
                    'Name'     => $this->attributes['name'],
                    'Side'     => $this->attributes['side'],
                    'Type'     => $this->attributes['type'],
                    'Readonly' => (isset($this->attributes['lock']) && $this->attributes['lock'] == 0) ? 0 : 1,
                    'Tpl'      => (int) $this->attributes['tpl'],
                    'Content'  => $text,
                    'Plugin'   => $this->key,
                    'Status'   => 'approval',
                    'Sticky'   => 1,
                    'Header'   => (isset($this->attributes['header']) && $this->attributes['header'] == '0') ? 0 : 1,
                );
                break;

            case 'aBlock':
                $this->aBlocks[] = array(
                    'Key'     => $this->attributes['key'],
                    'Version' => $this->attributes['version'],
                    'Name'    => $this->attributes['name'],
                    'Content' => $text,
                    'Plugin'  => $this->key,
                    'Status'  => 'approval',
                    'Column'  => (int) $this->attributes['column'],
                    'Ajax'    => (int) $this->attributes['ajax'],
                    'Fixed'   => (int) $this->attributes['fixed'],
                );
                break;

            case 'page':
                $this->pages[] = array(
                    'Key'        => $this->attributes['key'],
                    'Version'    => $this->attributes['version'],
                    'Login'      => (int) $this->attributes['login'],
                    'Name'       => $this->attributes['name'],
                    'Page_type'  => $this->attributes['type'],
                    'Path'       => $this->attributes['path'],
                    'Get_vars'   => $this->attributes['get'],
                    'Controller' => $this->attributes['controller'],
                    'Menus'      => $this->attributes['menus'],
                    'Tpl'        => (int) $this->attributes['tpl'],
                    'Content'    => $text,
                    'Plugin'     => $this->key,
                );
                break;

            case 'email':
                $is_valid_type = in_array($this->attributes['type'], array('plain', 'html'));

                $this->emails[] = array(
                    'Key'     => $this->attributes['key'],
                    'Type'    => $is_valid_type ? $this->attributes['type'] : 'plain',
                    'Version' => $this->attributes['version'],
                    'Subject' => $this->attributes['subject'],
                    'Body'    => $text,
                    'Plugin'  => $this->key,
                );
                break;

            case 'update':
                $_class = strval($this->attributes['class'] ?: $this->class);

                $this->updates[] = array(
                    'Version' => $this->attributes['version'],
                    'Files'   => $this->attributes['files'],
                    'Class'   => $_class,
                    'Code'    => $text,
                );
                break;

            case 'notice':
                $this->notices[] = array(
                    'Version' => $this->attributes['version'],
                    'Content' => $text,
                );
                break;

            case 'file';
                $this->files[] = $text;
                break;

            case 'install':
            case 'uninstall':
                $_class = strval($this->attributes['class'] ?: $this->class);

                $this->{$this->inTag} = array(
                    'class' => $_class ?: false,
                    'code'  => $text,
                );
                break;

            case 'version':
            case 'date':
            case 'class':
            case 'title':
            case 'description':
            case 'author':
            case 'owner':
            case 'controller':
                $this->controllerUpdate = $this->attributes['version'];
            case 'notice':
            case 'compatible':
                $this->{$this->inTag} = $text;
                break;
        }
    }

    /**
     * Uninstall plugin
     *
     * @package xAjax
     *
     * @param  string $plugin_key
     * @return object
     */
    public function ajaxUnInstall($plugin_key)
    {
        global $_response, $lang, $rlValid, $rlDb;

        $rlValid->sql($plugin_key);

        $plugin_info = $rlDb->getRow("
            SELECT `Class`, `Uninstall` FROM `{db_prefix}plugins` WHERE `Key` = '{$plugin_key}'
        ");

        $tables = array(
            'lang_keys',
            'hooks',
            'config',
            'config_groups',
            'blocks',
            'admin_blocks',
            'pages',
            'email_templates',
        );
        foreach ($tables as $table) {
            $rlDb->query("DELETE FROM `{db_prefix}{$table}` WHERE `Plugin` = '{$plugin_key}'");
        }
        $rlDb->query("DELETE FROM `{db_prefix}plugins` WHERE `Key` = '{$plugin_key}'");

        /**
         * @since 4.7.0 - Using PluginManager class instead of the internal method
         * @since 4.6.0
         */
        try {
            $instance = PluginManager::getPluginInstance($plugin_key, $plugin_info['Class']);

            if ($instance && $instance instanceof PluginInterface) {
                $instance->uninstall();
            } elseif ($plugin_info['Uninstall'] !== '') {
                @eval($plugin_info['Uninstall']);
            }
        } catch (Exception $e) {
            $GLOBALS['rlDebug']->logger($e->getMessage());
        }

        // Reload grid
        $_response->script('pluginsGrid.reload();');
        $_response->script("printMessage('notice', '{$lang['notice_plugin_uninstalled']}');");

        // Remove menu item
        $_response->script("
            $('#mPlugin_{$plugin_key}').remove();
            apMenu['plugins']['{$plugin_key}'] = false;
        ");

        return $_response;
    }

    /**
     * Remote plugin installation
     *
     * @param string $key - Plugin key
     */
    public function ajaxRemoteInstall($key = false)
    {
        return false;
    }

    /**
     * Remote plugin update
     *
     * @param string  $key - plugin key
     **/
    public function ajaxRemoteUpdate($key = false)
    {
        return false;
    }

    /**
     * browse plugins
     *
     * @package xAjax
     *
     **/
    public function ajaxBrowsePlugins()
    {
        global $_response, $config, $lang, $rlSmarty, $reefless;

        // check admin session expire
        if ($reefless->checkSessionExpire() === false) {
            $redirect_url = RL_URL_HOME . ADMIN . "/index.php";
            $redirect_url .= empty($_SERVER['QUERY_STRING']) ? '?session_expired' : '?' . $_SERVER['QUERY_STRING'] . '&session_expired';
            $_response->redirect($redirect_url);
        }

        /* scan plugins directory */
        $plugins_exist = $reefless->scanDir(RL_PLUGINS, true);

        if (1 == 1) {
            $fail_msg = $lang['flynax_connect_fail'];
            $_response->script("
                printMessage('error', '{$fail_msg}');
                $('.button_bar > #browse_plugins').html('{$lang['browse']}');
            ");
            return $_response;
        }

        foreach ($plugins as $key => $plugin) {
            if (is_numeric(array_search($plugin['key'], $plugins_exist))) {
                unset($plugins[$key]);
            }
        }

        if (count($plugins)) {
            $rlSmarty->assign_by_ref('plugins', $plugins);
            // build DOM
            $tpl = 'blocks' . RL_DS . 'flynaxPluginsBrowse.block.tpl';
            $_response->assign('browse_content', 'innerHTML', $rlSmarty->fetch($tpl, null, null, false));
        } else {
            $no_new_plugins = $GLOBALS['rlLang']->getSystem('no_new_plugins');
            $_response->script("$('#browse_content').html(\"{$no_new_plugins}\");");
        }

        $_response->script("
            $('#update_area, #search_area').slideUp('fast');
            $('#browse_area').slideDown('normal');
            $('.button_bar > #browse_plugins').html('{$lang['more_plugins']}');
            plugins_loaded = true;
        ");

        $_response->call('rlPluginRemoteInstall');

        return $_response;
    }

    /**
     * Checking compatibility between version of the plugin and version of the software
     *
     * @since 4.6.0
     *
     * @param  string $plugin - Key of plugin which need be checked
     * @return bool
     */
    public function checkCompatibility($plugin)
    {
        $compatible = $this->getXmlData($plugin, ['compatible'])['compatible'];
        return $this->checkCompatibilityByVersion($compatible);
    }

    /**
     * Get XML data of plugin by provided key
     *
     * @since 4.8.0
     *
     * @param  string $key
     * @param  array  $tags - List of tags which must be provided from plugin
     * @return mixed
     */
    public function getXmlData($key = '', $tags = ['title', 'description', 'version', 'compatible'])
    {
        $key  = (string) $key;
        $data = false;

        if (!$key || !$tags || !is_readable($installXmlFile = RL_PLUGINS . $key . '/install.xml')) {
            return $data;
        }

        require_once RL_LIBS . 'saxyParser/xml_saxy_parser.php';
        $rlParser = new SAXY_Parser();
        $rlParser->xml_set_element_handler([&$this, 'startElement'], [&$this, 'endElement']);
        $rlParser->xml_set_character_data_handler([&$this, 'charData']);
        $rlParser->xml_set_comment_handler([&$this, 'commentElement']);
        $rlParser->parse(file_get_contents($installXmlFile));

        foreach ($tags as $tag) {
            $data[$tag] = $this->$tag ?: '';
            unset($this->$tag);
        }

        return $data;
    }

    /**
     * Compare needed version and version of the software
     *
     * @since 4.6.0
     *
     * @param  string $version - Version of the plugin
     * @return bool
     */
    public function checkCompatibilityByVersion($version)
    {
        if ($version && version_compare($version, $GLOBALS['config']['rl_version']) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Get phrases from the localization file
     *
     * @since 4.8.1
     *
     * @param  string $code      - Language code
     * @param  string $pluginKey - Plugin key
     * @return array|bool        - Phrases array as key => phrases or false
     */
    public function getLanguagePhrases($code, $pluginKey)
    {
        $code = strtolower($code);
        $file = RL_PLUGINS . $pluginKey . RL_DS . 'i18n' . RL_DS . $code . '.json';

        if (file_exists($file) && $phrases = file_get_contents($file)) {
            return json_decode($phrases, true);
        }

        return false;
    }
}
