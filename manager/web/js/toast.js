'use strict';

/**
 * Created by pomy on 11/23/16.
 */
(function( global ) {
    'use strict';

    var ToastConstructor = Vue.extend( {
        data    : function data() {
            return {
                shown: false
            };
        },
        template: "<div v-show=\"shown\" class=\"toast-mask\" id=\"ui-toast\">\n        <transition name=\"toast\">\n            <div v-show=\"shown\" class=\"toast\" :style=\"{color:textColor}\" :class=\"{middle:isMobile}\">\n                <i :class=\"['toast-icon',type+'-icon',{'toast-spin': type==='loading'}]\"></i>\n                <span class=\"message\" v-text=\"message\"></span>\n            </div>\n        </transition>\n    </div>",

        props   : {
            message : String,
            type    : {
                type   : String,
                default: 'info' //info/success/warn/error/loading
            },
            duration: {
                type   : Number,
                default: 1500
            }
        },
        computed: {
            textColor: function textColor() {
                switch ( this.type ) {
                case "info":
                case "loading":
                    return "#369BE9";
                case "success":
                    return "#16C294";
                case "error":
                    return "#E95471";
                case "warn":
                    return "#FA9E33";

                }
            },
            isMobile : function isMobile() {
                return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent )
                );
            }
        }
    } );

    var instance = null;

    ToastConstructor.prototype.remove = function() {
        this.shown = false;
        instance   = null;
        removeDom( this.$el );
    };

    var processProps = function processProps( props ) {
        if ( typeof props === 'string' ) {
            props = {
                message: props
            };
        }
        return props || {};
    };

    var getAnInstance = function getAnInstance() {

        if ( instance ) {
            return instance;
        }

        return new ToastConstructor( {
            el: document.createElement( 'div' )
        } );
    };

    var removeDom = function removeDom( el ) {
        if ( el.parentNode ) {
            el.parentNode.removeChild( el );
        }
    };

    var show = function show( props ) {
        instance = getAnInstance();

        clearTimeout( instance.timer );
        instance.message  = props.message || '';
        instance.duration = typeof props.duration === "number" && props.duration >= 0 ? props.duration : 1500;
        instance.type     = props.type || 'info';

        document.body.appendChild( instance.$el );

        Vue.nextTick( function() {
            instance.shown = true;
            //if duration is 0, toast will always show
            //you can invoke remove to hidden it
            if ( instance.duration ) {
                instance.timer = setTimeout( function() {
                    instance.remove();
                }, instance.duration );
            }
        } );
        return instance;
    };

    var info = function info( props ) {
        props = processProps( props );
        props = Object.assign( { type: 'info' }, props );
        return show( props );
    };

    var error = function error( props ) {
        props = processProps( props );
        props = Object.assign( { type: 'error' }, props );
        return show( props );
    };

    var warn = function warn( props ) {
        props = processProps( props );
        props = Object.assign( { type: 'warn' }, props );
        return show( props );
    };

    var success = function success( props ) {
        props = processProps( props );
        props = Object.assign( { type: 'success' }, props );
        return show( props );
    };

    var loading = function loading( props ) {
        props = processProps( props );
        props = Object.assign( { type: 'loading' }, props );
        return show( props );
    };

    global.Toast = {
        info   : info,
        error  : error,
        warn   : warn,
        success: success,
        loading: loading
    };
})( window );
