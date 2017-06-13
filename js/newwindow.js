function newwindow() {
var load = window.open('http://www.spirithouse.com.au/newwindow/menu.php','','scrollbars=yes,height=300,width=600,resizable=yes,toolbar=no,location=no,status=no,menubar=no');
}
function tandc_window() {
var load = window.open('http://www.spirithouse.com.au/newwindow/tandc.htm','','scrollbars=yes,height=330,width=600,resizable=yes,toolbar=no,location=no,status=no,menubar=no');
}
function vouchertandc_window() {
var load = window.open('http://www.spirithouse.com.au/newwindow/vouchertandc.htm','','scrollbars=yes,height=330,width=600,resizable=yes,toolbar=no,location=no,status=no,menubar=no');
}

function functmenu1() {
var load = window.open('http://www.spirithouse.com.au/newwindow/55menu.php','','scrollbars=yes,height=300,width=600,resizable=yes,toolbar=no,location=no,status=no,menubar=no');
}
function newwindowmap() {
var load = window.open('http://www.spirithouse.com.au/newwindow/map.htm','','scrollbars=yes,height=600,width=600,resizable=yes,toolbar=no,location=no,status=no,menubar=no');
}
function newwindownews() {
var load = window.open('http://www.spirithouse.com.au/signup.php','','scrollbars=yes,height=300,width=600,resizable=yes,toolbar=no,location=no,status=no,menubar=no');
}

 function openWindow(ClassID, ClassName) {
        winStats='toolbar=no,location=no,directories=no,menubar=no,';
        winStats+='scrollbars=yes,width=350,height=200';

        if (navigator.appName.indexOf("Microsoft")>=0) {
            winStats+=',left=10,top=25'
        } else {
            winStats+=',screenX=10,screenY=25'
        }

        floater=window.open("classinfo.php?classid=" + ClassID, "",winStats);
    }

function newwindowsuppliers(state) {
var load = window.open('http://www.spirithouse.com.au/takehome4.php?state='+state,'','scrollbars=yes,height=300,width=700,resizable=yes,toolbar=no,location=no,status=no,menubar=no');
}