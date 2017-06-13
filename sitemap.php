<?
require_once("databaseconnect.php");

  if(isset($_GET['doctype'])) { $doctype=$_GET['doctype']; } else { $doctype="html"; }

  echo output_head($doctype);

  $items['col1'][0]['name']="Spirit House Restaurant &amp; Cooking School - Sunshine Coast Cooking School - Best Thai Restaurant";
  $items['col1'][0]['link']="http://www.spirithouse.com.au";

  $items['col1'][1]['name']="Spirit House restaurant";
  $items['col1'][1]['link']="http://www.spirithouse.com.au/restaurant";
  
  $items['col1'][2]['name']="Spirit House Thai Food";
  $items['col1'][2]['link']="http://www.spirithouse.com.au/restaurant/food";
  
  $items['col1'][3]['name']="Spirit House menus";
  $items['col1'][3]['link']="http://www.spirithouse.com.au/restaurant/menu";
  
  $items['col1'][4]['name']="Spirit House functions";
  $items['col1'][4]['link']="http://www.spirithouse.com.au/restaurant/functions";
  
  $items['col1'][5]['name']="Spirit House finger food";
  $items['col1'][5]['link']="http://www.spirithouse.com.au/restaurant/f-food";
  
  $items['col1'][6]['name']="Spirit House wine list";
  $items['col1'][6]['link']="http://www.spirithouse.com.au/restaurant/wines.php";

  $items['col1'][7]['name']="Spirit House - Sunshine Coast weddings";
  $items['col1'][7]['link']="http://www.spirithouse.com.au/restaurant/weddings.php";
  
  $items['col1'][8]['name']="Spirit House - bookings and map";
  $items['col1'][8]['link']="http://www.spirithouse.com.au/restaurant/rest-book";
  
  $items['col1'][9]['name']="Spirit House best Thai Restaurant";
  $items['col1'][9]['link']="http://www.spirithouse.com.au/restaurant/awards";
  
  $items['col1'][10]['name']="Spirit House cooking school - Sunshine Coast cooking school";
  $items['col1'][10]['link']="http://www.spirithouse.com.au/school";
  
  $items['col1'][11]['name']="Spirit House - Sunshine Coast cooking school directions";
  $items['col1'][11]['link']="http://www.spirithouse.com.au/school/maps";
  
  $items['col1'][12]['name']="Spirit House questions";
  $items['col1'][12]['link']="http://www.spirithouse.com.au/school/faq";
  
  $items['col1'][13]['name']="Spirit House Gift Vouchers";
  $items['col1'][13]['link']="https://www.spirithouse.com.au/vouchers";
  
  $items['col1'][14]['name']="Spirit House Shop and Products";
  $items['col1'][14]['link']="http://www.spirithouse.com.au/shop";
  
  $items['col1'][15]['name']="Spirit House food tours of Asia";
  $items['col1'][15]['link']="http://www.spirithouse.com.au/tours";
                                                                              
  $items['col1'][16]['name']="Spirit House food tours of Thailand";
  $items['col1'][16]['link']="http://www.spirithouse.com.au/tours/thailand";
  
  $items['col1'][17]['name']="Spirit House food tours of Laos";
  $items['col1'][17]['link']="http://www.spirithouse.com.au/tours/laos";
  
  $items['col1'][18]['name']="Spirit House food tours of Vietnam";
  $items['col1'][18]['link']="http://www.spirithouse.com.au/tours/vietnam";
  
  

  $items['col1']['title']="Main site pages";





  $sql="select wp_terms.term_id, wp_terms.name, slug, `count` as taxcount from wp_terms, wp_term_taxonomy where wp_term_taxonomy.term_id=wp_terms.term_id";
  $result=mysql_query($sql);
  $no=mysql_num_rows($result);
  for($i=0;$i<$no;$i++) {
      $catid=mysql_result($result,$i,"term_id");
      $catname=mysql_result($result,$i,"name");
      $catcount=mysql_result($result,$i,"taxcount");
      $slug=mysql_result($result,$i,"slug");
      $fontsize=($catcount/13);
      $items['col2'][$i]['name']="$catname";
      $items['col2'][$i]['link']="http://www.spirithouse.com.au/funstuff/category/".strtolower($slug);
  }
  $items['col2']['title']="Blog subjects";




  $sql="SELECT ID, post_title, post_name from wp_posts where post_status='publish' and post_type='post' order by  post_date desc";
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  for($i=0;$i<$no;$i++) {
    $ID=trim(mysql_result($rs,$i,"ID"));
    $post_title=str_replace("'", "", trim(mysql_result($rs,$i,"post_title")));
    $post_name=str_replace("'", "", trim(mysql_result($rs,$i,"post_name")));
    $items['col3'][$i]['name']=htmlentities($post_title);
    $items['col3'][$i]['link']="http://www.spirithouse.com.au/funstuff/".$post_name;
  }
  $items['col3']['title']="Blog articles";

   $sql="SELECT ID, post_title, post_name from wp_posts where post_status='publish' and post_type='page' order by  post_date desc";
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  for($i=0;$i<$no;$i++) {
    $ID=trim(mysql_result($rs,$i,"ID"));
    $post_title=str_replace("'", "", trim(mysql_result($rs,$i,"post_title")));
    $post_name=str_replace("'", "", trim(mysql_result($rs,$i,"post_name")));
    $items['col4'][$i]['name']=htmlentities($post_title);
    $items['col4'][$i]['link']="http://www.spirithouse.com.au/funstuff/".$post_name;
  }
  $items['col4']['title']="Landing pages";

  $sql="SELECT classid, classname from classes where classname!='' order by classname";
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  for($i=0;$i<$no;$i++) {
    $classname=trim(mysql_result($rs,$i,"classname"));
    $classid=mysql_result($rs,$i,"classid");
    $items['col5'][$i]['name']=htmlentities($classname);
    $items['col5'][$i]['link']="http://www.spirithouse.com.au/".str_replace(" ", "-", $classname)."-cooking-class";
  }
  $items['col5']['title']="Cooking classes";
  
  
  $items['col6'][0]['name']="Spirit House Shop";
  $items['col6'][0]['link']="http://www.spirithouse.com.au/shop";
  $sql="select * from `shop_categorytable` where `show`=1";
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  for($i=0;$i<$no;$i++) {
    $categoryName=trim(mysql_result($rs,$i,"categoryName"));    
    $items['col6'][$i+1]['name']=$categoryName;
    $items['col6'][$i+1]['link']="http://www.spirithouse.com.au/shop/".str_replace(" ", "-", strtolower($categoryName));
  }
  $items['col6']['title']="Shop categories";







  if($doctype=="html") {
    echo output_html_content($items);
  } else {
    echo output_xml_content($items);
  }

  echo output_footer($doctype);




