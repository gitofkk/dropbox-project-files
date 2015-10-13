<?php

// Include the Dropbox SDK libraries
require_once "dropbox-sdk/Dropbox/autoload.php";
use \Dropbox as dbx;

// Load App key and secret from Json file
$appInfo = dbx\AppInfo::loadFromJsonFile("app-info.json");

/**
* This access token used to access our own account via the API. 
* We can generate this token at Dropbox App console.
*/
$accessToken = "qA5IINGA9dsAAAAAAAAABuZ1AN6v3Kw-0A9IHAZ9ZzarJp4NTTRbOXFlPdyPS38L";

// OAuth 2.0 authorization using the access token
$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");

// Download the files from Dropbox.
$f = fopen("data-a.csv", "w+b");
$fileMetadata = $dbxClient->getFile("/data-a.csv", $f);
fclose($f);

$f = fopen("data-b.csv", "w+b");
$fileMetadata = $dbxClient->getFile("/data-b.csv", $f);
fclose($f);


/*
* Read the CSV files content into array. 
* write the array data to a new combined CSV file.
*/
$file_a = fopen("data-a.csv", "r");
$file_b = fopen("data-b.csv", "r");
$file_c = fopen('data-c.csv', 'w');
while (($data_a = fgetcsv($file_a, 0, ",")) 
	&& ($data_b = fgetcsv($file_b, 0, ","))) {

	$data_c = array($data_a[0], $data_a[2], $data_a[4], 
		$data_a[5], $data_b[0], $data_b[2], $data_b[3]);

	fputcsv($file_c, $data_c);
	
}
fclose($file_a);
fclose($file_b);
fclose($file_c);


// Upload the combined csv file to Dropbox.
$f = fopen("data-c.csv", "rb");
$result = $dbxClient->uploadFile("/data-c.csv", dbx\WriteMode::add(), $f);
fclose($f);

echo "Thank you. A new file data-c.csv has been created on your droopbox. Please check.";