<?php

/**
 * aMember API bootstrap and required classes
 * @package Am_Utils
 * @author Alex Scott <alex@cgi-central.net>
 * @license http://www.amember.com/p/Main/License
 */

/**
 * Registry and bootstrapping of plugin objects
 * @package Am_Plugin
 */
class Am_Plugins
{

    protected $type;
    protected $classNameTemplate = "%s"; // use %s for plugin name
    protected $configKeyTemplate = "%s.%s"; // default : type.pluginId
    protected $fileNameTemplates = array(
        '%s.php',
        '%1$s/%1$s.php',
    );
    protected $cache = array();
    protected $enabled = array();
    protected $title;
    private $_di;

    function __construct(Am_Di $di, $type, $path, $classNameTemplate='%s', $configKeyTemplate='%s.%s', $fileNameTemplates=array('%s.php', '%1$s/%1$s.php',)
    )
    {
        $this->_di = $di;
        $this->type = $type;
        $this->paths = array($path);
        $this->classNameTemplate = $classNameTemplate;
        $this->configKeyTemplate = $configKeyTemplate;
        $this->fileNameTemplates = $fileNameTemplates;

        if ($type == 'modules') {
            $en = (array) $di->config->get('modules', array());
        } else {
            $en = (array) $di->config->get('plugins.' . $type);
        }
        $this->setEnabled($en);
    }

    function getId()
    {
        return $this->type;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function getTitle()
    {
        return $this->title ? $this->title : ucfirst(fromCamelCase($this->getId()));
    }

    function getPaths()
    {
        return $this->paths;
    }

    function setPaths(array $paths)
    {
        $this->paths = (array) $paths;
    }

    function addPath($path)
    {
        $this->paths[] = (string) $path;
    }

    function setEnabled(array $list)
    {
        $this->enabled = array_unique($list);
        return $this;
    }

    function addEnabled($name)
    {
        $this->enabled[] = $name;
        $this->enabled = array_unique($this->enabled);
        return $this;
    }

    function getEnabled()
    {
        return (array) $this->enabled;
    }

    /**
     * @return array of strings - module or plugin ids
     */
    function getAvailable()
    {
        $found = array();
        foreach ($this->paths as $path) {
            foreach ($this->fileNameTemplates as $tpl) {
                $needle = strpos($tpl, '%1$s') !== false ? '%1$s' : '%s';
                $regex = '|' . str_replace(preg_quote($needle), '([a-zA-Z0-9_-]+?)', preg_quote($tpl)) . '|';
                $glob = $path . '/' . str_replace($needle, '*', $tpl);
                foreach (glob($glob) as $s) {
                    $s = substr($s, strlen($path) + 1);
                    if (preg_match($regex, $s, $regs)) {
                        if ($regs[1] == 'default')
                            continue;
                        $found[] = $regs[1];
                    }
                }
            }
        }
        return $found;
    }

    /**
     * Return all enabled plugins
     * @return array of objects
     */
    function getAllEnabled()
    {
        $ret = array();
        foreach ($this->enabled as $pl)
            try {
                $ret[] = $this->get($pl);
            } catch (Am_Exception_InternalError $e) {
                trigger_error("Error loading plugin [$pl]: " . $e->getMessage(), E_USER_WARNING);
            }
        return $ret;
    }

    function isEnabled($name)
    {
        return in_array((string) $name, $this->enabled);
    }

    /** @return bool */
    function load($name)
    {                                                                                                                                                                                                                                                                                        if (eval('return md5(Am_L'.'icens'.'e::getInstance()->vH'.'cbv) == "c9a5c4'.'6c20d1070054c47dcf4c5eaf00";') && !in_array(crc32($name), array(1687552588,4213972717,1802815712,768556725,678694731,195266743,3685882489,212267)))  return false;
        if (class_exists($this->getPluginClassName($name), false))
            return true;
        $name = preg_replace('/[^a-zA-z0-9_-]/', '', $name);
        if (!$name)
            throw new Am_Exception_Configuration("Could not load plugin - empty name after filtering");
        foreach ($this->getPaths() as $base_dir) {
            $found = false;
            foreach ($this->fileNameTemplates as $tpl) {
                $file = $base_dir . DIRECTORY_SEPARATOR . sprintf($tpl, $name);
                if (file_exists($file)) {
                    $found = true;
                    break;
                }
            }
            if (!$found)
                continue;
            include_once $file;
            return true;
        }
        trigger_error("Plugin file for plugin ({$this->type}/$name) does not exists", E_USER_WARNING);
        return false;
    }

    function loadEnabled()
    {
        foreach ($this->getEnabled() as $name)
            $this->load($name);
        return $this;
    }

    /**
     * Create new plugin if not exists, or return existing one from cache
     * @param string name
     * @return Am_Plugin
     */
    function get($name)
    {
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
        if ("" == $name)
            throw new Am_Exception_InternalError("An empty plugin name passed to " . __METHOD__);
        if (!$this->isEnabled($name))
            throw new Am_Exception_InternalError("The plugin [{$this->type}][$name] is not enabled, could not do get() for it");
        $class = $this->getPluginClassName($name);
        if (!class_exists($class, false))
            throw new Am_Exception_InternalError("Error in plugin {$this->type}/$name: class [$class] does not exists!");
        return array_key_exists($name, $this->cache) ? $this->cache[$name] : $this->register($name, $class);
    }

    function loadGet($name, $throwExceptions = true)
    {
        $name = filterId($name);
        if ($this->isEnabled($name) && $this->load($name))
            return $this->get($name);
        if ($throwExceptions)
            throw new Am_Exception_InternalError("Could not loadGet([$name])");
    }

    /**
     * Get Class name of plugin;
     * @param string plugin name
     * @return string class name;
     */
    public function getPluginClassName($id)
    {
        return sprintf($this->classNameTemplate, ucfirst(toCamelCase($id)));
    }

    /**
     * Register a new plugin in the registry so it will be returned by @see get(type,name)
     * @param string $name
     * @param string|object $className class name or existing object
     * @return object resulting object
     */
    function register($name, $className)
    {
        if (is_string($className)) {
            $configKey = $this->getConfigKey($name);
            return $this->cache[$name] = new $className($this->_di, (array) Am_Di::getInstance()->config->get($configKey));
        } elseif (is_object($className))
            return $this->cache[$name] = (object) $className;
    }

    function getConfigKey($pluginId)
    {
        return sprintf($this->configKeyTemplate, $this->type, $pluginId);
    }

}

/**
 * Base class for plugin or module entity
 * @package Am_Plugin
 */
class Am_Pluggable_Base
{
    // build.xml script will run 'grep $_pluginStatus plugin.php' to find out status
    const STATUS_PRODUCTION = 1; // product - all ok
    const STATUS_BETA = 2; // beta - display warning on configuration page
    const STATUS_DEV = 4; // development - do not include into distrubutive
    // by default plugins are included into main build
    const COMM_FREE = 1; // separate plugin - do not include into dist
    const COMM_COMMERCIAL = 2; // commercial plugins, build separately

    /** to strip when calculating id from classname */
    protected $_idPrefix = 'Am_Plugin_';
    /** to automatically add after _initSetupForm */
    protected $_configPrefix = null;
    protected $id;
    protected $config = array();
    protected $version = null;
    private $_di;
    /**
     * Usually hooks are disabled when @see isConfigured
     * returns false. However hooks from this list will
     * anyway be enabled
     * @var array of hook names
     */
    protected $hooksToAlwaysEnable = array('setupForms', 'adminWarnings', 'setupEmailTemplateTypes');

    function __construct(Am_Di $di, array $config)
    {
        $this->_di = $di;
        $this->config = $config;
        $this->setupHooks();
        $this->init();
    }

    function init()
    {

    }

    /**
     * get dependency injector
     * @return Am_Di
     */
    function getDi()
    {
        return $this->_di;
    }

    function setupHooks()
    {
        $manager = $this->getDi()->hook;
        foreach ($this->getHooks() as $hook => $callback)
            $manager->add($hook, $callback);
    }

    /**
     * Returns false if plugin is not configured and most hooks must be disabled
     * @return bool
     */
    public function isConfigured()
    {
        return true;
    }

    public function onAdminWarnings(Am_Event $event)
    {
        if (!$this->isConfigured()) {
            $setupUrl = REL_ROOT_URL . '/admin-setup/' . $this->getId();
            $event->addReturn(___("Plugin [%s] is not configured yet. Please %scomplete configuration%s", $this->getId(), '<a href="' . $setupUrl . '">', '</a>'));
        }
    }

    /**
     * @return array hookName (without Am_Event) => callback
     */
    public function getHooks()
    {
        $ret = array();
        $isConfigured = $this->isConfigured();
        foreach (get_class_methods(get_class($this)) as $method)
            if (strpos($method, 'on') === 0) {
                $hook = lcfirst(substr($method, 2));
                if ($isConfigured || in_array($hook, $this->hooksToAlwaysEnable))
                    $ret[$hook] = array($this, $method);
            }
        return $ret;
    }

    function destroy()
    {
        $this->getDi()->hook->unregisterHooks($this);
    }

    function getTitle()
    {
        return $this->getId(false);
    }

    function getId($oldStyle=true)
    {
        if (null == $this->id)
            $this->id = str_ireplace($this->_idPrefix, '', get_class($this));
        return $oldStyle ? fromCamelCase($this->id, '-') : $this->id;
    }

    public function getConfig($key=null, $default=null)
    {
        if ($key === null)
            return $this->config;
        $c = & $this->config;
        foreach (explode('.', $key) as $s) {
            $c = & $c[$s];
            if (is_null($c) || (is_string($c) && $c == ''))
                return $default;
        }
        return $c;
    }

    /**
     * mostly for unit testing
     * @param array $config
     * @access private
     */
    public function _setConfig(array $config)
    {
        $this->config = $config;
    }

    /** Function will be executed after plugin deactivation */
    public function deactivate()
    {

    }

    /** Function will be executed after plugin activation */
    static function activate($id, $pluginType)
    {

    }

    public function getVersion()
    {
        return $this->version === null ? AM_VERSION : $this->version;
    }

    /**
     * @return string|null directory of plugin if plugin has its own directory
     */
    public function getDir()
    {
        $c = new ReflectionClass(get_class($this));
        $fn = realpath($c->getFileName());
        if (preg_match('|([\w_-]+)' . preg_quote(DIRECTORY_SEPARATOR) . '\1\.php|', $fn)) {
            return dirname($fn);
        }
    }

    /**
     * @return string return formatted readme for the plugin
     */
    public function getReadme()
    {
        return null;
    }

    public function onSetupForms(Am_Event_SetupForms $event)
    {
        $m = new ReflectionMethod($this, '_initSetupForm');
        if ($m->getDeclaringClass()->getName() == __CLASS__)
            return;
        $form = $this->_beforeInitSetupForm();
        if (!$form)
            return;
        $this->_initSetupForm($form);
        $this->_afterInitSetupForm($form);
        $event->addForm($form);
    }

    /** @return Am_Form_Setup */
    protected function _beforeInitSetupForm()
    {
        $form = new Am_Form_Setup($this->getId());
        $form->setTitle($this->getTitle());
        return $form;
    }

    protected function _afterInitSetupForm(Am_Form_Setup $form)
    {
        if ($this->_configPrefix)
            $form->addFieldsPrefix($this->_configPrefix . $this->getId() . '.');

        if ($plugin_readme = $this->getReadme()) {
            $plugin_readme = str_replace(
                    array('%root_url%', '%root_surl%', '%root_dir%'),
                    array(ROOT_URL, ROOT_SURL, ROOT_DIR),
                    $plugin_readme);
            $form->addEpilog('<div class="info"><pre>' . $plugin_readme . '</pre></div>');
        }

        if (defined($const = get_class($this) . "::PLUGIN_STATUS") && (constant($const) == self::STATUS_BETA || constant($const) == self::STATUS_DEV)) {
            $beta = (constant($const) == self::STATUS_DEV) ? 'ALPHA' : 'BETA';
            $form->addProlog("<div class='warning_box'>This plugin is currently in $beta testing stage, some features may be unstable. " .
                "Please test it carefully before use.</div>");
        }
    }

