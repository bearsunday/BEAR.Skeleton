<?php

namespace BEAR\Skeleton\Module;

use BEAR\Package\Module\Form\AuraForm\AuraFormModule;
use BEAR\Package\Module\Package\PackageModule;
use BEAR\Package\Module\Resource\SignalParamModule;
use BEAR\Package\Provide\ResourceView;
use BEAR\Package\Provide\ResourceView\HalModule;
use BEAR\Package\Provide as ProvideModule;
use BEAR\Package\Provide\TemplateEngine\Smarty\SmartyModule;
use BEAR\Sunday\Module as SundayModule;
use BEAR\Sunday\Module\Constant\NamedModule as Constant;
use Ray\Di\AbstractModule;
use BEAR\Skeleton\Module\Mode\DevModule;
use BEAR\Package\Provide\TemplateEngine\Twig\TwigModule;

use BEAR\Package\Module\Di\DiCompilerModule;
use BEAR\Package\Module\Di\DiModule;

class AppModule extends AbstractModule
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var string
     */
    private $context;

    /**
     * @param string $context
     */
    public function __construct($context = 'prod')
    {
        $appDir = dirname(dirname(__DIR__));
        $this->context = $context;
        $this->appDir = $appDir;
        $this->config = (require "{$appDir}/var/conf/{$context}.php") + (require "{$appDir}/var/conf/prod.php");
        $this->params = (require "{$appDir}/var/lib/params/{$context}.php") + (require "{$appDir}/var/lib/params/prod.php");
        parent::__construct();
        unset($appDir);
    }
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        // install core package
        $this->install(new PackageModule('BEAR\Skeleton\App', $this->context, $this->config));

        // install view package
//        $this->install(new SmartyModule($this));
        $this->install(new TwigModule($this));


        // install optional package
        $this->install(new SignalParamModule($this, $this->params));
        $this->install(new AuraFormModule);

        // install API module
        if ($this->context === 'api') {
            // install api output view package
            $this->install(new HalModule($this));
            //$this->install(new JsonModule($this));
        }

        // install application dependency
        $this->install(new App\Dependency);

        // install application aspect
        $this->install(new App\Aspect($this));
    }
}
