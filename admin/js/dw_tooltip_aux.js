/*************************************************************************
    This code is from Dynamic Web Coding at dyn-web.com
    Copyright 2008 by Sharon Paine 
    See Terms of Use at www.dyn-web.com/business/terms.php
    regarding conditions under which you may use this code.
    This notice must be retained in the code as is!
    
    for use with dw_tooltip.js 2008 version
*************************************************************************/


// Used when the tooltip content is in HTML elements with tipContent class attached 
dw_Tooltip.writeStyleRule = function() {
    if ( document.createElement && document.getElementsByTagName ) {
        document.write('<style type="text/css" media="screen">.tipContent { display:none; }</style>');
    }
}

///////////////////////////////////////////////////////////////////////////////////
//  Initialization functions (init tooltip and set up event handling or delegation)

addLoadEvent( dw_Tooltip.init );

// The following choices are available for event handling 
// see examples and documentation 
//addLoadEvent( dw_initShowTip );
//addLoadEvent( initDocMouseover );

// All mouseover events monitored (pick one)
function initDocMouseover() {
    //dw_Event.add(document, 'mouseover', dw_checkForTitle);
    //dw_Event.add(document, 'mouseover', dw_checkForTooltip);
    //dw_Event.add(document, 'mouseover', dw_checkLink);
}

//  used when showTip class attached to identify tooltip actuators 
function dw_initShowTip() { 
    if ( !document.getElementsByTagName ) { return; }
    var list = dw_getElementsByClassName( 'showTip' );
    for (var i=0; list[i]; i++) {
        dw_Event.add( list[i], 'mouseover', dw_Tooltip.activate );
        dw_Event.add( list[i], 'mouseout', dw_Tooltip.deactivate );
        dw_Event.add( list[i], 'focus', dw_Tooltip.activate );
        dw_Event.add( list[i], 'blur', dw_Tooltip.deactivate );
    }
    dw_Event.add( document, "keydown", dw_Tooltip.checkKey,  true ); // added here for focus 
    dw_Event.add( window, 'blur', dw_Tooltip.deactivate ); // for ie
}

// Checks whether element moused over has a title attribute  
function dw_checkForTitle(e) {
    e = dw_Event.DOMit(e); var tgt = e.target; 
    if (tgt.nodeType != 1) tgt = tgt.parentNode; // safari...
    if ( tgt.title ) {
        dw_Tooltip.activate(e);
        dw_Event.add( tgt, 'mouseout', dw_Tooltip.deactivate);
    }
}

// links with id or query string
function dw_checkForTooltip(e) {
    e = dw_Event.DOMit(e); var tgt = e.target; 
    if (tgt.nodeType != 1) tgt = tgt.parentNode; // safari...
    if ( tgt.tagName == 'A' && tgt.id || tgt.search ) { 
        dw_Tooltip.activate(e);
        dw_Event.add( tgt, 'mouseout', dw_Tooltip.deactivate);
    }
}

// removed to ajax demo
//function dw_checkLink(e) {}


/////////////////////////////////////////////////////////////////////
//  used by dw_initShowTip
function dw_getElementsByClassName(sClass, sTag, oCont) {
    var result = [], list, i;
    var re = new RegExp("\\b" + sClass + "\\b", "i");
    oCont = oCont? oCont: document;
    if ( document.getElementsByTagName ) {
        if ( !sTag || sTag == "*" ) {
            list = oCont.all? oCont.all: oCont.getElementsByTagName("*");
        } else {
            list = oCont.getElementsByTagName(sTag);
        }
        for (i=0; list[i]; i++) 
            if ( re.test( list[i].className ) ) result.push( list[i] );
    }
    return result;
}

if (!Array.prototype.push) {  // ie5.0
	Array.prototype.push =  function() {
		for (var i=0; arguments[i]; i++) this[this.length] = arguments[i];
		return this[this.length-1]; // return last value appended
	}
};


/////////////////////////////////////////////////////////////////////
//  Positioning algorithms 

dw_Tooltip.positionWindowCenter = function() {
    var x = Math.round( (dw_Viewport.width - dw_Tooltip.tip.offsetWidth)/2 ) + dw_Viewport.scrollX;
    var y = Math.round( (dw_Viewport.height - dw_Tooltip.tip.offsetHeight)/2 ) + dw_Viewport.scrollY;
    dw_Tooltip.setPosition(x,y);
}

// later... (rel link removed due to ie weirdness)

/////////////////////////////////////////////////////////////////////
// formatting and display functions 

// id: stickyTable classes: stickyBar (tr), div classes: stickyTitle, stickyContent
dw_Tooltip.wrapSticky = function(str, title) {
    title = title || '';
    var src = dw_Tooltip.defaultProps['closeBoxImage'];
    var msg = '<table id="stickyTable" border="0" cellpadding="0" cellspacing="0" width="100%"><tr class="stickyBar">' + 
        '<td><div class="stickyTitle">' + title + '</div></td>' + 
        '<td style="text-align:right"><a href="javascript: void dw_Tooltip.hide()">' + 
        '<img style="float:right" src="' + src + '" border="0" /></a></td></tr>' + 
        '<tr><td colspan="2"><div class="stickyContent">' + str + '</div></td></tr></table>';
    return msg;
}

// optional caption, optional width supported by all these wrapFn's