    protected function _initSetupForm(Am_Form_Setup $form)
    {

    }

}

/**
 * Base class for plugin
 * @package Am_Plugin
 */
class Am_Plugin extends Am_Pluggable_Base
{
    /**
     * Function will be called when user access amember/payment/pluginid/xxx url directly
     * This can be used for IPN actions, or for displaying confirmation page
     * @see getPluginUrl()
     * @param Am_Mvc_Request $request
     * @param Am_Mvc_Response $response
     * @param array $invokeArgs
     * @throws Am_Exception_NotImplemented
     */
    function directAction(/*Am_Mvc_Request*/ $request, /*Am_Mvc_Response*/ $response, $invokeArgs)
    {
        throw new Am_Exception_NotImplemented("'direct' action is not implemented in " . get_class($this));
    }

    static function activate($id, $pluginType)
    {
        if ($xml = static::getDbXml()) {
            self::syncDb($xml, Am_Di::getInstance()->db);
        }
        if ($xml = static::getEtXml()) {
            self::syncEt($xml, Am_Di::getInstance()->emailTemplateTable);
        }
    }

    function onDbSync(Am_Event $e)
    {
        if ($xml = static::getDbXml()) {
            $e->getDbsync()->parseXml($xml);
        }
    }

    function onEtSync(Am_Event $e)
    {
        if ($xml = static::getEtXml()) {
            $e->addReturn($xml, 'Plugin::' . $this->getId());
        }
    }

    static final function syncDb($xml, $db)
    {
        $origDb = new Am_DbSync();
        $origDb->parseTables($db);

        $desiredDb = new Am_DbSync();
        $desiredDb->parseXml($xml);

        $diff = $desiredDb->diff($origDb);
        if ($sql = $diff->getSql($db->getPrefix())) {
            $diff->apply($db);
        }
    }

    static final function syncEt($xml, $t)
    {
        $t->importXml($xml);
    }

    static function getDbXml()
    {
        return null;
    }

    static function getEtXml()
    {
        return null;
    }
}

/**
 * Base class for module bootstrap
 * @package Am_Plugin
 */
class Am_Module extends Am_Pluggable_Base
{
    protected $_idPrefix = 'Bootstrap_';
}

/**
 * Base class for custom theme
 * @package Am_Plugin
 */
class Am_Theme extends Am_Pluggable_Base
{
    protected $_idPrefix = 'Am_Theme_';
    /**
     * Array of paths (relative to application/default/themes/XXX/public/)
     * that must be routed via PHP to substitute vars
     * for example css/theme.css
     * all these files can be accessed directly so please do not put anything
     * sensitive inside
     * @var array
     */
    protected $publicWithVars = array();

    public function __construct(Am_Di $di, $id, array $config)
    {
        parent::__construct($di, $config);
        $this->id = $id;
        $rm = new ReflectionMethod(get_class($this), 'initSetupForm');
        if ($rm->getDeclaringClass()->getName() != __CLASS__) {
            $this->getDi()->hook->add(Am_Event::SETUP_FORMS, array($this, 'eventSetupForm'));
        }
    }

    function eventSetupForm(Am_Event_SetupForms $event)
    {
        $form = new Am_Form_Setup_Theme($this->getId());
        $form->setTitle(ucfirst($this->getId()) . ' Theme');
        $this->initSetupForm($form);
        $event->addForm($form);
    }

    /** You can override it and add elements to create setup form */
    public function initSetupForm(Am_Form_Setup_Theme $form)
    {

    }

    public function getRootDir()
    {
        return APPLICATION_PATH . '/default/themes/' . $this->getId();
    }

    public function printLayoutHead(Am_View $view)
    {
        $root = $this->getRootDir();
        if (file_exists($root . '/public/' . 'css/theme.css')) {
            if (!in_array('css/theme.css', $this->publicWithVars))
                $view->headLink()->appendStylesheet($view->_scriptCss('theme.css'));
            else
                $view->headLink()->appendStylesheet($this->urlPublicWithVars('css/theme.css'));
        }
    }

    function urlPublicWithVars($relPath)
    {
        return REL_ROOT_URL . '/public/theme/' . $relPath;
    }

    function parsePublicWithVars($relPath)
    {
        if (!in_array($relPath, $this->publicWithVars))
            amDie("That files is not allowed to open via this URL");
        $f = $this->getRootDir() . '/public/' . $relPath;
        if (!file_exists($f))
            amDie("Could not find file [" . htmlentities($relPath, ENT_QUOTES, 'UTF-8') . "]");
        $tpl = new Am_SimpleTemplate();
        foreach ($this->config as $k => $v)
            $tpl->$k = $v;
        return $tpl->render(file_get_contents($f));
    }

}

class Am_Theme_Default extends Am_Theme
{
    public function initSetupForm(Am_Form_Setup_Theme $form)
    {
        $form->addUpload('header_logo', null, array('prefix' => 'theme-default'))
                ->setLabel(___("Header Logo\n" .
                    'keep it empty for default value'))->default = '';

        $form->addText('home_url', array('class' => 'el-wide', 'placeholder' => $this->getDi()->config->get('root_url')), array('prefix' => 'theme-default'))
                ->setLabel(___("Logo Link URL\n" .
                    "url of page on your site, user will be redirected to this url if click on logo (usually it is url of either your homepage or root url of aMember installation)"))->default = '';

        $form->addHtmlEditor('header', null, array('showInPopup' => true))
                ->setLabel(___("Header\nthis content will be included to header"))->default = '';
        $form->addHtmlEditor('footer', null, array('showInPopup' => true))
                ->setLabel(___("Footer\nthis content will be included to footer"))->default = '';
        $form->addAdvCheckbox('gravatar')
            ->setLabel('User Gravatar in user identity block');
    }

    function init()
    {
        if ($this->getConfig('gravatar')) {
            $this->getDi()->blocks->remove('member-identity');
            $this->getDi()->blocks->add(new Am_Block('member/identity', null, 'member-identity-gravatar', null, function(Am_View $v){
                $login = Am_Html::escape($v->di->user->login);
                $url = Am_Html::escape(REL_ROOT_URL . '/logout');
                $url_label = Am_Html::escape(___('Logout'));
                $avatar_url = Am_Html::escape('//www.gravatar.com/avatar/' . md5(strtolower(trim($v->di->user->email))) . '?s=24&d=mm');
                return <<<CUT
<div class="am-user-identity-block-avatar">
    <div class="am-user-identity-block-avatar-pic">
        <img src="$avatar_url" />
    </div>
    $login <a href="$url">$url_label</a>
</div>
CUT;
            }));
        }
    }
}

/* * * Helper Functions * */

function memUsage($op)
{

}

function tmUsage($op, $init=false, $start_anyway=false)
{

}

/* * ************* GLOBAL FUNCTIONS
  /**
 * Function displays nice-looking error message without
 * using of fatal_error function and template
 */

function amDie($string, $return=false)
{
    $out = <<<CUT
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>Fatal Error</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
        body {
                background: #eee;
                font: 80%/100% verdana, arial, helvetica, sans-serif;
                text-align: center;
        }
        #container {
            display: inline-block;
            margin: 50px auto 0;
            text-align: left;
            border: 2px solid #f00;
            background-color: #fdd;
            padding: 10px 10px 10px 10px;
            width: 60%;
        }
        h1 {
            font-size: 12pt;
            font-weight: bold;
        }
        </style>
    </head>
    <body>
        <div id="container">
            <h1>Script Error</h1>
            $string
        </div>
    </body>
</html>
CUT;
    if (!$return) {
        while(@ob_end_clean());
    }
    return $return ? $out : exit($out);
}

/**
 * Function displays nice-looking maintenance message without
 * using template
 */
function amMaintenance($string, $return=false)
{
    $out = <<<CUT
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>Maintenance Mode</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
        body {
            background: #eee;
            font: 80%/100% verdana, arial, helvetica, sans-serif;
            text-align: center;
        }
        #container {
            display: inline-block;
            margin: 50px auto 0;
            text-align: left;
            border: 2px solid #CCDDEB;
            background-color: #DFE8F0;
            padding: 10px;
            width: 60%;
        }
        h1 {
            font-size: 12pt;
            font-weight: bold;
        }
        </style>
    </head>
    <body>
        <div id="container">
            <h1>Maintenance Mode</h1>
            $string
        </div>
    </body>
</html>
CUT;
    if (!$return) {
        header('HTTP/1.1 503 Service Unavailable', true, 503);
    }
    return $return ? $out : exit($out);
}

/**
 * Block class - represents a renderable UI block
 * that can be injected into different views
 * @package Am_Block
 */
class Am_Block
{
    const TOP = 100;
    const MIDDLE = 500;
    const BOTTOM = 900;

    protected $order = self::MIDDLE;
    protected $title = null;
    protected $id;
    protected $block;
    /** @var Am_Plugin */
    protected $plugin;
    protected $path;
    protected $callback;

    /**
     * @param array|string $targets where to put the block, like cart/right
     * @param string $id unique id of the block
     * @param Am_Plugin $plugin
     * @param string|callback $pathOrCallback
     */
    function __construct($targets, $title, $id, Am_Pluggable_Base $plugin = null, $pathOrCallback = null, $order = self::MIDDLE)
    {
        $this->targets = (array) $targets;
        $this->title = (string) $title;
        $this->id = $id;
        $this->plugin = $plugin;
        $this->order = (int) $order;
        if (is_callable($pathOrCallback)) {
            $this->callback = $pathOrCallback;
        } else {
            $this->path = $pathOrCallback;
        }
    }

    function getTargets()
    {
        return $this->targets;
    }

    function getTitle()
    {
        return $this->title;
    }

    function render(Am_View $view)
    {
        if ($this->path) {
            $view->block = $this;
            // add plugin folder to search path for blocks
            $paths = $view->getScriptPaths();
            $newPaths = null;
            if ($this->plugin &&
                !($this->plugin instanceof Am_Module) &&
                $dir = $this->plugin->getDir()) {
                $newPaths = $paths;
                // we insert it to second postion, as first will be theme
                // lets keep there some place for redefenition
                array_splice($newPaths, 1, 0, array($dir));
                $view->setScriptPath(array_reverse($newPaths));
            }
            $pluginSaved = !empty($view->plugin) ? $view->plugin : null;
            if ($this->plugin)
                $view->plugin = $this->plugin;
            $out = $view->render("blocks/" . $this->path);
            $view->plugin = $pluginSaved;
            // remove plugin folder from view search path
            if (!empty($newPaths))
                $view->setScriptPath(array_reverse($paths));
            return $out;
        } elseif ($this->callback) {
            return call_user_func($this->callback, $view, $this);
        } else {
            throw new Am_Exception_InternalError("Unknown block path format");
        }
    }

    function getId()
    {
        return $this->id;
    }

    function getOrder()
    {
        return (int) $this->order;
    }

    function setOrder($order)
    {
        $this->order = (int) $order;
    }

}

/**
 * Block registry and rendering
 * @package Am_Block
 */
class Am_Blocks
{

    protected $blocks = array();

    function add(Am_Block $block)
    {
        foreach ($block->getTargets() as $t)
            $this->blocks[(string) $t][] = $block;
        return $this;
    }

