<?php
function bbc_autoload($class_name) {
	require 'classes/' . strtolower($class_name) . '.php';
}
?>