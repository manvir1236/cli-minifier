<?php
// require(dirname(dirname(__DIR__)).'/autoload.php')
require 'vendor/autoload.php';
use MatthiasMullie\Minify;
$app = new Silly\Application();
use Symfony\Component\Console\Output\OutputInterface;
$app->command('min [files]', function ($files,  OutputInterface  $output) {
	$files = explode(',', $files);
	foreach ($files as $filename) {
		$extension = getFileExtension($filename);
		$file = validateFile($filename, $extension);
		
		$minifier = $extension == 'js' ? new Minify\JS($file) : new Minify\CSS($file);
		$newfilename = getMinifiedName($filename, $extension);
		$minifier->minify($newfilename);

		$newfile = getcwd().'\\'.$newfilename;
		
		$output->writeln($filename.' => Size Reduced From '.getFileSize($file).'Kb To '.getFileSize($newfile).'Kb');
	}
})->descriptions('Minify JS Files');

$app->run(); 


function getMinifiedName ($name, $type) {
	return preg_replace('/\.'.$type.'/si', ".min.$type", $name);
}
function getFileSize($file) {
	$size_in_kb = filesize($file) / 1024;
	return round($size_in_kb, 2);
}
function validateFile ($filename, $extension) {
	$file = getcwd().'\\'.$filename;
	if (!file_exists($file)) {
		die($filename." Not Exists\n");
	}
	if (!in_array($extension, ['css', 'js'])) {
		die($filename." Is Not a Valid File\n");
	}
	return $file;
}
function getFileExtension ($filename) {
	return pathinfo($filename, PATHINFO_EXTENSION);
}