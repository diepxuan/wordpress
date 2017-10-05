<?php

namespace Diepxuan\WordPress\Plugin;

class WordPress implements \Composer\Plugin\PluginInterface {

    /**
     * @param \Composer\Composer $composer
     * @param \Composer\IO\IOInterface $io
     * @return void
     */
	public function activate( \Composer\Composer $composer, \Composer\IO\IOInterface $io ) {
        /**
         * @var $installer \Diepxuan\WordPress\Setup\WordPress
         */
		$installer = new \Diepxuan\WordPress\Setup\WordPress( $io, $composer );
		$composer->getInstallationManager()->addInstaller( $installer );
	}
}
