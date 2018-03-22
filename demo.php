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
$emptyFileMetadata = new Google_Service_Drive_DriveFile();


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nCreating a standard 'My Drive' file...\n\n";

// Creating a standard 'My Drive' file...
$fileMetadata = new Google_Service_Drive_DriveFile(array(
'name' => $myDriveFileName,
'mimeType' => 'application/vnd.google-apps.spreadsheet'
));
$newDriveFile = $service->files->create($fileMetadata, array('fields' => 'id'));
$newDriveFileId = $newDriveFile->id;


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nSharing a standard 'My Drive' file with '$shareGroupName'...\n\n";

// Sharing a standard 'My Drive' file with '$shareGroupName'...
$service->getClient()->setUseBatch(true);
$batch = $service->createBatch();
$groupPermission = new Google_Service_Drive_Permission(array(
'type' => 'group',
'role' => 'writer',
'emailAddress' => $shareGroupName
));
$request = $service->permissions->create(
$newDriveFileId, $groupPermission, array('fields' => 'id'));
$batch->add($request, 'group');
$results = $batch->execute();
$service->getClient()->setUseBatch(false);


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nPopulating '$myDriveFileName' with test data...\n\n";

// Populating '$myDriveFileName' with test data...
$sheetNameAndRange = 'Sheet1';
$sheetDocument = $sheets->spreadsheets->get($newDriveFileId);
// Obtain a dataset from another document (a local CSV on the filesystem):
$localDataFile = __DIR__.'/TestData.csv';
$importCSV = array();
$handle = fopen($localDataFile, 'r');
while ($row = fgetcsv($handle, null, ',')) {
    $importCSV[] = $row;
}
$replacementData = new Google_Service_Sheets_ValueRange(['range' => $sheetNameAndRange,'majorDimension' => 'ROWS','values' => $importCSV]);
$sheets->spreadsheets_values->update($newDriveFileId,$sheetNameAndRange,$replacementData,['valueInputOption' => 'USER_ENTERED']);


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nCreating a Team Drive folder...\n\n";

// Creating a Team Drive folder...
$file = new Google_Service_Drive_DriveFile(array(
'name' => $teamDriveFolderName,
'mimeType' => 'application/vnd.google-apps.folder',
'teamDriveId' => $teamDriveId,
'parents' => array($teamDriveId)
));
$teamDriveFolder = $service->files->create($file, array(
'fields' => 'id','supportsTeamDrives' => true));
$teamDriveFolderId = $teamDriveFolder->id;


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nCreating a Team Drive file...\n\n";

// Creating a Team Drive file...
$fileMetadata = new Google_Service_Drive_DriveFile(array(
'name' => $teamDriveFileName,
'mimeType' => 'application/vnd.google-apps.spreadsheet',
'teamDriveId' => $teamDriveId,
'parents' => array($teamDriveFolderId)
));
$newTeamDriveFile = $service->files->create($fileMetadata, array('fields' => 'id','supportsTeamDrives' => true));
$newTeamDriveFileId = $newTeamDriveFile->id;


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nPopulating '$teamDriveFileName' with test data...\n\n";

// Populating '$teamDriveFileName' with test data...
$sheetNameAndRange = 'Sheet1';
$sheetDocument = $sheets->spreadsheets->get($newTeamDriveFileId);
// Obtain a dataset from another document (a local CSV on the filesystem):
$localDataFile = __DIR__.'/TestData.csv';
$importCSV = array();
$handle = fopen($localDataFile, 'r');
while ($row = fgetcsv($handle, null, ',')) {
    $importCSV[] = $row;
}
$replacementData = new Google_Service_Sheets_ValueRange(['range' => $sheetNameAndRange,'majorDimension' => 'ROWS','values' => $importCSV]);
$sheets->spreadsheets_values->update($newTeamDriveFileId,$sheetNameAndRange,$replacementData,['valueInputOption' => 'USER_ENTERED']);


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nMoving the '$myDriveFileName' document to recently created Team Drive folder '$teamDriveFolderName'... \n\n";

// Moving the '$myDriveFileName' document to recently created Team Drive folder '$teamDriveFolderName'...
$fileMovedToTeamDrive = $service->files->get($newDriveFileId, array('fields' => 'parents','supportsTeamDrives' => true));
$previousParents = join(',', $fileMovedToTeamDrive->parents);
$fileMovedToTeamDrive = $service->files->update($newDriveFileId, $emptyFileMetadata, array(
'addParents' => $teamDriveFolderId,
'removeParents' => $previousParents,
'supportsTeamDrives' => true,
'fields' => 'id, parents'));


print "Done. Press any key to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
print "\nList of all files available to this service account:\n\n";

// Obtain List of all files available to this service account:
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


print "\nDemo complete. Press any key to end. Thanks for watching! Don't forget to run cleanup.php!";
    $handle = fopen ("php://stdin","r");
$line = fgets($handle);
fclose($handle);
?>
