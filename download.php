<?
	
if($_GET['filetype']==1) {
  $basedir="files/";
  $filetype='pdf';
  $filename = $basedir.ereg_replace("[^A-Za-z0-9]", "", $_GET['filename'] ).".".$filetype;



} else  

if($_GET['filetype']==2) {
  $basedir="files/";
  $filetype='zip';
  $filename = $basedir.ereg_replace("[^A-Za-z0-9]", "", $_GET['filename'] ).".".$filetype;


}else{  
 
    
  die("NO FILE HERE");
}

# $filename = // your parameter
# filetype = // 1: dev file

#if cookie set for clientid, then fire up the database connection and store the page request.
$file_extension = strtolower(substr(strrchr($filename,"."),1));


if (! file_exists( $filename ) )
{
die("NO FILE HERE");
};
switch( $file_extension )
{
case "pdf": $ctype="application/pdf"; break;
case "exe": $ctype="application/octet-stream"; break;
case "zip": $ctype="application/zip"; break;
case "doc": $ctype="application/msword"; break;
case "xls": $ctype="application/vnd.ms-excel"; break;
case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
case "gif": $ctype="image/gif"; break;
case "png": $ctype="image/png"; break;
case "jpe":
case "jpeg":
case "jpg": $ctype="image/jpg"; break;
default: $ctype="application/force-download";
}
header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers
header("Content-Type: $ctype");
header("Content-Disposition: attachment; filename=".str_replace(" ", "_", basename($filename)).";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".@filesize($filename));
@readfile("$filename") or die("File not found.");
exit();
?>