<?php

namespace Diepxuan\WordPress\Plugin\Loader;

class Table
{

    protected $_loader;

    public function __construct(
        \Diepxuan\WordPress\Plugin\Loader\Loader $loader
    ) {
        $this->_loader = $loader;
    }

    public function list_table(
        $lt = '\WP_Plugins_List_Table',
        $ps = DIRECTORY_SEPARATOR,
        $mudir = WPMU_PLUGIN_DIR
    ) {
        $table  = new $lt;
        $spacer = '+&nbsp;&nbsp;';
        foreach ($this->_loader->get_muplugins() as $plugin_file) {
            $plugin_data = get_plugin_data($mudir . $ps . $plugin_file, false);
            if (empty($plugin_data['Name'])) {
                $plugin_data['Name'] = $plugin_file;
            }
            $plugin_data['Name'] = $spacer . $plugin_data['Name'];
            $table->single_row(array($plugin_file, $plugin_data));
        }
    }
}
