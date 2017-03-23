/**
 * Created by RamS-NSET on 2/7/2017.
 */
/*
 if (typeof console  != "undefined")
 if (typeof console.log != 'undefined')
 console.olog = console.log;
 else
 console.log = function() {};

 console.log = function(message) {
 console.log(message);
 $('#debugPanel').append('<p><code>' + message + '</code></p>');
 };
 console.error = console.debug = console.info =  console.log*/


function shadeColor(color, percent) {
    var f = parseInt(color.slice(1), 16), t = percent < 0 ? 0 : 255, p = percent < 0 ? percent * -1 : percent, R = f >> 16, G = f >> 8 & 0x00FF, B = f & 0x0000FF;
    return "#" + (0x1000000 + (Math.round((t - R) * p) + R) * 0x10000 + (Math.round((t - G) * p) + G) * 0x100 + (Math.round((t - B) * p) + B)).toString(16).slice(1);
}
//Utilities
var maxInArray = function (array) {
    var max;
    if (array.constructor === Array && array.length > 0) {
        max = array.reduce(function (a, b) {
            if (!isNaN(a) && !isNaN(b)) {
                return Math.max(a, b);
            } else if (!isNaN(a)) {
                return a;
            } else {
                return b;
            }
        });
    }
    return max;
}

var minInArray = function (array) {
    var min;
    if (array.constructor === Array && array.length > 0) {
        min = array.reduce(function (a, b) {
            if (!isNaN(a) && !isNaN(b)) {
                return Math.min(a, b);
            } else if (!isNaN(a)) {
                return a;
            } else {
                return b;
            }
        });
    }
    return min;
}