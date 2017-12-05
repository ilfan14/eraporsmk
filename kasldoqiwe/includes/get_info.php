<?php
$template_path 	= 'config/.htaccess';
$config_file = file_get_contents($template_path);
$subfolder = str_replace('/','',substr($_SERVER["REQUEST_URI"], 0, -18)).'/';
$subfolder = substr($_SERVER["REQUEST_URI"], 1, -18).'/';
echo $subfolder.'<br />';
echo $_SERVER["REQUEST_URI"].'<br />';
$new  = str_replace("%rewrite_base%","RewriteBase /".$subfolder, $config_file);
echo $new;
?>