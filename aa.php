<?
$fields = $_POST['fields']; 
if (is_array($fields)) { 
echo "<pre>";
print_r($fields);
echo "</pre>";
foreach ($fields as $key=>$val) {
echo "$key -> $val <br />";
}
$content = '<hr><br>Select * from menu where:<ul>';
$results = count($fields); 
for ($i=0;$i<count($fields);$i++) {
if ($i==$results-1) {$content = $content . "<li>$fields[$i] = 1";} ELSE {
$content = $content . "<li>$fields[$i] = 1 AND \n";}

} 
echo $content;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>
<body>
<form action="<? $_SERVER["PHP_SELF"];?>" method="post">
<input type="checkbox" name="fields[]" value="Coconut">Coconut<br /> 
<input type="checkbox" name="fields[]" value="Garlic">Garlic<br /> 
<input type="checkbox" name="fields[]" value="Chilli">Chilli<br /> 
<input type="checkbox" name="fields[]" value="Gluten">Gluten<br /> 
<input type="checkbox" name="fields[]" value="Veg">Vego 
<input name="submit" type="submit" value="submit">
</form>
</body>
</html>