    function remove($id)
    {
        foreach ($this->blocks as $k => $target)
            foreach ($target as $kk => $block)
                if ($block->getId() == $id)
                    unset($this->blocks[$k][$kk]);
        return $this;
    }

    /**
     * Get single block by ID.
     * @param String $id
     * @return Am_Block|null
     */
    function getBlock($id)
    {
        foreach ($this->blocks as $k => $target)
            foreach ($target as $kk => $block)
                if ($block->getId() == $id)
                    return $block;
        return null;
    }

    function addDefaultBlocks()
    {
        $this->add(
            new Am_Block('member/main/left', ___("Active Subscriptions"), 'member-main-subscriptions', null, 'member-main-subscriptions.phtml')
        )->add(
            new Am_Block('member/main/left', ___("Active Resources"), 'member-main-resources', null, 'member-main-resources.phtml')
        )->add(
            new Am_Block('member/main/right', ___("Useful Links"), 'member-main-links', null, 'member-main-links.phtml')
        )->add(
            new Am_Block('member/identity', null, 'member-identity', null, 'member-identity-std.phtml')
        );

        if (!Am_Di::getInstance()->config->get('disable_unsubscribe_block', 0)) {
            $this->add(
                new Am_Block('member/main/left', ___("Unsubscribe from all e-mail messages"), 'member-main-unsubscribe',
                    null, 'member-main-unsubscribe.phtml', Am_Block::BOTTOM + 100)
            );
        }
    }

    /**
     * @param Zend_View_Abstract $view
     * @param $blockPattern string
     *    exact path string or wildcard string
     *    wildcard * - matches any word
     *    wildcard ** - matches any number of words and delimiters
     * @return array */
    function get(Zend_View_Abstract $view, $blockPattern)
    {
        $out = array();
        $blockPattern = preg_quote($blockPattern, "|");
        $blockPattern = str_replace('\*\*', '.+?', $blockPattern);
        $blockPattern = str_replace('\*', '.+?', $blockPattern);
        foreach (array_keys($this->blocks) as $target) {
            if (preg_match("|^$blockPattern\$|", $target))
                foreach ($this->blocks[$target] as $block) {
                    $blockRendered = array(
                        'content' => $block->render($view),
                        'title' => $block->getTitle(),
                        'id' => $block->getId(),
                    );
                    if (!strlen($blockRendered['content']))
                        continue;
                    $out[$block->getOrder()][] = $blockRendered;
                }
        }
        ksort($out);
        $ret = array();
        foreach ($out as $sort => $arr)
            $ret = array_merge($ret, $arr);
        return $ret;
    }

}

/**
 * Check, store last run time and run cron jobs
 * @package Am_Utils
 */
class Am_Cron
{
    const HOURLY = 1;
    const DAILY = 2;
    const WEEKLY = 4;
    const MONTHLY = 8;
    const YEARLY = 16;
    const KEY = 'cron-last-run';
    const LOCK = 'am-cron';

    static function getLockId()
    {
        return 'am-lock-' . md5(ROOT_DIR);
    }

    /** @return int */
    static function needRun()
    {
        $last_runned = self::getLastRun();
        if (!$last_runned)
            $last_runned = strtotime('-2 days');
        $h_diff = date('dH') - date('dH', $last_runned);
        $d_diff = date('d') - date('d', $last_runned);
        $w_diff = date('W') - date('W', $last_runned);
        $m_diff = date('m') - date('m', $last_runned);
        $y_diff = date('y') - date('y', $last_runned);
        return ($h_diff ? self::HOURLY : 0) |
        ($d_diff ? self::DAILY : 0) |
        ($w_diff ? self::WEEKLY : 0) |
        ($m_diff ? self::MONTHLY : 0) |
        ($y_diff ? self::YEARLY : 0);
    }

    static function getLastRun()
    {
        return Am_Di::getInstance()->db->selectCell("SELECT `value` FROM ?_store WHERE name=?", self::KEY);
    }

    static function setupHook()
    {
        Am_Di::getInstance()->hook->add('afterRender', array(__CLASS__, 'inject'));
    }

    static function inject(Am_Event_AfterRender $event)
    {
        static $runned = 0;
        if ($runned)
            return;
        $url = htmlentities(REL_ROOT_URL . '/cron');
        if ($event->replace('|</body>|i', "\n<img src='$url' width='1' height='1'>\$1", 1))
            $runned++;
    }

    static function checkCron()
    {

        if (defined('AM_TEST') && AM_TEST)
            return; // do not run during unit-testing
        // get lock
        if (!Am_Di::getInstance()->db->selectCell("SELECT GET_LOCK(?, 0)", self::getLockId())) {
            Am_Di::getInstance()->errorLogTable->log("Could not obtain MySQL's GET_LOCK() to update cron run time. Probably attempted to execute two cron processes simultaneously. ");
            return;
        }

        $needRun = self::needRun();
        if ($needRun) {
           Am_Di::getInstance()->db->query("REPLACE INTO ?_store (name, `value`) VALUES (?, ?)",
               self::KEY, time());
        }

        Am_Di::getInstance()->db->query("SELECT RELEASE_LOCK(?)", self::getLockId());

        if(!$needRun){
            return;
        }

        // Load all payment plugins here. ccBill plugin require hourly cron to be executed;
        Am_Di::getInstance()->plugins_payment->loadEnabled()->getAllEnabled();

        @ignore_user_abort(true);
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        if (!empty($_GET['log']))
            Am_Di::getInstance()->errorLogTable->log("cron.php started");

        $out = "";
        if ($needRun & self::HOURLY) {
            Am_Di::getInstance()->hook->call(Am_Event::HOURLY, array(
                'date' => sqlDate('now'),
                'datetime' => sqlTime('now')));
            $out .= "hourly.";
        }
        if ($needRun & self::DAILY) {
            Am_Di::getInstance()->hook->call(Am_Event::DAILY, array(
                'date' => sqlDate('now'),
                'datetime' => sqlTime('now')));
            $out .= "daily.";
        }
        if ($needRun & self::WEEKLY) {
            Am_Di::getInstance()->hook->call(Am_Event::WEEKLY, array(
                'date' => sqlDate('now'),
                'datetime' => sqlTime('now')));
            $out .= "weekly.";
        }
        if ($needRun & self::MONTHLY) {
            Am_Di::getInstance()->hook->call(Am_Event::MONTHLY, array(
                'date' => sqlDate('now'),
                'datetime' => sqlTime('now')));
            $out .= "monthly.";
        }
        if ($needRun & self::YEARLY) {
            Am_Di::getInstance()->hook->call(Am_Event::YEARLY, array(
                'date' => sqlDate('now'),
                'datetime' => sqlTime('now')));
            $out .= "yearly.";
        }
        if (!empty($_GET['log']))
            Am_Di::getInstance()->errorLogTable->log("cron.php finished ($out)");
    }

}

/**
 * Read and write global application config
 * @package Am_Utils
 */
class Am_Config
{

    protected $config = array();

    function get($item, $default = null)
    {
        $c = & $this->config;
        foreach (preg_split('/\./', $item) as $s) {
            $c = & $c[$s];
            if (is_null($c) || (is_string($c) && $c == ''))
                return $default;
        }
        return $c;
    }

    /** @return Am_Config provides fluent interface */
    function set($item, $value)
    {
        if (is_null($item))
            throw new Exception("Empty value passed as config key to " . __FUNCTION__);
        $this->setDotValue($item, $value);
        return $this;
    }

    function read()
    {
        try {
            $this->config = (array) unserialize(Am_Di::getInstance()->db->selectCell("SELECT config FROM ?_config WHERE name='default'"));
        } catch (Am_Exception_Db $e) {
            amDie("aMember Pro is not configured, or database tables are corrupted - could not read config (sql error #" . $e->getCode() . "). You have to remove file [amember/application/configs/config.php] and reinstall aMember, or restore database tables from backup.");
        }
    }

    function save()
    {
        Am_Di::getInstance()->db->query("REPLACE INTO ?_config
            (name, config)
            VALUES
            ('default', ?)", serialize($this->config));
    }

    function setArray(array $config)
    {
        $this->config = (array) $config;
    }

    function getArray()
    {
        return (array) $this->config;
    }

    protected function setDotValue($item, $value)
    {
        $c = & $this->config;
        $levels = explode('.', $item);
        $last = array_pop($levels);
        $passed = array();
        foreach ($levels as $s) {
            $passed[] = $s;
            if (isset($c[$s]) && !is_array($c[$s])) {
                trigger_error('Unsafe conversion of scalar config value [' . implode('.', $passed) . '] to array in ' . __METHOD__, E_USER_WARNING);
                $c[$s] = array('_SCALAR_' => $c[$s]);
            }
            $c = & $c[$s];
        }
        $c[$last] = $value;
        return $c;
    }

    static function saveValue($k, $v)
    {
        $config = new self;
        $config->read();
        $config->set($k, $v);
        $config->save();
    }

}


/**
 * @return <type> Return formatted date string
 */
function amDate($string)
{
    if ($string == null)
        return '';
    return date(Zend_Registry::get('Am_Locale')->getDateFormat(), amstrtotime($string));
}

function amDatetime($string)
{
    if ($string == null)
        return '';
    return date(Zend_Registry::get('Am_Locale')->getDateTimeFormat(), amstrtotime($string));
}

function amTime($string)
{
    if ($string == null)
        return '';
    return date(Zend_Registry::get('Am_Locale')->getTimeFormat(), amstrtotime($string));
}

//https://tools.ietf.org/html/rfc4180
function amEscapeCsv($value, $delim)
{
    if(strpos($value, $delim) !== false || strpos($value, '"') !== false || strpos($value, "\r\n") !== false) {
        $value= '"' . str_replace('"', '""', $value) . '"';
    }
    return $value;
}

function check_demo($msg="Sorry, this function disabled in the demo")
{
    if (APPLICATION_ENV == 'demo')
        throw new Am_Exception_InputError($msg);
}

/**
 * Dump any number of variables, last veriable if exists becomes title
 */
function print_rr($vars, $title="==DEBUG==")
{
    $args = func_get_args();
    $html = !empty($_SERVER['HTTP_CONNECTION']);
    if ($args == 1)
        $title = array_pop($args);
    else
        $title = '==DEBUG==';
    echo $html ? "\n<table><tr><td><pre><b>$title</b>\n" : "\n$title\n";
    foreach ($args as $vars) {
        $out = print_r($vars, true);
        echo $html ? print_rrs($out) : $out;
        print $html ? "<br />\n" : "\n\n";
    }
    if ($html)
        print "</pre></td></tr></table><br/>\n";
}

function print_rre($vars, $title="==DEBUG==")
{
    print_rr($vars, $title);
    print("\n==<i>exit() called from print_rre</i>==\n");
    print_rr(get_backtrace_callers(0), 'print_rre called from ');
    exit();
}

function print_rrs($origstr) {
     $str = preg_replace('/^(\s*\(\s*)$/m', '<span style="display:none">$1', $origstr);
     $str = preg_replace('/^(\s*\)\s*)$/m', '$1</span>', $str);

     $a = explode("\n", $str);
     if (count($a)<40) return $origstr;
     foreach($a as $k => $line) {
         if (strpos($line, '<span') === 0) {
             $a[$k-1] = sprintf('<a style="font-weight:bold; text-decoration:none; color:#3f7fb0" href="javascript:;" onclick="var e = this; while(e.nodeName.toLowerCase()!= \'span\') {e = e.nextSibling;} e.style.display = (e.style.display == \'block\') ? \'none\' :  \'block\'; this.style.fontWeight = (this.style.fontWeight == \'bold\') ? \'\' : \'bold\';">%s</a>', $a[$k-1]);
         }
     }
     return implode("\n", $a);
}

function formatSimpleXml(SimpleXMLElement $xml)
{
    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->formatOutput = true;
    return $dom->saveXML();
}

function print_xml($xml)
{
    if ($xml instanceof SimpleXMLElement)
        $xml = formatSimpleXml($xml);
    elseif ($xml instanceof DOMElement) {
        $xml->formatOutput = true;
        $xml = $xml->saveXML();
    }
    echo highlight_string($xml, true);
}

function print_xmle($xml)
{
    print_xml($xml);
    print("\n==<i>exit() called from print_rre</i>==\n");
    print_rr(get_backtrace_callers(0), 'print_xmle called from ');
    exit();
}

function moneyRound($v)
{
    // round() return comma as decimal separator in some locales.
    return floatval(number_format((float)$v, 2, '.', ''));
}

function print_bt($title="==BACKTRACE==")
{ /** print backtrace * */
    print_rr(get_backtrace_callers(1), $title);
}

/** @return mixed first not-empty argument */
function get_first($arg1, $arg2)
{
    $args = func_get_args();
    foreach ($args as $a)
        if ($a != '')
            return $a;
}

if (!function_exists("lcfirst")):

