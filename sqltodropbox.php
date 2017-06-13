<?php
/**
 * Quickly and easily backup your MySQL database and have the .tgz copied to
 * your Dropbox account.
 *
 * Copyright (c) 2012 Eric Silva
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author Eric Silva [eric@ericsilva.org] [http://ericsilva.org/]
 * @version 1.0.0
 */

//require('DropboxUploader.php');


//comment this and uncomment above when the 't' error is fixed

require_once "dropbox-sdk/Dropbox/autoload.php";
use \Dropbox as dbx;

$appInfo = dbx\AppInfo::loadFromJsonFile("dropbox-sdk/Dropbox/app-info.json");
$webAuth = new dbx\WebAuthNoRedirect($appInfo, "acland-test");


$accessToken = "S6y9PbBMstwAAAAAAAB9rkKsS5GplOMMjZX0qpNGh4GTYcLHunFNeQZhvaDBfBRo";

/*$dbxClient = new dbx\Client($accessToken, "acland-test");
$f = fopen("upload-script.rtf", "rb");
$result = $dbxClient->uploadFile("/db_backups/upload-script.rtf", dbx\WriteMode::add(), $f);
fclose($f);
print_r($result);
*/


//$dbxClient = new dbx\Client($accessToken, "acland-test");
//$accountInfo = $dbxClient->getAccountInfo();
//print_r($accountInfo);


//exit();


// location of your temp directory
$tmpDir = "/tmp/";
// username for MySQL
$user = "sh_db00jg";
// password for MySQL
$password = "qudtas2";
// database name to backup
$dbName = "sh_db";
// the zip file backed up will have this prefixed
$prefix = "sh_";
//echo "got to here";

// Create the database backup file
$sqlFile = $tmpDir.$prefix.date('Y_m_d').".sql";
$backupFilename = $prefix.date('D_G').".tgz";
$backupFile = $tmpDir.$backupFilename;
// Destination folder in Dropbox (folder will be created if doesn't yet exist)
$dropbox_dest = "db_backups";

$createBackup = "mysqldump -u ".$user." --password=".$password." ".$dbName." > ".$sqlFile;
//echo $createBackup;
$createZip = "tar cvzf $backupFile $sqlFile";
//echo $createZip;
exec($createBackup);
exec($createZip);






$dbxClient = new dbx\Client($accessToken, "acland-test");
$f = fopen("$backupFile", "rb");
$result = $dbxClient->uploadFile("/".$dropbox_dest."/".$backupFilename."", dbx\WriteMode::add(), $f);
fclose($f);
//print_r($result);






//comment above when the 't' error is fixed and uncomment below

/*

try {
    // Upload database backup to Dropbox
    $uploader = new DropboxUploader($dropbox_user, $dropbox_password);
    $uploader->upload($backupFile, $dropbox_dest,  $backupFilename);
} catch(Exception $e) {
    die($e->getMessage());
}
*/

// Delete the temporary files
unlink($sqlFile);
unlink($backupFile);


?>


