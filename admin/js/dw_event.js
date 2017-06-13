//  dw_event.js version date Mar 2008
//  basic event handling file from dyn-web.com

var dw_Event = {
  
    add: function(obj, etype, fp, cap) {
        cap = cap || false;
        if (obj.addEventListener) obj.addEventListener(etype, fp, cap);
        else if (obj.attachEvent) obj.attachEvent("on" + etype, fp);
    }, 

    remove: function(obj, etype, fp, cap) {
        cap = cap || false;
        if (obj.removeEventListener) obj.removeEventListener(etype, fp, cap);
        else if (obj.detachEvent) obj.detachEvent("on" + etype, fp);
    }, 
    
    DOMit: function(e) { 
        e = e? e: window.event;
        // Beware!  Some browsers may not report element node as target (Safari, Konqueror)
        // but since target is a read-only property code using this must check 
        if (!e.target) e.target = e.srcElement;
        if (!e.preventDefault) e.preventDefault = function () { e.returnValue = false; return false; }
        if (!e.stopPropagation) e.stopPropagation = function () { e.cancelBubble = true; }
        return e;
    }
}


// Created by: Simon Willison | http://simon.incutio.com/ 
// minor mod by shp dyn-web
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    //window.onload = func;
    window.onload = function() { func(); }
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}