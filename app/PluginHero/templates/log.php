<?php

if ( ! defined( 'ABSPATH' ) ) exit;

echo "<pre>";
echo "Log date: " . esc_html($date) . "<br>";
echo "File: " . esc_html($file) . "<br>";
echo "Class: " . esc_html($class) . "<br>";
echo "Function: " . esc_html($function) . "<br>";
echo "Level: " . esc_html($level) . "<br>";
echo "Message: " . esc_html($message) . "<br>";
echo "Context: " . esc_html($context) . "<br>";
echo "----------------------------------------<br>";
echo "</pre>";