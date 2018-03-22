<?php
if (!function_exists('curl_reset')) {
    function curl_reset(&$ch) {
        $ch = curl_init();
    }
}
require_once '/path/to/google/vendor/autoload.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/google/api/credentials.json');


print "Creating Google API Client connections, adding scopes, and setting variables...\n\n";

$client = new Google_Client();
$client->setApplicationName('Your Demo Name');
$client->setDeveloperKey('YourDemoKeFromGoogleGoesHere');
$client->useApplicationDefaultCredentials();
$client->addScope(Google_Service_Drive::DRIVE);
$client->addScope(Google_Service_Sheets::SPREADSHEETS);
$service = new Google_Service_Drive($client);
$sheets = new Google_Service_Sheets($client);

$shareGroupName = 'googlegroupname@googlehost.com';
$teamDriveId = 'YourRootGoogleTeamDriveID';
$myDriveFileName = "You Will Be Baffled";
$teamDriveFolderName = "Demo and Testing Folder";
$teamDriveFileName = "You Will Be Astonished";


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nCleaning up files matching '$myDriveFileName,' '$teamDriveFileName,' and '$teamDriveFolderName'...\n\n";

// Cleaning up files matching '$myDriveFileName'...
$optParams = array(
'pageSize' => 20,
'supportsTeamDrives' => true,
'includeTeamDriveItems' => true,
'q' => 'name=\''.$myDriveFileName.'\'',
'fields' => 'files(id, name)'
);
$results = $service->files->listFiles($optParams);
foreach ($results->getFiles() as $file) {
    $service->files->delete($file->getId(), array('supportsTeamDrives' => true));
}

// Cleaning up files matching '$teamDriveFileName'...
$optParams = array(
'pageSize' => 20,
'supportsTeamDrives' => true,
'includeTeamDriveItems' => true,
'q' => 'name=\''.$teamDriveFileName.'\'',
'fields' => 'files(id, name)'
);
$results = $service->files->listFiles($optParams);
foreach ($results->getFiles() as $file) {
    $service->files->delete($file->getId(), array('supportsTeamDrives' => true));
}

// Cleaning up files matching '$teamDriveFolderName'...
$optParams = array(
'pageSize' => 20,
'supportsTeamDrives' => true,
'includeTeamDriveItems' => true,
'q' => 'name=\''.$teamDriveFolderName.'\'',
'fields' => 'files(id, name)'
);
$results = $service->files->listFiles($optParams);
foreach ($results->getFiles() as $file) {
    $service->files->delete($file->getId(), array('supportsTeamDrives' => true));
}


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nList of all files available to this service account:\n\n";

// List of all files available to this service account:
$optParams = array(
'pageSize' => 20,
'supportsTeamDrives' => true,
'includeTeamDriveItems' => true,
'fields' => 'files(id, name)'
);
$results = $service->files->listFiles($optParams);
foreach ($results->getFiles() as $file) {
    printf("%s (%s)\n", $file->getName(), $file->getId());
}


print "\nDone. Press any key to end the demo. Thanks for watching!";
    $handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
?>
