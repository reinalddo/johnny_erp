/**
 * Clone Function
 * @param {Object/Array} o Object or array to clone
 * @return {Object/Array} Deep clone of an object or an array
 * @author Ing. Jozef Sakáloš
 */
Ext.ns("Ext.ux.util");
Ext.ux.util.clone = function(o) {
    if(!o || 'object' !== typeof o) {
        return o;
    }
    if('function' === typeof o.clone) {
        return o.clone();
    }
    var c = '[object Array]' === Object.prototype.toString.call(o) ? [] : {};
    var p, v;
    for(p in o) {
        if(o.hasOwnProperty(p)) {
            v = o[p];
            if(v && 'object' === typeof v) {
                c[p] = Ext.ux.util.clone(v);
            }
            else {
                c[p] = v;
            }
        }
    }
    return c;
}; // eo function clone

/**
 *
 *FROM: http://www.xenoveritas.org/blog/xeno/the-correct-way-to-clone-javascript-arrays
 */
Ext.ux.util.deepCopy = function(obj) {
  if (typeof obj == 'object') {
    if (Ext.ux.util.isArray(obj)) {
      var l = obj.length;
      var r = new Array(l);
      for (var i = 0; i < l; i++) {
        r[i] = Ext.ux.util.deepCopy(obj[i]);
      }
      return r;
    } else {
      var r = {};
      r.prototype = obj.prototype;
      for (var k in obj) {
        r[k] = Ext.ux.util.deepCopy(obj[k]);
      }
      return r;
    }
  }
  return obj;
}

Ext.ux.util.ARRAY_PROPS = {
  length: 'number',
  sort: 'function',
  slice: 'function',
  splice: 'function'
};

/**
 * Determining if something is an array in JavaScript
 * is error-prone at best.
 */
Ext.ux.util.isArray = function (obj) {
  if (obj instanceof Array)
    return true;
  // Otherwise, guess:
  for (var k in Ext.ux.util.ARRAY_PROPS) {
    if (!(k in obj && typeof obj[k] == Ext.ux.util.ARRAY_PROPS[k]))
      return false;
  }
  return true;
}

Ext.ux.util.clone_obj = function(obj) {
        if (typeof obj !== 'object' || obj === null) {
            return obj;
        }

        var c = obj instanceof Array ? [] : {};

        for (var i in obj) {
            if (obj.hasOwnProperty(i)) {
                c[i] = Ext.ux.util.clone_obj(obj[i]);
            }
        }

        return c;
    }
