<?php
	
	
//phpinfo(); 



echo"<p>A simple test to eway.com.au with error result displayed:</p><br><br>";
 $ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'http://www.eway.com.au/');
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'read_header');
curl_setopt($ch, CURLOPT_WRITEFUNCTION, 'read_body');
curl_exec($ch);
if ($error = curl_error($ch)) {
    echo "Error: $error<br />\n";
}
function read_header($ch, $string)
{
    $length = strlen($string);
    echo "Received $length bytes<br />\n";
    return $length;
}

echo"<p>Testing other websites:</p><br><br>";

function nxs_cURLTest($url, $msg, $testText){  
  $ch = curl_init(); 
  //curl_setopt($ch, CURLOPT_SSLVERSION, 4);
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36"); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  $response = curl_exec($ch); 
  $errmsg = curl_error($ch); 
  $cInfo = curl_getinfo($ch); 
  curl_close($ch); 
  echo "Testing ... ".$url." - ".$cInfo['url']."<br />";
  if (stripos($response, $testText)!==false) 
    echo "....".$msg." - OK<br />"; 
  else 
  { 
    echo "....<b style='color:red;'>".$msg." - Problem</b><br /><pre>"; 
    print_r($errmsg); 
    print_r($cInfo); 
    print_r(htmlentities($response)); 
    echo "</pre>There is a problem with cURL. You need to contact your server admin or hosting provider.";
  }
}

  nxs_cURLTest("http://www.nextscripts.com/", "HTTPS to NXS", "Social Networks");
  nxs_cURLTest("http://www.google.com/intl/en/contact/", "HTTP to Google", "Mountain View, CA");
  nxs_cURLTest("https://www.google.com/intl/en/contact/", "HTTPS to Google", "Mountain View, CA");
  nxs_cURLTest("https://www.facebook.com/", "HTTPS to Facebook", 'id="facebook"');
  nxs_cURLTest("https://graph.facebook.com/", "HTTPS to API (Graph) Facebook", 'get');  
  nxs_cURLTest("https://www.linkedin.com/nhome/", "HTTPS to LinkedIn", 'rel="canonical" href="https://www.linkedin.com/');
  nxs_cURLTest("https://www.eway.com.au/", "HTTPS to eway", 'rel="canonical" href="https://www.eway.com.au');
  
  ?>

