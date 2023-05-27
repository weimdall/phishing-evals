<?php

namespace WHS\Admin\Core\Notifications\Messages;
use WHS\Models\Template;
use WHS\Library\Settings\Settings;

class AdminMailMessage
{

    public $vars = [];
    public $admin;
    public $company_id;

    public $mailtype;
    public $mailencoding;
    public $smtpport;
    public $smtphost;
    public $smtpuser;
    public $smtppass;
    public $smtpssl;
    public $smtpauth;
    public $smtp_client_id;
    public $smtp_conn_token;
    public $skip = false;



    /**
     * The "from" information for the message.
     *
     * @var array
     */
    public $from = [];

    /**
     * The "reply to" information for the message.
     *
     * @var array
     */
    public $replyTo = [];

    /**
     * The "cc" information for the message.
     *
     * @var array
     */
    public $cc = [];

    /**
     * The "bcc" information for the message.
     *
     * @var array
     */
    public $bcc = [];

    /**
     * The attachments for the message.
     *
     * @var array
     */
    public $attachments = [];

    /**
     * The raw attachments for the message.
     *
     * @var array
     */
    public $rawAttachments = [];

    /**
     * Priority level of the message.
     *
     * @var int
     */
    public $priority;

    /**
     * Message content
     *
     * @var string
     */
    public $content;

    /**
     * Subject
     *
     * @var string
     */
    public $subject;


    /**
     * Set the from address for the mail message.
     *
     * @param  string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function from($address, $name = null)
    {
        $this->from = [$address, $name];
        return $this;
    }

    /**
     * Set the "reply to" address of the message.
     *
     * @param  array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function replyTo($address, $name = null)
    {
        if ($this->arrayOfAddresses($address)) {
            $this->replyTo += $this->parseAddresses($address);
        } else {
            $this->replyTo[] = [$address, $name];
        }
        return $this;
    }

    /**
     * Set the cc address for the mail message.
     *
     * @param  array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function cc($address, $name = null)
    {
        if ($this->arrayOfAddresses($address)) {
            $this->cc += $this->parseAddresses($address);
        } else {
            $this->cc[] = [$address, $name];
        }
        return $this;
    }

    /**
     * Set the bcc address for the mail message.
     *
     * @param  array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function bcc($address, $name = null)
    {
        if ($this->arrayOfAddresses($address)) {
            $this->bcc += $this->parseAddresses($address);
        } else {
            $this->bcc[] = [$address, $name];
        }
        return $this;
    }

    /**
     * Attach a file to the message.
     *
     * @param  string  $file
     * @param  array  $options
     * @return $this
     */
    public function attach($file, array $options = [])
    {
        $this->attachments[] = compact('file', 'options');
        return $this;
    }

    /**
     * Attach in-memory data as an attachment.
     *
     * @param  string  $data
     * @param  string  $name
     * @param  array  $options
     * @return $this
     */
    public function attachData($data, $name, array $options = [])
    {
        $this->rawAttachments[] = compact('data', 'name', 'options');
        return $this;
    }

    /**
     * Set the priority of this message.
     *
     * The value is an integer where 1 is the highest priority and 5 is the lowest.
     *
     * @param  int  $level
     * @return $this
     */
    public function priority($level)
    {
        $this->priority = $level;
        return $this;
    }

    /**
     * Set the content of this message.
     * @return $this
     */
    public function content($msg)
    {
        $this->content = $msg;
        return $this;
    }

    /**
     * Set the subject of this message.
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function template($template, $vars, $admin, $company_id, $skip = false)
    {

        $this->vars = $vars;
        $this->admin = $admin;
        $this->company_id = $company_id;
        $this->skip = $skip;


        $this->systememail     = app()->settings->g('systememail');
        $this->systemname      = app()->settings->g('systemname');
        $this->mailtype        = app()->settings->g('mailtype');
        $this->mailencoding    = app()->settings->g('mailencoding');
        $this->smtpport        = app()->settings->g('smtpport');
        $this->smtphost        = app()->settings->g('smtphost');
        $this->smtpuser        = app()->settings->g('smtpuser');
        $this->smtppass        = app()->settings->g('smtppass');
        $this->smtpssl         = app()->settings->g('smtpssl');
        $this->smtpauth        = app()->settings->g('smtpauth');
        $this->smtp_client_id  = app()->settings->g('smtp_client_id');
        $this->smtp_conn_token = app()->settings->g('smtp_conn_token');


        $locale = app()->getLocale();
        $orig_company_id = app()->settings->getActiveCompany();
        if ($company_id) {
            app()->settings->loadCompany($company_id);

            $this->systememail     = app()->settings->c('systememail');
            $this->systemname      = app()->settings->c('systemname');
            $this->mailtype        = app()->settings->c('mailtype');
            $this->mailencoding    = app()->settings->c('mailencoding');
            $this->smtpport        = app()->settings->c('smtpport');
            $this->smtphost        = app()->settings->c('smtphost');
            $this->smtpuser        = app()->settings->c('smtpuser');
            $this->smtppass        = app()->settings->c('smtppass');
            $this->smtpssl         = app()->settings->c('smtpssl');
            $this->smtpauth        = app()->settings->c('smtpauth');
            $this->smtp_client_id  = app()->settings->c('smtp_client_id');
            $this->smtp_conn_token = app()->settings->c('smtp_conn_token');
        }

        app()->setLocale($admin->locale);

        $tpl = Template::where('name', $template)->where('msgtype', 'admin')->where('company_id', $company_id)->first();
        $vars['admin'] = $admin;
        $this->subject = $this->bladeCompile($tpl->subject, $vars);
        $this->content = view('default::email.global')->with('args', $vars)->with('content', $this->bladeCompile($tpl->content, $vars))->render();

        app()->setLocale($locale);
        if ($orig_company_id) {
            app()->settings->loadCompany($orig_company_id);
        }
        return $this;
    }

    public function bladeCompile($value, array $args = array())
    {
        $generated = \Blade::compileString($value);

        ob_start() and extract($args, EXTR_SKIP);
        try
        {
            eval('?>'.$generated);
        } catch (\Exception $e)  {
            ob_get_clean(); throw $e;
        }

        $content = ob_get_clean();
        return $content;
    }


}