/*************************************************************************
    This code is from Dynamic Web Coding at dyn-web.com
    Copyright 2003-2008 by Sharon Paine 
    See Terms of Use at www.dyn-web.com/business/terms.php
    regarding conditions under which you may use this code.
    This notice must be retained in the code as is!
    
    requires dw_event.js and dw_viewport.js
*************************************************************************/

dw_Tooltip = {
    
    offX: 8,
    offY: 12,
    showDelay: 100,
    hideDelay: 100,
    hoverDelay: 500, // for hover tip
    tipID: "tipDiv",
    tip: null, shim:null, timer: 0, hoverTimer: 0, 
    active: false, actuator: null, resetFlag: false, restored: true,
    on_init: function() {}, on_activate: function() {}, on_show: function() {}, 
    on_position: function() {}, on_deactivate: function() {}, on_hide: function() {},

    init: function() {
        var _this = dw_Tooltip;
        if ( document.createElement && document.body && typeof document.body.appendChild != "undefined" ) {
            var el = document.createElement("DIV");
            el.id = _this.tipID; el.style.position = 'absolute';
            el.style.visibility = 'hidden'; el.style.zIndex = 10000;
            document.body.appendChild(el);
            _this.tip = document.getElementById( _this.tipID);
            _this.setDefaults();
            if ( _this.checkOverlaySupport() ) { _this.prepOverlay(); }
            _this.setPosition(0, 0); _this.on_init();
        }
    },
    
    setDefaults: function() { // called when props changed (resetFlag set)
        if ( !this.defaultProps ) this.defaultProps = {};
        this.followMouse = ( typeof this.defaultProps['followMouse'] == 'boolean')? this.defaultProps['followMouse']: true;
        this.sticky = this.defaultProps['sticky'] || false;
        this.hoverable = this.defaultProps['hoverable'] || false;
        this.duration = this.defaultProps['duration'] || 0;
        this.adjustVert = ( typeof this.defaultProps['adjustVert'] == 'boolean')? this.defaultProps['adjustVert']: false;
        
        if ( this.defaultProps['klass'] ) {
            this.tip.className = this.klass = this.defaultProps['klass'];
        } else {
            this.tip.className = this.klass = '';
        }
        if ( this.defaultProps['positionFn'] && typeof this.defaultProps['positionFn'] == 'function'  ) {
            this.positionFn = this.defaultProps['positionFn'];
        } else {
            this.positionFn = this.positionRelEvent;
        }
        
        if ( this.defaultProps['wrapFn'] && typeof this.defaultProps['wrapFn'] == 'function'  ) {
            this.wrapFn = this.defaultProps['wrapFn'];
        } else {
            this.wrapFn = function(str) { return str; }
        }
        
        this.coordinateOptions();
    },
    
    activate: function(e) {
        var _this = dw_Tooltip; if (!_this.tip) return;
        _this.clearTimer('timer');  _this.clearTimer('hoverTimer');
        if ( !_this.restored ) _this.handleRestore();
        dw_Viewport.getAll();  _this.getContent(e);  _this.restored = false;
        if ( !_this.tip.innerHTML ) { return; }
        _this.active = true;
        _this.handleOptions(e);  _this.positionFn(e); _this.adjust();
        _this.on_activate();
        _this.timer = setTimeout(_this.show, _this.showDelay);
    },
    
    getContent: function(e) {
        var msg = '';  var tgt = this.getActuator(e);
        var source = this.defaultProps['content_source'];

        do { // if tgt image inside link, etc
            switch (source) {
                case 'title' : msg = this.getTitleContent(tgt); break;
                case 'class_id' : msg = this.getContentViaId(tgt); break;
                case 'ajax' : 
                    if ( this.defaultProps['loadingImage'] ) {
                        msg = '<img src="' + this.defaultProps['loadingImage'] + '" alt="" />';
                    } else if ( this.defaultProps['loadingMsg'] ) {
                        msg = this.defaultProps['loadingMsg'];
                    } else {
                        msg = 'Retrieving info ...';
                    }
                // default: from content_vars - string or object (below)
                // If no default content source is specified need to check for actuator-specific properties 
            }
            
            if ( msg ) {
                msg = this.wrapFn(msg);
            } else {
                msg = this.getFromContentVars(tgt); // wrapFn invoked there
            }
            
        } while ( !msg && (tgt = tgt.parentNode) );
        
        this.tip.innerHTML = msg;
    },
    
    positionRelEvent: function(e) {
        var _this = dw_Tooltip;
        if (typeof e == 'object') { // event 
            if ( e.type == 'mouseover' || e.type == 'mousemove' ) {
                x = _this.evX = _this.getMouseEventX(e);
                y = _this.evY = _this.getMouseEventY(e);
            } else { // focus
                var pos = dw_getPageOffsets( e.target );
                x = _this.evX = pos.x + e.target.offsetWidth; y =_this.evY = pos.y
            }
            
        } else { // if called after delay
            x = _this.evX; y = _this.evY; 
        }
        var maxX = _this.getMaxX(); var maxY = _this.getMaxY(); // get width/height
        if ( _this.adjustVert ) {
            x = ( x + _this.offX > maxX )? maxX: x + _this.offX;
            y = ( y + _this.offY > maxY )? y - ( _this.height + _this.offY ): y + _this.offY;    
        } else {
            x = ( x + _this.offX > maxX )? x - ( _this.width + _this.offX ): x + _this.offX;
            y = ( y + _this.offY > maxY )? maxY: y + _this.offY;            
        }
        _this.setPosition(x, y);
    },
    
    adjust: function() {
        var _this = dw_Tooltip;
        var imgs = _this.tip.getElementsByTagName('img');
        var img = imgs.length? imgs[imgs.length - 1]: null;
        checkComplete();
        
        function checkComplete() {
            if ( !_this.active ) return;
             _this.positionFn();
            if (img && !img.complete) {
                setTimeout( checkComplete, 50);
            }
        }
    },
    
    setPosition: function(x, y) {
        this.tip.style.left = x + 'px';
        this.tip.style.top = y + 'px';
        this.setOverlay(); this.on_position();
    },

    show: function() {
        var _this = dw_Tooltip;
        _this.tip.style.visibility = 'visible';
        if ( _this.shim ) {
            _this.shim.style.visibility = 'visible';
        }
        _this.on_show();
    },

    deactivate: function(e) {
        var _this = dw_Tooltip; if (!_this.tip) return;
        _this.clearTimer('timer');  _this.clearTimer('hoverTimer');
        if ( _this.sticky ) { return; } 
        
        if ( _this.hoverable ) { // delayed call to hide (time to check if hovered over tip)
            _this.hoverTimer = setTimeout( _this.hide, _this.hoverDelay );
            return;
        }
        if ( _this.duration ) {
            _this.timer = setTimeout( _this.hide, _this.duration );
            return;
        }
        _this.on_deactivate();
        _this.timer = setTimeout( _this.hide, _this.hideDelay );
    },
    
    hide: function() {
        var _this = dw_Tooltip; if (!_this.tip) return;
        _this.tip.style.visibility = 'hidden';
        if ( _this.shim ) { _this.shim.style.visibility = 'hidden'; }
        _this.handleRestore();  _this.on_hide();
    },
    
/////////////////////////////////////////////////////////////////////

    handleOptions: function(e) {
        this.coordinateOptions();
        if ( this.klass ) { this.tip.className = this.klass; }
        if ( this.hoverable ) {
            this.tip.onmouseout = dw_Tooltip.tipOutCheck;
            this.tip.onmouseover = function() { dw_Tooltip.clearTimer('hoverTimer'); }
        }
        if ( window.opera ) this.handleOperaHref(e.target);
        
        if ( this.followMouse && !this.hoverable && !(e.type == 'focus') ) {
            dw_Event.add(document, 'mousemove', this.positionRelEvent);
        }
        
        if ( this.sticky || this.duration ) {
            dw_Event.add( document, "mouseup", dw_Tooltip.checkHide );
        }
    },
    
    coordinateOptions: function() {
        if ( this.sticky || this.hoverable || this.duration ) { this.followMouse = false; }
        if ( this.sticky ) { this.hoverable = false; this.duration = 0; }
        if ( this.hoverable ) { this.duration = 0; }
        if ( this.positionFn != this.positionRelEvent ) this.followMouse = false;
    },

    handleRestore: function() {
        if ( this.followMouse ) {
            dw_Event.remove(document, 'mousemove', this.positionRelEvent);
        }
        if ( this.sticky || this.duration ) {
            dw_Event.remove( document, "mouseup",   dw_Tooltip.checkHide, false );
        }
        this.tip.onmouseover = this.tip.onmouseout = function() {}
        
        if ( this.resetFlag ) this.setDefaults(); 
        this.tip.innerHTML = ''; 
        this.active = false; this.actuator = null;
        this.tip.style.width = ''; 
        
        // restore title, href atts 
        if ( this.titleEl ) this.restoreTitle();
        // if () this.restoreOperaHref();
        
        this.restored = true;
    },
    
    // first class name is showTip, second class would point to content 
    getTipClass: function(cls) {
        if (!cls) return ''; var c = '';
        var classes = cls.split(/\s+/);
        if ( classes[0] == 'showTip' && classes[1] ) {
            c = classes[1];
        }
        return c; // return second class name or ''
    },
    
    getFromContentVars: function(target) {
        if (!this.content_vars) return '';
        var msg = '', id;
        var loc = this.defaultProps['actuatorLoc'] || 'showTip';
        switch (loc) {
            case 'showTip' :
                id = this.getTipClass(target.className);
                break;
            case 'queryString' :
                if ( this.defaultProps['queryVal'] ) {
                    id = dw_getValueFromQueryString( this.defaultProps['queryVal'], target );
                }
                break;
            case 'id' :
                id = target.id; 
                break;
         }
        if (!id) return '';
        
        if ( typeof this.content_vars[id] == 'string' ) {
            msg = this.wrapFn( this.content_vars[id] );
        } else if ( typeof this.content_vars[id] == 'object' ) {
            var obj = this.content_vars[id];
            this.checkForProps( obj ); // Check object for property settings 
            
            if ( obj['content'] ) { // if it has a content property that would contain the message 
                msg = this.wrapFn( obj['content'] );
            } else if ( obj['title'] ) { // true to signal content in title attribute 
                msg = this.wrapFn( this.getTitleContent(target) ); 
            } else if ( obj['html_id'] ) { // id of page element
                var el = document.getElementById( obj['html_id'] ); 
                if (el) {
                    msg = this.wrapFn( el.innerHTML );
                }
            } else {
                msg = this.wrapFn( obj ); // wrapFn will obtain props from obj (image, text, caption, etc)
            }
        }
        return msg;
    },
    
    // This function inspects the object for properties corresponding to those allowed in default props 
    // Other properties might be set in the object which would be used by either the positioning or wrap functions 
    checkForProps: function(obj) {
        var list = ['adjustVert', 'sticky', 'duration', 'hoverable', 'followMouse', 'klass', 'positionFn', 'wrapFn'];
        for (var i=0; list[i]; i++) {
            if ( typeof obj[ list[i] ] != 'undefined' ) {
                this[ list[i] ] = obj[ list[i] ];
                //alert(this[ list[i] ])
                this.resetFlag = true;
            }
        }
    },

    getTitleContent: function(target) {
        var msg = target.title || '';
        if (msg) {
            this.titleContent = msg;
            this.titleEl = target;
            target.removeAttribute('title');
            target.title = ''; // for ie
        }
        return msg;
    },
    
    getContentViaId: function(target) {
        var msg = '';
        var id = this.getTipClass(target.className);
        var el = document.getElementById(id);
        if (el) {
            msg = el.innerHTML;
        }
        return msg;
    },
    
    restoreTitle: function() {
        this.titleEl.setAttribute('title', this.titleContent )
        this.titleEl = null; this.titleContent = '';
    },
    
    // later
    handleOperaHref: function(target) {
       
    },
    
    restoreOperaHref: function() {
        
    },
    
    // hover tip
    tipOutCheck: function(e) {
        e = dw_Event.DOMit(e); var _this = dw_Tooltip;
        var tip = this; // assigned to onmouseover property of tip
        // is element moused into contained by tooltip?
        var toEl = e.relatedTarget? e.relatedTarget: e.toElement;
        if ( tip != toEl && !_this.contained(toEl, tip) ) {
            _this.timer = setTimeout( _this.hide, _this.hideDelay);
        }
    },

    // returns true of oNode is contained by oCont (container)
    contained: function(oNode, oCont) {
        if (!oNode) return; // in case alt-tab away while hovering (prevent error)
        while ( oNode = oNode.parentNode ) if ( oNode == oCont ) return true;
        return false;
    },
    
/////////////////////////////////////////////////////////////////////
// for sticky, duration, and onfocus activation
    checkKey: function(e) { // check for esc key
        e = e? e: window.event;  if ( e.keyCode == 27 ) dw_Tooltip.hide();
    },

    checkHide: function(e) { 
        e = dw_Event.DOMit(e);
        // hide tooltip if you click anywhere in the document 
        // except on the tooltip, unless that click is on the tooltip's close box    
        var tip = document.getElementById(dw_Tooltip.tipID);
        if ( e.target == tip || dw_Tooltip.contained(e.target, tip) ) {
            var tgt = e.target; // can't set e.target (read-only)
            if ( tgt.tagName && tgt.tagName == "IMG" ) tgt = tgt.parentNode; 
            if ( tgt.tagName != "A" || tgt.href.indexOf("dw_Tooltip.hide") != -1 ) return;
        }
        // slight delay to avoid crossing onfocus activation and doc click hide 
        dw_Tooltip.timer = setTimeout( dw_Tooltip.hide, 50);
    },
    
    // check need for and support of iframe shim (for ie win and select lists)
    checkOverlaySupport: function() {
        if ( navigator.userAgent.indexOf("Windows") != -1 && 
            typeof document.body != "undefined" && 
            typeof document.body.insertAdjacentHTML != "undefined" && 
            !window.opera && navigator.appVersion.indexOf("MSIE 5.0") == -1 
            ) return true;
        else return false;
    }, 
    
    prepOverlay: function() {
        document.body.insertAdjacentHTML("beforeEnd", '<iframe id="tipShim" src="javascript: false" style="position:absolute; left:0; top:0; z-index:500; visibility:hidden" scrolling="no" frameborder="0"></iframe>');
        this.shim = document.getElementById('tipShim'); 
        if (this.shim && this.tip) {
            this.shim.style.width = this.tip.offsetWidth + "px";
            this.shim.style.height = this.tip.offsetHeight + "px";
        }
    },
    
    setOverlay: function() { // position and dimensions
        if ( this.shim ) {
            this.shim.style.left = this.tip.style.left;
            this.shim.style.top = this.tip.style.top;
            this.shim.style.width = this.tip.offsetWidth + "px";
            this.shim.style.height = this.tip.offsetHeight + "px";
        }
    },
    
    clearTimer: function(timer) {
        if ( dw_Tooltip[timer] ) { clearTimeout( dw_Tooltip[timer] ); dw_Tooltip[timer] = 0; }
    },
    
    // enables second call to position fn (without e)
    getActuator: function(e) {
        var tgt;
        if (e) {
            e = dw_Event.DOMit(e);
            tgt = e.target; 
            if (tgt.nodeType != 1) tgt = tgt.parentNode; // safari...
            this.actuator = tgt;
        } else {
             tgt = this.actuator;
        }
        return tgt;
    },
    
    getWidth: function() { return this.width = this.tip.offsetWidth; },
    getHeight: function() { return this.height = this.tip.offsetHeight; },
    getMaxX: function() { return dw_Viewport.width + dw_Viewport.scrollX - this.getWidth() - 1; },
    getMaxY: function() { return dw_Viewport.height + dw_Viewport.scrollY - this.getHeight() - 1; },
    getMouseEventX: function(e) { return e.pageX? e.pageX: e.clientX + dw_Viewport.scrollX; },
    getMouseEventY: function(e) { return e.pageY? e.pageY: e.clientY + dw_Viewport.scrollY; }
    
}

// Get position of element in page (treacherous cross-browser territory! Don't expect perfect results)
// can get weird results in ie
function dw_getPageOffsets(el) {
    var left = el.offsetLeft;
    var top = el.offsetTop;
    if ( el.offsetParent && el.offsetParent.clientLeft || el.offsetParent.clientTop ) {
        left += el.offsetParent.clientLeft;
        top += el.offsetParent.clientTop;
    }
    while ( el = el.offsetParent ) {
        left += el.offsetLeft;
        top += el.offsetTop;
    }
    return { x:left, y:top };
}
