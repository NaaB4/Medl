<?php
/**
 * Plugin Name: EDCalculator
 * Plugin URI: https://tarifrechner.ivato.de
 * Description: Das WordPress Tarifrechner Plugin für Stadtwerke und Versorger
 * Version: 3.0.0
 * Author: ivato.de
 * Author URI: https://tarifrechner.ivato.de
 * Text Domain: edc
 * Domain Path: /languages
 *
 */

defined('ABSPATH') || exit;

if(!defined('EDC_PLUGIN_FILE')){
	define('EDC_PLUGIN_FILE', __FILE__ );
	include_once 'globals.php';
	include_once EDC_PLUGIN_PATH . '/init.php';
}

register_activation_hook(EDC_PLUGIN_FILE, array('EDCH','activateEDCPlugin'));
register_deactivation_hook(EDC_PLUGIN_FILE, array('EDCH','deactivateEDCPlugin'));
register_uninstall_hook(EDC_PLUGIN_FILE, array('EDCH','uninstallEDCPlugin'));