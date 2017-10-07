<?php

namespace Diepxuan\WordPress\Plugin;

class WordPressLoader
{

    protected $_util;
    protected $_loader;
    protected $_table;

    public function __construct()
    {
        if (!defined('ABSPATH')) {
            return;
        }

        if (!defined('WP_INSTALLING') || !WP_INSTALLING) {
            $_util   = new \Diepxuan\WordPress\Plugin\Loader\Util;
            $_loader = new \Diepxuan\WordPress\Plugin\Loader\Loader($_util);
            $_table  = new \Diepxuan\WordPress\Plugin\Loader\Table($_loader);
        }
        add_action(
            'after_plugin_row_autoload.php',
            array($_table, 'list_table'),
            10,
            0
        );

    }
}
