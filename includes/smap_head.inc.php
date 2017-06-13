<?
function head($title="Spirit House") {
  ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$title;?></title>
<link rel="styleSheet" media="print" href="http://www.spirithouse.com.au/css/print.css" type="text/css" />
<link rel="styleSheet" media="screen" href="http://www.spirithouse.com.au/css/screen.css" type="text/css" />
<link rel="styleSheet" media="screen" href="http://www.spirithouse.com.au/mooflow/MooFlow.css" type="text/css" />

 <script type="text/javascript" src="http://www.spirithouse.com.au/mooflow/mootools-1.2-core.js"></script>
 <script type="text/javascript" src="http://www.spirithouse.com.au/mooflow/mootools-1.2-more.js"></script>
  <script type="text/javascript" src="http://www.spirithouse.com.au/mooflow/MooFlow.js"></script>
<script type="text/javascript">
/* <![CDATA[ */

	var myMooFlowPage = {
	
		start: function(){
	
			var mf = new MooFlow($('MooFlow'), {
				startIndex: 2,
				useSlider: true,
				useAutoPlay: false,
				useCaption: true,
				useResize: true,
				useMouseWheel: true,
				useKeyInput: true
			});
			
		}
		
	};
	
	window.addEvent('domready', myMooFlowPage.start);
	
/* ]]> */

</script>



</head>
<?
}