<?php

if (!function_exists('env_path')) {
	function env_path($path) {
		$storagePath = Storage::getDriver()->getAdapter()->getPathPrefix();
		return join(DIRECTORY_SEPARATOR, [$storagePath, $path]);
	}
}
