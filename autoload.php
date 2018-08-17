<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AEG_NM_DIRPATH', dirname(__FILE__) . '/src/');

/**
 * Autoload the classes of the plugin.
 * At the current moment it autoload only the classes present in the ST_CLASSES_PATH folder, but not in ST_ADMIN_PATH
 *
 * @todo change doc and check if add a if file_exists before include
 * @param  string $class
 *
 * @return bool
 */
function nmwp_autoload($class) {

    if ( false === strpos( $class, 'aeg_NM_' ) ) {
        return false;
    }

    $file_name      = str_replace('_', DIRECTORY_SEPARATOR, $class);

    include AEG_NM_DIRPATH . $file_name . '.php';

    return true;

}

spl_autoload_register('nmwp_autoload');