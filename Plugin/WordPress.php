<?php

namespace Diepxuan\WordPress\Plugin;

class WordPress implements \Composer\Plugin\PluginInterface, \Composer\EventDispatcher\EventSubscriberInterface
{

    /**
     * @param \Composer\Composer $composer
     * @param \Composer\IO\IOInterface $io
     * @return void
     */
    public function activate(
        \Composer\Composer       $composer,
        \Composer\IO\IOInterface $io
    ) {
        /**
         * @var $installer \Diepxuan\WordPress\Setup\WordPress
         */
        $installer = new \Diepxuan\WordPress\Setup\WordPress($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'pre-autoload-dump'   => 'dumpRequireFile',
            'pre-package-install' => 'overridePluginTypes',
            'pre-package-update'  => 'overridePluginTypes',
        );
    }

    public function dumpRequireFile()
    {
        $muPluginDir = $this->getMuPluginDir();
        if (!$muPluginDir) {
            return;
        }
        $muPluginPath = $this->resolveMuPluginPath($muPluginDir);
        if (!file_exists($muPluginPath)) {
            mkdir($muPluginPath, 0755, true);
        }
        file_put_contents(
            $muPluginPath . 'autoload.php',
            "<?php\n\nnew \Diepxuan\WordPress\Plugin\WordPressLoader;\n"
        );
    }

    public function overridePluginTypes($event)
    {
        $operation = $event->getOperation();
        if ($operation instanceof \Composer\DependencyResolver\Operation\UpdateOperation) {
            $package = $operation->getInitialPackage();
        } else {
            $package = $operation->getPackage();
        }
        if ('wordpress-plugin' !== $package->getType()) {
            return;
        }
    }

    /**
     * @return string
     */
    protected function getMuPluginDir()
    {
        $path = false;
        if (empty($this->extras['installer-paths']) || !is_array($this->extras['installer-paths'])) {
            return false;
        }
        foreach ($this->extras['installer-paths'] as $path => $types) {
            if (!is_array($types)) {
                continue;
            }
            if (!in_array('type:wordpress-muplugin', $types)) {
                continue;
            }
            $path = str_replace('{$name}', '', $path);
            break;
        }
        return $path;
    }

    /**
     * @param  string $relpath
     * @return string
     */
    protected function resolveMuPluginPath($relpath)
    {
        if ($this->config->has('vendor-dir')) {
            $tag = $this->config->raw()['config']['vendor-dir'];
        } else {
            $tag = '';
        }
        $basepath = str_replace($tag, '', $this->config->get('vendor-dir'));
        return $basepath . $relpath;
    }
}
