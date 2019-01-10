<?php
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} else {
    require __DIR__.'/../../../autoload.php';
}
use MatthiasMullie\Minify;
$app = new Silly\Application();
$app->command('js [filename]', function ($filename) {
	$file = validateFile($filename);
	
	$minifier = new Minify\JS($file);
	$newfilename = getMinifiedName($filename, 'js');
	$minifier->minify($newfilename);

	$newfile = getcwd().'\\'.$newfilename;
	
	echo 'File Minified: Size Reduced From '.getFileSize($file).'Kb To '.getFileSize($newfile).'Kb';
})->descriptions('Minify JS Files');

$app->command('css [filename]', function ($filename) {
	$file = validateFile($filename);
	
	$minifier = new Minify\CSS($file);
	$newfilename = getMinifiedName($filename, 'css');
	$minifier->minify($newfilename);

	$newfile = getcwd().'\\'.$newfilename;
	
	echo 'File Minified: Size Reduced From '.getFileSize($file).'Kb To '.getFileSize($newfile).'Kb';
})->descriptions('Minify CSS Files');
$app->run();


function getMinifiedName ($name, $type) {
	return preg_replace('/\.'.$type.'/si', ".min.$type", $name);
}
function getFileSize($file) {
	$size_in_kb = filesize($file) / 1024;
	return round($size_in_kb, 2);
}
function validateFile ($filename) {
	$file = getcwd().'\\'.$filename;
	if (!file_exists($file)) {
		die('File Not Exists');
	}
	return $file;
}