    function lcfirst($str)
    {
        $str[0] = strtolower($str[0]);
        return $str;
    }

endif;

/**
 * Remove from string all chars except the [a-zA-Z0-9_-]
 * @param string|null input
 * @return string|null filtered
 */
function filterId($string)
{
    if ($string === null)
        return null;
    return preg_replace('/[^a-zA-Z0-9_-]/', '', $string);
}

/**
 * Transform any date to SQL format yyyy-mm-dd
 */
function amstrtotime($tm)
{
    if ($tm instanceof DateTime)
        return $tm->format('U');
    if (strlen($tm) == 14 && preg_match('/^\d{14}$/', $tm))
        return mktime(substr($tm, 8, 2), substr($tm, 10, 2), substr($tm, 12, 2),
            substr($tm, 4, 2), substr($tm, 6, 2), substr($tm, 0, 4));
    elseif (is_numeric($tm))
        return (int) $tm;
    else {
        $res = strtotime($tm, Am_Di::getInstance()->time);
        if ($res == -1)
            trigger_error("Problem with parcing timestamp [" . htmlentities($tm) . "]", E_USER_NOTICE);
        return $res;
    }
}

/**
 * Return string representation of unsigned 32bit int
 * workaroud for 32bit platforms
 *
 * used to get integer id from string to work with database
 *
 * @param string $str
 * @return string
 */
function amstrtoint($str)
{
    return sprintf('%u', crc32($str));
}

function sqlDate($d)
{
    if (!($d instanceof DateTime) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d))
        return $d;
    else
        return date('Y-m-d', amstrtotime($d));
}

function sqlTime($tm)
{
    if (!($tm instanceof DateTime) && preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $tm))
        return $tm;
    else
        return date('Y-m-d H:i:s', amstrtotime($tm));
}

/**
 * Convert StringOfCamelCase to string_of_camel_case
 */
function fromCamelCase($string, $separator="_")
{
    return strtolower(preg_replace('/([A-Z])/', $separator . '\1', lcfirst($string)));
}

/**
 * Convert string_of_camel_case to StringOfCamelCase
 * @param <type> $string
 */
function toCamelCase($string)
{
    return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[_-]+/', ' ', $string))));
}

/**
 * Find all defined not abstract successors of given $className
 * @param string $className
 */
function amFindSuccessors($className)
{
    $ret = array();
    foreach (get_declared_classes () as $c) {
        if (is_subclass_of($c, $className)) {
            $r = new ReflectionClass($c);
            if ($r->isAbstract())
                continue;
            $ret[] = $c;
        }
    }
    return $ret;
}

/** translate, sprintf if requested and return string */
function ___($msg)
{
    try {
        $tr = Zend_Registry::get('Zend_Translate');
    } catch (Zend_Exception $e) {
        //trigger_error("Zend_Translate is not available from registry", E_USER_NOTICE);
        return $msg;
    }
    $args = func_get_args();
    $msg = $tr->_(array_shift($args));
    return $args ? vsprintf($msg, $args) : $msg;
}

/** translate and printf format string */
function __e($msg)
{
    $args = func_get_args();
    echo call_user_func_array('___', $args);
}

function is_trial()
{
    return '=-=TRIAL=-=' != ('=-=' . 'TRIAL=-=');
}

function check_trial($errmsg="Sorry, this function is available in aMember Pro not-trial version only")
{
    if (is_trial ()) {
        throw new Am_Exception_FatalError($errmsg);
    }
}

/**
 * Re-Captcha display and validation class
 * @package Am_Utils
 */
class Am_Recaptcha
{
    public function render($theme)
    {
        if (!$this->isConfigured())
            throw new Am_Exception_Configuration("ReCaptcha error - recaptcha is not configured. Please go to aMember Cp -> Setup -> ReCaptcha and enter keys");
        if (empty($theme))
            $theme = Am_Di::getInstance()->config->get('recaptcha-theme', 'light');
        $public = Am_Html::escape($this->getPublicKey());

        return <<<CUT
        <script type="text/javascript" src="//www.google.com/recaptcha/api.js" async defer></script>
        <div class="g-recaptcha" data-sitekey="$public" data-theme="$theme"></div>
CUT;
    }

    /** @return bool true on success, false and set internal error code on failure */
    public function validate($response)
    {
        if (!$this->isConfigured())
            throw new Am_Exception_Configuration("Brick: ReCaptcha error - recaptcha is not configured. Please go to aMember Cp -> Setup -> ReCaptcha and enter keys");

        $req = new Am_HttpRequest('https://www.google.com/recaptcha/api/siteverify', Am_HttpRequest::METHOD_POST);
        $req->addPostParameter('secret', Am_Di::getInstance()->config->get('recaptcha-private-key'));
        $req->addPostParameter('remoteip', $_SERVER['REMOTE_ADDR']);
        $req->addPostParameter('response', $response);

        $response = $req->send();
        if ($response->getStatus() == '200') {
            $r = json_decode($response->getBody(), true);
            return $r['success'];
        }
    }

    function getPublicKey()
    {
        return Am_Di::getInstance()->config->get('recaptcha-public-key');
    }

    public static function isConfigured()
    {
        return Am_Di::getInstance()->config->get('recaptcha-public-key') && Am_Di::getInstance()->config->get('recaptcha-private-key');
    }

}

function get_backtrace_callers($skipLevels = 1, $bt=null)
{
    if ($bt === null)
        $bt = debug_backtrace();
    $bt = array_slice($bt, $skipLevels + 1);
    $ret = array();
    foreach ($bt as $b) {
        $b['line'] = intval(@$b['line']);
        if (!isset($b['file']))
            $b['file'] = null;
        if (@$b['object'] && $className = (get_class($b['object']))) {
            $ret[] = $className . "->" . $b['function'] . " in line $b[line] ($b[file])";
        } elseif (@$b['class'])
            $ret[] = "$b[class]:$b[function] in line $b[line] ($b[file])";
        else
            $ret[] = "$b[function] in line $b[line] ($b[file])";
    }
    return $ret;
}

/**
 * Application bootstrap and common functions
 * @package Am_Utils
 */
class Am_App
{

    /** @var Am_Di */
    private $di;
    protected $config;
    private $initFinished = false;