dw_Tooltip.wrapToWidth = function(obj) {
    var str = obj['str']; 
    var caption = obj['caption'] || '';
    if ( this.sticky && this.defaultProps['showCloseBox'] ) {
        str = dw_Tooltip.wrapSticky(str, caption );
    } else {
        if (caption) { str = '<div class="caption">' + obj['caption']  + '</div>' + str; }
    }
    if ( obj['w'] ) { this.setTipWidth( obj['w'] ) }
    return str;
}

dw_Tooltip.wrapImageToWidth = function(obj) {
    dw_getImage( obj['img'] );
    var caption = obj['caption'] || ''; var w = obj['w'];
    var str = '<img src="' + obj['img'] + '" width="' +w + '" height="' + obj['h'] + '" alt="">';
    if ( this.sticky && this.defaultProps['showCloseBox'] ) {
        str = dw_Tooltip.wrapSticky(str, caption );
        w += 8; // attempt to account for padding etc of inner wrapper
    } else {
        if (caption) { str = '<div class="caption">' + obj['caption']  + '</div>' + str; }
    }
    this.setTipWidth(w);
    return str;
}

// Image and text side by side
dw_Tooltip.wrapTextByImage = function(obj) {
    dw_getImage( obj['img'] );
    var caption = obj['caption'] || '';
    
    var str = '<table cellpadding="0" cellspacing="0" border="0"><tr>' + 
        '<td><div class="txt">' + obj['txt'] + '</div></td>' + 
         '<td><div class="img"><img src="' + obj['img'] + '" /></div>' + 
         '</td></tr></table>';
    
    if ( this.sticky && this.defaultProps['showCloseBox'] ) {
        str = dw_Tooltip.wrapSticky(str, caption );
    } else {
        if (caption) { str = '<div class="caption">' + obj['caption']  + '</div>' + str; }
    }
    if ( obj['w'] ) { this.setTipWidth( obj['w'] ) }
    return str;
}

dw_Tooltip.wrapImageOverText = function(obj) {
    dw_getImage( obj['img'] );
    var caption = obj['caption'] || '';
    var str = '<div class="img"><img src="' + obj['img'] + '" /></div><div class="txt">' + obj['txt'] + '</div>';
    if ( this.sticky && this.defaultProps['showCloseBox'] ) {
        str = dw_Tooltip.wrapSticky(str, caption );
    } else {
        if (caption) { str = '<div class="caption">' + obj['caption']  + '</div>' + str; }
    }
    if ( obj['w'] ) { this.setTipWidth( obj['w'] ) }
    return str;
}

dw_Tooltip.wrapTextOverImage = function(obj) {
    dw_getImage( obj['img'] );
    var caption = obj['caption'] || '';
    var str = '<div class="txt">' + obj['txt'] + '</div><div class="img"><img src="' + obj['img'] + '" /></div>';
    if ( this.sticky && this.defaultProps['showCloseBox'] ) {
        str = dw_Tooltip.wrapSticky(str, caption );
    } else {
        if (caption) { str = '<div class="caption">' + obj['caption']  + '</div>' + str; }
    }
    if ( obj['w'] ) { this.setTipWidth( obj['w'] ) }
    return str;
}

// several functions with option of setting width 
dw_Tooltip.setTipWidth = function(w) {
    w += dw_backCompatWidth( this.tip ); // in case padding and border set on tipDiv
    this.tip.style.width = w + "px";
}

/////////////////////////////////////////////////////////////////////
//  a few  utility functions 

function dw_getImage(src) {
    var img = new Image();
    img.src = src;
}

// To obtain padding and border for setting width on an element
function dw_backCompatWidth(el) {
    var val = 0;
    if ( el.currentStyle && !window.opera && (document.compatMode == null || document.compatMode == "BackCompat") ) {
        var p = parseInt( dw_getCurrentStyle(el, 'paddingLeft') ) + parseInt( dw_getCurrentStyle(el, 'paddingRight') );
        var b = parseInt( dw_getCurrentStyle(el, 'borderLeftWidth') ) + parseInt( dw_getCurrentStyle(el, 'borderRightWidth') )
        val = p + b;
    }
    return val;
}

// prop must be camelCase (e.g., paddingLeft, borderLeftWidth)
function dw_getCurrentStyle(el, prop) {
    var val = '';
    if (document.defaultView && document.defaultView.getComputedStyle) {
        val = document.defaultView.getComputedStyle(el, null)[prop];
    } else if (el.currentStyle) {
        val = el.currentStyle[prop];
            // from jquery, dean edwards, see http://erik.eae.net/archives/2007/07/27/18.54.15/#comment-102291
            if ( !/^\d+(px)?$/i.test(val) && /^\d/.test(val) ) {
				var style = el.style.left;
				var runtimeStyle = el.runtimeStyle.left;
				el.runtimeStyle.left = el.currentStyle.left;
				el.style.left = val || 0;
				val = el.style.pixelLeft + "px";
				el.style.left = style;
				el.runtimeStyle.left = runtimeStyle;
			}
    }
    return val;
}

// obj: link or window.location
function dw_getValueFromQueryString(name, obj) {
    obj = obj? obj: window.location; 
    if (obj.search && obj.search.indexOf(name != -1) ) {
        var pairs = obj.search.slice(1).split("&"); // name/value pairs
        var set;
        for (var i=0; pairs[i]; i++) {
            set = pairs[i].split("="); // Check each pair for match on name 
            if ( set[0] == name && set[1] ) {
                return set[1];
            }
        }
    }
    return '';
}
