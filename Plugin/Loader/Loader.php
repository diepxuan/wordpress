<?php

namespace Diepxuan\WordPress\Plugin\Loader;

class Loader
{
    /**
     * @var \Diepxuan\WordPress\Plugin\Loader\Util
     */
    protected $_util;

    /**
     * @param boolean $plugins
     * @param string  $ps
     * @param string  $mudir
     */
    public function __construct(
        \Diepxuan\WordPress\Plugin\Loader\Util $util,
                                               $plugins = false,
                                               $ps = DIRECTORY_SEPARATOR,
                                               $mudir = WPMU_PLUGIN_DIR
    ) {

        $this->_util = $util;

        if (!$plugins) {
            $plugins = $this->get_muplugins();
        }
        foreach ($plugins as $plugin) {
            require_once $mudir . $ps . $plugin;
        }
    }

    public function get_muplugins(
        $abs = ABSPATH,
        $pdir = WP_PLUGIN_DIR,
        $mudir = WPMU_PLUGIN_DIR,
        $ps = DIRECTORY_SEPARATOR
    ) {
        $key     = $this->get_muloader_key($mudir);
        $plugins = get_site_transient($key);
        if ($plugins === false) {
            if (!function_exists('get_plugins')) {
                require $abs . 'wp-admin/includes/plugin.php';
            }
            $plugins  = array();
            $rel_path = $this->_util->rel_path($pdir, $mudir);
            foreach (get_plugins($ps . $rel_path) as $plugin_file => $data) {
                if (dirname($plugin_file) !== '.') {
                    $plugins[] = $plugin_file;
                }
            }
            set_site_transient($key, $plugins);
        }
        return $plugins;
    }

    public function get_muloader_key($mudir = WPMU_PLUGIN_DIR)
    {
        $old_key = get_site_transient('diepxuan_mu_loader');
        $key     = md5(json_encode(scandir($mudir)));
        if ($old_key !== $key) {
            if ($old_key) {
                delete_site_transient($old_key);
            }
            set_site_transient('diepxuan_mu_loader', $key);
        }
        return $key;
    }

}
