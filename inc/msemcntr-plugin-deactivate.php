<?php
/**
 * @package  MSEMCNTRPlugin
 */

class MSEMCNTRPluginDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
