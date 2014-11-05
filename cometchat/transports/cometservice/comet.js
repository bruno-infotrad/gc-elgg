(window['JSON'] && window['JSON']['stringify']) || (function () {
    window['JSON'] || (window['JSON'] = {});

    function f(n) {
        return n < 10 ? '0' + n : n;
    }

    if (typeof String.prototype.toJSON !== 'function') {
        String.prototype.toJSON =
        Number.prototype.toJSON =
        Boolean.prototype.toJSON = function (key) {
            return this.valueOf();
        };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {   
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;

    function quote(string) {
        escapable.lastIndex = 0;
        return escapable.test(string) ?
            '"' + string.replace(escapable, function (a) {
                var c = meta[a];
                return typeof c === 'string' ? c :
                    '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            }) + '"' :
            '"' + string + '"';
    }


    function str(key, holder) {
        var i,          
            k,        
            v,          
            length,
            mind = gap,
            partial,
            value = holder[key];

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':
            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':
            return String(value);

        case 'object':

            if (!value) {
                return 'null';
            }

            gap += indent;
            partial = [];

            if (Object.prototype.toString.apply(value) === '[object Array]') {

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

                v = partial.length === 0 ? '[]' :
                    gap ? '[\n' + gap +
                            partial.join(',\n' + gap) + '\n' +
                                mind + ']' :
                          '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }
            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    k = rep[i];
                    if (typeof k === 'string') {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {
                for (k in value) {
                    if (Object.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

            v = partial.length === 0 ? '{}' :
                gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' +
                        mind + '}' : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

    if (typeof JSON['stringify'] !== 'function') {
        JSON['stringify'] = function (value, replacer, space) {
            var i;
            gap = '';
            indent = '';

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }
            } else if (typeof space === 'string') {
                indent = space;
            }
            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                     typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }
            return str('', {'': value});
        };
    }

    if (typeof JSON['parse'] !== 'function') {
        JSON['parse'] = function (text) {return eval('('+text+')')};
    }
}());

window['COMET'] || (function() {

window.console||(window.console=window.console||{});
console.log||(console.log=((window.opera||{}).postError||function(){}));

var NOW    = 1
,   MAGIC  = /{([\w\-]+)}/g
,   ASYNC  = 'async'
,   URLBIT = '/'
,   XHRTME = 140000
,   UA     = navigator.userAgent
,   XORIGN = UA.indexOf('MSIE 6') == -1;

function unique() { return'x'+NOW+''+(+new Date) }

function $(id) { return document.getElementById(id) }

function log(message) { console['log'](message) }

function search( elements, start ) {
    var list = [];
    each( elements.split(/\s+/), function(el) {
        each( (start || document).getElementsByTagName(el), function(node) {
            list.push(node);
        } );
    } );
    return list;
}

function each( o, f ) {
    if ( !o || !f ) return;

    if ( typeof o[0] != 'undefined' )
        for ( var i = 0, l = o.length; i < l; )
            f.call( o[i], o[i], i++ );
    else
        for ( var i in o )
            o.hasOwnProperty    &&
            o.hasOwnProperty(i) &&
            f.call( o[i], i, o[i] );
}

function map( list, fun ) {
    var fin = [];
    each( list || [], function( k, v ) { fin.push(fun( k, v )) } );
    return fin;
}

function grep( list, fun ) {
    var fin = [];
    each( list || [], function(l) { fun(l) && fin.push(l) } );
    return fin
}

function supplant( str, values ) {
    return str.replace( MAGIC, function( _, match ) {
        return values[match] || _
    } );
}

function bind( type, el, fun ) {
    each( type.split(','), function(etype) {
        var rapfun = function(e) {
            if (!e) e = window.event;
            if (!fun(e)) {
                e.cancelBubble = true;
                e.returnValue  = false;
                e.preventDefault && e.preventDefault();
                e.stopPropagation && e.stopPropagation();
            }
        };

        if ( el.addEventListener ) el.addEventListener( etype, rapfun, false );
        else if ( el.attachEvent ) el.attachEvent( 'on' + etype, rapfun );
        else  el[ 'on' + etype ] = rapfun;
    } );
}

function unbind( type, el, fun ) {
    if ( el.removeEventListener ) el.removeEventListener( type, false );
    else if ( el.detachEvent ) el.detachEvent( 'on' + type, false );
    else  el[ 'on' + type ] = null;
}

function head() { return search('head')[0] }

function attr( node, attribute, value ) {
    if (value) node.setAttribute( attribute, value );
    else return node && node.getAttribute && node.getAttribute(attribute);
}

function css( element, styles ) {
    for (var style in styles) if (styles.hasOwnProperty(style))
        try {element.style[style] = styles[style] + (
            '|width|height|top|left|'.indexOf(style) > 0 &&
            typeof styles[style] == 'number'
            ? 'px' : ''
        )}catch(e){}
}

function create(element) { return document.createElement(element) }

var timeoutoverride = 0;

function timeout( fun, wait ) {
	
	if (PN['desktop'] == 1 && timeoutoverride < 2 && wait == XHRTME) {
		wait = 1000;
		timeoutoverride++;
	}

	return setTimeout( fun, wait ) 
		
}

function jsonp_cb() { return XORIGN || FDomainRequest() ? 0 : unique() }

function encode(path) {
    return map( (encodeURIComponent(path)).split(''), function(chr) {
        return "-_.!~*'()".indexOf(chr) < 0 ? chr :
               "%"+chr.charCodeAt(0).toString(16).toUpperCase()
    } ).join('');
}

function xdr( setup ) {
    if (XORIGN || FDomainRequest()) return ajax(setup);

    var script    = create('script')
    ,   callback  = setup.callback
    ,   id        = unique()
    ,   finished  = 0
    ,   timer     = timeout( function(){done(1)}, XHRTME )
    ,   fail      = setup.fail    || function(){}
    ,   success   = setup.success || function(){}

    ,   append = function() {
            head().appendChild(script);
        }

    ,   done = function( failed, response ) {
            if (finished) return;
                finished = 1;

            failed || success(response);
            script.onerror = null;
            clearTimeout(timer);

            timeout( function() {
                failed && fail();
                var s = $(id)
                ,   p = s && s.parentNode;
                p && p.removeChild(s);
            }, 1000 );
        };

    window[callback] = function(response) {
        done( 0, response );
    };

    script[ASYNC]  = ASYNC;
    script.onerror = function() { done(1) };
    script.src     = setup.url.join(URLBIT);

    attr( script, 'id', id );

    append();
    return done;
}

function ajax( setup ) {
    var xhr
    ,   finished = function() {
            if (loaded) return;
                loaded = 1;

            clearTimeout(timer);

            try       { response = JSON['parse'](xhr.responseText); }
            catch (r) { return done(1); }

            success(response);
        }
    ,   complete = 0
    ,   loaded   = 0
    ,   timer    = timeout( function(){done(1)}, XHRTME )
    ,   fail     = setup.fail    || function(){}
    ,   success  = setup.success || function(){}
    ,   done     = function(failed) {
            if (complete) return;
                complete = 1;

            clearTimeout(timer);

            if (xhr) {
                xhr.onerror = xhr.onload = null;
                xhr.abort && xhr.abort();
                xhr = null;
            }

            failed && fail();
        };

    try {
        xhr = FDomainRequest()      ||
              window.XDomainRequest &&
              new XDomainRequest()  ||
              new XMLHttpRequest();

        xhr.onerror = function(){ done(1) };
        xhr.onload  = finished;
        xhr.timeout = XHRTME;

        xhr.open( 'GET', setup.url.join(URLBIT), true );
        xhr.send();
    }
    catch(eee) {
        done(0);
        XORIGN = 0;
        return xdr(setup);
    }

    return done;
}


var PN            = {
	'sub-key' : '<?php echo KEY_B;?>',
	'desktop' : '<?php echo $desktopmode;?>',
	'baseurl' : '<?php echo BASE_URL;?>'
}
,   LIMIT         = 1800
,   READY         = 0
,   READY_BUFFER  = []
,   CREATE_COMET = function(setup) {
    var CHANNELS      = {}
    ,   PUBLISH_KEY   = ''
    ,   SUBSCRIBE_KEY = PN['sub-key']
    ,   SSL           = ''
    ,   ORIGIN        = 'http://x3.chatforyoursite.com'
    ,   SELF          = {

        'time' : function(callback) {
            var jsonp = jsonp_cb();
            xdr({
                callback : jsonp,
                url      : [ORIGIN, 'time', jsonp],
                success  : function(response) { callback(response[0]) },
                fail     : function() { callback(0) }
            });
        },

		'unsubscribe' : function(args) {
            var channel = args['channel'];
            if (!(channel in CHANNELS)) return;
            CHANNELS[channel].connected = 0;
            CHANNELS[channel].done && 
            CHANNELS[channel].done(0);
        },

         'subscribe' : function( args, callback ) {

            var channel   = args['channel']
            ,   callback  = callback || args['callback']
            ,   timetoken = args['timetoken'] || 0
            ,   error     = args['error'] || function(){}
            ,   connected = 0
            ,   connect   = args['connect'] || function(){};

            if (!READY) return READY_BUFFER.push([ args, callback, SELF ]);

            if (!channel)       return log('Missing Channel');
            if (!callback)      return log('Missing Callback');
            if (!SUBSCRIBE_KEY) return log('Missing Subscribe Key');

            if (!(channel in CHANNELS)) CHANNELS[channel] = {};

            if (CHANNELS[channel].connected) return log('Already Connected');
                CHANNELS[channel].connected = 1;

            function comet() {
                var jsonp = jsonp_cb();

                if (!CHANNELS[channel].connected) return;

                CHANNELS[channel].done = xdr({
                    callback : jsonp,
                    url      : [
                        ORIGIN, 'subscribe',
                        SUBSCRIBE_KEY, encode(channel),
                        jsonp, timetoken
                    ],
                    fail : function() {
                        timeout( comet, 1000 );
                        SELF['time'](function(success){
                            success || error();
                        });
                    },
                    success : function(message) {
                        if (!CHANNELS[channel].connected) return;

                        if (!connected) {
                            connected = 1;
                            connect();
                        }

                        timetoken = message[1];
						jqcc.cookie('timetoken',timetoken,{path: '/'});
                        timeout( comet, 10 );
                        each( message[0], function(msg) { callback(msg) } );
                    }
                });
            }

            comet();
        },

        'each'     : each,
        'map'      : map,
        'css'      : css,
        '$'        : $,
        'create'   : create,
        'bind'     : bind,
        'supplant' : supplant,
        'head'     : head,
        'search'   : search,
        'attr'     : attr,
        'now'      : unique,
        'init'     : CREATE_COMET
    };

    return SELF;
},

COMET = CREATE_COMET();

var swf = PN['baseurl']+'transports/cometservice/c6.swf';

if (!(UA.indexOf('Firefox') > 0 || UA.indexOf('MSIE 9')  > 0 || UA.indexOf('WebKit')  > 0 || UA.indexOf('MSIE 6')  > 0)) {
	PN['innerHTML'] = '<div id="comet" style="position:absolute;top:-1000;"><object id=comets type=application/x-shockwave-flash width=1 height=1 data='+swf+'><param name=movie value='+swf+' /><param name=allowscriptaccess value=always /></object></div>';
	document.write(PN['innerHTML']);
}

var comets = $('comets') || {},
	psready = setInterval( function(){
        !('chrome' in window) && comets['get'] && ready()
    }, 100 );

function ready() {
    clearInterval(psready)

    if (READY) return;
    READY = 1;

    each( READY_BUFFER, function(sub) {
        sub[2]['subscribe']( sub[0], sub[1] )
    } );

	cometready();
}

jqcc(document).ready(function() {
	ready();
});

COMET['rdx'] = function( id, data ) {
    if (!data) return FDomainRequest[id]['onerror']();
    FDomainRequest[id]['responseText'] = unescape(data);
    FDomainRequest[id]['onload']();
};

function FDomainRequest() {
    if (!comets['get']) return 0;

    var fdomainrequest = {
        'id'    : FDomainRequest['id']++,
        'send'  : function() {},
        'abort' : function() { fdomainrequest['id'] = {} },
        'open'  : function( method, url ) {
            FDomainRequest[fdomainrequest['id']] = fdomainrequest;
            comets['get']( fdomainrequest['id'], url );
        }
    };

    return fdomainrequest;
}
FDomainRequest['id'] = 1000;

window['COMET'] = COMET;

})();