function output_html_content($items) {

  for($i=0;$i<sizeof($items);$i++) {
    echo "<td><h1 style='border-bottom:1px solid #999'>".$items['col'.($i+1)]['title']."</h1><br />";
    for($j=0;$j<sizeof($items['col'.($i+1)]);$j++) {
      if(array_key_exists($j, $items['col'.($i+1)])) echo "<a href='".$items['col'.($i+1)][$j]['link']."'>".$items['col'.($i+1)][$j]['name']."</a><br />";
    }
    echo "</td>";
  }

}

function output_xml_content($items) {

  for($i=0;$i<sizeof($items);$i++) {
    for($j=0;$j<sizeof($items['col'.($i+1)]);$j++) {
      if(array_key_exists($j, $items['col'.($i+1)])) {
        ?>
<url>
  <loc><?=$items['col'.($i+1)][$j]['link'];?></loc>
  <priority>0.5</priority>
  <changefreq>daily</changefreq>
</url>
<?
      }
    }
  }
}

function output_head($type="html") {
  if($type=="html") {
    ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
  <title>Spirit House sitemap</title>
 </head><body style='font-size:10px' >

 <br /><a href='http://www.spirithouse.com.au/sitemap.php?doctype=xml'>xml</a>
  <table><tr valign='top'>
  <?
  } else {
    echo '<?xml version="1.0" encoding="UTF-8"?>
';
?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?
  }
}

function output_footer($type="html") {
  if($type=="html") {
  ?></tr></table></body>
</html> <html> <?
  } else {
   ?></urlset><?
  }
}