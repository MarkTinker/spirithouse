<?
if(!empty($_POST))

{

    //database settings
    include("../databaseconnect.php");
    foreach($_POST as $field_name => $val)

    {

        //clean post values
        $field_userid = strip_tags(trim($field_name));

        $val = strip_tags(trim($val));
        //$val = mysql_real_escape_string ($val);


        //from the fieldname:user_id we need to get user_id
        $split_data = explode(':', $field_userid);
        $wineId = $split_data[1];
        $field_name = $split_data[0];
        // get the table name from the field_name - we split the fieldname at the capital
        // then take the first part of the array which should be the table name - assuming
        // all my tables are set up correctly
        //$field_split = preg_split('/(?=[A-Z])/',$field_name);
		//$table =  $field_split[0];
		//echo "this is the TABLE NAME - $table";
        
      
        
        $sql = "UPDATE wines SET `$field_name` = '$val' WHERE wineId = $wineId";
        if(!empty($wineId) && !empty($field_name) && !empty($val))

        {

            //update the values
            mysql_query($sql) or mysql_error();
            //$sql='update stuff';
            
            //$result=mysql_query($sql);

            echo "Updated - $sql";
        } else {
            echo "Invalid Requests - $sql";
        } 

    }

} else {
    echo "Invalid Requests";

}