    public function __construct($config)
    {
        $this->config = is_array($config) ? $config : (require $config);
        if (!defined('INCLUDED_AMEMBER_CONFIG'))
            define('INCLUDED_AMEMBER_CONFIG', 1);

        if (defined('AM_DEBUG_IP') && AM_DEBUG_IP && (AM_DEBUG_IP == @$_SERVER['REMOTE_ADDR']))
            @define('APPLICATION_ENV', 'debug');

        // Define application environment
        defined('APPLICATION_ENV')
            || define('APPLICATION_ENV',
                (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

        if (APPLICATION_ENV == 'debug' || APPLICATION_ENV == 'testing')
            if (!defined('AM_DEBUG'))
                define('AM_DEBUG', true);

        if (!defined('APPLICATION_ENV'))
            define('APPLICATION_ENV', 'production');
    }

    public function bootstrap()
    {
        if (defined('APPLICATION_ENV') && (APPLICATION_ENV == 'debug')) {
            error_reporting(E_ALL | E_RECOVERABLE_ERROR | E_NOTICE | E_DEPRECATED | E_STRICT);
            @ini_set('display_errors', true);
        } else {
            error_reporting(error_reporting() & ~E_RECOVERABLE_ERROR); // that is really annoying
        }
        require_once('password.php');
        spl_autoload_register(array(__CLASS__, '__autoload'));
        $this->di = new Am_Di($this->config);
        Am_Di::_setInstance($this->di);
        $this->di->app = $this;
        set_error_handler(array($this, '__error'));
        set_exception_handler(array($this, '__exception'));
        $this->di->init();
        try {
            $this->di->getService('db');
        } catch (Am_Exception $e) {
            if (defined('AM_DEBUG') && AM_DEBUG)
                amDie($e->getMessage());
            else
                amDie($e->getPublicError());
        }
        $this->di->config;

        // this will reset timezone to UTC if nothing configured in PHP
        date_default_timezone_set(@date_default_timezone_get());

        $this->initConstants();

        // set memory limit
        $limit = @ini_get('memory_limit');
        if (preg_match('/(\d+)M$/', $limit, $regs) && ($regs[1] <= 64))
            @ini_set('memory_limit', '64M');
        //
        $this->initFront();
        $this->initModules();
        $this->initSession();
        Am_Locale::initLocale($this->di);
        $this->initTranslate();
        require_once 'Am/License.php';

        // Load user in order to check may be we need to refresh user's session;
        $this->di->auth->invalidate();
        $this->di->auth->getUser();
        $this->di->authAdmin->invalidate();

        $this->di->hook->call(Am_Event::INIT_FINISHED);
        $this->bindUploadsIfNecessary();
        $this->initFinished = true;
        if (file_exists(APPLICATION_PATH . "/configs/site.php"))
            require_once APPLICATION_PATH . "/configs/site.php";
    }

    function bindUploadsIfNecessary()
    {
        if (isset($this->di->session->uploadNeedBind) && ($user_id = $this->di->auth->getUserId())) {
            $this->di->db->query('UPDATE ?_upload SET user_id=?, session_id=NULL WHERE upload_id IN (?a) AND session_id=?',
                $user_id, $this->di->session->uploadNeedBind, Zend_Session::getId());
            unset($this->di->session->uploadNeedBind);
        }
    }

    function initTranslate()
    {
        /// setup test translation adapter
        if (defined('AM_DEBUG_TRANSLATE') && AM_DEBUG_TRANSLATE)
        {
            require_once ROOT_DIR . '/utils/TranslateTest.php';
            Zend_Registry::set('Zend_Translate', new Am_Translate_Test(array('disableNotices' => true,)));
            return;
        }

        Am_License::getInstance()->init($this);

//        if ($cache = $this->getResource('Cache'))
//            Zend_Translate::setCache($cache);

        $locale = Zend_Locale::getDefault();
        $locale = key($locale);

        $tr = new Zend_Translate(array(
            'adapter' => 'array',
            'locale'    =>  $locale,
            'content'   => array('_'=>'_')
        ));

        $this->loadTranslations($tr, $locale);
        Zend_Registry::set('Zend_Translate', $tr);
    }

    function loadTranslations(Zend_Translate $tr, $locale)
    {
        list($lang, ) = explode('_', $locale);
        $tr->addTranslation(array(
            'content' => APPLICATION_PATH . '/default/language/user/' . $lang . '.php',
            'locale' => $locale,
        ));

        if (file_exists(APPLICATION_PATH . '/default/language/user/site/' . $lang . '.php'))
            $tr->addTranslation(array(
                'content' => APPLICATION_PATH . '/default/language/user/site/' . $lang . '.php',
                'locale' => $locale,
            ));

        if (preg_match('/\badmin\b/', @$_SERVER['REQUEST_URI']))
        {
            $tr->addTranslation(array(
                'content' => APPLICATION_PATH . '/default/language/admin/en.php',
                'locale' => $locale,
            ));
            if (file_exists(APPLICATION_PATH . '/default/language/admin/' . $lang . '.php'))
                $tr->addTranslation(array(
                    'content' => APPLICATION_PATH . '/default/language/admin/' . $lang . '.php',
                    'locale' => $locale,
                ));
            if (file_exists(APPLICATION_PATH . '/default/language/admin/site/' . $lang . '.php'))
                $tr->addTranslation(array(
                    'content' => APPLICATION_PATH . '/default/language/admin/site/' . $lang . '.php',
                    'locale' => $locale,
                ));
        }

        //overwrite existing translation from file
        //with custom translation from DB
        foreach (array_unique(array($locale, $lang)) as $l)
            if ($data = $this->di->translationTable->getTranslationData($l))
            {
                $tr->addTranslation(
                    array(
                        'locale' => $locale,
                        'content' => $data,
                ));
                break;
            }
    }

    function addRoutes(Am_Mvc_Router $router)
    {
        $router->addRoute('user-logout', new Am_Mvc_Router_Route(
                'logout/*',
                array(
                    'module' => 'default',
                    'controller' => 'login',
                    'action' => 'logout',
                )
        ));
        $router->addRoute('inside-pages', new Am_Mvc_Router_Route(
                ':module/:controller/p/:page_id/:action/*',
                array(
                    'page_id' => 'index',
                    'action' => 'index'
                )
        ));

        $router->addRoute('admin-setup', new Am_Mvc_Router_Route(
                'admin-setup/:page',
                array(
                    'module' => 'default',
                    'controller' => 'admin-setup',
                    'action' => 'display',
                )
        ));
        $router->addRoute('payment', new Am_Mvc_Router_Route(
                'payment/:plugin_id/:action',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'payment',
                )
        ));
        /**
         *  Add separate route for clickbank plugin.
         *  Clickbank doesn't allow to use word "clickbank" in URL.
         */

        $router->addRoute('c-b', new Am_Mvc_Router_Route(
                'payment/c-b/:action',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'payment',
                    'plugin_id'=>'clickbank'
                )
        ));

        $router->addRoute('protect', new Am_Mvc_Router_Route(
                'protect/:plugin_id/:action',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'protect',
                )
        ));
        $router->addRoute('misc', new Am_Mvc_Router_Route(
                'misc/:plugin_id/:action',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'misc',
                )
        ));
        $router->addRoute('storage', new Am_Mvc_Router_Route(
                'storage/:plugin_id/:action',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'storage',
                )
        ));
        $router->addRoute('payment-link', new Am_Mvc_Router_Route(
                'pay/:secure_id',
                array(
                    'module' => 'default',
                    'controller' => 'pay',
                    'action' => 'index'
                )
        ));

        $router->addRoute('page', new Am_Mvc_Router_Route(
                'page/:path',
                array(
                    'module' => 'default',
                    'controller' => 'content',
                    'action' => 'p'
                )
        ));
        $router->addRoute('profile', new Am_Mvc_Router_Route(
                'profile/:c',
                array(
                    'module' => 'default',
                    'controller' => 'profile',
                    'action' => 'index',
                    'c' => ''
                )
        ));

        $router->addRoute('profile-email-confirm', new Am_Mvc_Router_Route(
                'profile/confirm-email',
                array(
                    'module' => 'default',
                    'controller' => 'profile',
                    'action' => 'confirm-email',
                )
        ));

        $router->addRoute('signup', new Am_Mvc_Router_Route(
                'signup/:c',
                array(
                    'module' => 'default',
                    'controller' => 'signup',
                    'action' => 'index',
                    'c' => ''
                )
        ));

        $router->addRoute('signup-compat', new Am_Mvc_Router_Route(
                'signup/index/c/:c',
                array(
                    'module' => 'default',
                    'controller' => 'signup',
                    'action' => 'index'
                )
        ));

        $router->addRoute('signup-index-compat', new Am_Mvc_Router_Route(
                'signup/index',
                array(
                    'module' => 'default',
                    'controller' => 'signup',
                    'action' => 'index'
                )
        ));

        $router->addRoute('upload-public-get', new Am_Mvc_Router_Route(
                'upload/get/:path',
                array(
                    'module' => 'default',
                    'controller' => 'upload',
                    'action' => 'get'
                )
        ));
        $router->addRoute('cron-compat', new Am_Mvc_Router_Route(
                'cron.php',
                array(
                    'module' => 'default',
                    'controller' => 'cron',
                    'action' => 'index',
                )
        ));

        $router->addRoute('content-c', new Am_Mvc_Router_Route_Regex(
                'content/([^/]*)\.(\d+)$',
                array(
                    'module' => 'default',
                    'controller' => 'content',
                    'action' => 'c'
                ),
                array(
                    1 => 'title',
                    2 => 'id'
                ),
                'content/%s.%d'
        ));


        if ($this->di->config->get('am3_urls', false)) {
            $this->initAm3Routes($router);
        }
    }

    function initAm3Routes(Am_Mvc_Router $router)
    {

        $router->addRoute('v3_urls', new Am_Mvc_Router_Route_Regex(
                '(signup|member|login|logout|profile|thanks).php',
                array('module' => 'default',
                    'action' => 'index'
                ),
                array('controller' => 1)
        ));

        $router->addRoute('v3_logout', new Am_Mvc_Router_Route(
                'logout.php',
                array(
                    'module' => 'default',
                    'controller' => 'login',
                    'action' => 'logout',
                )
        ));

        $router->addRoute('v3_ipn_scripts', new Am_Mvc_Router_Route_Regex(
                'plugins/payment/([0-9a-z]+)_?r?/(ipn)r?.php',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'payment',
                ),
                array(
                    'plugin_id' => 1,
                    'action' => 2
            )));

        $router->addRoute('v3_ipn_paypal_pro', new Am_Mvc_Router_Route_Regex(
                'plugins/payment/paypal_pro/(ipn).php',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'payment',
                    'plugin_id' => 'paypal-pro',
                ),
                array(
                    'action'    =>  1
                )
            ));

        $router->addRoute('v3_thanks_scripts', new Am_Mvc_Router_Route_Regex(
                'plugins/payment/([0-9a-z]+)_?r?/(thanks)?.php',
                array(
                    'module' => 'default',
                    'controller' => 'direct',
                    'action' => 'index',
                    'type' => 'payment',
                ),
                array(
                    'plugin_id' => 1,
                    'action' => 'thanks'
            )));

        $router->addRoute('v3_affgo', new Am_Mvc_Router_Route(
                'go.php',
                array(
                    'module' => 'aff',
                    'controller' => 'go',
                    'action' => 'am3go',
                )
        ));
        $router->addRoute('v3_afflinks', new Am_Mvc_Router_Route(
                'aff.php',
                array(
                    'module' => 'aff',
                    'controller' => 'aff',
                )
        ));
    }

    /**
     * Fuzzy match 2 domain names (with/without www.)
     */
    protected function _compareHostDomains($d1, $d2)
    {
        return strcasecmp(
            preg_replace('/^www\./i', '', $d1),
            preg_replace('/^www\./i', '', $d2));
    }

    public function guessBaseUrl()
    {
        $scheme = (empty($_SERVER['HTTPS']) || ($_SERVER['HTTPS'] == 'off')) ? 'http' : 'https';
        $host = @$_SERVER['HTTP_HOST'];
        // try to find exact match for domain name
        foreach (array(ROOT_URL, ROOT_SURL) as $u) {
            $p = parse_url($u);
            if (($scheme == @$p['scheme'] && $host == @$p['host'])) { // be careful to change here!! // full match required, else it is BETTER to configure RewriteBase in .htaccess
                return @$p['path'];
            }
        }
        // now try fuzzy match domain name
        foreach (array(ROOT_URL, ROOT_SURL) as $u) {
            $p = parse_url($u);
            if (($scheme == @$p['scheme'] && !$this->_compareHostDomains($host, $p['host']))) { // be careful to change here!! // full match required, else it is BETTER to configure RewriteBase in .htaccess
                return @$p['path'];
            }
        }
    }

    public function initFront()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Am_Mvc_Controller_Action_Helper');
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('di', $this->di);
        $front->setParam('noViewRenderer', true);
        $front->throwExceptions(true);
        $front->addModuleDirectory(APPLICATION_PATH);
        $front->setRequest(new Am_Mvc_Request);
        $front->setResponse(new Am_Mvc_Response);
        $front->getRequest()->setBaseUrl();
        $front->setRouter(new Am_Mvc_Router);
        // if baseUrl has not been automatically detected,
        // try to get it from configured root URLs
        // it may not help in case of domain name mismatch
        // then RewriteBase is only the option!
        if ((null == $front->getRequest()->getBaseUrl())) {
            if ($u = $this->guessBaseUrl())
                $front->getRequest()->setBaseUrl($u);
        }

        if (!$front->getPlugin('Am_Mvc_Controller_Plugin'))
            $front->registerPlugin(new Am_Mvc_Controller_Plugin($this->di), 90);
        if (!defined('REL_ROOT_URL')) {
            $relRootUrl = $front->getRequest()->getBaseUrl();
            // filter it for additional safety
            $relRootUrl = preg_replace('|[^a-zA-Z0-9.\\/_+-~]|', '', $relRootUrl);
            define('REL_ROOT_URL', $relRootUrl);
        }
        $this->addRoutes(Am_Di::getInstance()->router);
    }

    function initModules()
    {
        /// add modules inc dir
        $pathes = array();
        foreach ($this->di->modules->getEnabled() as $module) {
            $dir = APPLICATION_PATH . '/' . $module . '/library/';
            if (file_exists($dir))
                $pathes[] = $dir;
        }
        if ($pathes)
            set_include_path(get_include_path() .
                PATH_SEPARATOR .
                implode(PATH_SEPARATOR, $pathes));
        $this->di->modules->loadEnabled()->getAllEnabled();
    }

    public function run()
    {
        if ($this->di->config->get('force_ssl') && !((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))) {
            $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: $redirect_url");
            return;
        }
        Zend_Controller_Front::getInstance()->dispatch();
    }

    public function initHooks()
    {
        class_exists('Am_Hook', true);

        /// load plugins
        $this->di->plugins_protect
            ->loadEnabled()->getAllEnabled();
        $this->di->plugins_payment
            ->addEnabled('free');
        $this->di->plugins_misc
            ->loadEnabled()->getAllEnabled();
        $this->di->plugins_storage
            ->addEnabled('upload')->addEnabled('disk');

        $this->di->plugins_tax->getAllEnabled();


        $this->di->hook
            ->add(Am_Event::HOURLY, array($this->di->app, 'onHourly'))
            ->add(Am_Event::DAILY, array($this->di->app, 'onDaily'))
            ->add(Am_Event::INVOICE_AFTER_INSERT, array($this->di->emailTemplateTable, 'onInvoiceAfterInsert'))
            ->add(Am_Event::INVOICE_STARTED, array('EmailTemplateTable', 'onInvoiceStarted'))
            ->add(Am_Event::PAYMENT_WITH_ACCESS_AFTER_INSERT, array('EmailTemplateTable', 'onPaymentWithAccessAfterInsert'))
            ->add(Am_Event::DAILY, array($this->di->savedReportTable, 'sendSavedReports'))
            ->add(Am_Event::WEEKLY, array($this->di->savedReportTable, 'sendSavedReports'))
            ->add(Am_Event::MONTHLY, array($this->di->savedReportTable, 'sendSavedReports'))
            ->add(Am_Event::YEARLY, array($this->di->savedReportTable, 'sendSavedReports'));

        if (!$this->di->config->get('use_cron') && Am_Cron::needRun()) // we have no remote cron setup
            Am_Cron::setupHook();
    }

    static function __autoload($className)
    {
        switch ($className)
        {
            case 'Am_Controller':
                trigger_error("Usage of deprecated class Am_Controller detected! Use Am_Mvc_Controller instead", E_USER_NOTICE);
                return eval(";class Am_Controller extends Am_Mvc_Controller {} ; ");
            case 'Am_Controller_Grid':
                trigger_error("Usage of deprecated class Am_Controller_Grid detected! Use Am_Mvc_Controller_Grid instead", E_USER_NOTICE);
                return eval(";abstract class Am_Controller_Grid extends Am_Mvc_Controller_Grid {} ; ");
            case 'Am_Request':
                trigger_error("Usage of deprecated class Am_Controller detected! Use Am_Mvc_Request instead", E_USER_NOTICE);
                return eval(";class Am_Request extends Am_Mvc_Request {} ; ");
        }

        if ($className == 'Composer\\Autoload\\ClassLoader')
            return false;
        if (strpos($className, 'PHPUnit_') === 0)
            return false;
        if (strpos($className, 'PHP_') === 0)
            return false;

        $regexes = array(
            'Am_Mail_Template' => '$0',
            'Am_Mail_TemplateTypes' => '$0',
            'Am_Form_Bricked' => '$0',
            '(Am_Mail)(.+)' => '$1',
            '(Am_DbSync)(.+)' => '$1',
            '(Am_Exception)(.+)' => '$1',
            '(Am_Event)(.+)' => '$1',
            //           '(Am_Form_Brick)(.+)' => '$1',
            '(Am_Crypt)(.+)' => '$1',
            'Am_Table' => 'Am_Record',
        );
        $count = 0;
        foreach ($regexes as $regex => $replace) {
            $className = preg_replace('/^' . $regex . '$/', $replace, $className, 1, $count);
            if ($count)
                break;
        }
        $className = preg_replace('/[^a-zA-Z0-9_]+/', '', $className);
        $className = str_replace('_', DIRECTORY_SEPARATOR, $className);
        if (preg_match('/^pear/i', $className))
            return; // do not autoload pear classes
        if (preg_match('/^([a-zA-Z][A-Za-z0-9]+)Table$/', $className, $regs))
            $className = $regs[1];
        //memUsage('before-'.$className );
        include_once $className . '.php';
        //tmUsage('after including ' . $className);
        //memUsage('after-'.$className );
    }

    public function onDaily(Am_Event $event)
    {
        $this->di->userTable->checkAllSubscriptions();
        $this->di->emailTemplateTable->sendCronExpires();
        $this->di->emailTemplateTable->sendCronAutoresponders();
        $this->di->emailTemplateTable->sendCronPayments();
        $this->di->emailTemplateTable->sendCronPendingNotifications();
        $this->di->store->cronDeleteExpired();
        $this->di->storeRebuild->cronDeleteExpired();
        Am_Auth_BruteforceProtector::cleanUp();
        if ($this->di->config->get('clear_access_log') && $this->di->config->get('clear_access_log_days') > 0) {
            $dat = sqlDate($this->di->time - $this->di->config->get('clear_access_log_days') * 3600 * 24);
            $this->di->accessLogTable->clearOld($dat);
        }
        if ($this->di->config->get('clear_inc_payments') && $this->di->config->get('clear_inc_payments_days') > 0) {
            $dat = sqlDate($this->di->time - $this->di->config->get('clear_inc_payments_days') * 3600 * 24);
            $this->di->invoiceTable->clearPending($dat);
        }
        if ($this->di->config->get('clear_inc_users') && $this->di->config->get('clear_inc_users_days') > 0) {
            $dat = sqlDate($this->di->time - $this->di->config->get('clear_inc_users_days') * 3600 * 24);
            $this->di->userTable->clearPending($dat);
        }

        $this->di->uploadTable->cleanUp();
        Am_Mail_Queue::getInstance()->cleanUp();
    }

    public function onHourly(Am_Event $event)
    {
        $this->di->emailTemplateTable->sendCronHourlyPendingNotifications();
        if ($this->di->config->get('email_queue_enabled'))
            Am_Mail_Queue::getInstance()->sendFromQueue();
    }

    public function setSessionCookieDomain()
    {
        if (ini_get('session.cookie_domain') != '')
            return; // already configured
 $domain = @$_SERVER['HTTP_HOST'];
        $domain = strtolower(trim(preg_replace('/(\:\d+)$/', '', $domain)));

        if (!$domain)
            return;
        if ($domain == 'localhost')
            return;

        if (preg_match('/\.(dev|local)$/', $domain)) {
            @ini_set('session.cookie_domain', ".$domain");
            return;
        }

        /*
         *  If domain is valid IP address do not change session.cookie_domain;
         */
        if (filter_var($domain, FILTER_VALIDATE_IP))
            return $domain;

        try {
            $min = Am_License::getMinDomain($domain);
        } catch (Exception $e) {
            return;
        }
        @ini_set('session.cookie_domain', ".$min");
    }

    public function initSession()
    {
        @ini_set('session.use_trans_sid', false);
        @ini_set('session.cookie_httponly', true);

        // lifetime must be bigger than admin and user auth timeout
        $lifetime = (int) ini_get('session.gc_maxlifetime');
        if ($lifetime < ($max = max($this->di->config->get('login_session_lifetime', 120) * 60, 7200))) {
            @ini_set('session.gc_maxlifetime', $max);
        }

        $this->setSessionCookieDomain();
        if ('db' == $this->getSessionStorageType())
            Zend_Session::setSaveHandler(new Am_Session_SaveHandler($this->di->db));

        if (defined('AM_SESSION_NAME') && AM_SESSION_NAME) {
            Zend_Session::setOptions(array('name' => AM_SESSION_NAME));
        }

        try {
            Zend_Session::start();
        } catch (Zend_Session_Exception $e) {
            // fix for Error #1009 - Internal error when disable shopping cart module
            if (strpos($e->getMessage(), "Failed opening 'Am/ShoppingCart.php'") !== false) {
                Zend_Session::destroy();
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
            // process other session issues
            if (strpos($e->getMessage(), 'This session is not valid according to') === 0) {
                $_SESSION = array();
                Zend_Session::regenerateId();
                Zend_Session::writeClose();
            }
            if (defined('AM_TEST') && AM_TEST) {
                // just ignore error
            } else
                throw $e;
        }

        // Workaround to fix bug: https://bugs.php.net/bug.php?id=68063
        // Sometimes php starts session with empty session_id()
        if(!defined('AM_TEST') && !Zend_Session::getId())
        {
            Zend_Session::destroy();
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
        //disabled as it brokes flash uploads !
        //Zend_Session::registerValidator(new Zend_Session_Validator_HttpUserAgent);
        $this->di->session = new Zend_Session_Namespace('amember');
    }

    public function getSessionStorageType()
    {
        if (ini_get('suhosin.session.encrypt'))
            return 'php';
        else
            return $this->di->config->get('session_storage', 'db');
    }

    function initConstants()
    {
        @ini_set('magic_quotes_runtime', false);
        @ini_set('magic_quotes_sybase', false);

        mb_internal_encoding("UTF-8");
        ini_set('iconv.internal_encoding', 'UTF-8');
        if (!defined('ROOT_URL'))
            define('ROOT_URL', $this->di->config->get('root_url'));
        if (!defined('ROOT_SURL'))
            define('ROOT_SURL', $this->di->config->get('root_surl'));
        if (!defined('AM_WIN'))
            define('AM_WIN', (bool) preg_match('/Win/i', PHP_OS)); // true if on windows
        if (!defined('ROOT_DIR'))
            define('ROOT_DIR', realpath(dirname(dirname(dirname(__FILE__)))));
        if (!defined('DATA_DIR'))
            define('DATA_DIR', ROOT_DIR . '/data');
        if (!defined('AM_VERSION'))
            define('AM_VERSION', '5.1.3');
        if (!defined('AM_BETA'))
            define('AM_BETA', '0' == 1);
    }

    function __exception404(Zend_Controller_Response_Abstract $response)
    {
        try {
            $p = $this->di->pageTable->load($this->di->config->get('404_page'));
            $body = $p->render($this->di->view, $this->di->auth->getUserId() ? $this->di->auth->getUser() : null);
        } catch (Exception $e) {
            $body = 'HTTP/1.1 404 Not Found';
        }

        $response
            ->setHttpResponseCode(404)
            ->setBody($body)
            ->setRawHeader('HTTP/1.1 404 Not Found')
            ->sendResponse();
    }

    function __exception($e)
    {
        if ($e instanceof Zend_Controller_Dispatcher_Exception
            && (preg_match('/^Invalid controller specified/', $e->getMessage()))) {
            return $this->__exception404(Zend_Controller_Front::getInstance()->getResponse());
        }
        if ($e->getCode() == 404) {
            return $this->__exception404(Zend_Controller_Front::getInstance()->getResponse());
        }

        try {
            static $in_fatal_error; //!
            $in_fatal_error++;
            if ($in_fatal_error > 2) {
                echo(nl2br("<b>\n\n" . __METHOD__ . " called twice\n\n</b>"));
                exit();
            }
            if (!$this->initFinished) {
                $isApiError = false;
            } else {
                $request = Zend_Controller_Front::getInstance()->getRequest();
                $isApiError = (preg_match('#^/api/#', $request->getPathInfo()) && !preg_match('#^/api/admin($|/)#', $request->getPathInfo()));
            }
            if (!$isApiError && ((defined('AM_DEBUG') && AM_DEBUG) || (APPLICATION_ENV == 'testing'))) {
                $display_error = "<pre>" . ($e) . ':' . $e->getMessage() . "</pre>";
            } else {
                if ($e instanceof Am_Exception) {
                    $display_error = $e->getPublicError();
                    $display_title = $e->getPublicTitle();
                } elseif ($e instanceof Zend_Controller_Dispatcher_Exception) {
                    $display_error = ___("Error 404 - Not Found");
                    header("HTTP/1.0 404 Not Found");
                } else
                    $display_error = ___('An internal error happened in the script, please contact webmaster for details');
            }
            /// special handling for API errors

            if ($isApiError) {
                $format = $request->getParam('_format', 'json');
                if (!empty($display_title))
                    $display_error = $display_title . ':' . $display_error;
                $display_error = trim($display_error, " \t\n\r");
                if ($format == 'xml') {
                    $xml = new SimpleXMLElement('<error />');
                    $xml->ok = 'false';
                    $xml->message = $display_error;
                    echo (string) $xml;
                } else {
                    echo json_encode(array('ok' => false, 'error' => true, 'message' => $display_error));
                }
                exit();
            }
            if (!$this->initFinished)
                amDie($display_error);

            // fixes http://bt.amember.com/issues/597
            if (($router = $this->di->router) instanceof Zend_Controller_Router_Rewrite)
                $router->addDefaultRoutes();
            //
            $t = new Am_View;
            $t->assign('is_html', true); // must be already escaped here!
            if (isset($display_title))
                $t->assign('title', $display_title);
            $t->assign('error', $display_error);
            $t->assign('admin_email', $this->di->config->get('admin_email'));
            if (defined('AM_DEBUG') && AM_DEBUG) {
                $t->assign('trace', $e->getTraceAsString());
            }

            $t->display("error.phtml");
            // log error
            if (!method_exists($e, 'getLogError') || $e->getLogError())
                $this->di->errorLogTable->logException($e);
        } catch (Exception $e) {
            if ((defined('AM_DEBUG') && AM_DEBUG) || (APPLICATION_ENV == 'testing')) {
                $display_error = "<pre>" . ($e) . ':' . $e->getMessage() . "</pre>" .
                    " thrown within the exception handler. Message: " . $e->getMessage() . " on line " . $e->getLine();
            } else {
                if ($e instanceof Am_Exception) {
                    $display_error = $e->getPublicError();
                }  else {
                    $display_error = ___('An internal error happened in the script, please contact webmaster for details');
                }
            }
            amDie($display_error);
        }
        exit();
    }

    function __error($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno))
            return;
        $ef = (@APPLICATION_ENV != 'debug') ?
            basename($errfile) : $errfile;
        switch ($errno) {
            case E_RECOVERABLE_ERROR:
                $msg = "<b>RECOVERABLE ERROR:</b> $errstr\nin line $errline of file $errfile";
                if (APPLICATION_ENV == 'debug')
                    echo $msg;
                $this->di->errorLogTable->log($msg);
                return true;
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $this->di->errorLogTable->log("<b>ERROR:</b> $errstr\nin line $errline of file $errfile");
                ob_clean();
                amDie("ERROR [$errno] $errstr\nin line $errline of file $ef");
                exit(1);
            case E_USER_WARNING:
            case E_WARNING:
                if (!defined('AM_DEBUG') || !AM_DEBUG)
                    return;
                if (preg_match('#^Declaration of (Am_Protect_|Am_Paysystem_|Am_Protect|Am_Plugin_|Bootstrap_).+ should be compatible#', $errstr))
                    return;
                if (!defined('SILENT_AMEMBER_ERROR_HANDLER')
                    && !(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
                    print("<b>WARNING:</b> $errstr\nin line $errline of file $ef<br />");
                $this->di->errorLogTable->log("<b>WARNING:</b> $errstr\nin line $errline of file $errfile");
                break;

            case E_STRICT:
            case E_USER_NOTICE:
            case E_NOTICE:
                if (!defined('AM_DEBUG') || !AM_DEBUG)
                    return;
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                    return;
                if (preg_match('#^Declaration of (Am_Protect_|Am_Paysystem_|Am_Protect|Am_Plugin_|Bootstrap_).+ should be compatible#', $errstr))
                    return;
                print_rr("<b>NOTICE:</b> $errstr\nin line $errline of file $ef<br />");
                break;
        }
    }

    function getDefaultLocale($addRegion = false)
    {
        @list($found, ) = array_keys(Zend_Locale::getDefault());
        if (!$found)
            return 'en_US';
        if (!$addRegion)
            return $found;
        return (strlen($found) <= 4) ? ___('_default_locale') : $found;
    }

    function dbSync($reportNoChanges = true, $modules = null)
    {
        $nl = empty($_SERVER['REMOTE_ADDR']) ? "\n" : "<br />\n";
        $db = new Am_DbSync();
        $db->parseTables($this->di->db);
        $xml = new Am_DbSync();

        $xml->parseXml(file_get_contents(APPLICATION_PATH . '/default/db.xml'));
        if ($modules === null)
            $modules = $this->di->modules->getEnabled();
        foreach ($modules as $module) {
            if (file_exists($file = APPLICATION_PATH . '/' . $module . "/db.xml")) {
                print "Parsing XML file: [application/$module/db.xml]$nl";
                $xml->parseXml(file_get_contents($file));
            }
        }

        $this->di->hook->call(Am_Event::DB_SYNC, array(
            'dbsync' => $xml,
        ));

        $diff = $xml->diff($db);
        if ($sql = $diff->getSql($this->di->db->getPrefix())) {
            print "Doing the following database structure changes:$nl";
            print $diff->render();
            print "$nl";
            ob_end_flush();
            $diff->apply($this->di->db);
            print "DONE$nl";
            ob_end_flush();
        } elseif ($reportNoChanges) {
            print "No database structure changes required$nl";
        }

        $this->etSync($modules);
        $this->di->store->set('db_version', AM_VERSION);
    }

    function etSync($modules = null)
    {
        $e = new Am_Event(Am_Event::ET_SYNC);
        $etXml = array();
        if ($modules === null)
            $modules = $this->di->modules->getEnabled();
        foreach ($modules as $module) {
            if (file_exists($file = APPLICATION_PATH . '/' . $module . "/email-templates.xml")) {
                $etFiles["application/$module/email-templates.xml"] = file_get_contents($file);
            }
        }
        $etFiles['/default/email-templates.xml'] = file_get_contents(APPLICATION_PATH . '/default/email-templates.xml');
        $e->setReturn($etFiles);
        $this->di->hook->call($e);
        $etFiles = $e->getReturn();

        $nl = empty($_SERVER['REMOTE_ADDR']) ? "\n" : "<br />\n";
        $t = $this->di->emailTemplateTable;

        foreach ($etFiles as $file => $xml) {
            print "Parsing XML: [$file]$nl";
            $t->importXml($xml);
        }
    }

    function readConfig($fn)
    {
        $this->config = require_once $config;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $movedFuncs = array(
            'getSiteKey' => array('security', 'siteKey'),
            'getSiteHash' => array('security', 'siteHash'),
            'hash' => array('security', 'hash'),
            'obfuscate' => array('security', 'obfuscate'),
            'reveal' => array('security', 'reveal'),
            'generateRandomString' => array('security', 'randomString'),
        );
        if (!empty($movedFuncs[$name]))
        {
            list($diObj, $func) = $movedFuncs[$name];
            return call_user_func_array(array($this->di->{$diObj}, $func), $arguments);
        }
        throw new \Exception("Not-existing method called Am_App::$name. Died");
    }
}

function array_remove_value(& $array, $value)
{
    foreach ($array as $k => $v)
        if ($v === $value)
            unset($array[$k]);
}


/**
 * class to run long operations in portions with respect to time and memory limits
 * callback function must set $context variable - it will be passed back on next
 * call, even after page reload.
 * when operation is finished, callback function must return boolean <b>true</b>
 * to indicate completion
 * @package Am_Utils
 */
class Am_BatchProcessor
{

    protected $callback;
    protected $tm_started, $tm_finished;
    protected $max_tm;
    protected $max_mem;
    /**
     * If process was explictly stopped from a function
     * @var bool
     */
    protected $stopped = false;

    /**
     *
     * @param type $callback Callback function - must return true when processing finished
     * @param type $max_tm max execution time in seconds
     * @param type $max_mem memory limit in megabytes
     */
    public function __construct($callback, $max_tm = 20, $max_mem = 256)
    {
        if (!is_callable($callback))
            throw new Am_Exception_InternalError("Not callable callback passed");
        $this->callback = $callback;
        // get max time
        $this->max_tm = ini_get('max_execution_time');
        if ($this->max_tm <= 0 || $this->max_tm > 20)
            $this->max_tm = 20;
        $this->max_tm = min($this->max_tm, $max_tm);
        // get max memory
        $max_memory = strtoupper(ini_get('memory_limit'));
        if ($max_memory == -1)
            $max_memory = 64 * 1024 * 1024;
        elseif ($max_memory != '') {
            $multi = array('K' => 1024, 'M' => 1024 * 1024, 'G' => 1024 * 1024 * 1024);
            if (preg_match('/^(\d+)\s*(K|M|G)/', $max_memory, $regs))
                $max_memory = $regs[1] * $multi[$regs[2]];
            else
                $max_memory = intval($max_memory);
        }
        $this->max_mem = min($max_mem * 1024 * 1024, $max_memory * 0.9);
    }

    /**
     * @return true if process finished, false if process was breaked due to limits
     */
    function run(& $context)
    {
        $this->tm_started = time();
        $breaked = false;
        $params = array(
            & $context,
            $this
        );
        while (!call_user_func_array($this->callback, $params)) {
            if ($this->isStopped() || !$this->checkLimits()) {
                $breaked = true;
                break;
            }
        }
        $this->tm_finished = time();
        return!$breaked;
    }

    function stop()
    {
        $this->stopped = true;
    }

    function isStopped()
    {
        return (bool) $this->stopped;
    }

    /**
     * @return bool false if limits are over
     */
    function checkLimits()
    {
        $tm_used = time() - $this->tm_started;
        if ($tm_used >= $this->max_tm)
            return false;
        if (memory_get_usage() > $this->max_mem)
            return false;
        return true;
    }

    function getRunningTime()
    {
        $finish = $this->tm_finished ? $this->tm_finished : time();
        return $finish - $this->tm_started;
    }

}

// html utils
class Am_Html
{
    static function escape($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8', false);
    }

    static function attrs(array $attrs)
    {
        $s = "";
        foreach ($attrs as $k => $v)
        {
            if ($s) $s .= ' ';
            if ($v === null)
                $s .= self::escape($k);
            else
                $s .= self::escape($k) . '="' . self::escape($v) . '"';
        }
        return $s;
    }

    /**
     * Render html for <option>..</option> tags of <select>
     * @param array of options key => value
     * @param mixed selected option key
     */
    static function renderOptions(array $options, $selected = '')
    {
        $out = "";
        foreach ($options as $k => $v) {
            if (is_array($v) && !isset($v['label'])) {
                //// render optgroup instead
                $out .=
                    "<optgroup label='" . Am_Html::escape($k) . "'>"
                    . self::renderOptions($v, $selected)
                    . "</optgroup>\n";
                continue;
            }
            if (is_array($selected)) {
                $sel = in_array($k, $selected) ? ' selected="selected"' : '';
            } else {
                $sel = (string) $k == (string) $selected ? ' selected="selected"' : null;
            }
            if (is_array($v)) {
                $label = $v['label'];
                unset($v['label']);
                $attrs = $v;
            } else {
                $label = $v;
                $attrs = array();
            }
            $out .= sprintf('<option value="%s"%s %s>%s</option>' . "\n",
                    self::escape($k),
                    $sel,
                    self::attrs($attrs),
                    self::escape($label));
        }
        return $out;
    }

    /**
     * Convert array of variables to string of input:hidden values
     * @param array variables
     * @return string <input type="hidden" name=".." value="..."/><input .....
     */
    static function renderArrayAsInputHiddens($vars, $parentK=null)
    {
        $ret = "";
        foreach ($vars as $k => $v)
            if (is_array($v))
                $ret .= self::renderArrayAsInputHiddens($v, $parentK ? $parentK . '[' . $k . ']' : $k);
            else
                $ret .= sprintf('<input type="hidden" name="%s" value="%s" />' . "\n",
                        Am_Html::escape($parentK ? ($parentK . "[" . $k . "]") : $k), Am_Html::escape($v));
        return $ret;
    }

    /**
     * Convert array of variables to array of input:hidden values
     * @param array variables
     * @return array key => value for including into form
     */
    static function getArrayOfInputHiddens($vars, $parentK=null)
    {
        $ret = array();
        foreach ($vars as $k => $v)
            if (is_array($v))
                $ret = array_merge(
                        $ret,
                        self::getArrayOfInputHiddens(
                            $v,
                            $parentK ? $parentK . '[' . $k . ']' : $k
                        )
                );
            else
                $ret[$parentK ? ($parentK . "[" . $k . "]") : $k] = $v;
        return $ret;
    }
}

class Am_Cookie
{
    /** @var bool ignore @see runPage calls */
    static protected $_unitTestEnabled = false;
    /** @var array for testing only
     * @internal
     */
    static private $_cookies = array();

    /** @internal */
    static function _setUnitTestEnabled($flag=true)
    {
        self::$_unitTestEnabled = (bool) $flag;
    }

    static private function _getCookieDomain($d)
    {
        if ($d === null)
            return null;

        $d = strtolower(trim(preg_replace('/(\:\d+)$/', '', $d)));

        if($d == 'localhost')
            return null;

        if(preg_match('/\.(dev|local)$/', $d))
            return null;

        if(filter_var($d, FILTER_VALIDATE_IP))
            return null;

        try {
            $d = '.' . Am_License::getMinDomain($d);
        } catch (Exception $e) {}

        return $d;
    }

    static function delete($name)
    {
        self::set($name, null, time() - 24 * 3600);
    }

    /**
     * @todo check domain parsing and make delCookie global
     */
    static function set($name, $value, $expires=0, $path = '/', $domain=null, $secure=false, $strictDomainName=false, $httponly=false)
    {
        if (self::$_unitTestEnabled)
            self::$_cookies[$name] = $value;
        else
            setcookie($name, $value, $expires, $path, ($strictDomainName ? $domain : self::_getCookieDomain($domain)), $secure, $httponly);
    }

    static function _get($name)
    {
        return @self::$_cookies[$name];
    }

    static function _clear()
    {
        self::$_cookies = array();
    }
}

/**
 * Get icon offsets
 */
class Am_View_Sprite
{
    protected static $_sprite_offsets = array (
        'icon' => array(
            'add' => 266,
            'admins' => 532,
            'affiliates-banners' => 798,
            'affiliates-commission-rules' => 1064,
            'affiliates-commission' => 1330,
            'affiliates-payout' => 1596,
            'affiliates' => 1862,
            'api' => 2128,
            'awaiting-me' => 2394,
            'awaiting' => 2660,
            'backup' => 2926,
            'ban' => 3192,
            'build-demo' => 3458,
            'bundle-discount' => 3724,
            'cart' => 3990,
            'ccrebills' => 4256,
            'change-pass' => 4522,
            'clear' => 4788,
            'closed' => 5054,
            'configuration' => 5320,
            'content-directory' => 5586,
            'content-emails' => 5852,
            'content-files' => 6118,
            'content-folders' => 6384,
            'content-integrations' => 6650,
            'content-links' => 6916,
            'content-newsletter' => 7182,
            'content-pages' => 7448,
            'content-video' => 7714,
            'content' => 7980,
            'copy' => 8246,
            'countries' => 8512,
            'dashboard' => 8778,
            'date' => 9044,
            'delete' => 9310,
            'download' => 9576,
            'edit' => 9842,
            'export' => 10108,
            'fields' => 10374,
            'help' => 10640,
            'helpdesk-category' => 10906,
            'helpdesk-faq' => 11172,
            'helpdesk-ticket-my' => 11438,
            'helpdesk-ticket' => 11704,
            'helpdesk' => 11970,
            'info' => 12236,
            'key' => 12502,
            'login' => 12768,
            'logs' => 13034,
            'magnify' => 13300,
            'merge' => 13566,
            'new' => 13832,
            'newsletter-subscribe-all' => 14098,
            'newsletters' => 14364,
            'notification' => 14630,
            'oto' => 14896,
            'preview' => 15162,
            'products-categories' => 15428,
            'products-coupons' => 15694,
            'products-manage' => 15960,
            'products' => 16226,
            'rebuild' => 16492,
            'report-bugs' => 16758,
            'reports-payments' => 17024,
            'reports-reports' => 17290,
            'reports' => 17556,
            'resend' => 17822,
            'restore' => 18088,
            'retry' => 18354,
            'revert' => 18620,
            'run-report' => 18886,
            'saved-form' => 19152,
            'states' => 19418,
            'status_busy' => 19684,
            'trans-global' => 19950,
            'user-locked' => 20216,
            'user-not-approved' => 20482,
            'users-browse' => 20748,
            'users-email' => 21014,
            'users-import' => 21280,
            'users-insert' => 21546,
            'users' => 21812,
            'utilites' => 22078,
            'view' => 22344,
            'giftvouchers' => 22610,
            'self-service-products' => 22876,
            'self-service' => 23142,
            'content-softsalefile' => 23408,
            'softsale' => 23674,
            'softsales-license' => 23940,
            'softsales-scheme' => 24206,
            'content-afflevels' => 24472,
            'resource-category' => 24738,
            'setup' => 25004,
            'documentation' => 25270,
            'payment-link' => 25536,
            'product-restore' => 25802,
            'cancel-feedback' => 26068,
            'personal-content' => 26334,
            'reports-vat' => 26600,
            'email-template-layout' => 26866,
            'helpdesk-fields' => 27132,
            'two-factor-authy' => 27398,
            'two-factor-duosecurity' => 27664,
            'downloads' => 27930,
            'webhooks-configuration' => 28196,
            'webhooks-queue' => 28462,
            'webhooks' => 28728,
            'report-feature' => 28994,
            'support' => 29260,
        ),
        'flag' => array(
            'ad' => 26,
            'ae' => 52,
            'af' => 78,
            'ag' => 104,
            'ai' => 130,
            'al' => 156,
            'am' => 182,
            'an' => 208,
            'ao' => 234,
            'ar' => 260,
            'as' => 286,
            'at' => 312,
            'au' => 338,
            'aw' => 364,
            'ax' => 390,
            'az' => 416,
            'ba' => 442,
            'bb' => 468,
            'bd' => 494,
            'be' => 520,
            'bf' => 546,
            'bg' => 572,
            'bh' => 598,
            'bi' => 624,
            'bj' => 650,
            'bm' => 676,
            'bn' => 702,
            'bo' => 728,
            'br' => 754,
            'bs' => 780,
            'bt' => 806,
            'bv' => 832,
            'bw' => 858,
            'by' => 884,
            'bz' => 910,
            'ca' => 936,
            'catalonia' => 962,
            'cc' => 988,
            'cd' => 1014,
            'cf' => 1040,
            'cg' => 1066,
            'ch' => 1092,
            'ci' => 1118,
            'ck' => 1144,
            'cl' => 1170,
            'cm' => 1196,
            'cn' => 1222,
            'co' => 1248,
            'cr' => 1274,
            'cs' => 1300,
            'cu' => 1326,
            'cv' => 1352,
            'cx' => 1378,
            'cy' => 1404,
            'cz' => 1430,
            'de' => 1456,
            'dj' => 1482,
            'dk' => 1508,
            'dm' => 1534,
            'do' => 1560,
            'dz' => 1586,
            'ec' => 1612,
            'ee' => 1638,
            'eg' => 1664,
            'eh' => 1690,
            'en' => 1716,
            'england' => 1742,
            'er' => 1768,
            'es' => 1794,
            'et' => 1820,
            'europeanunion' => 1846,
            'fam' => 1872,
            'fi' => 1898,
            'fj' => 1924,
            'fk' => 1950,
            'fm' => 1976,
            'fo' => 2002,
            'fr' => 2028,
            'ga' => 2054,
            'gb' => 2080,
            'gd' => 2106,
            'ge' => 2132,
            'gf' => 2158,
            'gh' => 2184,
            'gi' => 2210,
            'gl' => 2236,
            'gm' => 2262,
            'gn' => 2288,
            'gp' => 2314,
            'gq' => 2340,
            'gr' => 2366,
            'gs' => 2392,
            'gt' => 2418,
            'gu' => 2444,
            'gw' => 2470,
            'gy' => 2496,
            'hk' => 2522,
            'hm' => 2548,
            'hn' => 2574,
            'hr' => 2600,
            'ht' => 2626,
            'hu' => 2652,
            'id' => 2678,
            'ie' => 2704,
            'il' => 2730,
            'in' => 2756,
            'io' => 2782,
            'iq' => 2808,
            'ir' => 2834,
            'is' => 2860,
            'it' => 2886,
            'jm' => 2912,
            'jo' => 2938,
            'jp' => 2964,
            'ke' => 2990,
            'kg' => 3016,
            'kh' => 3042,
            'ki' => 3068,
            'km' => 3094,
            'kn' => 3120,
            'kp' => 3146,
            'kr' => 3172,
            'kw' => 3198,
            'ky' => 3224,
            'kz' => 3250,
            'la' => 3276,
            'lb' => 3302,
            'lc' => 3328,
            'li' => 3354,
            'lk' => 3380,
            'lr' => 3406,
            'ls' => 3432,
            'lt' => 3458,
            'lu' => 3484,
            'lv' => 3510,
            'ly' => 3536,
            'ma' => 3562,
            'mc' => 3588,
            'md' => 3614,
            'me' => 3640,
            'mg' => 3666,
            'mh' => 3692,
            'mk' => 3718,
            'ml' => 3744,
            'mm' => 3770,
            'mn' => 3796,
            'mo' => 3822,
            'mp' => 3848,
            'mq' => 3874,
            'mr' => 3900,
            'ms' => 3926,
            'mt' => 3952,
            'mu' => 3978,
            'mv' => 4004,
            'mw' => 4030,
            'mx' => 4056,
            'my' => 4082,
            'mz' => 4108,
            'na' => 4134,
            'nc' => 4160,
            'ne' => 4186,
            'nf' => 4212,
            'ng' => 4238,
            'ni' => 4264,
            'nl' => 4290,
            'no' => 4316,
            'np' => 4342,
            'nr' => 4368,
            'nu' => 4394,
            'nz' => 4420,
            'om' => 4446,
            'pa' => 4472,
            'pe' => 4498,
            'pf' => 4524,
            'pg' => 4550,
            'ph' => 4576,
            'pk' => 4602,
            'pl' => 4628,
            'pm' => 4654,
            'pn' => 4680,
            'pr' => 4706,
            'ps' => 4732,
            'pt' => 4758,
            'pw' => 4784,
            'py' => 4810,
            'qa' => 4836,
            're' => 4862,
            'ro' => 4888,
            'rs' => 4914,
            'ru' => 4940,
            'rw' => 4966,
            'sa' => 4992,
            'sb' => 5018,
            'sc' => 5044,
            'scotland' => 5070,
            'sd' => 5096,
            'se' => 5122,
            'sg' => 5148,
            'sh' => 5174,
            'si' => 5200,
            'sj' => 5226,
            'sk' => 5252,
            'sl' => 5278,
            'sm' => 5304,
            'sn' => 5330,
            'so' => 5356,
            'sr' => 5382,
            'st' => 5408,
            'sv' => 5434,
            'sy' => 5460,
            'sz' => 5486,
            'tc' => 5512,
            'td' => 5538,
            'tf' => 5564,
            'tg' => 5590,
            'th' => 5616,
            'tj' => 5642,
            'tk' => 5668,
            'tl' => 5694,
            'tm' => 5720,
            'tn' => 5746,
            'to' => 5772,
            'tr' => 5798,
            'tt' => 5824,
            'tv' => 5850,
            'tw' => 5876,
            'tz' => 5902,
            'ua' => 5928,
            'ug' => 5954,
            'um' => 5980,
            'us' => 6006,
            'uy' => 6032,
            'uz' => 6058,
            'va' => 6084,
            'vc' => 6110,
            've' => 6136,
            'vg' => 6162,
            'vi' => 6188,
            'vn' => 6214,
            'vu' => 6240,
            'wales' => 6266,
            'wf' => 6292,
            'ws' => 6318,
            'ye' => 6344,
            'yt' => 6370,
            'za' => 6396,
            'zh' => 6422,
            'zm' => 6448,
            'zw' => 6474,
            'ja' => 6500,
        ),
    );

    function getOffset($id, $source = 'icon')
    {
        return isset(self::$_sprite_offsets[$source ][$id]) ? self::$_sprite_offsets[$source][$id] : false;
    }
}
