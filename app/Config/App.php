<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public string $baseURL = 'http://localhost:8080/';
    public array $allowedHostnames = [];
    public string $indexPage = '';
    public string $uriProtocol = 'REQUEST_URI';
    public string $defaultLocale = 'id';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['id', 'en'];
    public string $appTimezone = 'Asia/Makassar';
    public string $charset = 'UTF-8';
    public bool $forceGlobalSecureRequests = false;
    public bool $CSPEnabled = false;

    public function __construct()
    {
        parent::__construct();
        $this->baseURL = env('app.baseURL', env('app_baseURL', env('APP_BASEURL', 'http://localhost:8080/')));
    }
}
