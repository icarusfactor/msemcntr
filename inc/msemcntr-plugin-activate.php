<?php
/**
 * @package  MSEMCNTRPlugin
 */

class MSEMCNTRPluginActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
