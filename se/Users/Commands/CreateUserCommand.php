<?php

namespace Platform\Users\Commands;

class CreateUserCommand
{
    /**
     * @var string
     */
    public $displayName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var integer
     */
    public $provider;

    /**
     * @var integer
     */
    public $admin;

    /**
     * @var boolean
     */
    public $isSocial;

    /**
     * @var boolean
     */
    public $isPasswordChangeRequired;

    /**
     * @var string
     */
    public $app;

    /**
     * @param array  $data
     */
    public function __construct($data, $sharedTechpack =  false)
    {
        $this->displayName = isset($data['displayName']) ? $data['displayName'] : $data['email'];
        $this->email       = $data['email'];
        $this->password    = isset($data['password']) ? $data['password'] : null;
        $this->provider    = isset($data['provider']) ? $data['provider'] : 1;
        $this->admin       = isset($data['admin']) ? $data['admin'] : false;
        $this->isSocial    = isset($data['isSocial']) ? $data['isSocial'] : false;
        $this->isPasswordChangeRequired = isset($data['isPasswordChangeRequired']) ? $data['isPasswordChangeRequired'] : false;
        $this->app         = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : NULL;
        $this->sharedTechpack = $sharedTechpack;
    }
}
