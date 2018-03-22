# GoogleSheetsGroupsTeamDrive-Demo
Example files for running a basic demo of creating, sharing, editing, moving, and deleting Google Sheets in My Drive and Team Drive via the Google API PHP Client Library.

With some additions (credentials and setting variables), this is production-ready code for demo via the Google REST API.
## Dependencies
Operating Environment:
* Designed for use in a LAMP environment (really: any Linux environment with PHP).
Requires:
* `Google API Client Library for PHP` (see: https://developers.google.com/api-client-library/php/start/installation)
## The Execution:
* `demo.php`
    * Steps through the process of creating, sharing, editing (adding data from the included `TestData.csv`), and moving Google Sheets in My Drive/Team Drive.
* `cleanup.php`
    * Steps through the process of cleaning up (deleting) items matching those generated in `demo.php` in Team Drive (assuming permissions).
## Credential Files
No credential files are included. _You will need to obtain your own project-specific key_ via https://console.developers.google.com and reference as necessary. The Google API key used in this demo must be the `.json` version.
