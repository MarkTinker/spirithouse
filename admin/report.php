<?
include("../databaseconnect.php");
//include("../globals.inc.php");
include("includes/auth.inc.php");
include("includes/functions.inc.php");
require_once 'Spreadsheet/Excel/Writer.php';

  // Creating a workbook
  $workbook = new Spreadsheet_Excel_Writer();
  $format_title =& $workbook->addFormat(array('Size' => 15,
                                          'Align' => 'center',
                                          'Color' => 'white',
                                          'Pattern' => 1,
                                          'FgColor' => 'blue'));
  $format_title->setAlign('merge');

  $format_currency =& $workbook->addFormat();
  $format_currency->setNumFormat('$#');
  $format_currency->setAlign('right');

  $format_bold_currency =& $workbook->addFormat();
  $format_bold_currency->setNumFormat('$#');
  $format_bold_currency->setAlign('right');
  $format_bold_currency->setBold();

  $format_currency_neg =& $workbook->addFormat();
  $format_currency_neg->setNumFormat('$#');
  $format_currency_neg->setAlign('right');
  $format_currency_neg->setColor('red');

  $format_bold =& $workbook->addFormat();
  $format_bold->setBold();

  $format_bold_center =& $workbook->addFormat();
  $format_bold_center->setBold();
  $format_bold_center->setAlign('center');

  $format_align_left =& $workbook->addFormat();
  $format_align_left->setAlign('left');

  // sending HTTP headers
    $workbook->send("Class-Master-List-".date("Y-m-d").".xls");
    $worksheet =& $workbook->addWorksheet('Sales');

    $worksheet->write(0, 0, "Class master list", $format_title);
    $worksheet->write(0, 1, "", $format_title);
    $worksheet->write(0, 2, "", $format_title);
    $worksheet->write(0, 3, "", $format_title);
    $worksheet->write(0, 4, "", $format_title);

    $worksheet->write(2, 0, "Date", $format_bold);
    $worksheet->write(2, 1, "Class", $format_bold);
    $worksheet->write(2, 2, "Nametags", $format_bold);
    $worksheet->write(2, 3, "Seats left", $format_bold);
    $worksheet->write(2, 4, "Booking info", $format_bold);

    $worksheet->setColumn(0,0,10);
    $worksheet->setColumn(1,1,25);
    $worksheet->setColumn(2,2,40);
    $worksheet->setColumn(3,3,10);
    $worksheet->setColumn(4,4,40);

    // Freeze those cells!
    $freeze = array(3,0,4,0);
    $worksheet->freezePanes($freeze);

    $startingrow=4;

    $sql="select scheduleid, classname, classseats, scheduledate, bookings
          FROM classes, schedule
          where classes.classid=schedule.classid and scheduledate >=now()
          order by scheduledate ";
      #echo $sql;
      #echo "<br />";
      $printed=false;
      $result=mysql_query($sql);
      $no=mysql_num_rows($result);
      if($no!=0) {
        for($i=0;$i<$no;$i++) {
            $scheduleid=mysql_result($result,$i,"scheduleid");
          $classname=mysql_result($result,$i,"classname");
          $maxseats=mysql_result($result,$i,"classseats"); 
          $scheduledate=mysql_result($result,$i,"scheduledate");
          $bookings=mysql_result($result,$i,"bookings");
          $seatsleft=$maxseats-$bookings;
          if($seatsleft==0) $seatsleft="Full";

          $bkg_details=get_class_booking_details($scheduleid);

          #if(sizeof($bkg_details)>0 && !$printed) {
            #mail('murray@harvestmarketing.com', '', print_r($bkg_details));
            #$printed=true;
          #}
          $newrow=$startingrow+$i;

          $worksheet->write($newrow, 0, $scheduledate);
          $worksheet->write($newrow, 1, $classname);

          $nametags="";
          for($j=0;$j<sizeof($bkg_details);$j++) {
             $nametags.=$bkg_details[$j]['nametags']." ";
          }
          $worksheet->write($newrow, 2, $nametags);
          $worksheet->write($newrow, 3, $seatsleft);

          $bookinginfo="";
          for($j=0;$j<sizeof($bkg_details);$j++) {
             $bookinginfo.="(".$bkg_details[$j]['bookingname']." ".$bkg_details[$j]['phone'].". Total: ".$bkg_details[$j]['total'];
             if(strlen($bkg_details[$j]['notes'])>0) $bookinginfo.=" Notes: ".$bkg_details[$j]['notes'];
             $bookinginfo.=") ";
          }

          $worksheet->write($newrow, 4, $bookinginfo);

        }

      }




  // Let's send the file
  $workbook->close();