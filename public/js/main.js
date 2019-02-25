(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

},{}],2:[function(require,module,exports){
// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };

},{}],3:[function(require,module,exports){
var Vue // late bind
var map = Object.create(null)
var shimmed = false
var isBrowserify = false

/**
 * Determine compatibility and apply patch.
 *
 * @param {Function} vue
 * @param {Boolean} browserify
 */

exports.install = function (vue, browserify) {
  if (shimmed) return
  shimmed = true

  Vue = vue
  isBrowserify = browserify

  exports.compatible = !!Vue.internalDirectives
  if (!exports.compatible) {
    console.warn(
      '[HMR] vue-loader hot reload is only compatible with ' +
      'Vue.js 1.0.0+.'
    )
    return
  }

  // patch view directive
  patchView(Vue.internalDirectives.component)
  console.log('[HMR] Vue component hot reload shim applied.')
  // shim router-view if present
  var routerView = Vue.elementDirective('router-view')
  if (routerView) {
    patchView(routerView)
    console.log('[HMR] vue-router <router-view> hot reload shim applied.')
  }
}

/**
 * Shim the view directive (component or router-view).
 *
 * @param {Object} View
 */

function patchView (View) {
  var unbuild = View.unbuild
  View.unbuild = function (defer) {
    if (!this.hotUpdating) {
      var prevComponent = this.childVM && this.childVM.constructor
      removeView(prevComponent, this)
      // defer = true means we are transitioning to a new
      // Component. Register this new component to the list.
      if (defer) {
        addView(this.Component, this)
      }
    }
    // call original
    return unbuild.call(this, defer)
  }
}

/**
 * Add a component view to a Component's hot list
 *
 * @param {Function} Component
 * @param {Directive} view - view directive instance
 */

function addView (Component, view) {
  var id = Component && Component.options.hotID
  if (id) {
    if (!map[id]) {
      map[id] = {
        Component: Component,
        views: [],
        instances: []
      }
    }
    map[id].views.push(view)
  }
}

/**
 * Remove a component view from a Component's hot list
 *
 * @param {Function} Component
 * @param {Directive} view - view directive instance
 */

function removeView (Component, view) {
  var id = Component && Component.options.hotID
  if (id) {
    map[id].views.$remove(view)
  }
}

/**
 * Create a record for a hot module, which keeps track of its construcotr,
 * instnaces and views (component directives or router-views).
 *
 * @param {String} id
 * @param {Object} options
 */

exports.createRecord = function (id, options) {
  if (typeof options === 'function') {
    options = options.options
  }
  if (typeof options.el !== 'string' && typeof options.data !== 'object') {
    makeOptionsHot(id, options)
    map[id] = {
      Component: null,
      views: [],
      instances: []
    }
  }
}

/**
 * Make a Component options object hot.
 *
 * @param {String} id
 * @param {Object} options
 */

function makeOptionsHot (id, options) {
  options.hotID = id
  injectHook(options, 'created', function () {
    var record = map[id]
    if (!record.Component) {
      record.Component = this.constructor
    }
    record.instances.push(this)
  })
  injectHook(options, 'beforeDestroy', function () {
    map[id].instances.$remove(this)
  })
}

/**
 * Inject a hook to a hot reloadable component so that
 * we can keep track of it.
 *
 * @param {Object} options
 * @param {String} name
 * @param {Function} hook
 */

function injectHook (options, name, hook) {
  var existing = options[name]
  options[name] = existing
    ? Array.isArray(existing)
      ? existing.concat(hook)
      : [existing, hook]
    : [hook]
}

/**
 * Update a hot component.
 *
 * @param {String} id
 * @param {Object|null} newOptions
 * @param {String|null} newTemplate
 */

exports.update = function (id, newOptions, newTemplate) {
  var record = map[id]
  // force full-reload if an instance of the component is active but is not
  // managed by a view
  if (!record || (record.instances.length && !record.views.length)) {
    console.log('[HMR] Root or manually-mounted instance modified. Full reload may be required.')
    if (!isBrowserify) {
      window.location.reload()
    } else {
      // browserify-hmr somehow sends incomplete bundle if we reload here
      return
    }
  }
  if (!isBrowserify) {
    // browserify-hmr already logs this
    console.log('[HMR] Updating component: ' + format(id))
  }
  var Component = record.Component
  // update constructor
  if (newOptions) {
    // in case the user exports a constructor
    Component = record.Component = typeof newOptions === 'function'
      ? newOptions
      : Vue.extend(newOptions)
    makeOptionsHot(id, Component.options)
  }
  if (newTemplate) {
    Component.options.template = newTemplate
  }
  // handle recursive lookup
  if (Component.options.name) {
    Component.options.components[Component.options.name] = Component
  }
  // reset constructor cached linker
  Component.linker = null
  // reload all views
  record.views.forEach(function (view) {
    updateView(view, Component)
  })
  // flush devtools
  if (window.__VUE_DEVTOOLS_GLOBAL_HOOK__) {
    window.__VUE_DEVTOOLS_GLOBAL_HOOK__.emit('flush')
  }
}

/**
 * Update a component view instance
 *
 * @param {Directive} view
 * @param {Function} Component
 */

function updateView (view, Component) {
  if (!view._bound) {
    return
  }
  view.Component = Component
  view.hotUpdating = true
  // disable transitions
  view.vm._isCompiled = false
  // save state
  var state = extractState(view.childVM)
  // remount, make sure to disable keep-alive
  var keepAlive = view.keepAlive
  view.keepAlive = false
  view.mountComponent()
  view.keepAlive = keepAlive
  // restore state
  restoreState(view.childVM, state, true)
  // re-eanble transitions
  view.vm._isCompiled = true
  view.hotUpdating = false
}

/**
 * Extract state from a Vue instance.
 *
 * @param {Vue} vm
 * @return {Object}
 */

function extractState (vm) {
  return {
    cid: vm.constructor.cid,
    data: vm.$data,
    children: vm.$children.map(extractState)
  }
}

/**
 * Restore state to a reloaded Vue instance.
 *
 * @param {Vue} vm
 * @param {Object} state
 */

function restoreState (vm, state, isRoot) {
  var oldAsyncConfig
  if (isRoot) {
    // set Vue into sync mode during state rehydration
    oldAsyncConfig = Vue.config.async
    Vue.config.async = false
  }
  // actual restore
  if (isRoot || !vm._props) {
    vm.$data = state.data
  } else {
    Object.keys(state.data).forEach(function (key) {
      if (!vm._props[key]) {
        // for non-root, only restore non-props fields
        vm.$data[key] = state.data[key]
      }
    })
  }
  // verify child consistency
  var hasSameChildren = vm.$children.every(function (c, i) {
    return state.children[i] && state.children[i].cid === c.constructor.cid
  })
  if (hasSameChildren) {
    // rehydrate children
    vm.$children.forEach(function (c, i) {
      restoreState(c, state.children[i])
    })
  }
  if (isRoot) {
    Vue.config.async = oldAsyncConfig
  }
}

function format (id) {
  var match = id.match(/[^\/]+\.vue$/)
  return match ? match[0] : id
}

},{}],4:[function(require,module,exports){
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
  typeof define === 'function' && define.amd ? define(['exports'], factory) :
  (factory((global.infiniteScroll = global.infiniteScroll || {})));
}(this, function (exports) { 'use strict';

  var throttle = function throttle(fn, delay) {
    var now, lastExec, timer, context, args; //eslint-disable-line

    var execute = function execute() {
      fn.apply(context, args);
      lastExec = now;
    };

    return function () {
      context = this;
      args = arguments;

      now = Date.now();

      if (timer) {
        clearTimeout(timer);
        timer = null;
      }

      if (lastExec) {
        var diff = delay - (now - lastExec);
        if (diff < 0) {
          execute();
        } else {
          timer = setTimeout(function () {
            execute();
          }, diff);
        }
      } else {
        execute();
      }
    };
  };

  var getScrollTop = function getScrollTop(element) {
    if (element === window) {
      return Math.max(window.pageYOffset || 0, document.documentElement.scrollTop);
    }

    return element.scrollTop;
  };

  var getComputedStyle = document.defaultView.getComputedStyle;

  var getScrollEventTarget = function getScrollEventTarget(element) {
    var currentNode = element;
    // bugfix, see http://w3help.org/zh-cn/causes/SD9013 and http://stackoverflow.com/questions/17016740/onscroll-function-is-not-working-for-chrome
    while (currentNode && currentNode.tagName !== 'HTML' && currentNode.tagName !== 'BODY' && currentNode.nodeType === 1) {
      var overflowY = getComputedStyle(currentNode).overflowY;
      if (overflowY === 'scroll' || overflowY === 'auto') {
        return currentNode;
      }
      currentNode = currentNode.parentNode;
    }
    return window;
  };

  var getVisibleHeight = function getVisibleHeight(element) {
    if (element === window) {
      return document.documentElement.clientHeight;
    }

    return element.clientHeight;
  };

  var getElementTop = function getElementTop(element) {
    if (element === window) {
      return getScrollTop(window);
    }
    return element.getBoundingClientRect().top + getScrollTop(window);
  };

  var isAttached = function isAttached(element) {
    var currentNode = element.parentNode;
    while (currentNode) {
      if (currentNode.tagName === 'HTML') {
        return true;
      }
      if (currentNode.nodeType === 11) {
        return false;
      }
      currentNode = currentNode.parentNode;
    }
    return false;
  };

  var infiniteScroll = {
    doBind: function doBind() {
      if (this.binded) return; // eslint-disable-line
      this.binded = true;

      var directive = this;
      var element = directive.el;

      directive.scrollEventTarget = getScrollEventTarget(element);
      directive.scrollListener = throttle(directive.doCheck.bind(directive), 200);
      directive.scrollEventTarget.addEventListener('scroll', directive.scrollListener);

      var disabledExpr = element.getAttribute('infinite-scroll-disabled');
      var disabled = false;

      if (disabledExpr) {
        this.vm.$watch(disabledExpr, function (value) {
          directive.disabled = value;
          if (!value && directive.immediateCheck) {
            directive.doCheck();
          }
        });
        disabled = Boolean(directive.vm.$get(disabledExpr));
      }
      directive.disabled = disabled;

      var distanceExpr = element.getAttribute('infinite-scroll-distance');
      var distance = 0;
      if (distanceExpr) {
        distance = Number(directive.vm.$get(distanceExpr));
        if (isNaN(distance)) {
          distance = 0;
        }
      }
      directive.distance = distance;

      var immediateCheckExpr = element.getAttribute('infinite-scroll-immediate-check');
      var immediateCheck = true;
      if (immediateCheckExpr) {
        immediateCheck = Boolean(directive.vm.$get(immediateCheckExpr));
      }
      directive.immediateCheck = immediateCheck;

      if (immediateCheck) {
        directive.doCheck();
      }

      var eventName = element.getAttribute('infinite-scroll-listen-for-event');
      if (eventName) {
        directive.vm.$on(eventName, function () {
          directive.doCheck();
        });
      }
    },

    doCheck: function doCheck(force) {
      var scrollEventTarget = this.scrollEventTarget;
      var element = this.el;
      var distance = this.distance;

      if (force !== true && this.disabled) return; //eslint-disable-line
      var viewportScrollTop = getScrollTop(scrollEventTarget);
      var viewportBottom = viewportScrollTop + getVisibleHeight(scrollEventTarget);

      var shouldTrigger = false;

      if (scrollEventTarget === element) {
        shouldTrigger = scrollEventTarget.scrollHeight - viewportBottom <= distance;
      } else {
        var elementBottom = getElementTop(element) - getElementTop(scrollEventTarget) + element.offsetHeight + viewportScrollTop;

        shouldTrigger = viewportBottom + distance >= elementBottom;
      }

      if (shouldTrigger && this.expression) {
        this.vm.$get(this.expression);
      }
    },

    bind: function bind() {
      var directive = this;
      var element = this.el;

      directive.vm.$on('hook:ready', function () {
        if (isAttached(element)) {
          directive.doBind();
        }
      });

      this.bindTryCount = 0;

      var tryBind = function tryBind() {
        if (directive.bindTryCount > 10) return; //eslint-disable-line
        directive.bindTryCount++;
        if (isAttached(element)) {
          directive.doBind();
        } else {
          setTimeout(tryBind, 50);
        }
      };

      tryBind();
    },

    unbind: function unbind() {
      this.scrollEventTarget.removeEventListener('scroll', this.scrollListener);
    }
  };

  if (window.Vue) {
    window.infiniteScroll = infiniteScroll;
    Vue.use(install);
  }

  function install(Vue) {
    Vue.directive('infiniteScroll', infiniteScroll);
  }

  exports.install = install;
  exports.infiniteScroll = infiniteScroll;

}));
},{}],5:[function(require,module,exports){
/*!
 * vue-resource v1.3.5
 * https://github.com/pagekit/vue-resource
 * Released under the MIT License.
 */

'use strict';

/**
 * Promises/A+ polyfill v1.1.4 (https://github.com/bramstein/promis)
 */

var RESOLVED = 0;
var REJECTED = 1;
var PENDING  = 2;

function Promise$1(executor) {

    this.state = PENDING;
    this.value = undefined;
    this.deferred = [];

    var promise = this;

    try {
        executor(function (x) {
            promise.resolve(x);
        }, function (r) {
            promise.reject(r);
        });
    } catch (e) {
        promise.reject(e);
    }
}

Promise$1.reject = function (r) {
    return new Promise$1(function (resolve, reject) {
        reject(r);
    });
};

Promise$1.resolve = function (x) {
    return new Promise$1(function (resolve, reject) {
        resolve(x);
    });
};

Promise$1.all = function all(iterable) {
    return new Promise$1(function (resolve, reject) {
        var count = 0, result = [];

        if (iterable.length === 0) {
            resolve(result);
        }

        function resolver(i) {
            return function (x) {
                result[i] = x;
                count += 1;

                if (count === iterable.length) {
                    resolve(result);
                }
            };
        }

        for (var i = 0; i < iterable.length; i += 1) {
            Promise$1.resolve(iterable[i]).then(resolver(i), reject);
        }
    });
};

Promise$1.race = function race(iterable) {
    return new Promise$1(function (resolve, reject) {
        for (var i = 0; i < iterable.length; i += 1) {
            Promise$1.resolve(iterable[i]).then(resolve, reject);
        }
    });
};

var p$1 = Promise$1.prototype;

p$1.resolve = function resolve(x) {
    var promise = this;

    if (promise.state === PENDING) {
        if (x === promise) {
            throw new TypeError('Promise settled with itself.');
        }

        var called = false;

        try {
            var then = x && x['then'];

            if (x !== null && typeof x === 'object' && typeof then === 'function') {
                then.call(x, function (x) {
                    if (!called) {
                        promise.resolve(x);
                    }
                    called = true;

                }, function (r) {
                    if (!called) {
                        promise.reject(r);
                    }
                    called = true;
                });
                return;
            }
        } catch (e) {
            if (!called) {
                promise.reject(e);
            }
            return;
        }

        promise.state = RESOLVED;
        promise.value = x;
        promise.notify();
    }
};

p$1.reject = function reject(reason) {
    var promise = this;

    if (promise.state === PENDING) {
        if (reason === promise) {
            throw new TypeError('Promise settled with itself.');
        }

        promise.state = REJECTED;
        promise.value = reason;
        promise.notify();
    }
};

p$1.notify = function notify() {
    var promise = this;

    nextTick(function () {
        if (promise.state !== PENDING) {
            while (promise.deferred.length) {
                var deferred = promise.deferred.shift(),
                    onResolved = deferred[0],
                    onRejected = deferred[1],
                    resolve = deferred[2],
                    reject = deferred[3];

                try {
                    if (promise.state === RESOLVED) {
                        if (typeof onResolved === 'function') {
                            resolve(onResolved.call(undefined, promise.value));
                        } else {
                            resolve(promise.value);
                        }
                    } else if (promise.state === REJECTED) {
                        if (typeof onRejected === 'function') {
                            resolve(onRejected.call(undefined, promise.value));
                        } else {
                            reject(promise.value);
                        }
                    }
                } catch (e) {
                    reject(e);
                }
            }
        }
    });
};

p$1.then = function then(onResolved, onRejected) {
    var promise = this;

    return new Promise$1(function (resolve, reject) {
        promise.deferred.push([onResolved, onRejected, resolve, reject]);
        promise.notify();
    });
};

p$1.catch = function (onRejected) {
    return this.then(undefined, onRejected);
};

/**
 * Promise adapter.
 */

if (typeof Promise === 'undefined') {
    window.Promise = Promise$1;
}

function PromiseObj(executor, context) {

    if (executor instanceof Promise) {
        this.promise = executor;
    } else {
        this.promise = new Promise(executor.bind(context));
    }

    this.context = context;
}

PromiseObj.all = function (iterable, context) {
    return new PromiseObj(Promise.all(iterable), context);
};

PromiseObj.resolve = function (value, context) {
    return new PromiseObj(Promise.resolve(value), context);
};

PromiseObj.reject = function (reason, context) {
    return new PromiseObj(Promise.reject(reason), context);
};

PromiseObj.race = function (iterable, context) {
    return new PromiseObj(Promise.race(iterable), context);
};

var p = PromiseObj.prototype;

p.bind = function (context) {
    this.context = context;
    return this;
};

p.then = function (fulfilled, rejected) {

    if (fulfilled && fulfilled.bind && this.context) {
        fulfilled = fulfilled.bind(this.context);
    }

    if (rejected && rejected.bind && this.context) {
        rejected = rejected.bind(this.context);
    }

    return new PromiseObj(this.promise.then(fulfilled, rejected), this.context);
};

p.catch = function (rejected) {

    if (rejected && rejected.bind && this.context) {
        rejected = rejected.bind(this.context);
    }

    return new PromiseObj(this.promise.catch(rejected), this.context);
};

p.finally = function (callback) {

    return this.then(function (value) {
            callback.call(this);
            return value;
        }, function (reason) {
            callback.call(this);
            return Promise.reject(reason);
        }
    );
};

/**
 * Utility functions.
 */

var ref = {};
var hasOwnProperty = ref.hasOwnProperty;

var ref$1 = [];
var slice = ref$1.slice;
var debug = false;
var ntick;

var inBrowser = typeof window !== 'undefined';

function Util (ref) {
    var config = ref.config;
    var nextTick = ref.nextTick;

    ntick = nextTick;
    debug = config.debug || !config.silent;
}

function warn(msg) {
    if (typeof console !== 'undefined' && debug) {
        console.warn('[VueResource warn]: ' + msg);
    }
}

function error(msg) {
    if (typeof console !== 'undefined') {
        console.error(msg);
    }
}

function nextTick(cb, ctx) {
    return ntick(cb, ctx);
}

function trim(str) {
    return str ? str.replace(/^\s*|\s*$/g, '') : '';
}

function trimEnd(str, chars) {

    if (str && chars === undefined) {
        return str.replace(/\s+$/, '');
    }

    if (!str || !chars) {
        return str;
    }

    return str.replace(new RegExp(("[" + chars + "]+$")), '');
}

function toLower(str) {
    return str ? str.toLowerCase() : '';
}

function toUpper(str) {
    return str ? str.toUpperCase() : '';
}

var isArray = Array.isArray;

function isString(val) {
    return typeof val === 'string';
}



function isFunction(val) {
    return typeof val === 'function';
}

function isObject(obj) {
    return obj !== null && typeof obj === 'object';
}

function isPlainObject(obj) {
    return isObject(obj) && Object.getPrototypeOf(obj) == Object.prototype;
}

function isBlob(obj) {
    return typeof Blob !== 'undefined' && obj instanceof Blob;
}

function isFormData(obj) {
    return typeof FormData !== 'undefined' && obj instanceof FormData;
}

function when(value, fulfilled, rejected) {

    var promise = PromiseObj.resolve(value);

    if (arguments.length < 2) {
        return promise;
    }

    return promise.then(fulfilled, rejected);
}

function options(fn, obj, opts) {

    opts = opts || {};

    if (isFunction(opts)) {
        opts = opts.call(obj);
    }

    return merge(fn.bind({$vm: obj, $options: opts}), fn, {$options: opts});
}

function each(obj, iterator) {

    var i, key;

    if (isArray(obj)) {
        for (i = 0; i < obj.length; i++) {
            iterator.call(obj[i], obj[i], i);
        }
    } else if (isObject(obj)) {
        for (key in obj) {
            if (hasOwnProperty.call(obj, key)) {
                iterator.call(obj[key], obj[key], key);
            }
        }
    }

    return obj;
}

var assign = Object.assign || _assign;

function merge(target) {

    var args = slice.call(arguments, 1);

    args.forEach(function (source) {
        _merge(target, source, true);
    });

    return target;
}

function defaults(target) {

    var args = slice.call(arguments, 1);

    args.forEach(function (source) {

        for (var key in source) {
            if (target[key] === undefined) {
                target[key] = source[key];
            }
        }

    });

    return target;
}

function _assign(target) {

    var args = slice.call(arguments, 1);

    args.forEach(function (source) {
        _merge(target, source);
    });

    return target;
}

function _merge(target, source, deep) {
    for (var key in source) {
        if (deep && (isPlainObject(source[key]) || isArray(source[key]))) {
            if (isPlainObject(source[key]) && !isPlainObject(target[key])) {
                target[key] = {};
            }
            if (isArray(source[key]) && !isArray(target[key])) {
                target[key] = [];
            }
            _merge(target[key], source[key], deep);
        } else if (source[key] !== undefined) {
            target[key] = source[key];
        }
    }
}

/**
 * Root Prefix Transform.
 */

function root (options$$1, next) {

    var url = next(options$$1);

    if (isString(options$$1.root) && !/^(https?:)?\//.test(url)) {
        url = trimEnd(options$$1.root, '/') + '/' + url;
    }

    return url;
}

/**
 * Query Parameter Transform.
 */

function query (options$$1, next) {

    var urlParams = Object.keys(Url.options.params), query = {}, url = next(options$$1);

    each(options$$1.params, function (value, key) {
        if (urlParams.indexOf(key) === -1) {
            query[key] = value;
        }
    });

    query = Url.params(query);

    if (query) {
        url += (url.indexOf('?') == -1 ? '?' : '&') + query;
    }

    return url;
}

/**
 * URL Template v2.0.6 (https://github.com/bramstein/url-template)
 */

function expand(url, params, variables) {

    var tmpl = parse(url), expanded = tmpl.expand(params);

    if (variables) {
        variables.push.apply(variables, tmpl.vars);
    }

    return expanded;
}

function parse(template) {

    var operators = ['+', '#', '.', '/', ';', '?', '&'], variables = [];

    return {
        vars: variables,
        expand: function expand(context) {
            return template.replace(/\{([^\{\}]+)\}|([^\{\}]+)/g, function (_, expression, literal) {
                if (expression) {

                    var operator = null, values = [];

                    if (operators.indexOf(expression.charAt(0)) !== -1) {
                        operator = expression.charAt(0);
                        expression = expression.substr(1);
                    }

                    expression.split(/,/g).forEach(function (variable) {
                        var tmp = /([^:\*]*)(?::(\d+)|(\*))?/.exec(variable);
                        values.push.apply(values, getValues(context, operator, tmp[1], tmp[2] || tmp[3]));
                        variables.push(tmp[1]);
                    });

                    if (operator && operator !== '+') {

                        var separator = ',';

                        if (operator === '?') {
                            separator = '&';
                        } else if (operator !== '#') {
                            separator = operator;
                        }

                        return (values.length !== 0 ? operator : '') + values.join(separator);
                    } else {
                        return values.join(',');
                    }

                } else {
                    return encodeReserved(literal);
                }
            });
        }
    };
}

function getValues(context, operator, key, modifier) {

    var value = context[key], result = [];

    if (isDefined(value) && value !== '') {
        if (typeof value === 'string' || typeof value === 'number' || typeof value === 'boolean') {
            value = value.toString();

            if (modifier && modifier !== '*') {
                value = value.substring(0, parseInt(modifier, 10));
            }

            result.push(encodeValue(operator, value, isKeyOperator(operator) ? key : null));
        } else {
            if (modifier === '*') {
                if (Array.isArray(value)) {
                    value.filter(isDefined).forEach(function (value) {
                        result.push(encodeValue(operator, value, isKeyOperator(operator) ? key : null));
                    });
                } else {
                    Object.keys(value).forEach(function (k) {
                        if (isDefined(value[k])) {
                            result.push(encodeValue(operator, value[k], k));
                        }
                    });
                }
            } else {
                var tmp = [];

                if (Array.isArray(value)) {
                    value.filter(isDefined).forEach(function (value) {
                        tmp.push(encodeValue(operator, value));
                    });
                } else {
                    Object.keys(value).forEach(function (k) {
                        if (isDefined(value[k])) {
                            tmp.push(encodeURIComponent(k));
                            tmp.push(encodeValue(operator, value[k].toString()));
                        }
                    });
                }

                if (isKeyOperator(operator)) {
                    result.push(encodeURIComponent(key) + '=' + tmp.join(','));
                } else if (tmp.length !== 0) {
                    result.push(tmp.join(','));
                }
            }
        }
    } else {
        if (operator === ';') {
            result.push(encodeURIComponent(key));
        } else if (value === '' && (operator === '&' || operator === '?')) {
            result.push(encodeURIComponent(key) + '=');
        } else if (value === '') {
            result.push('');
        }
    }

    return result;
}

function isDefined(value) {
    return value !== undefined && value !== null;
}

function isKeyOperator(operator) {
    return operator === ';' || operator === '&' || operator === '?';
}

function encodeValue(operator, value, key) {

    value = (operator === '+' || operator === '#') ? encodeReserved(value) : encodeURIComponent(value);

    if (key) {
        return encodeURIComponent(key) + '=' + value;
    } else {
        return value;
    }
}

function encodeReserved(str) {
    return str.split(/(%[0-9A-Fa-f]{2})/g).map(function (part) {
        if (!/%[0-9A-Fa-f]/.test(part)) {
            part = encodeURI(part);
        }
        return part;
    }).join('');
}

/**
 * URL Template (RFC 6570) Transform.
 */

function template (options) {

    var variables = [], url = expand(options.url, options.params, variables);

    variables.forEach(function (key) {
        delete options.params[key];
    });

    return url;
}

/**
 * Service for URL templating.
 */

function Url(url, params) {

    var self = this || {}, options$$1 = url, transform;

    if (isString(url)) {
        options$$1 = {url: url, params: params};
    }

    options$$1 = merge({}, Url.options, self.$options, options$$1);

    Url.transforms.forEach(function (handler) {

        if (isString(handler)) {
            handler = Url.transform[handler];
        }

        if (isFunction(handler)) {
            transform = factory(handler, transform, self.$vm);
        }

    });

    return transform(options$$1);
}

/**
 * Url options.
 */

Url.options = {
    url: '',
    root: null,
    params: {}
};

/**
 * Url transforms.
 */

Url.transform = {template: template, query: query, root: root};
Url.transforms = ['template', 'query', 'root'];

/**
 * Encodes a Url parameter string.
 *
 * @param {Object} obj
 */

Url.params = function (obj) {

    var params = [], escape = encodeURIComponent;

    params.add = function (key, value) {

        if (isFunction(value)) {
            value = value();
        }

        if (value === null) {
            value = '';
        }

        this.push(escape(key) + '=' + escape(value));
    };

    serialize(params, obj);

    return params.join('&').replace(/%20/g, '+');
};

/**
 * Parse a URL and return its components.
 *
 * @param {String} url
 */

Url.parse = function (url) {

    var el = document.createElement('a');

    if (document.documentMode) {
        el.href = url;
        url = el.href;
    }

    el.href = url;

    return {
        href: el.href,
        protocol: el.protocol ? el.protocol.replace(/:$/, '') : '',
        port: el.port,
        host: el.host,
        hostname: el.hostname,
        pathname: el.pathname.charAt(0) === '/' ? el.pathname : '/' + el.pathname,
        search: el.search ? el.search.replace(/^\?/, '') : '',
        hash: el.hash ? el.hash.replace(/^#/, '') : ''
    };
};

function factory(handler, next, vm) {
    return function (options$$1) {
        return handler.call(vm, options$$1, next);
    };
}

function serialize(params, obj, scope) {

    var array = isArray(obj), plain = isPlainObject(obj), hash;

    each(obj, function (value, key) {

        hash = isObject(value) || isArray(value);

        if (scope) {
            key = scope + '[' + (plain || hash ? key : '') + ']';
        }

        if (!scope && array) {
            params.add(value.name, value.value);
        } else if (hash) {
            serialize(params, value, key);
        } else {
            params.add(key, value);
        }
    });
}

/**
 * XDomain client (Internet Explorer).
 */

function xdrClient (request) {
    return new PromiseObj(function (resolve) {

        var xdr = new XDomainRequest(), handler = function (ref) {
            var type = ref.type;


            var status = 0;

            if (type === 'load') {
                status = 200;
            } else if (type === 'error') {
                status = 500;
            }

            resolve(request.respondWith(xdr.responseText, {status: status}));
        };

        request.abort = function () { return xdr.abort(); };

        xdr.open(request.method, request.getUrl());

        if (request.timeout) {
            xdr.timeout = request.timeout;
        }

        xdr.onload = handler;
        xdr.onabort = handler;
        xdr.onerror = handler;
        xdr.ontimeout = handler;
        xdr.onprogress = function () {};
        xdr.send(request.getBody());
    });
}

/**
 * CORS Interceptor.
 */

var SUPPORTS_CORS = inBrowser && 'withCredentials' in new XMLHttpRequest();

function cors (request, next) {

    if (inBrowser) {

        var orgUrl = Url.parse(location.href);
        var reqUrl = Url.parse(request.getUrl());

        if (reqUrl.protocol !== orgUrl.protocol || reqUrl.host !== orgUrl.host) {

            request.crossOrigin = true;
            request.emulateHTTP = false;

            if (!SUPPORTS_CORS) {
                request.client = xdrClient;
            }
        }
    }

    next();
}

/**
 * Form data Interceptor.
 */

function form (request, next) {

    if (isFormData(request.body)) {

        request.headers.delete('Content-Type');

    } else if (isObject(request.body) && request.emulateJSON) {

        request.body = Url.params(request.body);
        request.headers.set('Content-Type', 'application/x-www-form-urlencoded');
    }

    next();
}

/**
 * JSON Interceptor.
 */

function json (request, next) {

    var type = request.headers.get('Content-Type') || '';

    if (isObject(request.body) && type.indexOf('application/json') === 0) {
        request.body = JSON.stringify(request.body);
    }

    next(function (response) {

        return response.bodyText ? when(response.text(), function (text) {

            type = response.headers.get('Content-Type') || '';

            if (type.indexOf('application/json') === 0 || isJson(text)) {

                try {
                    response.body = JSON.parse(text);
                } catch (e) {
                    response.body = null;
                }

            } else {
                response.body = text;
            }

            return response;

        }) : response;

    });
}

function isJson(str) {

    var start = str.match(/^\s*(\[|\{)/);
    var end = {'[': /]\s*$/, '{': /}\s*$/};

    return start && end[start[1]].test(str);
}

/**
 * JSONP client (Browser).
 */

function jsonpClient (request) {
    return new PromiseObj(function (resolve) {

        var name = request.jsonp || 'callback', callback = request.jsonpCallback || '_jsonp' + Math.random().toString(36).substr(2), body = null, handler, script;

        handler = function (ref) {
            var type = ref.type;


            var status = 0;

            if (type === 'load' && body !== null) {
                status = 200;
            } else if (type === 'error') {
                status = 500;
            }

            if (status && window[callback]) {
                delete window[callback];
                document.body.removeChild(script);
            }

            resolve(request.respondWith(body, {status: status}));
        };

        window[callback] = function (result) {
            body = JSON.stringify(result);
        };

        request.abort = function () {
            handler({type: 'abort'});
        };

        request.params[name] = callback;

        if (request.timeout) {
            setTimeout(request.abort, request.timeout);
        }

        script = document.createElement('script');
        script.src = request.getUrl();
        script.type = 'text/javascript';
        script.async = true;
        script.onload = handler;
        script.onerror = handler;

        document.body.appendChild(script);
    });
}

/**
 * JSONP Interceptor.
 */

function jsonp (request, next) {

    if (request.method == 'JSONP') {
        request.client = jsonpClient;
    }

    next();
}

/**
 * Before Interceptor.
 */

function before (request, next) {

    if (isFunction(request.before)) {
        request.before.call(this, request);
    }

    next();
}

/**
 * HTTP method override Interceptor.
 */

function method (request, next) {

    if (request.emulateHTTP && /^(PUT|PATCH|DELETE)$/i.test(request.method)) {
        request.headers.set('X-HTTP-Method-Override', request.method);
        request.method = 'POST';
    }

    next();
}

/**
 * Header Interceptor.
 */

function header (request, next) {

    var headers = assign({}, Http.headers.common,
        !request.crossOrigin ? Http.headers.custom : {},
        Http.headers[toLower(request.method)]
    );

    each(headers, function (value, name) {
        if (!request.headers.has(name)) {
            request.headers.set(name, value);
        }
    });

    next();
}

/**
 * XMLHttp client (Browser).
 */

function xhrClient (request) {
    return new PromiseObj(function (resolve) {

        var xhr = new XMLHttpRequest(), handler = function (event) {

            var response = request.respondWith(
                'response' in xhr ? xhr.response : xhr.responseText, {
                    status: xhr.status === 1223 ? 204 : xhr.status, // IE9 status bug
                    statusText: xhr.status === 1223 ? 'No Content' : trim(xhr.statusText)
                }
            );

            each(trim(xhr.getAllResponseHeaders()).split('\n'), function (row) {
                response.headers.append(row.slice(0, row.indexOf(':')), row.slice(row.indexOf(':') + 1));
            });

            resolve(response);
        };

        request.abort = function () { return xhr.abort(); };

        if (request.progress) {
            if (request.method === 'GET') {
                xhr.addEventListener('progress', request.progress);
            } else if (/^(POST|PUT)$/i.test(request.method)) {
                xhr.upload.addEventListener('progress', request.progress);
            }
        }

        xhr.open(request.method, request.getUrl(), true);

        if (request.timeout) {
            xhr.timeout = request.timeout;
        }

        if (request.responseType && 'responseType' in xhr) {
            xhr.responseType = request.responseType;
        }

        if (request.withCredentials || request.credentials) {
            xhr.withCredentials = true;
        }

        if (!request.crossOrigin) {
            request.headers.set('X-Requested-With', 'XMLHttpRequest');
        }

        request.headers.forEach(function (value, name) {
            xhr.setRequestHeader(name, value);
        });

        xhr.onload = handler;
        xhr.onabort = handler;
        xhr.onerror = handler;
        xhr.ontimeout = handler;
        xhr.send(request.getBody());
    });
}

/**
 * Http client (Node).
 */

function nodeClient (request) {

    var client = require('got');

    return new PromiseObj(function (resolve) {

        var url = request.getUrl();
        var body = request.getBody();
        var method = request.method;
        var headers = {}, handler;

        request.headers.forEach(function (value, name) {
            headers[name] = value;
        });

        client(url, {body: body, method: method, headers: headers}).then(handler = function (resp) {

            var response = request.respondWith(resp.body, {
                    status: resp.statusCode,
                    statusText: trim(resp.statusMessage)
                }
            );

            each(resp.headers, function (value, name) {
                response.headers.set(name, value);
            });

            resolve(response);

        }, function (error$$1) { return handler(error$$1.response); });
    });
}

/**
 * Base client.
 */

function Client (context) {

    var reqHandlers = [sendRequest], resHandlers = [], handler;

    if (!isObject(context)) {
        context = null;
    }

    function Client(request) {
        return new PromiseObj(function (resolve, reject) {

            function exec() {

                handler = reqHandlers.pop();

                if (isFunction(handler)) {
                    handler.call(context, request, next);
                } else {
                    warn(("Invalid interceptor of type " + (typeof handler) + ", must be a function"));
                    next();
                }
            }

            function next(response) {

                if (isFunction(response)) {

                    resHandlers.unshift(response);

                } else if (isObject(response)) {

                    resHandlers.forEach(function (handler) {
                        response = when(response, function (response) {
                            return handler.call(context, response) || response;
                        }, reject);
                    });

                    when(response, resolve, reject);

                    return;
                }

                exec();
            }

            exec();

        }, context);
    }

    Client.use = function (handler) {
        reqHandlers.push(handler);
    };

    return Client;
}

function sendRequest(request, resolve) {

    var client = request.client || (inBrowser ? xhrClient : nodeClient);

    resolve(client(request));
}

/**
 * HTTP Headers.
 */

var Headers = function Headers(headers) {
    var this$1 = this;


    this.map = {};

    each(headers, function (value, name) { return this$1.append(name, value); });
};

Headers.prototype.has = function has (name) {
    return getName(this.map, name) !== null;
};

Headers.prototype.get = function get (name) {

    var list = this.map[getName(this.map, name)];

    return list ? list.join() : null;
};

Headers.prototype.getAll = function getAll (name) {
    return this.map[getName(this.map, name)] || [];
};

Headers.prototype.set = function set (name, value) {
    this.map[normalizeName(getName(this.map, name) || name)] = [trim(value)];
};

Headers.prototype.append = function append (name, value){

    var list = this.map[getName(this.map, name)];

    if (list) {
        list.push(trim(value));
    } else {
        this.set(name, value);
    }
};

Headers.prototype.delete = function delete$1 (name){
    delete this.map[getName(this.map, name)];
};

Headers.prototype.deleteAll = function deleteAll (){
    this.map = {};
};

Headers.prototype.forEach = function forEach (callback, thisArg) {
        var this$1 = this;

    each(this.map, function (list, name) {
        each(list, function (value) { return callback.call(thisArg, value, name, this$1); });
    });
};

function getName(map, name) {
    return Object.keys(map).reduce(function (prev, curr) {
        return toLower(name) === toLower(curr) ? curr : prev;
    }, null);
}

function normalizeName(name) {

    if (/[^a-z0-9\-#$%&'*+.\^_`|~]/i.test(name)) {
        throw new TypeError('Invalid character in header field name');
    }

    return trim(name);
}

/**
 * HTTP Response.
 */

var Response = function Response(body, ref) {
    var url = ref.url;
    var headers = ref.headers;
    var status = ref.status;
    var statusText = ref.statusText;


    this.url = url;
    this.ok = status >= 200 && status < 300;
    this.status = status || 0;
    this.statusText = statusText || '';
    this.headers = new Headers(headers);
    this.body = body;

    if (isString(body)) {

        this.bodyText = body;

    } else if (isBlob(body)) {

        this.bodyBlob = body;

        if (isBlobText(body)) {
            this.bodyText = blobText(body);
        }
    }
};

Response.prototype.blob = function blob () {
    return when(this.bodyBlob);
};

Response.prototype.text = function text () {
    return when(this.bodyText);
};

Response.prototype.json = function json () {
    return when(this.text(), function (text) { return JSON.parse(text); });
};

Object.defineProperty(Response.prototype, 'data', {

    get: function get() {
        return this.body;
    },

    set: function set(body) {
        this.body = body;
    }

});

function blobText(body) {
    return new PromiseObj(function (resolve) {

        var reader = new FileReader();

        reader.readAsText(body);
        reader.onload = function () {
            resolve(reader.result);
        };

    });
}

function isBlobText(body) {
    return body.type.indexOf('text') === 0 || body.type.indexOf('json') !== -1;
}

/**
 * HTTP Request.
 */

var Request = function Request(options$$1) {

    this.body = null;
    this.params = {};

    assign(this, options$$1, {
        method: toUpper(options$$1.method || 'GET')
    });

    if (!(this.headers instanceof Headers)) {
        this.headers = new Headers(this.headers);
    }
};

Request.prototype.getUrl = function getUrl (){
    return Url(this);
};

Request.prototype.getBody = function getBody (){
    return this.body;
};

Request.prototype.respondWith = function respondWith (body, options$$1) {
    return new Response(body, assign(options$$1 || {}, {url: this.getUrl()}));
};

/**
 * Service for sending network requests.
 */

var COMMON_HEADERS = {'Accept': 'application/json, text/plain, */*'};
var JSON_CONTENT_TYPE = {'Content-Type': 'application/json;charset=utf-8'};

function Http(options$$1) {

    var self = this || {}, client = Client(self.$vm);

    defaults(options$$1 || {}, self.$options, Http.options);

    Http.interceptors.forEach(function (handler) {

        if (isString(handler)) {
            handler = Http.interceptor[handler];
        }

        if (isFunction(handler)) {
            client.use(handler);
        }

    });

    return client(new Request(options$$1)).then(function (response) {

        return response.ok ? response : PromiseObj.reject(response);

    }, function (response) {

        if (response instanceof Error) {
            error(response);
        }

        return PromiseObj.reject(response);
    });
}

Http.options = {};

Http.headers = {
    put: JSON_CONTENT_TYPE,
    post: JSON_CONTENT_TYPE,
    patch: JSON_CONTENT_TYPE,
    delete: JSON_CONTENT_TYPE,
    common: COMMON_HEADERS,
    custom: {}
};

Http.interceptor = {before: before, method: method, jsonp: jsonp, json: json, form: form, header: header, cors: cors};
Http.interceptors = ['before', 'method', 'jsonp', 'json', 'form', 'header', 'cors'];

['get', 'delete', 'head', 'jsonp'].forEach(function (method$$1) {

    Http[method$$1] = function (url, options$$1) {
        return this(assign(options$$1 || {}, {url: url, method: method$$1}));
    };

});

['post', 'put', 'patch'].forEach(function (method$$1) {

    Http[method$$1] = function (url, body, options$$1) {
        return this(assign(options$$1 || {}, {url: url, method: method$$1, body: body}));
    };

});

/**
 * Service for interacting with RESTful services.
 */

function Resource(url, params, actions, options$$1) {

    var self = this || {}, resource = {};

    actions = assign({},
        Resource.actions,
        actions
    );

    each(actions, function (action, name) {

        action = merge({url: url, params: assign({}, params)}, options$$1, action);

        resource[name] = function () {
            return (self.$http || Http)(opts(action, arguments));
        };
    });

    return resource;
}

function opts(action, args) {

    var options$$1 = assign({}, action), params = {}, body;

    switch (args.length) {

        case 2:

            params = args[0];
            body = args[1];

            break;

        case 1:

            if (/^(POST|PUT|PATCH)$/i.test(options$$1.method)) {
                body = args[0];
            } else {
                params = args[0];
            }

            break;

        case 0:

            break;

        default:

            throw 'Expected up to 2 arguments [params, body], got ' + args.length + ' arguments';
    }

    options$$1.body = body;
    options$$1.params = assign({}, options$$1.params, params);

    return options$$1;
}

Resource.actions = {

    get: {method: 'GET'},
    save: {method: 'POST'},
    query: {method: 'GET'},
    update: {method: 'PUT'},
    remove: {method: 'DELETE'},
    delete: {method: 'DELETE'}

};

/**
 * Install plugin.
 */

function plugin(Vue) {

    if (plugin.installed) {
        return;
    }

    Util(Vue);

    Vue.url = Url;
    Vue.http = Http;
    Vue.resource = Resource;
    Vue.Promise = PromiseObj;

    Object.defineProperties(Vue.prototype, {

        $url: {
            get: function get() {
                return options(Vue.url, this, this.$options.url);
            }
        },

        $http: {
            get: function get() {
                return options(Vue.http, this, this.$options.http);
            }
        },

        $resource: {
            get: function get() {
                return Vue.resource.bind(this);
            }
        },

        $promise: {
            get: function get() {
                var this$1 = this;

                return function (executor) { return new Vue.Promise(executor, this$1); };
            }
        }

    });
}

if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(plugin);
}

module.exports = plugin;

},{"got":1}],6:[function(require,module,exports){
/*!
 * vue-router v0.7.13
 * (c) 2016 Evan You
 * Released under the MIT License.
 */
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  global.VueRouter = factory();
}(this, function () { 'use strict';

  var babelHelpers = {};

  babelHelpers.classCallCheck = function (instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError("Cannot call a class as a function");
    }
  };
  function Target(path, matcher, delegate) {
    this.path = path;
    this.matcher = matcher;
    this.delegate = delegate;
  }

  Target.prototype = {
    to: function to(target, callback) {
      var delegate = this.delegate;

      if (delegate && delegate.willAddRoute) {
        target = delegate.willAddRoute(this.matcher.target, target);
      }

      this.matcher.add(this.path, target);

      if (callback) {
        if (callback.length === 0) {
          throw new Error("You must have an argument in the function passed to `to`");
        }
        this.matcher.addChild(this.path, target, callback, this.delegate);
      }
      return this;
    }
  };

  function Matcher(target) {
    this.routes = {};
    this.children = {};
    this.target = target;
  }

  Matcher.prototype = {
    add: function add(path, handler) {
      this.routes[path] = handler;
    },

    addChild: function addChild(path, target, callback, delegate) {
      var matcher = new Matcher(target);
      this.children[path] = matcher;

      var match = generateMatch(path, matcher, delegate);

      if (delegate && delegate.contextEntered) {
        delegate.contextEntered(target, match);
      }

      callback(match);
    }
  };

  function generateMatch(startingPath, matcher, delegate) {
    return function (path, nestedCallback) {
      var fullPath = startingPath + path;

      if (nestedCallback) {
        nestedCallback(generateMatch(fullPath, matcher, delegate));
      } else {
        return new Target(startingPath + path, matcher, delegate);
      }
    };
  }

  function addRoute(routeArray, path, handler) {
    var len = 0;
    for (var i = 0, l = routeArray.length; i < l; i++) {
      len += routeArray[i].path.length;
    }

    path = path.substr(len);
    var route = { path: path, handler: handler };
    routeArray.push(route);
  }

  function eachRoute(baseRoute, matcher, callback, binding) {
    var routes = matcher.routes;

    for (var path in routes) {
      if (routes.hasOwnProperty(path)) {
        var routeArray = baseRoute.slice();
        addRoute(routeArray, path, routes[path]);

        if (matcher.children[path]) {
          eachRoute(routeArray, matcher.children[path], callback, binding);
        } else {
          callback.call(binding, routeArray);
        }
      }
    }
  }

  function map (callback, addRouteCallback) {
    var matcher = new Matcher();

    callback(generateMatch("", matcher, this.delegate));

    eachRoute([], matcher, function (route) {
      if (addRouteCallback) {
        addRouteCallback(this, route);
      } else {
        this.add(route);
      }
    }, this);
  }

  var specials = ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'];

  var escapeRegex = new RegExp('(\\' + specials.join('|\\') + ')', 'g');

  var noWarning = false;
  function warn(msg) {
    if (!noWarning && typeof console !== 'undefined') {
      console.error('[vue-router] ' + msg);
    }
  }

  function tryDecode(uri, asComponent) {
    try {
      return asComponent ? decodeURIComponent(uri) : decodeURI(uri);
    } catch (e) {
      warn('malformed URI' + (asComponent ? ' component: ' : ': ') + uri);
    }
  }

  function isArray(test) {
    return Object.prototype.toString.call(test) === "[object Array]";
  }

  // A Segment represents a segment in the original route description.
  // Each Segment type provides an `eachChar` and `regex` method.
  //
  // The `eachChar` method invokes the callback with one or more character
  // specifications. A character specification consumes one or more input
  // characters.
  //
  // The `regex` method returns a regex fragment for the segment. If the
  // segment is a dynamic of star segment, the regex fragment also includes
  // a capture.
  //
  // A character specification contains:
  //
  // * `validChars`: a String with a list of all valid characters, or
  // * `invalidChars`: a String with a list of all invalid characters
  // * `repeat`: true if the character specification can repeat

  function StaticSegment(string) {
    this.string = string;
  }
  StaticSegment.prototype = {
    eachChar: function eachChar(callback) {
      var string = this.string,
          ch;

      for (var i = 0, l = string.length; i < l; i++) {
        ch = string.charAt(i);
        callback({ validChars: ch });
      }
    },

    regex: function regex() {
      return this.string.replace(escapeRegex, '\\$1');
    },

    generate: function generate() {
      return this.string;
    }
  };

  function DynamicSegment(name) {
    this.name = name;
  }
  DynamicSegment.prototype = {
    eachChar: function eachChar(callback) {
      callback({ invalidChars: "/", repeat: true });
    },

    regex: function regex() {
      return "([^/]+)";
    },

    generate: function generate(params) {
      var val = params[this.name];
      return val == null ? ":" + this.name : val;
    }
  };

  function StarSegment(name) {
    this.name = name;
  }
  StarSegment.prototype = {
    eachChar: function eachChar(callback) {
      callback({ invalidChars: "", repeat: true });
    },

    regex: function regex() {
      return "(.+)";
    },

    generate: function generate(params) {
      var val = params[this.name];
      return val == null ? ":" + this.name : val;
    }
  };

  function EpsilonSegment() {}
  EpsilonSegment.prototype = {
    eachChar: function eachChar() {},
    regex: function regex() {
      return "";
    },
    generate: function generate() {
      return "";
    }
  };

  function parse(route, names, specificity) {
    // normalize route as not starting with a "/". Recognition will
    // also normalize.
    if (route.charAt(0) === "/") {
      route = route.substr(1);
    }

    var segments = route.split("/"),
        results = [];

    // A routes has specificity determined by the order that its different segments
    // appear in. This system mirrors how the magnitude of numbers written as strings
    // works.
    // Consider a number written as: "abc". An example would be "200". Any other number written
    // "xyz" will be smaller than "abc" so long as `a > z`. For instance, "199" is smaller
    // then "200", even though "y" and "z" (which are both 9) are larger than "0" (the value
    // of (`b` and `c`). This is because the leading symbol, "2", is larger than the other
    // leading symbol, "1".
    // The rule is that symbols to the left carry more weight than symbols to the right
    // when a number is written out as a string. In the above strings, the leading digit
    // represents how many 100's are in the number, and it carries more weight than the middle
    // number which represents how many 10's are in the number.
    // This system of number magnitude works well for route specificity, too. A route written as
    // `a/b/c` will be more specific than `x/y/z` as long as `a` is more specific than
    // `x`, irrespective of the other parts.
    // Because of this similarity, we assign each type of segment a number value written as a
    // string. We can find the specificity of compound routes by concatenating these strings
    // together, from left to right. After we have looped through all of the segments,
    // we convert the string to a number.
    specificity.val = '';

    for (var i = 0, l = segments.length; i < l; i++) {
      var segment = segments[i],
          match;

      if (match = segment.match(/^:([^\/]+)$/)) {
        results.push(new DynamicSegment(match[1]));
        names.push(match[1]);
        specificity.val += '3';
      } else if (match = segment.match(/^\*([^\/]+)$/)) {
        results.push(new StarSegment(match[1]));
        specificity.val += '2';
        names.push(match[1]);
      } else if (segment === "") {
        results.push(new EpsilonSegment());
        specificity.val += '1';
      } else {
        results.push(new StaticSegment(segment));
        specificity.val += '4';
      }
    }

    specificity.val = +specificity.val;

    return results;
  }

  // A State has a character specification and (`charSpec`) and a list of possible
  // subsequent states (`nextStates`).
  //
  // If a State is an accepting state, it will also have several additional
  // properties:
  //
  // * `regex`: A regular expression that is used to extract parameters from paths
  //   that reached this accepting state.
  // * `handlers`: Information on how to convert the list of captures into calls
  //   to registered handlers with the specified parameters
  // * `types`: How many static, dynamic or star segments in this route. Used to
  //   decide which route to use if multiple registered routes match a path.
  //
  // Currently, State is implemented naively by looping over `nextStates` and
  // comparing a character specification against a character. A more efficient
  // implementation would use a hash of keys pointing at one or more next states.

  function State(charSpec) {
    this.charSpec = charSpec;
    this.nextStates = [];
  }

  State.prototype = {
    get: function get(charSpec) {
      var nextStates = this.nextStates;

      for (var i = 0, l = nextStates.length; i < l; i++) {
        var child = nextStates[i];

        var isEqual = child.charSpec.validChars === charSpec.validChars;
        isEqual = isEqual && child.charSpec.invalidChars === charSpec.invalidChars;

        if (isEqual) {
          return child;
        }
      }
    },

    put: function put(charSpec) {
      var state;

      // If the character specification already exists in a child of the current
      // state, just return that state.
      if (state = this.get(charSpec)) {
        return state;
      }

      // Make a new state for the character spec
      state = new State(charSpec);

      // Insert the new state as a child of the current state
      this.nextStates.push(state);

      // If this character specification repeats, insert the new state as a child
      // of itself. Note that this will not trigger an infinite loop because each
      // transition during recognition consumes a character.
      if (charSpec.repeat) {
        state.nextStates.push(state);
      }

      // Return the new state
      return state;
    },

    // Find a list of child states matching the next character
    match: function match(ch) {
      // DEBUG "Processing `" + ch + "`:"
      var nextStates = this.nextStates,
          child,
          charSpec,
          chars;

      // DEBUG "  " + debugState(this)
      var returned = [];

      for (var i = 0, l = nextStates.length; i < l; i++) {
        child = nextStates[i];

        charSpec = child.charSpec;

        if (typeof (chars = charSpec.validChars) !== 'undefined') {
          if (chars.indexOf(ch) !== -1) {
            returned.push(child);
          }
        } else if (typeof (chars = charSpec.invalidChars) !== 'undefined') {
          if (chars.indexOf(ch) === -1) {
            returned.push(child);
          }
        }
      }

      return returned;
    }

    /** IF DEBUG
    , debug: function() {
      var charSpec = this.charSpec,
          debug = "[",
          chars = charSpec.validChars || charSpec.invalidChars;
       if (charSpec.invalidChars) { debug += "^"; }
      debug += chars;
      debug += "]";
       if (charSpec.repeat) { debug += "+"; }
       return debug;
    }
    END IF **/
  };

  /** IF DEBUG
  function debug(log) {
    console.log(log);
  }

  function debugState(state) {
    return state.nextStates.map(function(n) {
      if (n.nextStates.length === 0) { return "( " + n.debug() + " [accepting] )"; }
      return "( " + n.debug() + " <then> " + n.nextStates.map(function(s) { return s.debug() }).join(" or ") + " )";
    }).join(", ")
  }
  END IF **/

  // Sort the routes by specificity
  function sortSolutions(states) {
    return states.sort(function (a, b) {
      return b.specificity.val - a.specificity.val;
    });
  }

  function recognizeChar(states, ch) {
    var nextStates = [];

    for (var i = 0, l = states.length; i < l; i++) {
      var state = states[i];

      nextStates = nextStates.concat(state.match(ch));
    }

    return nextStates;
  }

  var oCreate = Object.create || function (proto) {
    function F() {}
    F.prototype = proto;
    return new F();
  };

  function RecognizeResults(queryParams) {
    this.queryParams = queryParams || {};
  }
  RecognizeResults.prototype = oCreate({
    splice: Array.prototype.splice,
    slice: Array.prototype.slice,
    push: Array.prototype.push,
    length: 0,
    queryParams: null
  });

  function findHandler(state, path, queryParams) {
    var handlers = state.handlers,
        regex = state.regex;
    var captures = path.match(regex),
        currentCapture = 1;
    var result = new RecognizeResults(queryParams);

    for (var i = 0, l = handlers.length; i < l; i++) {
      var handler = handlers[i],
          names = handler.names,
          params = {};

      for (var j = 0, m = names.length; j < m; j++) {
        params[names[j]] = captures[currentCapture++];
      }

      result.push({ handler: handler.handler, params: params, isDynamic: !!names.length });
    }

    return result;
  }

  function addSegment(currentState, segment) {
    segment.eachChar(function (ch) {
      var state;

      currentState = currentState.put(ch);
    });

    return currentState;
  }

  function decodeQueryParamPart(part) {
    // http://www.w3.org/TR/html401/interact/forms.html#h-17.13.4.1
    part = part.replace(/\+/gm, '%20');
    return tryDecode(part, true);
  }

  // The main interface

  var RouteRecognizer = function RouteRecognizer() {
    this.rootState = new State();
    this.names = {};
  };

  RouteRecognizer.prototype = {
    add: function add(routes, options) {
      var currentState = this.rootState,
          regex = "^",
          specificity = {},
          handlers = [],
          allSegments = [],
          name;

      var isEmpty = true;

      for (var i = 0, l = routes.length; i < l; i++) {
        var route = routes[i],
            names = [];

        var segments = parse(route.path, names, specificity);

        allSegments = allSegments.concat(segments);

        for (var j = 0, m = segments.length; j < m; j++) {
          var segment = segments[j];

          if (segment instanceof EpsilonSegment) {
            continue;
          }

          isEmpty = false;

          // Add a "/" for the new segment
          currentState = currentState.put({ validChars: "/" });
          regex += "/";

          // Add a representation of the segment to the NFA and regex
          currentState = addSegment(currentState, segment);
          regex += segment.regex();
        }

        var handler = { handler: route.handler, names: names };
        handlers.push(handler);
      }

      if (isEmpty) {
        currentState = currentState.put({ validChars: "/" });
        regex += "/";
      }

      currentState.handlers = handlers;
      currentState.regex = new RegExp(regex + "$");
      currentState.specificity = specificity;

      if (name = options && options.as) {
        this.names[name] = {
          segments: allSegments,
          handlers: handlers
        };
      }
    },

    handlersFor: function handlersFor(name) {
      var route = this.names[name],
          result = [];
      if (!route) {
        throw new Error("There is no route named " + name);
      }

      for (var i = 0, l = route.handlers.length; i < l; i++) {
        result.push(route.handlers[i]);
      }

      return result;
    },

    hasRoute: function hasRoute(name) {
      return !!this.names[name];
    },

    generate: function generate(name, params) {
      var route = this.names[name],
          output = "";
      if (!route) {
        throw new Error("There is no route named " + name);
      }

      var segments = route.segments;

      for (var i = 0, l = segments.length; i < l; i++) {
        var segment = segments[i];

        if (segment instanceof EpsilonSegment) {
          continue;
        }

        output += "/";
        output += segment.generate(params);
      }

      if (output.charAt(0) !== '/') {
        output = '/' + output;
      }

      if (params && params.queryParams) {
        output += this.generateQueryString(params.queryParams);
      }

      return output;
    },

    generateQueryString: function generateQueryString(params) {
      var pairs = [];
      var keys = [];
      for (var key in params) {
        if (params.hasOwnProperty(key)) {
          keys.push(key);
        }
      }
      keys.sort();
      for (var i = 0, len = keys.length; i < len; i++) {
        key = keys[i];
        var value = params[key];
        if (value == null) {
          continue;
        }
        var pair = encodeURIComponent(key);
        if (isArray(value)) {
          for (var j = 0, l = value.length; j < l; j++) {
            var arrayPair = key + '[]' + '=' + encodeURIComponent(value[j]);
            pairs.push(arrayPair);
          }
        } else {
          pair += "=" + encodeURIComponent(value);
          pairs.push(pair);
        }
      }

      if (pairs.length === 0) {
        return '';
      }

      return "?" + pairs.join("&");
    },

    parseQueryString: function parseQueryString(queryString) {
      var pairs = queryString.split("&"),
          queryParams = {};
      for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split('='),
            key = decodeQueryParamPart(pair[0]),
            keyLength = key.length,
            isArray = false,
            value;
        if (pair.length === 1) {
          value = 'true';
        } else {
          //Handle arrays
          if (keyLength > 2 && key.slice(keyLength - 2) === '[]') {
            isArray = true;
            key = key.slice(0, keyLength - 2);
            if (!queryParams[key]) {
              queryParams[key] = [];
            }
          }
          value = pair[1] ? decodeQueryParamPart(pair[1]) : '';
        }
        if (isArray) {
          queryParams[key].push(value);
        } else {
          queryParams[key] = value;
        }
      }
      return queryParams;
    },

    recognize: function recognize(path, silent) {
      noWarning = silent;
      var states = [this.rootState],
          pathLen,
          i,
          l,
          queryStart,
          queryParams = {},
          isSlashDropped = false;

      queryStart = path.indexOf('?');
      if (queryStart !== -1) {
        var queryString = path.substr(queryStart + 1, path.length);
        path = path.substr(0, queryStart);
        if (queryString) {
          queryParams = this.parseQueryString(queryString);
        }
      }

      path = tryDecode(path);
      if (!path) return;

      // DEBUG GROUP path

      if (path.charAt(0) !== "/") {
        path = "/" + path;
      }

      pathLen = path.length;
      if (pathLen > 1 && path.charAt(pathLen - 1) === "/") {
        path = path.substr(0, pathLen - 1);
        isSlashDropped = true;
      }

      for (i = 0, l = path.length; i < l; i++) {
        states = recognizeChar(states, path.charAt(i));
        if (!states.length) {
          break;
        }
      }

      // END DEBUG GROUP

      var solutions = [];
      for (i = 0, l = states.length; i < l; i++) {
        if (states[i].handlers) {
          solutions.push(states[i]);
        }
      }

      states = sortSolutions(solutions);

      var state = solutions[0];

      if (state && state.handlers) {
        // if a trailing slash was dropped and a star segment is the last segment
        // specified, put the trailing slash back
        if (isSlashDropped && state.regex.source.slice(-5) === "(.+)$") {
          path = path + "/";
        }
        return findHandler(state, path, queryParams);
      }
    }
  };

  RouteRecognizer.prototype.map = map;

  var genQuery = RouteRecognizer.prototype.generateQueryString;

  // export default for holding the Vue reference
  var exports$1 = {};
  /**
   * Warn stuff.
   *
   * @param {String} msg
   */

  function warn$1(msg) {
    /* istanbul ignore next */
    if (typeof console !== 'undefined') {
      console.error('[vue-router] ' + msg);
    }
  }

  /**
   * Resolve a relative path.
   *
   * @param {String} base
   * @param {String} relative
   * @param {Boolean} append
   * @return {String}
   */

  function resolvePath(base, relative, append) {
    var query = base.match(/(\?.*)$/);
    if (query) {
      query = query[1];
      base = base.slice(0, -query.length);
    }
    // a query!
    if (relative.charAt(0) === '?') {
      return base + relative;
    }
    var stack = base.split('/');
    // remove trailing segment if:
    // - not appending
    // - appending to trailing slash (last segment is empty)
    if (!append || !stack[stack.length - 1]) {
      stack.pop();
    }
    // resolve relative path
    var segments = relative.replace(/^\//, '').split('/');
    for (var i = 0; i < segments.length; i++) {
      var segment = segments[i];
      if (segment === '.') {
        continue;
      } else if (segment === '..') {
        stack.pop();
      } else {
        stack.push(segment);
      }
    }
    // ensure leading slash
    if (stack[0] !== '') {
      stack.unshift('');
    }
    return stack.join('/');
  }

  /**
   * Forgiving check for a promise
   *
   * @param {Object} p
   * @return {Boolean}
   */

  function isPromise(p) {
    return p && typeof p.then === 'function';
  }

  /**
   * Retrive a route config field from a component instance
   * OR a component contructor.
   *
   * @param {Function|Vue} component
   * @param {String} name
   * @return {*}
   */

  function getRouteConfig(component, name) {
    var options = component && (component.$options || component.options);
    return options && options.route && options.route[name];
  }

  /**
   * Resolve an async component factory. Have to do a dirty
   * mock here because of Vue core's internal API depends on
   * an ID check.
   *
   * @param {Object} handler
   * @param {Function} cb
   */

  var resolver = undefined;

  function resolveAsyncComponent(handler, cb) {
    if (!resolver) {
      resolver = {
        resolve: exports$1.Vue.prototype._resolveComponent,
        $options: {
          components: {
            _: handler.component
          }
        }
      };
    } else {
      resolver.$options.components._ = handler.component;
    }
    resolver.resolve('_', function (Component) {
      handler.component = Component;
      cb(Component);
    });
  }

  /**
   * Map the dynamic segments in a path to params.
   *
   * @param {String} path
   * @param {Object} params
   * @param {Object} query
   */

  function mapParams(path, params, query) {
    if (params === undefined) params = {};

    path = path.replace(/:([^\/]+)/g, function (_, key) {
      var val = params[key];
      /* istanbul ignore if */
      if (!val) {
        warn$1('param "' + key + '" not found when generating ' + 'path for "' + path + '" with params ' + JSON.stringify(params));
      }
      return val || '';
    });
    if (query) {
      path += genQuery(query);
    }
    return path;
  }

  var hashRE = /#.*$/;

  var HTML5History = (function () {
    function HTML5History(_ref) {
      var root = _ref.root;
      var onChange = _ref.onChange;
      babelHelpers.classCallCheck(this, HTML5History);

      if (root && root !== '/') {
        // make sure there's the starting slash
        if (root.charAt(0) !== '/') {
          root = '/' + root;
        }
        // remove trailing slash
        this.root = root.replace(/\/$/, '');
        this.rootRE = new RegExp('^\\' + this.root);
      } else {
        this.root = null;
      }
      this.onChange = onChange;
      // check base tag
      var baseEl = document.querySelector('base');
      this.base = baseEl && baseEl.getAttribute('href');
    }

    HTML5History.prototype.start = function start() {
      var _this = this;

      this.listener = function (e) {
        var url = location.pathname + location.search;
        if (_this.root) {
          url = url.replace(_this.rootRE, '');
        }
        _this.onChange(url, e && e.state, location.hash);
      };
      window.addEventListener('popstate', this.listener);
      this.listener();
    };

    HTML5History.prototype.stop = function stop() {
      window.removeEventListener('popstate', this.listener);
    };

    HTML5History.prototype.go = function go(path, replace, append) {
      var url = this.formatPath(path, append);
      if (replace) {
        history.replaceState({}, '', url);
      } else {
        // record scroll position by replacing current state
        history.replaceState({
          pos: {
            x: window.pageXOffset,
            y: window.pageYOffset
          }
        }, '', location.href);
        // then push new state
        history.pushState({}, '', url);
      }
      var hashMatch = path.match(hashRE);
      var hash = hashMatch && hashMatch[0];
      path = url
      // strip hash so it doesn't mess up params
      .replace(hashRE, '')
      // remove root before matching
      .replace(this.rootRE, '');
      this.onChange(path, null, hash);
    };

    HTML5History.prototype.formatPath = function formatPath(path, append) {
      return path.charAt(0) === '/'
      // absolute path
      ? this.root ? this.root + '/' + path.replace(/^\//, '') : path : resolvePath(this.base || location.pathname, path, append);
    };

    return HTML5History;
  })();

  var HashHistory = (function () {
    function HashHistory(_ref) {
      var hashbang = _ref.hashbang;
      var onChange = _ref.onChange;
      babelHelpers.classCallCheck(this, HashHistory);

      this.hashbang = hashbang;
      this.onChange = onChange;
    }

    HashHistory.prototype.start = function start() {
      var self = this;
      this.listener = function () {
        var path = location.hash;
        var raw = path.replace(/^#!?/, '');
        // always
        if (raw.charAt(0) !== '/') {
          raw = '/' + raw;
        }
        var formattedPath = self.formatPath(raw);
        if (formattedPath !== path) {
          location.replace(formattedPath);
          return;
        }
        // determine query
        // note it's possible to have queries in both the actual URL
        // and the hash fragment itself.
        var query = location.search && path.indexOf('?') > -1 ? '&' + location.search.slice(1) : location.search;
        self.onChange(path.replace(/^#!?/, '') + query);
      };
      window.addEventListener('hashchange', this.listener);
      this.listener();
    };

    HashHistory.prototype.stop = function stop() {
      window.removeEventListener('hashchange', this.listener);
    };

    HashHistory.prototype.go = function go(path, replace, append) {
      path = this.formatPath(path, append);
      if (replace) {
        location.replace(path);
      } else {
        location.hash = path;
      }
    };

    HashHistory.prototype.formatPath = function formatPath(path, append) {
      var isAbsoloute = path.charAt(0) === '/';
      var prefix = '#' + (this.hashbang ? '!' : '');
      return isAbsoloute ? prefix + path : prefix + resolvePath(location.hash.replace(/^#!?/, ''), path, append);
    };

    return HashHistory;
  })();

  var AbstractHistory = (function () {
    function AbstractHistory(_ref) {
      var onChange = _ref.onChange;
      babelHelpers.classCallCheck(this, AbstractHistory);

      this.onChange = onChange;
      this.currentPath = '/';
    }

    AbstractHistory.prototype.start = function start() {
      this.onChange('/');
    };

    AbstractHistory.prototype.stop = function stop() {
      // noop
    };

    AbstractHistory.prototype.go = function go(path, replace, append) {
      path = this.currentPath = this.formatPath(path, append);
      this.onChange(path);
    };

    AbstractHistory.prototype.formatPath = function formatPath(path, append) {
      return path.charAt(0) === '/' ? path : resolvePath(this.currentPath, path, append);
    };

    return AbstractHistory;
  })();

  /**
   * Determine the reusability of an existing router view.
   *
   * @param {Directive} view
   * @param {Object} handler
   * @param {Transition} transition
   */

  function canReuse(view, handler, transition) {
    var component = view.childVM;
    if (!component || !handler) {
      return false;
    }
    // important: check view.Component here because it may
    // have been changed in activate hook
    if (view.Component !== handler.component) {
      return false;
    }
    var canReuseFn = getRouteConfig(component, 'canReuse');
    return typeof canReuseFn === 'boolean' ? canReuseFn : canReuseFn ? canReuseFn.call(component, {
      to: transition.to,
      from: transition.from
    }) : true; // defaults to true
  }

  /**
   * Check if a component can deactivate.
   *
   * @param {Directive} view
   * @param {Transition} transition
   * @param {Function} next
   */

  function canDeactivate(view, transition, next) {
    var fromComponent = view.childVM;
    var hook = getRouteConfig(fromComponent, 'canDeactivate');
    if (!hook) {
      next();
    } else {
      transition.callHook(hook, fromComponent, next, {
        expectBoolean: true
      });
    }
  }

  /**
   * Check if a component can activate.
   *
   * @param {Object} handler
   * @param {Transition} transition
   * @param {Function} next
   */

  function canActivate(handler, transition, next) {
    resolveAsyncComponent(handler, function (Component) {
      // have to check due to async-ness
      if (transition.aborted) {
        return;
      }
      // determine if this component can be activated
      var hook = getRouteConfig(Component, 'canActivate');
      if (!hook) {
        next();
      } else {
        transition.callHook(hook, null, next, {
          expectBoolean: true
        });
      }
    });
  }

  /**
   * Call deactivate hooks for existing router-views.
   *
   * @param {Directive} view
   * @param {Transition} transition
   * @param {Function} next
   */

  function deactivate(view, transition, next) {
    var component = view.childVM;
    var hook = getRouteConfig(component, 'deactivate');
    if (!hook) {
      next();
    } else {
      transition.callHooks(hook, component, next);
    }
  }

  /**
   * Activate / switch component for a router-view.
   *
   * @param {Directive} view
   * @param {Transition} transition
   * @param {Number} depth
   * @param {Function} [cb]
   */

  function activate(view, transition, depth, cb, reuse) {
    var handler = transition.activateQueue[depth];
    if (!handler) {
      saveChildView(view);
      if (view._bound) {
        view.setComponent(null);
      }
      cb && cb();
      return;
    }

    var Component = view.Component = handler.component;
    var activateHook = getRouteConfig(Component, 'activate');
    var dataHook = getRouteConfig(Component, 'data');
    var waitForData = getRouteConfig(Component, 'waitForData');

    view.depth = depth;
    view.activated = false;

    var component = undefined;
    var loading = !!(dataHook && !waitForData);

    // "reuse" is a flag passed down when the parent view is
    // either reused via keep-alive or as a child of a kept-alive view.
    // of course we can only reuse if the current kept-alive instance
    // is of the correct type.
    reuse = reuse && view.childVM && view.childVM.constructor === Component;

    if (reuse) {
      // just reuse
      component = view.childVM;
      component.$loadingRouteData = loading;
    } else {
      saveChildView(view);

      // unbuild current component. this step also destroys
      // and removes all nested child views.
      view.unbuild(true);

      // build the new component. this will also create the
      // direct child view of the current one. it will register
      // itself as view.childView.
      component = view.build({
        _meta: {
          $loadingRouteData: loading
        },
        created: function created() {
          this._routerView = view;
        }
      });

      // handle keep-alive.
      // when a kept-alive child vm is restored, we need to
      // add its cached child views into the router's view list,
      // and also properly update current view's child view.
      if (view.keepAlive) {
        component.$loadingRouteData = loading;
        var cachedChildView = component._keepAliveRouterView;
        if (cachedChildView) {
          view.childView = cachedChildView;
          component._keepAliveRouterView = null;
        }
      }
    }

    // cleanup the component in case the transition is aborted
    // before the component is ever inserted.
    var cleanup = function cleanup() {
      component.$destroy();
    };

    // actually insert the component and trigger transition
    var insert = function insert() {
      if (reuse) {
        cb && cb();
        return;
      }
      var router = transition.router;
      if (router._rendered || router._transitionOnLoad) {
        view.transition(component);
      } else {
        // no transition on first render, manual transition
        /* istanbul ignore if */
        if (view.setCurrent) {
          // 0.12 compat
          view.setCurrent(component);
        } else {
          // 1.0
          view.childVM = component;
        }
        component.$before(view.anchor, null, false);
      }
      cb && cb();
    };

    var afterData = function afterData() {
      // activate the child view
      if (view.childView) {
        activate(view.childView, transition, depth + 1, null, reuse || view.keepAlive);
      }
      insert();
    };

    // called after activation hook is resolved
    var afterActivate = function afterActivate() {
      view.activated = true;
      if (dataHook && waitForData) {
        // wait until data loaded to insert
        loadData(component, transition, dataHook, afterData, cleanup);
      } else {
        // load data and insert at the same time
        if (dataHook) {
          loadData(component, transition, dataHook);
        }
        afterData();
      }
    };

    if (activateHook) {
      transition.callHooks(activateHook, component, afterActivate, {
        cleanup: cleanup,
        postActivate: true
      });
    } else {
      afterActivate();
    }
  }

  /**
   * Reuse a view, just reload data if necessary.
   *
   * @param {Directive} view
   * @param {Transition} transition
   */

  function reuse(view, transition) {
    var component = view.childVM;
    var dataHook = getRouteConfig(component, 'data');
    if (dataHook) {
      loadData(component, transition, dataHook);
    }
  }

  /**
   * Asynchronously load and apply data to component.
   *
   * @param {Vue} component
   * @param {Transition} transition
   * @param {Function} hook
   * @param {Function} cb
   * @param {Function} cleanup
   */

  function loadData(component, transition, hook, cb, cleanup) {
    component.$loadingRouteData = true;
    transition.callHooks(hook, component, function () {
      component.$loadingRouteData = false;
      component.$emit('route-data-loaded', component);
      cb && cb();
    }, {
      cleanup: cleanup,
      postActivate: true,
      processData: function processData(data) {
        // handle promise sugar syntax
        var promises = [];
        if (isPlainObject(data)) {
          Object.keys(data).forEach(function (key) {
            var val = data[key];
            if (isPromise(val)) {
              promises.push(val.then(function (resolvedVal) {
                component.$set(key, resolvedVal);
              }));
            } else {
              component.$set(key, val);
            }
          });
        }
        if (promises.length) {
          return promises[0].constructor.all(promises);
        }
      }
    });
  }

  /**
   * Save the child view for a kept-alive view so that
   * we can restore it when it is switched back to.
   *
   * @param {Directive} view
   */

  function saveChildView(view) {
    if (view.keepAlive && view.childVM && view.childView) {
      view.childVM._keepAliveRouterView = view.childView;
    }
    view.childView = null;
  }

  /**
   * Check plain object.
   *
   * @param {*} val
   */

  function isPlainObject(val) {
    return Object.prototype.toString.call(val) === '[object Object]';
  }

  /**
   * A RouteTransition object manages the pipeline of a
   * router-view switching process. This is also the object
   * passed into user route hooks.
   *
   * @param {Router} router
   * @param {Route} to
   * @param {Route} from
   */

  var RouteTransition = (function () {
    function RouteTransition(router, to, from) {
      babelHelpers.classCallCheck(this, RouteTransition);

      this.router = router;
      this.to = to;
      this.from = from;
      this.next = null;
      this.aborted = false;
      this.done = false;
    }

    /**
     * Abort current transition and return to previous location.
     */

    RouteTransition.prototype.abort = function abort() {
      if (!this.aborted) {
        this.aborted = true;
        // if the root path throws an error during validation
        // on initial load, it gets caught in an infinite loop.
        var abortingOnLoad = !this.from.path && this.to.path === '/';
        if (!abortingOnLoad) {
          this.router.replace(this.from.path || '/');
        }
      }
    };

    /**
     * Abort current transition and redirect to a new location.
     *
     * @param {String} path
     */

    RouteTransition.prototype.redirect = function redirect(path) {
      if (!this.aborted) {
        this.aborted = true;
        if (typeof path === 'string') {
          path = mapParams(path, this.to.params, this.to.query);
        } else {
          path.params = path.params || this.to.params;
          path.query = path.query || this.to.query;
        }
        this.router.replace(path);
      }
    };

    /**
     * A router view transition's pipeline can be described as
     * follows, assuming we are transitioning from an existing
     * <router-view> chain [Component A, Component B] to a new
     * chain [Component A, Component C]:
     *
     *  A    A
     *  | => |
     *  B    C
     *
     * 1. Reusablity phase:
     *   -> canReuse(A, A)
     *   -> canReuse(B, C)
     *   -> determine new queues:
     *      - deactivation: [B]
     *      - activation: [C]
     *
     * 2. Validation phase:
     *   -> canDeactivate(B)
     *   -> canActivate(C)
     *
     * 3. Activation phase:
     *   -> deactivate(B)
     *   -> activate(C)
     *
     * Each of these steps can be asynchronous, and any
     * step can potentially abort the transition.
     *
     * @param {Function} cb
     */

    RouteTransition.prototype.start = function start(cb) {
      var transition = this;

      // determine the queue of views to deactivate
      var deactivateQueue = [];
      var view = this.router._rootView;
      while (view) {
        deactivateQueue.unshift(view);
        view = view.childView;
      }
      var reverseDeactivateQueue = deactivateQueue.slice().reverse();

      // determine the queue of route handlers to activate
      var activateQueue = this.activateQueue = toArray(this.to.matched).map(function (match) {
        return match.handler;
      });

      // 1. Reusability phase
      var i = undefined,
          reuseQueue = undefined;
      for (i = 0; i < reverseDeactivateQueue.length; i++) {
        if (!canReuse(reverseDeactivateQueue[i], activateQueue[i], transition)) {
          break;
        }
      }
      if (i > 0) {
        reuseQueue = reverseDeactivateQueue.slice(0, i);
        deactivateQueue = reverseDeactivateQueue.slice(i).reverse();
        activateQueue = activateQueue.slice(i);
      }

      // 2. Validation phase
      transition.runQueue(deactivateQueue, canDeactivate, function () {
        transition.runQueue(activateQueue, canActivate, function () {
          transition.runQueue(deactivateQueue, deactivate, function () {
            // 3. Activation phase

            // Update router current route
            transition.router._onTransitionValidated(transition);

            // trigger reuse for all reused views
            reuseQueue && reuseQueue.forEach(function (view) {
              return reuse(view, transition);
            });

            // the root of the chain that needs to be replaced
            // is the top-most non-reusable view.
            if (deactivateQueue.length) {
              var _view = deactivateQueue[deactivateQueue.length - 1];
              var depth = reuseQueue ? reuseQueue.length : 0;
              activate(_view, transition, depth, cb);
            } else {
              cb();
            }
          });
        });
      });
    };

    /**
     * Asynchronously and sequentially apply a function to a
     * queue.
     *
     * @param {Array} queue
     * @param {Function} fn
     * @param {Function} cb
     */

    RouteTransition.prototype.runQueue = function runQueue(queue, fn, cb) {
      var transition = this;
      step(0);
      function step(index) {
        if (index >= queue.length) {
          cb();
        } else {
          fn(queue[index], transition, function () {
            step(index + 1);
          });
        }
      }
    };

    /**
     * Call a user provided route transition hook and handle
     * the response (e.g. if the user returns a promise).
     *
     * If the user neither expects an argument nor returns a
     * promise, the hook is assumed to be synchronous.
     *
     * @param {Function} hook
     * @param {*} [context]
     * @param {Function} [cb]
     * @param {Object} [options]
     *                 - {Boolean} expectBoolean
     *                 - {Boolean} postActive
     *                 - {Function} processData
     *                 - {Function} cleanup
     */

    RouteTransition.prototype.callHook = function callHook(hook, context, cb) {
      var _ref = arguments.length <= 3 || arguments[3] === undefined ? {} : arguments[3];

      var _ref$expectBoolean = _ref.expectBoolean;
      var expectBoolean = _ref$expectBoolean === undefined ? false : _ref$expectBoolean;
      var _ref$postActivate = _ref.postActivate;
      var postActivate = _ref$postActivate === undefined ? false : _ref$postActivate;
      var processData = _ref.processData;
      var cleanup = _ref.cleanup;

      var transition = this;
      var nextCalled = false;

      // abort the transition
      var abort = function abort() {
        cleanup && cleanup();
        transition.abort();
      };

      // handle errors
      var onError = function onError(err) {
        postActivate ? next() : abort();
        if (err && !transition.router._suppress) {
          warn$1('Uncaught error during transition: ');
          throw err instanceof Error ? err : new Error(err);
        }
      };

      // since promise swallows errors, we have to
      // throw it in the next tick...
      var onPromiseError = function onPromiseError(err) {
        try {
          onError(err);
        } catch (e) {
          setTimeout(function () {
            throw e;
          }, 0);
        }
      };

      // advance the transition to the next step
      var next = function next() {
        if (nextCalled) {
          warn$1('transition.next() should be called only once.');
          return;
        }
        nextCalled = true;
        if (transition.aborted) {
          cleanup && cleanup();
          return;
        }
        cb && cb();
      };

      var nextWithBoolean = function nextWithBoolean(res) {
        if (typeof res === 'boolean') {
          res ? next() : abort();
        } else if (isPromise(res)) {
          res.then(function (ok) {
            ok ? next() : abort();
          }, onPromiseError);
        } else if (!hook.length) {
          next();
        }
      };

      var nextWithData = function nextWithData(data) {
        var res = undefined;
        try {
          res = processData(data);
        } catch (err) {
          return onError(err);
        }
        if (isPromise(res)) {
          res.then(next, onPromiseError);
        } else {
          next();
        }
      };

      // expose a clone of the transition object, so that each
      // hook gets a clean copy and prevent the user from
      // messing with the internals.
      var exposed = {
        to: transition.to,
        from: transition.from,
        abort: abort,
        next: processData ? nextWithData : next,
        redirect: function redirect() {
          transition.redirect.apply(transition, arguments);
        }
      };

      // actually call the hook
      var res = undefined;
      try {
        res = hook.call(context, exposed);
      } catch (err) {
        return onError(err);
      }

      if (expectBoolean) {
        // boolean hooks
        nextWithBoolean(res);
      } else if (isPromise(res)) {
        // promise
        if (processData) {
          res.then(nextWithData, onPromiseError);
        } else {
          res.then(next, onPromiseError);
        }
      } else if (processData && isPlainOjbect(res)) {
        // data promise sugar
        nextWithData(res);
      } else if (!hook.length) {
        next();
      }
    };

    /**
     * Call a single hook or an array of async hooks in series.
     *
     * @param {Array} hooks
     * @param {*} context
     * @param {Function} cb
     * @param {Object} [options]
     */

    RouteTransition.prototype.callHooks = function callHooks(hooks, context, cb, options) {
      var _this = this;

      if (Array.isArray(hooks)) {
        this.runQueue(hooks, function (hook, _, next) {
          if (!_this.aborted) {
            _this.callHook(hook, context, next, options);
          }
        }, cb);
      } else {
        this.callHook(hooks, context, cb, options);
      }
    };

    return RouteTransition;
  })();

  function isPlainOjbect(val) {
    return Object.prototype.toString.call(val) === '[object Object]';
  }

  function toArray(val) {
    return val ? Array.prototype.slice.call(val) : [];
  }

  var internalKeysRE = /^(component|subRoutes|fullPath)$/;

  /**
   * Route Context Object
   *
   * @param {String} path
   * @param {Router} router
   */

  var Route = function Route(path, router) {
    var _this = this;

    babelHelpers.classCallCheck(this, Route);

    var matched = router._recognizer.recognize(path);
    if (matched) {
      // copy all custom fields from route configs
      [].forEach.call(matched, function (match) {
        for (var key in match.handler) {
          if (!internalKeysRE.test(key)) {
            _this[key] = match.handler[key];
          }
        }
      });
      // set query and params
      this.query = matched.queryParams;
      this.params = [].reduce.call(matched, function (prev, cur) {
        if (cur.params) {
          for (var key in cur.params) {
            prev[key] = cur.params[key];
          }
        }
        return prev;
      }, {});
    }
    // expose path and router
    this.path = path;
    // for internal use
    this.matched = matched || router._notFoundHandler;
    // internal reference to router
    Object.defineProperty(this, 'router', {
      enumerable: false,
      value: router
    });
    // Important: freeze self to prevent observation
    Object.freeze(this);
  };

  function applyOverride (Vue) {
    var _Vue$util = Vue.util;
    var extend = _Vue$util.extend;
    var isArray = _Vue$util.isArray;
    var defineReactive = _Vue$util.defineReactive;

    // override Vue's init and destroy process to keep track of router instances
    var init = Vue.prototype._init;
    Vue.prototype._init = function (options) {
      options = options || {};
      var root = options._parent || options.parent || this;
      var router = root.$router;
      var route = root.$route;
      if (router) {
        // expose router
        this.$router = router;
        router._children.push(this);
        /* istanbul ignore if */
        if (this._defineMeta) {
          // 0.12
          this._defineMeta('$route', route);
        } else {
          // 1.0
          defineReactive(this, '$route', route);
        }
      }
      init.call(this, options);
    };

    var destroy = Vue.prototype._destroy;
    Vue.prototype._destroy = function () {
      if (!this._isBeingDestroyed && this.$router) {
        this.$router._children.$remove(this);
      }
      destroy.apply(this, arguments);
    };

    // 1.0 only: enable route mixins
    var strats = Vue.config.optionMergeStrategies;
    var hooksToMergeRE = /^(data|activate|deactivate)$/;

    if (strats) {
      strats.route = function (parentVal, childVal) {
        if (!childVal) return parentVal;
        if (!parentVal) return childVal;
        var ret = {};
        extend(ret, parentVal);
        for (var key in childVal) {
          var a = ret[key];
          var b = childVal[key];
          // for data, activate and deactivate, we need to merge them into
          // arrays similar to lifecycle hooks.
          if (a && hooksToMergeRE.test(key)) {
            ret[key] = (isArray(a) ? a : [a]).concat(b);
          } else {
            ret[key] = b;
          }
        }
        return ret;
      };
    }
  }

  function View (Vue) {

    var _ = Vue.util;
    var componentDef =
    // 0.12
    Vue.directive('_component') ||
    // 1.0
    Vue.internalDirectives.component;
    // <router-view> extends the internal component directive
    var viewDef = _.extend({}, componentDef);

    // with some overrides
    _.extend(viewDef, {

      _isRouterView: true,

      bind: function bind() {
        var route = this.vm.$route;
        /* istanbul ignore if */
        if (!route) {
          warn$1('<router-view> can only be used inside a ' + 'router-enabled app.');
          return;
        }
        // force dynamic directive so v-component doesn't
        // attempt to build right now
        this._isDynamicLiteral = true;
        // finally, init by delegating to v-component
        componentDef.bind.call(this);

        // locate the parent view
        var parentView = undefined;
        var parent = this.vm;
        while (parent) {
          if (parent._routerView) {
            parentView = parent._routerView;
            break;
          }
          parent = parent.$parent;
        }
        if (parentView) {
          // register self as a child of the parent view,
          // instead of activating now. This is so that the
          // child's activate hook is called after the
          // parent's has resolved.
          this.parentView = parentView;
          parentView.childView = this;
        } else {
          // this is the root view!
          var router = route.router;
          router._rootView = this;
        }

        // handle late-rendered view
        // two possibilities:
        // 1. root view rendered after transition has been
        //    validated;
        // 2. child view rendered after parent view has been
        //    activated.
        var transition = route.router._currentTransition;
        if (!parentView && transition.done || parentView && parentView.activated) {
          var depth = parentView ? parentView.depth + 1 : 0;
          activate(this, transition, depth);
        }
      },

      unbind: function unbind() {
        if (this.parentView) {
          this.parentView.childView = null;
        }
        componentDef.unbind.call(this);
      }
    });

    Vue.elementDirective('router-view', viewDef);
  }

  var trailingSlashRE = /\/$/;
  var regexEscapeRE = /[-.*+?^${}()|[\]\/\\]/g;
  var queryStringRE = /\?.*$/;

  // install v-link, which provides navigation support for
  // HTML5 history mode
  function Link (Vue) {
    var _Vue$util = Vue.util;
    var _bind = _Vue$util.bind;
    var isObject = _Vue$util.isObject;
    var addClass = _Vue$util.addClass;
    var removeClass = _Vue$util.removeClass;

    var onPriority = Vue.directive('on').priority;
    var LINK_UPDATE = '__vue-router-link-update__';

    var activeId = 0;

    Vue.directive('link-active', {
      priority: 9999,
      bind: function bind() {
        var _this = this;

        var id = String(activeId++);
        // collect v-links contained within this element.
        // we need do this here before the parent-child relationship
        // gets messed up by terminal directives (if, for, components)
        var childLinks = this.el.querySelectorAll('[v-link]');
        for (var i = 0, l = childLinks.length; i < l; i++) {
          var link = childLinks[i];
          var existingId = link.getAttribute(LINK_UPDATE);
          var value = existingId ? existingId + ',' + id : id;
          // leave a mark on the link element which can be persisted
          // through fragment clones.
          link.setAttribute(LINK_UPDATE, value);
        }
        this.vm.$on(LINK_UPDATE, this.cb = function (link, path) {
          if (link.activeIds.indexOf(id) > -1) {
            link.updateClasses(path, _this.el);
          }
        });
      },
      unbind: function unbind() {
        this.vm.$off(LINK_UPDATE, this.cb);
      }
    });

    Vue.directive('link', {
      priority: onPriority - 2,

      bind: function bind() {
        var vm = this.vm;
        /* istanbul ignore if */
        if (!vm.$route) {
          warn$1('v-link can only be used inside a router-enabled app.');
          return;
        }
        this.router = vm.$route.router;
        // update things when the route changes
        this.unwatch = vm.$watch('$route', _bind(this.onRouteUpdate, this));
        // check v-link-active ids
        var activeIds = this.el.getAttribute(LINK_UPDATE);
        if (activeIds) {
          this.el.removeAttribute(LINK_UPDATE);
          this.activeIds = activeIds.split(',');
        }
        // no need to handle click if link expects to be opened
        // in a new window/tab.
        /* istanbul ignore if */
        if (this.el.tagName === 'A' && this.el.getAttribute('target') === '_blank') {
          return;
        }
        // handle click
        this.handler = _bind(this.onClick, this);
        this.el.addEventListener('click', this.handler);
      },

      update: function update(target) {
        this.target = target;
        if (isObject(target)) {
          this.append = target.append;
          this.exact = target.exact;
          this.prevActiveClass = this.activeClass;
          this.activeClass = target.activeClass;
        }
        this.onRouteUpdate(this.vm.$route);
      },

      onClick: function onClick(e) {
        // don't redirect with control keys
        /* istanbul ignore if */
        if (e.metaKey || e.ctrlKey || e.shiftKey) return;
        // don't redirect when preventDefault called
        /* istanbul ignore if */
        if (e.defaultPrevented) return;
        // don't redirect on right click
        /* istanbul ignore if */
        if (e.button !== 0) return;

        var target = this.target;
        if (target) {
          // v-link with expression, just go
          e.preventDefault();
          this.router.go(target);
        } else {
          // no expression, delegate for an <a> inside
          var el = e.target;
          while (el.tagName !== 'A' && el !== this.el) {
            el = el.parentNode;
          }
          if (el.tagName === 'A' && sameOrigin(el)) {
            e.preventDefault();
            var path = el.pathname;
            if (this.router.history.root) {
              path = path.replace(this.router.history.rootRE, '');
            }
            this.router.go({
              path: path,
              replace: target && target.replace,
              append: target && target.append
            });
          }
        }
      },

      onRouteUpdate: function onRouteUpdate(route) {
        // router.stringifyPath is dependent on current route
        // and needs to be called again whenver route changes.
        var newPath = this.router.stringifyPath(this.target);
        if (this.path !== newPath) {
          this.path = newPath;
          this.updateActiveMatch();
          this.updateHref();
        }
        if (this.activeIds) {
          this.vm.$emit(LINK_UPDATE, this, route.path);
        } else {
          this.updateClasses(route.path, this.el);
        }
      },

      updateActiveMatch: function updateActiveMatch() {
        this.activeRE = this.path && !this.exact ? new RegExp('^' + this.path.replace(/\/$/, '').replace(queryStringRE, '').replace(regexEscapeRE, '\\$&') + '(\\/|$)') : null;
      },

      updateHref: function updateHref() {
        if (this.el.tagName !== 'A') {
          return;
        }
        var path = this.path;
        var router = this.router;
        var isAbsolute = path.charAt(0) === '/';
        // do not format non-hash relative paths
        var href = path && (router.mode === 'hash' || isAbsolute) ? router.history.formatPath(path, this.append) : path;
        if (href) {
          this.el.href = href;
        } else {
          this.el.removeAttribute('href');
        }
      },

      updateClasses: function updateClasses(path, el) {
        var activeClass = this.activeClass || this.router._linkActiveClass;
        // clear old class
        if (this.prevActiveClass && this.prevActiveClass !== activeClass) {
          toggleClasses(el, this.prevActiveClass, removeClass);
        }
        // remove query string before matching
        var dest = this.path.replace(queryStringRE, '');
        path = path.replace(queryStringRE, '');
        // add new class
        if (this.exact) {
          if (dest === path ||
          // also allow additional trailing slash
          dest.charAt(dest.length - 1) !== '/' && dest === path.replace(trailingSlashRE, '')) {
            toggleClasses(el, activeClass, addClass);
          } else {
            toggleClasses(el, activeClass, removeClass);
          }
        } else {
          if (this.activeRE && this.activeRE.test(path)) {
            toggleClasses(el, activeClass, addClass);
          } else {
            toggleClasses(el, activeClass, removeClass);
          }
        }
      },

      unbind: function unbind() {
        this.el.removeEventListener('click', this.handler);
        this.unwatch && this.unwatch();
      }
    });

    function sameOrigin(link) {
      return link.protocol === location.protocol && link.hostname === location.hostname && link.port === location.port;
    }

    // this function is copied from v-bind:class implementation until
    // we properly expose it...
    function toggleClasses(el, key, fn) {
      key = key.trim();
      if (key.indexOf(' ') === -1) {
        fn(el, key);
        return;
      }
      var keys = key.split(/\s+/);
      for (var i = 0, l = keys.length; i < l; i++) {
        fn(el, keys[i]);
      }
    }
  }

  var historyBackends = {
    abstract: AbstractHistory,
    hash: HashHistory,
    html5: HTML5History
  };

  // late bind during install
  var Vue = undefined;

  /**
   * Router constructor
   *
   * @param {Object} [options]
   */

  var Router = (function () {
    function Router() {
      var _this = this;

      var _ref = arguments.length <= 0 || arguments[0] === undefined ? {} : arguments[0];

      var _ref$hashbang = _ref.hashbang;
      var hashbang = _ref$hashbang === undefined ? true : _ref$hashbang;
      var _ref$abstract = _ref.abstract;
      var abstract = _ref$abstract === undefined ? false : _ref$abstract;
      var _ref$history = _ref.history;
      var history = _ref$history === undefined ? false : _ref$history;
      var _ref$saveScrollPosition = _ref.saveScrollPosition;
      var saveScrollPosition = _ref$saveScrollPosition === undefined ? false : _ref$saveScrollPosition;
      var _ref$transitionOnLoad = _ref.transitionOnLoad;
      var transitionOnLoad = _ref$transitionOnLoad === undefined ? false : _ref$transitionOnLoad;
      var _ref$suppressTransitionError = _ref.suppressTransitionError;
      var suppressTransitionError = _ref$suppressTransitionError === undefined ? false : _ref$suppressTransitionError;
      var _ref$root = _ref.root;
      var root = _ref$root === undefined ? null : _ref$root;
      var _ref$linkActiveClass = _ref.linkActiveClass;
      var linkActiveClass = _ref$linkActiveClass === undefined ? 'v-link-active' : _ref$linkActiveClass;
      babelHelpers.classCallCheck(this, Router);

      /* istanbul ignore if */
      if (!Router.installed) {
        throw new Error('Please install the Router with Vue.use() before ' + 'creating an instance.');
      }

      // Vue instances
      this.app = null;
      this._children = [];

      // route recognizer
      this._recognizer = new RouteRecognizer();
      this._guardRecognizer = new RouteRecognizer();

      // state
      this._started = false;
      this._startCb = null;
      this._currentRoute = {};
      this._currentTransition = null;
      this._previousTransition = null;
      this._notFoundHandler = null;
      this._notFoundRedirect = null;
      this._beforeEachHooks = [];
      this._afterEachHooks = [];

      // trigger transition on initial render?
      this._rendered = false;
      this._transitionOnLoad = transitionOnLoad;

      // history mode
      this._root = root;
      this._abstract = abstract;
      this._hashbang = hashbang;

      // check if HTML5 history is available
      var hasPushState = typeof window !== 'undefined' && window.history && window.history.pushState;
      this._history = history && hasPushState;
      this._historyFallback = history && !hasPushState;

      // create history object
      var inBrowser = Vue.util.inBrowser;
      this.mode = !inBrowser || this._abstract ? 'abstract' : this._history ? 'html5' : 'hash';

      var History = historyBackends[this.mode];
      this.history = new History({
        root: root,
        hashbang: this._hashbang,
        onChange: function onChange(path, state, anchor) {
          _this._match(path, state, anchor);
        }
      });

      // other options
      this._saveScrollPosition = saveScrollPosition;
      this._linkActiveClass = linkActiveClass;
      this._suppress = suppressTransitionError;
    }

    /**
     * Allow directly passing components to a route
     * definition.
     *
     * @param {String} path
     * @param {Object} handler
     */

    // API ===================================================

    /**
    * Register a map of top-level paths.
    *
    * @param {Object} map
    */

    Router.prototype.map = function map(_map) {
      for (var route in _map) {
        this.on(route, _map[route]);
      }
      return this;
    };

    /**
     * Register a single root-level path
     *
     * @param {String} rootPath
     * @param {Object} handler
     *                 - {String} component
     *                 - {Object} [subRoutes]
     *                 - {Boolean} [forceRefresh]
     *                 - {Function} [before]
     *                 - {Function} [after]
     */

    Router.prototype.on = function on(rootPath, handler) {
      if (rootPath === '*') {
        this._notFound(handler);
      } else {
        this._addRoute(rootPath, handler, []);
      }
      return this;
    };

    /**
     * Set redirects.
     *
     * @param {Object} map
     */

    Router.prototype.redirect = function redirect(map) {
      for (var path in map) {
        this._addRedirect(path, map[path]);
      }
      return this;
    };

    /**
     * Set aliases.
     *
     * @param {Object} map
     */

    Router.prototype.alias = function alias(map) {
      for (var path in map) {
        this._addAlias(path, map[path]);
      }
      return this;
    };

    /**
     * Set global before hook.
     *
     * @param {Function} fn
     */

    Router.prototype.beforeEach = function beforeEach(fn) {
      this._beforeEachHooks.push(fn);
      return this;
    };

    /**
     * Set global after hook.
     *
     * @param {Function} fn
     */

    Router.prototype.afterEach = function afterEach(fn) {
      this._afterEachHooks.push(fn);
      return this;
    };

    /**
     * Navigate to a given path.
     * The path can be an object describing a named path in
     * the format of { name: '...', params: {}, query: {}}
     * The path is assumed to be already decoded, and will
     * be resolved against root (if provided)
     *
     * @param {String|Object} path
     * @param {Boolean} [replace]
     */

    Router.prototype.go = function go(path) {
      var replace = false;
      var append = false;
      if (Vue.util.isObject(path)) {
        replace = path.replace;
        append = path.append;
      }
      path = this.stringifyPath(path);
      if (path) {
        this.history.go(path, replace, append);
      }
    };

    /**
     * Short hand for replacing current path
     *
     * @param {String} path
     */

    Router.prototype.replace = function replace(path) {
      if (typeof path === 'string') {
        path = { path: path };
      }
      path.replace = true;
      this.go(path);
    };

    /**
     * Start the router.
     *
     * @param {VueConstructor} App
     * @param {String|Element} container
     * @param {Function} [cb]
     */

    Router.prototype.start = function start(App, container, cb) {
      /* istanbul ignore if */
      if (this._started) {
        warn$1('already started.');
        return;
      }
      this._started = true;
      this._startCb = cb;
      if (!this.app) {
        /* istanbul ignore if */
        if (!App || !container) {
          throw new Error('Must start vue-router with a component and a ' + 'root container.');
        }
        /* istanbul ignore if */
        if (App instanceof Vue) {
          throw new Error('Must start vue-router with a component, not a ' + 'Vue instance.');
        }
        this._appContainer = container;
        var Ctor = this._appConstructor = typeof App === 'function' ? App : Vue.extend(App);
        // give it a name for better debugging
        Ctor.options.name = Ctor.options.name || 'RouterApp';
      }

      // handle history fallback in browsers that do not
      // support HTML5 history API
      if (this._historyFallback) {
        var _location = window.location;
        var _history = new HTML5History({ root: this._root });
        var path = _history.root ? _location.pathname.replace(_history.rootRE, '') : _location.pathname;
        if (path && path !== '/') {
          _location.assign((_history.root || '') + '/' + this.history.formatPath(path) + _location.search);
          return;
        }
      }

      this.history.start();
    };

    /**
     * Stop listening to route changes.
     */

    Router.prototype.stop = function stop() {
      this.history.stop();
      this._started = false;
    };

    /**
     * Normalize named route object / string paths into
     * a string.
     *
     * @param {Object|String|Number} path
     * @return {String}
     */

    Router.prototype.stringifyPath = function stringifyPath(path) {
      var generatedPath = '';
      if (path && typeof path === 'object') {
        if (path.name) {
          var extend = Vue.util.extend;
          var currentParams = this._currentTransition && this._currentTransition.to.params;
          var targetParams = path.params || {};
          var params = currentParams ? extend(extend({}, currentParams), targetParams) : targetParams;
          generatedPath = encodeURI(this._recognizer.generate(path.name, params));
        } else if (path.path) {
          generatedPath = encodeURI(path.path);
        }
        if (path.query) {
          // note: the generated query string is pre-URL-encoded by the recognizer
          var query = this._recognizer.generateQueryString(path.query);
          if (generatedPath.indexOf('?') > -1) {
            generatedPath += '&' + query.slice(1);
          } else {
            generatedPath += query;
          }
        }
      } else {
        generatedPath = encodeURI(path ? path + '' : '');
      }
      return generatedPath;
    };

    // Internal methods ======================================

    /**
    * Add a route containing a list of segments to the internal
    * route recognizer. Will be called recursively to add all
    * possible sub-routes.
    *
    * @param {String} path
    * @param {Object} handler
    * @param {Array} segments
    */

    Router.prototype._addRoute = function _addRoute(path, handler, segments) {
      guardComponent(path, handler);
      handler.path = path;
      handler.fullPath = (segments.reduce(function (path, segment) {
        return path + segment.path;
      }, '') + path).replace('//', '/');
      segments.push({
        path: path,
        handler: handler
      });
      this._recognizer.add(segments, {
        as: handler.name
      });
      // add sub routes
      if (handler.subRoutes) {
        for (var subPath in handler.subRoutes) {
          // recursively walk all sub routes
          this._addRoute(subPath, handler.subRoutes[subPath],
          // pass a copy in recursion to avoid mutating
          // across branches
          segments.slice());
        }
      }
    };

    /**
     * Set the notFound route handler.
     *
     * @param {Object} handler
     */

    Router.prototype._notFound = function _notFound(handler) {
      guardComponent('*', handler);
      this._notFoundHandler = [{ handler: handler }];
    };

    /**
     * Add a redirect record.
     *
     * @param {String} path
     * @param {String} redirectPath
     */

    Router.prototype._addRedirect = function _addRedirect(path, redirectPath) {
      if (path === '*') {
        this._notFoundRedirect = redirectPath;
      } else {
        this._addGuard(path, redirectPath, this.replace);
      }
    };

    /**
     * Add an alias record.
     *
     * @param {String} path
     * @param {String} aliasPath
     */

    Router.prototype._addAlias = function _addAlias(path, aliasPath) {
      this._addGuard(path, aliasPath, this._match);
    };

    /**
     * Add a path guard.
     *
     * @param {String} path
     * @param {String} mappedPath
     * @param {Function} handler
     */

    Router.prototype._addGuard = function _addGuard(path, mappedPath, _handler) {
      var _this2 = this;

      this._guardRecognizer.add([{
        path: path,
        handler: function handler(match, query) {
          var realPath = mapParams(mappedPath, match.params, query);
          _handler.call(_this2, realPath);
        }
      }]);
    };

    /**
     * Check if a path matches any redirect records.
     *
     * @param {String} path
     * @return {Boolean} - if true, will skip normal match.
     */

    Router.prototype._checkGuard = function _checkGuard(path) {
      var matched = this._guardRecognizer.recognize(path, true);
      if (matched) {
        matched[0].handler(matched[0], matched.queryParams);
        return true;
      } else if (this._notFoundRedirect) {
        matched = this._recognizer.recognize(path);
        if (!matched) {
          this.replace(this._notFoundRedirect);
          return true;
        }
      }
    };

    /**
     * Match a URL path and set the route context on vm,
     * triggering view updates.
     *
     * @param {String} path
     * @param {Object} [state]
     * @param {String} [anchor]
     */

    Router.prototype._match = function _match(path, state, anchor) {
      var _this3 = this;

      if (this._checkGuard(path)) {
        return;
      }

      var currentRoute = this._currentRoute;
      var currentTransition = this._currentTransition;

      if (currentTransition) {
        if (currentTransition.to.path === path) {
          // do nothing if we have an active transition going to the same path
          return;
        } else if (currentRoute.path === path) {
          // We are going to the same path, but we also have an ongoing but
          // not-yet-validated transition. Abort that transition and reset to
          // prev transition.
          currentTransition.aborted = true;
          this._currentTransition = this._prevTransition;
          return;
        } else {
          // going to a totally different path. abort ongoing transition.
          currentTransition.aborted = true;
        }
      }

      // construct new route and transition context
      var route = new Route(path, this);
      var transition = new RouteTransition(this, route, currentRoute);

      // current transition is updated right now.
      // however, current route will only be updated after the transition has
      // been validated.
      this._prevTransition = currentTransition;
      this._currentTransition = transition;

      if (!this.app) {
        (function () {
          // initial render
          var router = _this3;
          _this3.app = new _this3._appConstructor({
            el: _this3._appContainer,
            created: function created() {
              this.$router = router;
            },
            _meta: {
              $route: route
            }
          });
        })();
      }

      // check global before hook
      var beforeHooks = this._beforeEachHooks;
      var startTransition = function startTransition() {
        transition.start(function () {
          _this3._postTransition(route, state, anchor);
        });
      };

      if (beforeHooks.length) {
        transition.runQueue(beforeHooks, function (hook, _, next) {
          if (transition === _this3._currentTransition) {
            transition.callHook(hook, null, next, {
              expectBoolean: true
            });
          }
        }, startTransition);
      } else {
        startTransition();
      }

      if (!this._rendered && this._startCb) {
        this._startCb.call(null);
      }

      // HACK:
      // set rendered to true after the transition start, so
      // that components that are acitvated synchronously know
      // whether it is the initial render.
      this._rendered = true;
    };

    /**
     * Set current to the new transition.
     * This is called by the transition object when the
     * validation of a route has succeeded.
     *
     * @param {Transition} transition
     */

    Router.prototype._onTransitionValidated = function _onTransitionValidated(transition) {
      // set current route
      var route = this._currentRoute = transition.to;
      // update route context for all children
      if (this.app.$route !== route) {
        this.app.$route = route;
        this._children.forEach(function (child) {
          child.$route = route;
        });
      }
      // call global after hook
      if (this._afterEachHooks.length) {
        this._afterEachHooks.forEach(function (hook) {
          return hook.call(null, {
            to: transition.to,
            from: transition.from
          });
        });
      }
      this._currentTransition.done = true;
    };

    /**
     * Handle stuff after the transition.
     *
     * @param {Route} route
     * @param {Object} [state]
     * @param {String} [anchor]
     */

    Router.prototype._postTransition = function _postTransition(route, state, anchor) {
      // handle scroll positions
      // saved scroll positions take priority
      // then we check if the path has an anchor
      var pos = state && state.pos;
      if (pos && this._saveScrollPosition) {
        Vue.nextTick(function () {
          window.scrollTo(pos.x, pos.y);
        });
      } else if (anchor) {
        Vue.nextTick(function () {
          var el = document.getElementById(anchor.slice(1));
          if (el) {
            window.scrollTo(window.scrollX, el.offsetTop);
          }
        });
      }
    };

    return Router;
  })();

  function guardComponent(path, handler) {
    var comp = handler.component;
    if (Vue.util.isPlainObject(comp)) {
      comp = handler.component = Vue.extend(comp);
    }
    /* istanbul ignore if */
    if (typeof comp !== 'function') {
      handler.component = null;
      warn$1('invalid component for route "' + path + '".');
    }
  }

  /* Installation */

  Router.installed = false;

  /**
   * Installation interface.
   * Install the necessary directives.
   */

  Router.install = function (externalVue) {
    /* istanbul ignore if */
    if (Router.installed) {
      warn$1('already installed.');
      return;
    }
    Vue = externalVue;
    applyOverride(Vue);
    View(Vue);
    Link(Vue);
    exports$1.Vue = Vue;
    Router.installed = true;
  };

  // auto install
  /* istanbul ignore if */
  if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(Router);
  }

  return Router;

}));
},{}],7:[function(require,module,exports){
(function (process){
/*!
 * Vue.js v1.0.28
 * (c) 2016 Evan You
 * Released under the MIT License.
 */
'use strict';

function set(obj, key, val) {
  if (hasOwn(obj, key)) {
    obj[key] = val;
    return;
  }
  if (obj._isVue) {
    set(obj._data, key, val);
    return;
  }
  var ob = obj.__ob__;
  if (!ob) {
    obj[key] = val;
    return;
  }
  ob.convert(key, val);
  ob.dep.notify();
  if (ob.vms) {
    var i = ob.vms.length;
    while (i--) {
      var vm = ob.vms[i];
      vm._proxy(key);
      vm._digest();
    }
  }
  return val;
}

/**
 * Delete a property and trigger change if necessary.
 *
 * @param {Object} obj
 * @param {String} key
 */

function del(obj, key) {
  if (!hasOwn(obj, key)) {
    return;
  }
  delete obj[key];
  var ob = obj.__ob__;
  if (!ob) {
    if (obj._isVue) {
      delete obj._data[key];
      obj._digest();
    }
    return;
  }
  ob.dep.notify();
  if (ob.vms) {
    var i = ob.vms.length;
    while (i--) {
      var vm = ob.vms[i];
      vm._unproxy(key);
      vm._digest();
    }
  }
}

var hasOwnProperty = Object.prototype.hasOwnProperty;
/**
 * Check whether the object has the property.
 *
 * @param {Object} obj
 * @param {String} key
 * @return {Boolean}
 */

function hasOwn(obj, key) {
  return hasOwnProperty.call(obj, key);
}

/**
 * Check if an expression is a literal value.
 *
 * @param {String} exp
 * @return {Boolean}
 */

var literalValueRE = /^\s?(true|false|-?[\d\.]+|'[^']*'|"[^"]*")\s?$/;

function isLiteral(exp) {
  return literalValueRE.test(exp);
}

/**
 * Check if a string starts with $ or _
 *
 * @param {String} str
 * @return {Boolean}
 */

function isReserved(str) {
  var c = (str + '').charCodeAt(0);
  return c === 0x24 || c === 0x5F;
}

/**
 * Guard text output, make sure undefined outputs
 * empty string
 *
 * @param {*} value
 * @return {String}
 */

function _toString(value) {
  return value == null ? '' : value.toString();
}

/**
 * Check and convert possible numeric strings to numbers
 * before setting back to data
 *
 * @param {*} value
 * @return {*|Number}
 */

function toNumber(value) {
  if (typeof value !== 'string') {
    return value;
  } else {
    var parsed = Number(value);
    return isNaN(parsed) ? value : parsed;
  }
}

/**
 * Convert string boolean literals into real booleans.
 *
 * @param {*} value
 * @return {*|Boolean}
 */

function toBoolean(value) {
  return value === 'true' ? true : value === 'false' ? false : value;
}

/**
 * Strip quotes from a string
 *
 * @param {String} str
 * @return {String | false}
 */

function stripQuotes(str) {
  var a = str.charCodeAt(0);
  var b = str.charCodeAt(str.length - 1);
  return a === b && (a === 0x22 || a === 0x27) ? str.slice(1, -1) : str;
}

/**
 * Camelize a hyphen-delimited string.
 *
 * @param {String} str
 * @return {String}
 */

var camelizeRE = /-(\w)/g;

function camelize(str) {
  return str.replace(camelizeRE, toUpper);
}

function toUpper(_, c) {
  return c ? c.toUpperCase() : '';
}

/**
 * Hyphenate a camelCase string.
 *
 * @param {String} str
 * @return {String}
 */

var hyphenateRE = /([^-])([A-Z])/g;

function hyphenate(str) {
  return str.replace(hyphenateRE, '$1-$2').replace(hyphenateRE, '$1-$2').toLowerCase();
}

/**
 * Converts hyphen/underscore/slash delimitered names into
 * camelized classNames.
 *
 * e.g. my-component => MyComponent
 *      some_else    => SomeElse
 *      some/comp    => SomeComp
 *
 * @param {String} str
 * @return {String}
 */

var classifyRE = /(?:^|[-_\/])(\w)/g;

function classify(str) {
  return str.replace(classifyRE, toUpper);
}

/**
 * Simple bind, faster than native
 *
 * @param {Function} fn
 * @param {Object} ctx
 * @return {Function}
 */

function bind(fn, ctx) {
  return function (a) {
    var l = arguments.length;
    return l ? l > 1 ? fn.apply(ctx, arguments) : fn.call(ctx, a) : fn.call(ctx);
  };
}

/**
 * Convert an Array-like object to a real Array.
 *
 * @param {Array-like} list
 * @param {Number} [start] - start index
 * @return {Array}
 */

function toArray(list, start) {
  start = start || 0;
  var i = list.length - start;
  var ret = new Array(i);
  while (i--) {
    ret[i] = list[i + start];
  }
  return ret;
}

/**
 * Mix properties into target object.
 *
 * @param {Object} to
 * @param {Object} from
 */

function extend(to, from) {
  var keys = Object.keys(from);
  var i = keys.length;
  while (i--) {
    to[keys[i]] = from[keys[i]];
  }
  return to;
}

/**
 * Quick object check - this is primarily used to tell
 * Objects from primitive values when we know the value
 * is a JSON-compliant type.
 *
 * @param {*} obj
 * @return {Boolean}
 */

function isObject(obj) {
  return obj !== null && typeof obj === 'object';
}

/**
 * Strict object type check. Only returns true
 * for plain JavaScript objects.
 *
 * @param {*} obj
 * @return {Boolean}
 */

var toString = Object.prototype.toString;
var OBJECT_STRING = '[object Object]';

function isPlainObject(obj) {
  return toString.call(obj) === OBJECT_STRING;
}

/**
 * Array type check.
 *
 * @param {*} obj
 * @return {Boolean}
 */

var isArray = Array.isArray;

/**
 * Define a property.
 *
 * @param {Object} obj
 * @param {String} key
 * @param {*} val
 * @param {Boolean} [enumerable]
 */

function def(obj, key, val, enumerable) {
  Object.defineProperty(obj, key, {
    value: val,
    enumerable: !!enumerable,
    writable: true,
    configurable: true
  });
}

/**
 * Debounce a function so it only gets called after the
 * input stops arriving after the given wait period.
 *
 * @param {Function} func
 * @param {Number} wait
 * @return {Function} - the debounced function
 */

function _debounce(func, wait) {
  var timeout, args, context, timestamp, result;
  var later = function later() {
    var last = Date.now() - timestamp;
    if (last < wait && last >= 0) {
      timeout = setTimeout(later, wait - last);
    } else {
      timeout = null;
      result = func.apply(context, args);
      if (!timeout) context = args = null;
    }
  };
  return function () {
    context = this;
    args = arguments;
    timestamp = Date.now();
    if (!timeout) {
      timeout = setTimeout(later, wait);
    }
    return result;
  };
}

/**
 * Manual indexOf because it's slightly faster than
 * native.
 *
 * @param {Array} arr
 * @param {*} obj
 */

function indexOf(arr, obj) {
  var i = arr.length;
  while (i--) {
    if (arr[i] === obj) return i;
  }
  return -1;
}

/**
 * Make a cancellable version of an async callback.
 *
 * @param {Function} fn
 * @return {Function}
 */

function cancellable(fn) {
  var cb = function cb() {
    if (!cb.cancelled) {
      return fn.apply(this, arguments);
    }
  };
  cb.cancel = function () {
    cb.cancelled = true;
  };
  return cb;
}

/**
 * Check if two values are loosely equal - that is,
 * if they are plain objects, do they have the same shape?
 *
 * @param {*} a
 * @param {*} b
 * @return {Boolean}
 */

function looseEqual(a, b) {
  /* eslint-disable eqeqeq */
  return a == b || (isObject(a) && isObject(b) ? JSON.stringify(a) === JSON.stringify(b) : false);
  /* eslint-enable eqeqeq */
}

var hasProto = ('__proto__' in {});

// Browser environment sniffing
var inBrowser = typeof window !== 'undefined' && Object.prototype.toString.call(window) !== '[object Object]';

// detect devtools
var devtools = inBrowser && window.__VUE_DEVTOOLS_GLOBAL_HOOK__;

// UA sniffing for working around browser-specific quirks
var UA = inBrowser && window.navigator.userAgent.toLowerCase();
var isIE = UA && UA.indexOf('trident') > 0;
var isIE9 = UA && UA.indexOf('msie 9.0') > 0;
var isAndroid = UA && UA.indexOf('android') > 0;
var isIOS = UA && /iphone|ipad|ipod|ios/.test(UA);

var transitionProp = undefined;
var transitionEndEvent = undefined;
var animationProp = undefined;
var animationEndEvent = undefined;

// Transition property/event sniffing
if (inBrowser && !isIE9) {
  var isWebkitTrans = window.ontransitionend === undefined && window.onwebkittransitionend !== undefined;
  var isWebkitAnim = window.onanimationend === undefined && window.onwebkitanimationend !== undefined;
  transitionProp = isWebkitTrans ? 'WebkitTransition' : 'transition';
  transitionEndEvent = isWebkitTrans ? 'webkitTransitionEnd' : 'transitionend';
  animationProp = isWebkitAnim ? 'WebkitAnimation' : 'animation';
  animationEndEvent = isWebkitAnim ? 'webkitAnimationEnd' : 'animationend';
}

/* istanbul ignore next */
function isNative(Ctor) {
  return (/native code/.test(Ctor.toString())
  );
}

/**
 * Defer a task to execute it asynchronously. Ideally this
 * should be executed as a microtask, so we leverage
 * MutationObserver if it's available, and fallback to
 * setTimeout(0).
 *
 * @param {Function} cb
 * @param {Object} ctx
 */

var nextTick = (function () {
  var callbacks = [];
  var pending = false;
  var timerFunc = undefined;

  function nextTickHandler() {
    pending = false;
    var copies = callbacks.slice(0);
    callbacks.length = 0;
    for (var i = 0; i < copies.length; i++) {
      copies[i]();
    }
  }

  // the nextTick behavior leverages the microtask queue, which can be accessed
  // via either native Promise.then or MutationObserver.
  // MutationObserver has wider support, however it is seriously bugged in
  // UIWebView in iOS >= 9.3.3 when triggered in touch event handlers. It
  // completely stops working after triggering a few times... so, if native
  // Promise is available, we will use it:
  /* istanbul ignore if */
  if (typeof Promise !== 'undefined' && isNative(Promise)) {
    var p = Promise.resolve();
    var noop = function noop() {};
    timerFunc = function () {
      p.then(nextTickHandler);
      // in problematic UIWebViews, Promise.then doesn't completely break, but
      // it can get stuck in a weird state where callbacks are pushed into the
      // microtask queue but the queue isn't being flushed, until the browser
      // needs to do some other work, e.g. handle a timer. Therefore we can
      // "force" the microtask queue to be flushed by adding an empty timer.
      if (isIOS) setTimeout(noop);
    };
  } else if (typeof MutationObserver !== 'undefined') {
    // use MutationObserver where native Promise is not available,
    // e.g. IE11, iOS7, Android 4.4
    var counter = 1;
    var observer = new MutationObserver(nextTickHandler);
    var textNode = document.createTextNode(String(counter));
    observer.observe(textNode, {
      characterData: true
    });
    timerFunc = function () {
      counter = (counter + 1) % 2;
      textNode.data = String(counter);
    };
  } else {
    // fallback to setTimeout
    /* istanbul ignore next */
    timerFunc = setTimeout;
  }

  return function (cb, ctx) {
    var func = ctx ? function () {
      cb.call(ctx);
    } : cb;
    callbacks.push(func);
    if (pending) return;
    pending = true;
    timerFunc(nextTickHandler, 0);
  };
})();

var _Set = undefined;
/* istanbul ignore if */
if (typeof Set !== 'undefined' && isNative(Set)) {
  // use native Set when available.
  _Set = Set;
} else {
  // a non-standard Set polyfill that only works with primitive keys.
  _Set = function () {
    this.set = Object.create(null);
  };
  _Set.prototype.has = function (key) {
    return this.set[key] !== undefined;
  };
  _Set.prototype.add = function (key) {
    this.set[key] = 1;
  };
  _Set.prototype.clear = function () {
    this.set = Object.create(null);
  };
}

function Cache(limit) {
  this.size = 0;
  this.limit = limit;
  this.head = this.tail = undefined;
  this._keymap = Object.create(null);
}

var p = Cache.prototype;

/**
 * Put <value> into the cache associated with <key>.
 * Returns the entry which was removed to make room for
 * the new entry. Otherwise undefined is returned.
 * (i.e. if there was enough room already).
 *
 * @param {String} key
 * @param {*} value
 * @return {Entry|undefined}
 */

p.put = function (key, value) {
  var removed;

  var entry = this.get(key, true);
  if (!entry) {
    if (this.size === this.limit) {
      removed = this.shift();
    }
    entry = {
      key: key
    };
    this._keymap[key] = entry;
    if (this.tail) {
      this.tail.newer = entry;
      entry.older = this.tail;
    } else {
      this.head = entry;
    }
    this.tail = entry;
    this.size++;
  }
  entry.value = value;

  return removed;
};

/**
 * Purge the least recently used (oldest) entry from the
 * cache. Returns the removed entry or undefined if the
 * cache was empty.
 */

p.shift = function () {
  var entry = this.head;
  if (entry) {
    this.head = this.head.newer;
    this.head.older = undefined;
    entry.newer = entry.older = undefined;
    this._keymap[entry.key] = undefined;
    this.size--;
  }
  return entry;
};

/**
 * Get and register recent use of <key>. Returns the value
 * associated with <key> or undefined if not in cache.
 *
 * @param {String} key
 * @param {Boolean} returnEntry
 * @return {Entry|*}
 */

p.get = function (key, returnEntry) {
  var entry = this._keymap[key];
  if (entry === undefined) return;
  if (entry === this.tail) {
    return returnEntry ? entry : entry.value;
  }
  // HEAD--------------TAIL
  //   <.older   .newer>
  //  <--- add direction --
  //   A  B  C  <D>  E
  if (entry.newer) {
    if (entry === this.head) {
      this.head = entry.newer;
    }
    entry.newer.older = entry.older; // C <-- E.
  }
  if (entry.older) {
    entry.older.newer = entry.newer; // C. --> E
  }
  entry.newer = undefined; // D --x
  entry.older = this.tail; // D. --> E
  if (this.tail) {
    this.tail.newer = entry; // E. <-- D
  }
  this.tail = entry;
  return returnEntry ? entry : entry.value;
};

var cache$1 = new Cache(1000);
var reservedArgRE = /^in$|^-?\d+/;

/**
 * Parser state
 */

var str;
var dir;
var len;
var index;
var chr;
var state;
var startState = 0;
var filterState = 1;
var filterNameState = 2;
var filterArgState = 3;

var doubleChr = 0x22;
var singleChr = 0x27;
var pipeChr = 0x7C;
var escapeChr = 0x5C;
var spaceChr = 0x20;

var expStartChr = { 0x5B: 1, 0x7B: 1, 0x28: 1 };
var expChrPair = { 0x5B: 0x5D, 0x7B: 0x7D, 0x28: 0x29 };

function peek() {
  return str.charCodeAt(index + 1);
}

function next() {
  return str.charCodeAt(++index);
}

function eof() {
  return index >= len;
}

function eatSpace() {
  while (peek() === spaceChr) {
    next();
  }
}

function isStringStart(chr) {
  return chr === doubleChr || chr === singleChr;
}

function isExpStart(chr) {
  return expStartChr[chr];
}

function isExpEnd(start, chr) {
  return expChrPair[start] === chr;
}

function parseString() {
  var stringQuote = next();
  var chr;
  while (!eof()) {
    chr = next();
    // escape char
    if (chr === escapeChr) {
      next();
    } else if (chr === stringQuote) {
      break;
    }
  }
}

function parseSpecialExp(chr) {
  var inExp = 0;
  var startChr = chr;

  while (!eof()) {
    chr = peek();
    if (isStringStart(chr)) {
      parseString();
      continue;
    }

    if (startChr === chr) {
      inExp++;
    }
    if (isExpEnd(startChr, chr)) {
      inExp--;
    }

    next();

    if (inExp === 0) {
      break;
    }
  }
}

/**
 * syntax:
 * expression | filterName  [arg  arg [| filterName arg arg]]
 */

function parseExpression() {
  var start = index;
  while (!eof()) {
    chr = peek();
    if (isStringStart(chr)) {
      parseString();
    } else if (isExpStart(chr)) {
      parseSpecialExp(chr);
    } else if (chr === pipeChr) {
      next();
      chr = peek();
      if (chr === pipeChr) {
        next();
      } else {
        if (state === startState || state === filterArgState) {
          state = filterState;
        }
        break;
      }
    } else if (chr === spaceChr && (state === filterNameState || state === filterArgState)) {
      eatSpace();
      break;
    } else {
      if (state === filterState) {
        state = filterNameState;
      }
      next();
    }
  }

  return str.slice(start + 1, index) || null;
}

function parseFilterList() {
  var filters = [];
  while (!eof()) {
    filters.push(parseFilter());
  }
  return filters;
}

function parseFilter() {
  var filter = {};
  var args;

  state = filterState;
  filter.name = parseExpression().trim();

  state = filterArgState;
  args = parseFilterArguments();

  if (args.length) {
    filter.args = args;
  }
  return filter;
}

function parseFilterArguments() {
  var args = [];
  while (!eof() && state !== filterState) {
    var arg = parseExpression();
    if (!arg) {
      break;
    }
    args.push(processFilterArg(arg));
  }

  return args;
}

/**
 * Check if an argument is dynamic and strip quotes.
 *
 * @param {String} arg
 * @return {Object}
 */

function processFilterArg(arg) {
  if (reservedArgRE.test(arg)) {
    return {
      value: toNumber(arg),
      dynamic: false
    };
  } else {
    var stripped = stripQuotes(arg);
    var dynamic = stripped === arg;
    return {
      value: dynamic ? arg : stripped,
      dynamic: dynamic
    };
  }
}

/**
 * Parse a directive value and extract the expression
 * and its filters into a descriptor.
 *
 * Example:
 *
 * "a + 1 | uppercase" will yield:
 * {
 *   expression: 'a + 1',
 *   filters: [
 *     { name: 'uppercase', args: null }
 *   ]
 * }
 *
 * @param {String} s
 * @return {Object}
 */

function parseDirective(s) {
  var hit = cache$1.get(s);
  if (hit) {
    return hit;
  }

  // reset parser state
  str = s;
  dir = {};
  len = str.length;
  index = -1;
  chr = '';
  state = startState;

  var filters;

  if (str.indexOf('|') < 0) {
    dir.expression = str.trim();
  } else {
    dir.expression = parseExpression().trim();
    filters = parseFilterList();
    if (filters.length) {
      dir.filters = filters;
    }
  }

  cache$1.put(s, dir);
  return dir;
}

var directive = Object.freeze({
  parseDirective: parseDirective
});

var regexEscapeRE = /[-.*+?^${}()|[\]\/\\]/g;
var cache = undefined;
var tagRE = undefined;
var htmlRE = undefined;
/**
 * Escape a string so it can be used in a RegExp
 * constructor.
 *
 * @param {String} str
 */

function escapeRegex(str) {
  return str.replace(regexEscapeRE, '\\$&');
}

function compileRegex() {
  var open = escapeRegex(config.delimiters[0]);
  var close = escapeRegex(config.delimiters[1]);
  var unsafeOpen = escapeRegex(config.unsafeDelimiters[0]);
  var unsafeClose = escapeRegex(config.unsafeDelimiters[1]);
  tagRE = new RegExp(unsafeOpen + '((?:.|\\n)+?)' + unsafeClose + '|' + open + '((?:.|\\n)+?)' + close, 'g');
  htmlRE = new RegExp('^' + unsafeOpen + '((?:.|\\n)+?)' + unsafeClose + '$');
  // reset cache
  cache = new Cache(1000);
}

/**
 * Parse a template text string into an array of tokens.
 *
 * @param {String} text
 * @return {Array<Object> | null}
 *               - {String} type
 *               - {String} value
 *               - {Boolean} [html]
 *               - {Boolean} [oneTime]
 */

function parseText(text) {
  if (!cache) {
    compileRegex();
  }
  var hit = cache.get(text);
  if (hit) {
    return hit;
  }
  if (!tagRE.test(text)) {
    return null;
  }
  var tokens = [];
  var lastIndex = tagRE.lastIndex = 0;
  var match, index, html, value, first, oneTime;
  /* eslint-disable no-cond-assign */
  while (match = tagRE.exec(text)) {
    /* eslint-enable no-cond-assign */
    index = match.index;
    // push text token
    if (index > lastIndex) {
      tokens.push({
        value: text.slice(lastIndex, index)
      });
    }
    // tag token
    html = htmlRE.test(match[0]);
    value = html ? match[1] : match[2];
    first = value.charCodeAt(0);
    oneTime = first === 42; // *
    value = oneTime ? value.slice(1) : value;
    tokens.push({
      tag: true,
      value: value.trim(),
      html: html,
      oneTime: oneTime
    });
    lastIndex = index + match[0].length;
  }
  if (lastIndex < text.length) {
    tokens.push({
      value: text.slice(lastIndex)
    });
  }
  cache.put(text, tokens);
  return tokens;
}

/**
 * Format a list of tokens into an expression.
 * e.g. tokens parsed from 'a {{b}} c' can be serialized
 * into one single expression as '"a " + b + " c"'.
 *
 * @param {Array} tokens
 * @param {Vue} [vm]
 * @return {String}
 */

function tokensToExp(tokens, vm) {
  if (tokens.length > 1) {
    return tokens.map(function (token) {
      return formatToken(token, vm);
    }).join('+');
  } else {
    return formatToken(tokens[0], vm, true);
  }
}

/**
 * Format a single token.
 *
 * @param {Object} token
 * @param {Vue} [vm]
 * @param {Boolean} [single]
 * @return {String}
 */

function formatToken(token, vm, single) {
  return token.tag ? token.oneTime && vm ? '"' + vm.$eval(token.value) + '"' : inlineFilters(token.value, single) : '"' + token.value + '"';
}

/**
 * For an attribute with multiple interpolation tags,
 * e.g. attr="some-{{thing | filter}}", in order to combine
 * the whole thing into a single watchable expression, we
 * have to inline those filters. This function does exactly
 * that. This is a bit hacky but it avoids heavy changes
 * to directive parser and watcher mechanism.
 *
 * @param {String} exp
 * @param {Boolean} single
 * @return {String}
 */

var filterRE = /[^|]\|[^|]/;
function inlineFilters(exp, single) {
  if (!filterRE.test(exp)) {
    return single ? exp : '(' + exp + ')';
  } else {
    var dir = parseDirective(exp);
    if (!dir.filters) {
      return '(' + exp + ')';
    } else {
      return 'this._applyFilters(' + dir.expression + // value
      ',null,' + // oldValue (null for read)
      JSON.stringify(dir.filters) + // filter descriptors
      ',false)'; // write?
    }
  }
}

var text = Object.freeze({
  compileRegex: compileRegex,
  parseText: parseText,
  tokensToExp: tokensToExp
});

var delimiters = ['{{', '}}'];
var unsafeDelimiters = ['{{{', '}}}'];

var config = Object.defineProperties({

  /**
   * Whether to print debug messages.
   * Also enables stack trace for warnings.
   *
   * @type {Boolean}
   */

  debug: false,

  /**
   * Whether to suppress warnings.
   *
   * @type {Boolean}
   */

  silent: false,

  /**
   * Whether to use async rendering.
   */

  async: true,

  /**
   * Whether to warn against errors caught when evaluating
   * expressions.
   */

  warnExpressionErrors: true,

  /**
   * Whether to allow devtools inspection.
   * Disabled by default in production builds.
   */

  devtools: process.env.NODE_ENV !== 'production',

  /**
   * Internal flag to indicate the delimiters have been
   * changed.
   *
   * @type {Boolean}
   */

  _delimitersChanged: true,

  /**
   * List of asset types that a component can own.
   *
   * @type {Array}
   */

  _assetTypes: ['component', 'directive', 'elementDirective', 'filter', 'transition', 'partial'],

  /**
   * prop binding modes
   */

  _propBindingModes: {
    ONE_WAY: 0,
    TWO_WAY: 1,
    ONE_TIME: 2
  },

  /**
   * Max circular updates allowed in a batcher flush cycle.
   */

  _maxUpdateCount: 100

}, {
  delimiters: { /**
                 * Interpolation delimiters. Changing these would trigger
                 * the text parser to re-compile the regular expressions.
                 *
                 * @type {Array<String>}
                 */

    get: function get() {
      return delimiters;
    },
    set: function set(val) {
      delimiters = val;
      compileRegex();
    },
    configurable: true,
    enumerable: true
  },
  unsafeDelimiters: {
    get: function get() {
      return unsafeDelimiters;
    },
    set: function set(val) {
      unsafeDelimiters = val;
      compileRegex();
    },
    configurable: true,
    enumerable: true
  }
});

var warn = undefined;
var formatComponentName = undefined;

if (process.env.NODE_ENV !== 'production') {
  (function () {
    var hasConsole = typeof console !== 'undefined';

    warn = function (msg, vm) {
      if (hasConsole && !config.silent) {
        console.error('[Vue warn]: ' + msg + (vm ? formatComponentName(vm) : ''));
      }
    };

    formatComponentName = function (vm) {
      var name = vm._isVue ? vm.$options.name : vm.name;
      return name ? ' (found in component: <' + hyphenate(name) + '>)' : '';
    };
  })();
}

/**
 * Append with transition.
 *
 * @param {Element} el
 * @param {Element} target
 * @param {Vue} vm
 * @param {Function} [cb]
 */

function appendWithTransition(el, target, vm, cb) {
  applyTransition(el, 1, function () {
    target.appendChild(el);
  }, vm, cb);
}

/**
 * InsertBefore with transition.
 *
 * @param {Element} el
 * @param {Element} target
 * @param {Vue} vm
 * @param {Function} [cb]
 */

function beforeWithTransition(el, target, vm, cb) {
  applyTransition(el, 1, function () {
    before(el, target);
  }, vm, cb);
}

/**
 * Remove with transition.
 *
 * @param {Element} el
 * @param {Vue} vm
 * @param {Function} [cb]
 */

function removeWithTransition(el, vm, cb) {
  applyTransition(el, -1, function () {
    remove(el);
  }, vm, cb);
}

/**
 * Apply transitions with an operation callback.
 *
 * @param {Element} el
 * @param {Number} direction
 *                  1: enter
 *                 -1: leave
 * @param {Function} op - the actual DOM operation
 * @param {Vue} vm
 * @param {Function} [cb]
 */

function applyTransition(el, direction, op, vm, cb) {
  var transition = el.__v_trans;
  if (!transition ||
  // skip if there are no js hooks and CSS transition is
  // not supported
  !transition.hooks && !transitionEndEvent ||
  // skip transitions for initial compile
  !vm._isCompiled ||
  // if the vm is being manipulated by a parent directive
  // during the parent's compilation phase, skip the
  // animation.
  vm.$parent && !vm.$parent._isCompiled) {
    op();
    if (cb) cb();
    return;
  }
  var action = direction > 0 ? 'enter' : 'leave';
  transition[action](op, cb);
}

var transition = Object.freeze({
  appendWithTransition: appendWithTransition,
  beforeWithTransition: beforeWithTransition,
  removeWithTransition: removeWithTransition,
  applyTransition: applyTransition
});

/**
 * Query an element selector if it's not an element already.
 *
 * @param {String|Element} el
 * @return {Element}
 */

function query(el) {
  if (typeof el === 'string') {
    var selector = el;
    el = document.querySelector(el);
    if (!el) {
      process.env.NODE_ENV !== 'production' && warn('Cannot find element: ' + selector);
    }
  }
  return el;
}

/**
 * Check if a node is in the document.
 * Note: document.documentElement.contains should work here
 * but always returns false for comment nodes in phantomjs,
 * making unit tests difficult. This is fixed by doing the
 * contains() check on the node's parentNode instead of
 * the node itself.
 *
 * @param {Node} node
 * @return {Boolean}
 */

function inDoc(node) {
  if (!node) return false;
  var doc = node.ownerDocument.documentElement;
  var parent = node.parentNode;
  return doc === node || doc === parent || !!(parent && parent.nodeType === 1 && doc.contains(parent));
}

/**
 * Get and remove an attribute from a node.
 *
 * @param {Node} node
 * @param {String} _attr
 */

function getAttr(node, _attr) {
  var val = node.getAttribute(_attr);
  if (val !== null) {
    node.removeAttribute(_attr);
  }
  return val;
}

/**
 * Get an attribute with colon or v-bind: prefix.
 *
 * @param {Node} node
 * @param {String} name
 * @return {String|null}
 */

function getBindAttr(node, name) {
  var val = getAttr(node, ':' + name);
  if (val === null) {
    val = getAttr(node, 'v-bind:' + name);
  }
  return val;
}

/**
 * Check the presence of a bind attribute.
 *
 * @param {Node} node
 * @param {String} name
 * @return {Boolean}
 */

function hasBindAttr(node, name) {
  return node.hasAttribute(name) || node.hasAttribute(':' + name) || node.hasAttribute('v-bind:' + name);
}

/**
 * Insert el before target
 *
 * @param {Element} el
 * @param {Element} target
 */

function before(el, target) {
  target.parentNode.insertBefore(el, target);
}

/**
 * Insert el after target
 *
 * @param {Element} el
 * @param {Element} target
 */

function after(el, target) {
  if (target.nextSibling) {
    before(el, target.nextSibling);
  } else {
    target.parentNode.appendChild(el);
  }
}

/**
 * Remove el from DOM
 *
 * @param {Element} el
 */

function remove(el) {
  el.parentNode.removeChild(el);
}

/**
 * Prepend el to target
 *
 * @param {Element} el
 * @param {Element} target
 */

function prepend(el, target) {
  if (target.firstChild) {
    before(el, target.firstChild);
  } else {
    target.appendChild(el);
  }
}

/**
 * Replace target with el
 *
 * @param {Element} target
 * @param {Element} el
 */

function replace(target, el) {
  var parent = target.parentNode;
  if (parent) {
    parent.replaceChild(el, target);
  }
}

/**
 * Add event listener shorthand.
 *
 * @param {Element} el
 * @param {String} event
 * @param {Function} cb
 * @param {Boolean} [useCapture]
 */

function on(el, event, cb, useCapture) {
  el.addEventListener(event, cb, useCapture);
}

/**
 * Remove event listener shorthand.
 *
 * @param {Element} el
 * @param {String} event
 * @param {Function} cb
 */

function off(el, event, cb) {
  el.removeEventListener(event, cb);
}

/**
 * For IE9 compat: when both class and :class are present
 * getAttribute('class') returns wrong value...
 *
 * @param {Element} el
 * @return {String}
 */

function getClass(el) {
  var classname = el.className;
  if (typeof classname === 'object') {
    classname = classname.baseVal || '';
  }
  return classname;
}

/**
 * In IE9, setAttribute('class') will result in empty class
 * if the element also has the :class attribute; However in
 * PhantomJS, setting `className` does not work on SVG elements...
 * So we have to do a conditional check here.
 *
 * @param {Element} el
 * @param {String} cls
 */

function setClass(el, cls) {
  /* istanbul ignore if */
  if (isIE9 && !/svg$/.test(el.namespaceURI)) {
    el.className = cls;
  } else {
    el.setAttribute('class', cls);
  }
}

/**
 * Add class with compatibility for IE & SVG
 *
 * @param {Element} el
 * @param {String} cls
 */

function addClass(el, cls) {
  if (el.classList) {
    el.classList.add(cls);
  } else {
    var cur = ' ' + getClass(el) + ' ';
    if (cur.indexOf(' ' + cls + ' ') < 0) {
      setClass(el, (cur + cls).trim());
    }
  }
}

/**
 * Remove class with compatibility for IE & SVG
 *
 * @param {Element} el
 * @param {String} cls
 */

function removeClass(el, cls) {
  if (el.classList) {
    el.classList.remove(cls);
  } else {
    var cur = ' ' + getClass(el) + ' ';
    var tar = ' ' + cls + ' ';
    while (cur.indexOf(tar) >= 0) {
      cur = cur.replace(tar, ' ');
    }
    setClass(el, cur.trim());
  }
  if (!el.className) {
    el.removeAttribute('class');
  }
}

/**
 * Extract raw content inside an element into a temporary
 * container div
 *
 * @param {Element} el
 * @param {Boolean} asFragment
 * @return {Element|DocumentFragment}
 */

function extractContent(el, asFragment) {
  var child;
  var rawContent;
  /* istanbul ignore if */
  if (isTemplate(el) && isFragment(el.content)) {
    el = el.content;
  }
  if (el.hasChildNodes()) {
    trimNode(el);
    rawContent = asFragment ? document.createDocumentFragment() : document.createElement('div');
    /* eslint-disable no-cond-assign */
    while (child = el.firstChild) {
      /* eslint-enable no-cond-assign */
      rawContent.appendChild(child);
    }
  }
  return rawContent;
}

/**
 * Trim possible empty head/tail text and comment
 * nodes inside a parent.
 *
 * @param {Node} node
 */

function trimNode(node) {
  var child;
  /* eslint-disable no-sequences */
  while ((child = node.firstChild, isTrimmable(child))) {
    node.removeChild(child);
  }
  while ((child = node.lastChild, isTrimmable(child))) {
    node.removeChild(child);
  }
  /* eslint-enable no-sequences */
}

function isTrimmable(node) {
  return node && (node.nodeType === 3 && !node.data.trim() || node.nodeType === 8);
}

/**
 * Check if an element is a template tag.
 * Note if the template appears inside an SVG its tagName
 * will be in lowercase.
 *
 * @param {Element} el
 */

function isTemplate(el) {
  return el.tagName && el.tagName.toLowerCase() === 'template';
}

/**
 * Create an "anchor" for performing dom insertion/removals.
 * This is used in a number of scenarios:
 * - fragment instance
 * - v-html
 * - v-if
 * - v-for
 * - component
 *
 * @param {String} content
 * @param {Boolean} persist - IE trashes empty textNodes on
 *                            cloneNode(true), so in certain
 *                            cases the anchor needs to be
 *                            non-empty to be persisted in
 *                            templates.
 * @return {Comment|Text}
 */

function createAnchor(content, persist) {
  var anchor = config.debug ? document.createComment(content) : document.createTextNode(persist ? ' ' : '');
  anchor.__v_anchor = true;
  return anchor;
}

/**
 * Find a component ref attribute that starts with $.
 *
 * @param {Element} node
 * @return {String|undefined}
 */

var refRE = /^v-ref:/;

function findRef(node) {
  if (node.hasAttributes()) {
    var attrs = node.attributes;
    for (var i = 0, l = attrs.length; i < l; i++) {
      var name = attrs[i].name;
      if (refRE.test(name)) {
        return camelize(name.replace(refRE, ''));
      }
    }
  }
}

/**
 * Map a function to a range of nodes .
 *
 * @param {Node} node
 * @param {Node} end
 * @param {Function} op
 */

function mapNodeRange(node, end, op) {
  var next;
  while (node !== end) {
    next = node.nextSibling;
    op(node);
    node = next;
  }
  op(end);
}

/**
 * Remove a range of nodes with transition, store
 * the nodes in a fragment with correct ordering,
 * and call callback when done.
 *
 * @param {Node} start
 * @param {Node} end
 * @param {Vue} vm
 * @param {DocumentFragment} frag
 * @param {Function} cb
 */

function removeNodeRange(start, end, vm, frag, cb) {
  var done = false;
  var removed = 0;
  var nodes = [];
  mapNodeRange(start, end, function (node) {
    if (node === end) done = true;
    nodes.push(node);
    removeWithTransition(node, vm, onRemoved);
  });
  function onRemoved() {
    removed++;
    if (done && removed >= nodes.length) {
      for (var i = 0; i < nodes.length; i++) {
        frag.appendChild(nodes[i]);
      }
      cb && cb();
    }
  }
}

/**
 * Check if a node is a DocumentFragment.
 *
 * @param {Node} node
 * @return {Boolean}
 */

function isFragment(node) {
  return node && node.nodeType === 11;
}

/**
 * Get outerHTML of elements, taking care
 * of SVG elements in IE as well.
 *
 * @param {Element} el
 * @return {String}
 */

function getOuterHTML(el) {
  if (el.outerHTML) {
    return el.outerHTML;
  } else {
    var container = document.createElement('div');
    container.appendChild(el.cloneNode(true));
    return container.innerHTML;
  }
}

var commonTagRE = /^(div|p|span|img|a|b|i|br|ul|ol|li|h1|h2|h3|h4|h5|h6|code|pre|table|th|td|tr|form|label|input|select|option|nav|article|section|header|footer)$/i;
var reservedTagRE = /^(slot|partial|component)$/i;

var isUnknownElement = undefined;
if (process.env.NODE_ENV !== 'production') {
  isUnknownElement = function (el, tag) {
    if (tag.indexOf('-') > -1) {
      // http://stackoverflow.com/a/28210364/1070244
      return el.constructor === window.HTMLUnknownElement || el.constructor === window.HTMLElement;
    } else {
      return (/HTMLUnknownElement/.test(el.toString()) &&
        // Chrome returns unknown for several HTML5 elements.
        // https://code.google.com/p/chromium/issues/detail?id=540526
        // Firefox returns unknown for some "Interactive elements."
        !/^(data|time|rtc|rb|details|dialog|summary)$/.test(tag)
      );
    }
  };
}

/**
 * Check if an element is a component, if yes return its
 * component id.
 *
 * @param {Element} el
 * @param {Object} options
 * @return {Object|undefined}
 */

function checkComponentAttr(el, options) {
  var tag = el.tagName.toLowerCase();
  var hasAttrs = el.hasAttributes();
  if (!commonTagRE.test(tag) && !reservedTagRE.test(tag)) {
    if (resolveAsset(options, 'components', tag)) {
      return { id: tag };
    } else {
      var is = hasAttrs && getIsBinding(el, options);
      if (is) {
        return is;
      } else if (process.env.NODE_ENV !== 'production') {
        var expectedTag = options._componentNameMap && options._componentNameMap[tag];
        if (expectedTag) {
          warn('Unknown custom element: <' + tag + '> - ' + 'did you mean <' + expectedTag + '>? ' + 'HTML is case-insensitive, remember to use kebab-case in templates.');
        } else if (isUnknownElement(el, tag)) {
          warn('Unknown custom element: <' + tag + '> - did you ' + 'register the component correctly? For recursive components, ' + 'make sure to provide the "name" option.');
        }
      }
    }
  } else if (hasAttrs) {
    return getIsBinding(el, options);
  }
}

/**
 * Get "is" binding from an element.
 *
 * @param {Element} el
 * @param {Object} options
 * @return {Object|undefined}
 */

function getIsBinding(el, options) {
  // dynamic syntax
  var exp = el.getAttribute('is');
  if (exp != null) {
    if (resolveAsset(options, 'components', exp)) {
      el.removeAttribute('is');
      return { id: exp };
    }
  } else {
    exp = getBindAttr(el, 'is');
    if (exp != null) {
      return { id: exp, dynamic: true };
    }
  }
}

/**
 * Option overwriting strategies are functions that handle
 * how to merge a parent option value and a child option
 * value into the final value.
 *
 * All strategy functions follow the same signature:
 *
 * @param {*} parentVal
 * @param {*} childVal
 * @param {Vue} [vm]
 */

var strats = config.optionMergeStrategies = Object.create(null);

/**
 * Helper that recursively merges two data objects together.
 */

function mergeData(to, from) {
  var key, toVal, fromVal;
  for (key in from) {
    toVal = to[key];
    fromVal = from[key];
    if (!hasOwn(to, key)) {
      set(to, key, fromVal);
    } else if (isObject(toVal) && isObject(fromVal)) {
      mergeData(toVal, fromVal);
    }
  }
  return to;
}

/**
 * Data
 */

strats.data = function (parentVal, childVal, vm) {
  if (!vm) {
    // in a Vue.extend merge, both should be functions
    if (!childVal) {
      return parentVal;
    }
    if (typeof childVal !== 'function') {
      process.env.NODE_ENV !== 'production' && warn('The "data" option should be a function ' + 'that returns a per-instance value in component ' + 'definitions.', vm);
      return parentVal;
    }
    if (!parentVal) {
      return childVal;
    }
    // when parentVal & childVal are both present,
    // we need to return a function that returns the
    // merged result of both functions... no need to
    // check if parentVal is a function here because
    // it has to be a function to pass previous merges.
    return function mergedDataFn() {
      return mergeData(childVal.call(this), parentVal.call(this));
    };
  } else if (parentVal || childVal) {
    return function mergedInstanceDataFn() {
      // instance merge
      var instanceData = typeof childVal === 'function' ? childVal.call(vm) : childVal;
      var defaultData = typeof parentVal === 'function' ? parentVal.call(vm) : undefined;
      if (instanceData) {
        return mergeData(instanceData, defaultData);
      } else {
        return defaultData;
      }
    };
  }
};

/**
 * El
 */

strats.el = function (parentVal, childVal, vm) {
  if (!vm && childVal && typeof childVal !== 'function') {
    process.env.NODE_ENV !== 'production' && warn('The "el" option should be a function ' + 'that returns a per-instance value in component ' + 'definitions.', vm);
    return;
  }
  var ret = childVal || parentVal;
  // invoke the element factory if this is instance merge
  return vm && typeof ret === 'function' ? ret.call(vm) : ret;
};

/**
 * Hooks and param attributes are merged as arrays.
 */

strats.init = strats.created = strats.ready = strats.attached = strats.detached = strats.beforeCompile = strats.compiled = strats.beforeDestroy = strats.destroyed = strats.activate = function (parentVal, childVal) {
  return childVal ? parentVal ? parentVal.concat(childVal) : isArray(childVal) ? childVal : [childVal] : parentVal;
};

/**
 * Assets
 *
 * When a vm is present (instance creation), we need to do
 * a three-way merge between constructor options, instance
 * options and parent options.
 */

function mergeAssets(parentVal, childVal) {
  var res = Object.create(parentVal || null);
  return childVal ? extend(res, guardArrayAssets(childVal)) : res;
}

config._assetTypes.forEach(function (type) {
  strats[type + 's'] = mergeAssets;
});

/**
 * Events & Watchers.
 *
 * Events & watchers hashes should not overwrite one
 * another, so we merge them as arrays.
 */

strats.watch = strats.events = function (parentVal, childVal) {
  if (!childVal) return parentVal;
  if (!parentVal) return childVal;
  var ret = {};
  extend(ret, parentVal);
  for (var key in childVal) {
    var parent = ret[key];
    var child = childVal[key];
    if (parent && !isArray(parent)) {
      parent = [parent];
    }
    ret[key] = parent ? parent.concat(child) : [child];
  }
  return ret;
};

/**
 * Other object hashes.
 */

strats.props = strats.methods = strats.computed = function (parentVal, childVal) {
  if (!childVal) return parentVal;
  if (!parentVal) return childVal;
  var ret = Object.create(null);
  extend(ret, parentVal);
  extend(ret, childVal);
  return ret;
};

/**
 * Default strategy.
 */

var defaultStrat = function defaultStrat(parentVal, childVal) {
  return childVal === undefined ? parentVal : childVal;
};

/**
 * Make sure component options get converted to actual
 * constructors.
 *
 * @param {Object} options
 */

function guardComponents(options) {
  if (options.components) {
    var components = options.components = guardArrayAssets(options.components);
    var ids = Object.keys(components);
    var def;
    if (process.env.NODE_ENV !== 'production') {
      var map = options._componentNameMap = {};
    }
    for (var i = 0, l = ids.length; i < l; i++) {
      var key = ids[i];
      if (commonTagRE.test(key) || reservedTagRE.test(key)) {
        process.env.NODE_ENV !== 'production' && warn('Do not use built-in or reserved HTML elements as component ' + 'id: ' + key);
        continue;
      }
      // record a all lowercase <-> kebab-case mapping for
      // possible custom element case error warning
      if (process.env.NODE_ENV !== 'production') {
        map[key.replace(/-/g, '').toLowerCase()] = hyphenate(key);
      }
      def = components[key];
      if (isPlainObject(def)) {
        components[key] = Vue.extend(def);
      }
    }
  }
}

/**
 * Ensure all props option syntax are normalized into the
 * Object-based format.
 *
 * @param {Object} options
 */

function guardProps(options) {
  var props = options.props;
  var i, val;
  if (isArray(props)) {
    options.props = {};
    i = props.length;
    while (i--) {
      val = props[i];
      if (typeof val === 'string') {
        options.props[val] = null;
      } else if (val.name) {
        options.props[val.name] = val;
      }
    }
  } else if (isPlainObject(props)) {
    var keys = Object.keys(props);
    i = keys.length;
    while (i--) {
      val = props[keys[i]];
      if (typeof val === 'function') {
        props[keys[i]] = { type: val };
      }
    }
  }
}

/**
 * Guard an Array-format assets option and converted it
 * into the key-value Object format.
 *
 * @param {Object|Array} assets
 * @return {Object}
 */

function guardArrayAssets(assets) {
  if (isArray(assets)) {
    var res = {};
    var i = assets.length;
    var asset;
    while (i--) {
      asset = assets[i];
      var id = typeof asset === 'function' ? asset.options && asset.options.name || asset.id : asset.name || asset.id;
      if (!id) {
        process.env.NODE_ENV !== 'production' && warn('Array-syntax assets must provide a "name" or "id" field.');
      } else {
        res[id] = asset;
      }
    }
    return res;
  }
  return assets;
}

/**
 * Merge two option objects into a new one.
 * Core utility used in both instantiation and inheritance.
 *
 * @param {Object} parent
 * @param {Object} child
 * @param {Vue} [vm] - if vm is present, indicates this is
 *                     an instantiation merge.
 */

function mergeOptions(parent, child, vm) {
  guardComponents(child);
  guardProps(child);
  if (process.env.NODE_ENV !== 'production') {
    if (child.propsData && !vm) {
      warn('propsData can only be used as an instantiation option.');
    }
  }
  var options = {};
  var key;
  if (child['extends']) {
    parent = typeof child['extends'] === 'function' ? mergeOptions(parent, child['extends'].options, vm) : mergeOptions(parent, child['extends'], vm);
  }
  if (child.mixins) {
    for (var i = 0, l = child.mixins.length; i < l; i++) {
      var mixin = child.mixins[i];
      var mixinOptions = mixin.prototype instanceof Vue ? mixin.options : mixin;
      parent = mergeOptions(parent, mixinOptions, vm);
    }
  }
  for (key in parent) {
    mergeField(key);
  }
  for (key in child) {
    if (!hasOwn(parent, key)) {
      mergeField(key);
    }
  }
  function mergeField(key) {
    var strat = strats[key] || defaultStrat;
    options[key] = strat(parent[key], child[key], vm, key);
  }
  return options;
}

/**
 * Resolve an asset.
 * This function is used because child instances need access
 * to assets defined in its ancestor chain.
 *
 * @param {Object} options
 * @param {String} type
 * @param {String} id
 * @param {Boolean} warnMissing
 * @return {Object|Function}
 */

function resolveAsset(options, type, id, warnMissing) {
  /* istanbul ignore if */
  if (typeof id !== 'string') {
    return;
  }
  var assets = options[type];
  var camelizedId;
  var res = assets[id] ||
  // camelCase ID
  assets[camelizedId = camelize(id)] ||
  // Pascal Case ID
  assets[camelizedId.charAt(0).toUpperCase() + camelizedId.slice(1)];
  if (process.env.NODE_ENV !== 'production' && warnMissing && !res) {
    warn('Failed to resolve ' + type.slice(0, -1) + ': ' + id, options);
  }
  return res;
}

var uid$1 = 0;

/**
 * A dep is an observable that can have multiple
 * directives subscribing to it.
 *
 * @constructor
 */
function Dep() {
  this.id = uid$1++;
  this.subs = [];
}

// the current target watcher being evaluated.
// this is globally unique because there could be only one
// watcher being evaluated at any time.
Dep.target = null;

/**
 * Add a directive subscriber.
 *
 * @param {Directive} sub
 */

Dep.prototype.addSub = function (sub) {
  this.subs.push(sub);
};

/**
 * Remove a directive subscriber.
 *
 * @param {Directive} sub
 */

Dep.prototype.removeSub = function (sub) {
  this.subs.$remove(sub);
};

/**
 * Add self as a dependency to the target watcher.
 */

Dep.prototype.depend = function () {
  Dep.target.addDep(this);
};

/**
 * Notify all subscribers of a new value.
 */

Dep.prototype.notify = function () {
  // stablize the subscriber list first
  var subs = toArray(this.subs);
  for (var i = 0, l = subs.length; i < l; i++) {
    subs[i].update();
  }
};

var arrayProto = Array.prototype;
var arrayMethods = Object.create(arrayProto)

/**
 * Intercept mutating methods and emit events
 */

;['push', 'pop', 'shift', 'unshift', 'splice', 'sort', 'reverse'].forEach(function (method) {
  // cache original method
  var original = arrayProto[method];
  def(arrayMethods, method, function mutator() {
    // avoid leaking arguments:
    // http://jsperf.com/closure-with-arguments
    var i = arguments.length;
    var args = new Array(i);
    while (i--) {
      args[i] = arguments[i];
    }
    var result = original.apply(this, args);
    var ob = this.__ob__;
    var inserted;
    switch (method) {
      case 'push':
        inserted = args;
        break;
      case 'unshift':
        inserted = args;
        break;
      case 'splice':
        inserted = args.slice(2);
        break;
    }
    if (inserted) ob.observeArray(inserted);
    // notify change
    ob.dep.notify();
    return result;
  });
});

/**
 * Swap the element at the given index with a new value
 * and emits corresponding event.
 *
 * @param {Number} index
 * @param {*} val
 * @return {*} - replaced element
 */

def(arrayProto, '$set', function $set(index, val) {
  if (index >= this.length) {
    this.length = Number(index) + 1;
  }
  return this.splice(index, 1, val)[0];
});

/**
 * Convenience method to remove the element at given index or target element reference.
 *
 * @param {*} item
 */

def(arrayProto, '$remove', function $remove(item) {
  /* istanbul ignore if */
  if (!this.length) return;
  var index = indexOf(this, item);
  if (index > -1) {
    return this.splice(index, 1);
  }
});

var arrayKeys = Object.getOwnPropertyNames(arrayMethods);

/**
 * By default, when a reactive property is set, the new value is
 * also converted to become reactive. However in certain cases, e.g.
 * v-for scope alias and props, we don't want to force conversion
 * because the value may be a nested value under a frozen data structure.
 *
 * So whenever we want to set a reactive property without forcing
 * conversion on the new value, we wrap that call inside this function.
 */

var shouldConvert = true;

function withoutConversion(fn) {
  shouldConvert = false;
  fn();
  shouldConvert = true;
}

/**
 * Observer class that are attached to each observed
 * object. Once attached, the observer converts target
 * object's property keys into getter/setters that
 * collect dependencies and dispatches updates.
 *
 * @param {Array|Object} value
 * @constructor
 */

function Observer(value) {
  this.value = value;
  this.dep = new Dep();
  def(value, '__ob__', this);
  if (isArray(value)) {
    var augment = hasProto ? protoAugment : copyAugment;
    augment(value, arrayMethods, arrayKeys);
    this.observeArray(value);
  } else {
    this.walk(value);
  }
}

// Instance methods

/**
 * Walk through each property and convert them into
 * getter/setters. This method should only be called when
 * value type is Object.
 *
 * @param {Object} obj
 */

Observer.prototype.walk = function (obj) {
  var keys = Object.keys(obj);
  for (var i = 0, l = keys.length; i < l; i++) {
    this.convert(keys[i], obj[keys[i]]);
  }
};

/**
 * Observe a list of Array items.
 *
 * @param {Array} items
 */

Observer.prototype.observeArray = function (items) {
  for (var i = 0, l = items.length; i < l; i++) {
    observe(items[i]);
  }
};

/**
 * Convert a property into getter/setter so we can emit
 * the events when the property is accessed/changed.
 *
 * @param {String} key
 * @param {*} val
 */

Observer.prototype.convert = function (key, val) {
  defineReactive(this.value, key, val);
};

/**
 * Add an owner vm, so that when $set/$delete mutations
 * happen we can notify owner vms to proxy the keys and
 * digest the watchers. This is only called when the object
 * is observed as an instance's root $data.
 *
 * @param {Vue} vm
 */

Observer.prototype.addVm = function (vm) {
  (this.vms || (this.vms = [])).push(vm);
};

/**
 * Remove an owner vm. This is called when the object is
 * swapped out as an instance's $data object.
 *
 * @param {Vue} vm
 */

Observer.prototype.removeVm = function (vm) {
  this.vms.$remove(vm);
};

// helpers

/**
 * Augment an target Object or Array by intercepting
 * the prototype chain using __proto__
 *
 * @param {Object|Array} target
 * @param {Object} src
 */

function protoAugment(target, src) {
  /* eslint-disable no-proto */
  target.__proto__ = src;
  /* eslint-enable no-proto */
}

/**
 * Augment an target Object or Array by defining
 * hidden properties.
 *
 * @param {Object|Array} target
 * @param {Object} proto
 */

function copyAugment(target, src, keys) {
  for (var i = 0, l = keys.length; i < l; i++) {
    var key = keys[i];
    def(target, key, src[key]);
  }
}

/**
 * Attempt to create an observer instance for a value,
 * returns the new observer if successfully observed,
 * or the existing observer if the value already has one.
 *
 * @param {*} value
 * @param {Vue} [vm]
 * @return {Observer|undefined}
 * @static
 */

function observe(value, vm) {
  if (!value || typeof value !== 'object') {
    return;
  }
  var ob;
  if (hasOwn(value, '__ob__') && value.__ob__ instanceof Observer) {
    ob = value.__ob__;
  } else if (shouldConvert && (isArray(value) || isPlainObject(value)) && Object.isExtensible(value) && !value._isVue) {
    ob = new Observer(value);
  }
  if (ob && vm) {
    ob.addVm(vm);
  }
  return ob;
}

/**
 * Define a reactive property on an Object.
 *
 * @param {Object} obj
 * @param {String} key
 * @param {*} val
 */

function defineReactive(obj, key, val) {
  var dep = new Dep();

  var property = Object.getOwnPropertyDescriptor(obj, key);
  if (property && property.configurable === false) {
    return;
  }

  // cater for pre-defined getter/setters
  var getter = property && property.get;
  var setter = property && property.set;

  var childOb = observe(val);
  Object.defineProperty(obj, key, {
    enumerable: true,
    configurable: true,
    get: function reactiveGetter() {
      var value = getter ? getter.call(obj) : val;
      if (Dep.target) {
        dep.depend();
        if (childOb) {
          childOb.dep.depend();
        }
        if (isArray(value)) {
          for (var e, i = 0, l = value.length; i < l; i++) {
            e = value[i];
            e && e.__ob__ && e.__ob__.dep.depend();
          }
        }
      }
      return value;
    },
    set: function reactiveSetter(newVal) {
      var value = getter ? getter.call(obj) : val;
      if (newVal === value) {
        return;
      }
      if (setter) {
        setter.call(obj, newVal);
      } else {
        val = newVal;
      }
      childOb = observe(newVal);
      dep.notify();
    }
  });
}



var util = Object.freeze({
	defineReactive: defineReactive,
	set: set,
	del: del,
	hasOwn: hasOwn,
	isLiteral: isLiteral,
	isReserved: isReserved,
	_toString: _toString,
	toNumber: toNumber,
	toBoolean: toBoolean,
	stripQuotes: stripQuotes,
	camelize: camelize,
	hyphenate: hyphenate,
	classify: classify,
	bind: bind,
	toArray: toArray,
	extend: extend,
	isObject: isObject,
	isPlainObject: isPlainObject,
	def: def,
	debounce: _debounce,
	indexOf: indexOf,
	cancellable: cancellable,
	looseEqual: looseEqual,
	isArray: isArray,
	hasProto: hasProto,
	inBrowser: inBrowser,
	devtools: devtools,
	isIE: isIE,
	isIE9: isIE9,
	isAndroid: isAndroid,
	isIOS: isIOS,
	get transitionProp () { return transitionProp; },
	get transitionEndEvent () { return transitionEndEvent; },
	get animationProp () { return animationProp; },
	get animationEndEvent () { return animationEndEvent; },
	nextTick: nextTick,
	get _Set () { return _Set; },
	query: query,
	inDoc: inDoc,
	getAttr: getAttr,
	getBindAttr: getBindAttr,
	hasBindAttr: hasBindAttr,
	before: before,
	after: after,
	remove: remove,
	prepend: prepend,
	replace: replace,
	on: on,
	off: off,
	setClass: setClass,
	addClass: addClass,
	removeClass: removeClass,
	extractContent: extractContent,
	trimNode: trimNode,
	isTemplate: isTemplate,
	createAnchor: createAnchor,
	findRef: findRef,
	mapNodeRange: mapNodeRange,
	removeNodeRange: removeNodeRange,
	isFragment: isFragment,
	getOuterHTML: getOuterHTML,
	mergeOptions: mergeOptions,
	resolveAsset: resolveAsset,
	checkComponentAttr: checkComponentAttr,
	commonTagRE: commonTagRE,
	reservedTagRE: reservedTagRE,
	get warn () { return warn; }
});

var uid = 0;

function initMixin (Vue) {
  /**
   * The main init sequence. This is called for every
   * instance, including ones that are created from extended
   * constructors.
   *
   * @param {Object} options - this options object should be
   *                           the result of merging class
   *                           options and the options passed
   *                           in to the constructor.
   */

  Vue.prototype._init = function (options) {
    options = options || {};

    this.$el = null;
    this.$parent = options.parent;
    this.$root = this.$parent ? this.$parent.$root : this;
    this.$children = [];
    this.$refs = {}; // child vm references
    this.$els = {}; // element references
    this._watchers = []; // all watchers as an array
    this._directives = []; // all directives

    // a uid
    this._uid = uid++;

    // a flag to avoid this being observed
    this._isVue = true;

    // events bookkeeping
    this._events = {}; // registered callbacks
    this._eventsCount = {}; // for $broadcast optimization

    // fragment instance properties
    this._isFragment = false;
    this._fragment = // @type {DocumentFragment}
    this._fragmentStart = // @type {Text|Comment}
    this._fragmentEnd = null; // @type {Text|Comment}

    // lifecycle state
    this._isCompiled = this._isDestroyed = this._isReady = this._isAttached = this._isBeingDestroyed = this._vForRemoving = false;
    this._unlinkFn = null;

    // context:
    // if this is a transcluded component, context
    // will be the common parent vm of this instance
    // and its host.
    this._context = options._context || this.$parent;

    // scope:
    // if this is inside an inline v-for, the scope
    // will be the intermediate scope created for this
    // repeat fragment. this is used for linking props
    // and container directives.
    this._scope = options._scope;

    // fragment:
    // if this instance is compiled inside a Fragment, it
    // needs to register itself as a child of that fragment
    // for attach/detach to work properly.
    this._frag = options._frag;
    if (this._frag) {
      this._frag.children.push(this);
    }

    // push self into parent / transclusion host
    if (this.$parent) {
      this.$parent.$children.push(this);
    }

    // merge options.
    options = this.$options = mergeOptions(this.constructor.options, options, this);

    // set ref
    this._updateRef();

    // initialize data as empty object.
    // it will be filled up in _initData().
    this._data = {};

    // call init hook
    this._callHook('init');

    // initialize data observation and scope inheritance.
    this._initState();

    // setup event system and option events.
    this._initEvents();

    // call created hook
    this._callHook('created');

    // if `el` option is passed, start compilation.
    if (options.el) {
      this.$mount(options.el);
    }
  };
}

var pathCache = new Cache(1000);

// actions
var APPEND = 0;
var PUSH = 1;
var INC_SUB_PATH_DEPTH = 2;
var PUSH_SUB_PATH = 3;

// states
var BEFORE_PATH = 0;
var IN_PATH = 1;
var BEFORE_IDENT = 2;
var IN_IDENT = 3;
var IN_SUB_PATH = 4;
var IN_SINGLE_QUOTE = 5;
var IN_DOUBLE_QUOTE = 6;
var AFTER_PATH = 7;
var ERROR = 8;

var pathStateMachine = [];

pathStateMachine[BEFORE_PATH] = {
  'ws': [BEFORE_PATH],
  'ident': [IN_IDENT, APPEND],
  '[': [IN_SUB_PATH],
  'eof': [AFTER_PATH]
};

pathStateMachine[IN_PATH] = {
  'ws': [IN_PATH],
  '.': [BEFORE_IDENT],
  '[': [IN_SUB_PATH],
  'eof': [AFTER_PATH]
};

pathStateMachine[BEFORE_IDENT] = {
  'ws': [BEFORE_IDENT],
  'ident': [IN_IDENT, APPEND]
};

pathStateMachine[IN_IDENT] = {
  'ident': [IN_IDENT, APPEND],
  '0': [IN_IDENT, APPEND],
  'number': [IN_IDENT, APPEND],
  'ws': [IN_PATH, PUSH],
  '.': [BEFORE_IDENT, PUSH],
  '[': [IN_SUB_PATH, PUSH],
  'eof': [AFTER_PATH, PUSH]
};

pathStateMachine[IN_SUB_PATH] = {
  "'": [IN_SINGLE_QUOTE, APPEND],
  '"': [IN_DOUBLE_QUOTE, APPEND],
  '[': [IN_SUB_PATH, INC_SUB_PATH_DEPTH],
  ']': [IN_PATH, PUSH_SUB_PATH],
  'eof': ERROR,
  'else': [IN_SUB_PATH, APPEND]
};

pathStateMachine[IN_SINGLE_QUOTE] = {
  "'": [IN_SUB_PATH, APPEND],
  'eof': ERROR,
  'else': [IN_SINGLE_QUOTE, APPEND]
};

pathStateMachine[IN_DOUBLE_QUOTE] = {
  '"': [IN_SUB_PATH, APPEND],
  'eof': ERROR,
  'else': [IN_DOUBLE_QUOTE, APPEND]
};

/**
 * Determine the type of a character in a keypath.
 *
 * @param {Char} ch
 * @return {String} type
 */

function getPathCharType(ch) {
  if (ch === undefined) {
    return 'eof';
  }

  var code = ch.charCodeAt(0);

  switch (code) {
    case 0x5B: // [
    case 0x5D: // ]
    case 0x2E: // .
    case 0x22: // "
    case 0x27: // '
    case 0x30:
      // 0
      return ch;

    case 0x5F: // _
    case 0x24:
      // $
      return 'ident';

    case 0x20: // Space
    case 0x09: // Tab
    case 0x0A: // Newline
    case 0x0D: // Return
    case 0xA0: // No-break space
    case 0xFEFF: // Byte Order Mark
    case 0x2028: // Line Separator
    case 0x2029:
      // Paragraph Separator
      return 'ws';
  }

  // a-z, A-Z
  if (code >= 0x61 && code <= 0x7A || code >= 0x41 && code <= 0x5A) {
    return 'ident';
  }

  // 1-9
  if (code >= 0x31 && code <= 0x39) {
    return 'number';
  }

  return 'else';
}

/**
 * Format a subPath, return its plain form if it is
 * a literal string or number. Otherwise prepend the
 * dynamic indicator (*).
 *
 * @param {String} path
 * @return {String}
 */

function formatSubPath(path) {
  var trimmed = path.trim();
  // invalid leading 0
  if (path.charAt(0) === '0' && isNaN(path)) {
    return false;
  }
  return isLiteral(trimmed) ? stripQuotes(trimmed) : '*' + trimmed;
}

/**
 * Parse a string path into an array of segments
 *
 * @param {String} path
 * @return {Array|undefined}
 */

function parse(path) {
  var keys = [];
  var index = -1;
  var mode = BEFORE_PATH;
  var subPathDepth = 0;
  var c, newChar, key, type, transition, action, typeMap;

  var actions = [];

  actions[PUSH] = function () {
    if (key !== undefined) {
      keys.push(key);
      key = undefined;
    }
  };

  actions[APPEND] = function () {
    if (key === undefined) {
      key = newChar;
    } else {
      key += newChar;
    }
  };

  actions[INC_SUB_PATH_DEPTH] = function () {
    actions[APPEND]();
    subPathDepth++;
  };

  actions[PUSH_SUB_PATH] = function () {
    if (subPathDepth > 0) {
      subPathDepth--;
      mode = IN_SUB_PATH;
      actions[APPEND]();
    } else {
      subPathDepth = 0;
      key = formatSubPath(key);
      if (key === false) {
        return false;
      } else {
        actions[PUSH]();
      }
    }
  };

  function maybeUnescapeQuote() {
    var nextChar = path[index + 1];
    if (mode === IN_SINGLE_QUOTE && nextChar === "'" || mode === IN_DOUBLE_QUOTE && nextChar === '"') {
      index++;
      newChar = '\\' + nextChar;
      actions[APPEND]();
      return true;
    }
  }

  while (mode != null) {
    index++;
    c = path[index];

    if (c === '\\' && maybeUnescapeQuote()) {
      continue;
    }

    type = getPathCharType(c);
    typeMap = pathStateMachine[mode];
    transition = typeMap[type] || typeMap['else'] || ERROR;

    if (transition === ERROR) {
      return; // parse error
    }

    mode = transition[0];
    action = actions[transition[1]];
    if (action) {
      newChar = transition[2];
      newChar = newChar === undefined ? c : newChar;
      if (action() === false) {
        return;
      }
    }

    if (mode === AFTER_PATH) {
      keys.raw = path;
      return keys;
    }
  }
}

/**
 * External parse that check for a cache hit first
 *
 * @param {String} path
 * @return {Array|undefined}
 */

function parsePath(path) {
  var hit = pathCache.get(path);
  if (!hit) {
    hit = parse(path);
    if (hit) {
      pathCache.put(path, hit);
    }
  }
  return hit;
}

/**
 * Get from an object from a path string
 *
 * @param {Object} obj
 * @param {String} path
 */

function getPath(obj, path) {
  return parseExpression$1(path).get(obj);
}

/**
 * Warn against setting non-existent root path on a vm.
 */

var warnNonExistent;
if (process.env.NODE_ENV !== 'production') {
  warnNonExistent = function (path, vm) {
    warn('You are setting a non-existent path "' + path.raw + '" ' + 'on a vm instance. Consider pre-initializing the property ' + 'with the "data" option for more reliable reactivity ' + 'and better performance.', vm);
  };
}

/**
 * Set on an object from a path
 *
 * @param {Object} obj
 * @param {String | Array} path
 * @param {*} val
 */

function setPath(obj, path, val) {
  var original = obj;
  if (typeof path === 'string') {
    path = parse(path);
  }
  if (!path || !isObject(obj)) {
    return false;
  }
  var last, key;
  for (var i = 0, l = path.length; i < l; i++) {
    last = obj;
    key = path[i];
    if (key.charAt(0) === '*') {
      key = parseExpression$1(key.slice(1)).get.call(original, original);
    }
    if (i < l - 1) {
      obj = obj[key];
      if (!isObject(obj)) {
        obj = {};
        if (process.env.NODE_ENV !== 'production' && last._isVue) {
          warnNonExistent(path, last);
        }
        set(last, key, obj);
      }
    } else {
      if (isArray(obj)) {
        obj.$set(key, val);
      } else if (key in obj) {
        obj[key] = val;
      } else {
        if (process.env.NODE_ENV !== 'production' && obj._isVue) {
          warnNonExistent(path, obj);
        }
        set(obj, key, val);
      }
    }
  }
  return true;
}

var path = Object.freeze({
  parsePath: parsePath,
  getPath: getPath,
  setPath: setPath
});

var expressionCache = new Cache(1000);

var allowedKeywords = 'Math,Date,this,true,false,null,undefined,Infinity,NaN,' + 'isNaN,isFinite,decodeURI,decodeURIComponent,encodeURI,' + 'encodeURIComponent,parseInt,parseFloat';
var allowedKeywordsRE = new RegExp('^(' + allowedKeywords.replace(/,/g, '\\b|') + '\\b)');

// keywords that don't make sense inside expressions
var improperKeywords = 'break,case,class,catch,const,continue,debugger,default,' + 'delete,do,else,export,extends,finally,for,function,if,' + 'import,in,instanceof,let,return,super,switch,throw,try,' + 'var,while,with,yield,enum,await,implements,package,' + 'protected,static,interface,private,public';
var improperKeywordsRE = new RegExp('^(' + improperKeywords.replace(/,/g, '\\b|') + '\\b)');

var wsRE = /\s/g;
var newlineRE = /\n/g;
var saveRE = /[\{,]\s*[\w\$_]+\s*:|('(?:[^'\\]|\\.)*'|"(?:[^"\\]|\\.)*"|`(?:[^`\\]|\\.)*\$\{|\}(?:[^`\\"']|\\.)*`|`(?:[^`\\]|\\.)*`)|new |typeof |void /g;
var restoreRE = /"(\d+)"/g;
var pathTestRE = /^[A-Za-z_$][\w$]*(?:\.[A-Za-z_$][\w$]*|\['.*?'\]|\[".*?"\]|\[\d+\]|\[[A-Za-z_$][\w$]*\])*$/;
var identRE = /[^\w$\.](?:[A-Za-z_$][\w$]*)/g;
var literalValueRE$1 = /^(?:true|false|null|undefined|Infinity|NaN)$/;

function noop() {}

/**
 * Save / Rewrite / Restore
 *
 * When rewriting paths found in an expression, it is
 * possible for the same letter sequences to be found in
 * strings and Object literal property keys. Therefore we
 * remove and store these parts in a temporary array, and
 * restore them after the path rewrite.
 */

var saved = [];

/**
 * Save replacer
 *
 * The save regex can match two possible cases:
 * 1. An opening object literal
 * 2. A string
 * If matched as a plain string, we need to escape its
 * newlines, since the string needs to be preserved when
 * generating the function body.
 *
 * @param {String} str
 * @param {String} isString - str if matched as a string
 * @return {String} - placeholder with index
 */

function save(str, isString) {
  var i = saved.length;
  saved[i] = isString ? str.replace(newlineRE, '\\n') : str;
  return '"' + i + '"';
}

/**
 * Path rewrite replacer
 *
 * @param {String} raw
 * @return {String}
 */

function rewrite(raw) {
  var c = raw.charAt(0);
  var path = raw.slice(1);
  if (allowedKeywordsRE.test(path)) {
    return raw;
  } else {
    path = path.indexOf('"') > -1 ? path.replace(restoreRE, restore) : path;
    return c + 'scope.' + path;
  }
}

/**
 * Restore replacer
 *
 * @param {String} str
 * @param {String} i - matched save index
 * @return {String}
 */

function restore(str, i) {
  return saved[i];
}

/**
 * Rewrite an expression, prefixing all path accessors with
 * `scope.` and generate getter/setter functions.
 *
 * @param {String} exp
 * @return {Function}
 */

function compileGetter(exp) {
  if (improperKeywordsRE.test(exp)) {
    process.env.NODE_ENV !== 'production' && warn('Avoid using reserved keywords in expression: ' + exp);
  }
  // reset state
  saved.length = 0;
  // save strings and object literal keys
  var body = exp.replace(saveRE, save).replace(wsRE, '');
  // rewrite all paths
  // pad 1 space here because the regex matches 1 extra char
  body = (' ' + body).replace(identRE, rewrite).replace(restoreRE, restore);
  return makeGetterFn(body);
}

/**
 * Build a getter function. Requires eval.
 *
 * We isolate the try/catch so it doesn't affect the
 * optimization of the parse function when it is not called.
 *
 * @param {String} body
 * @return {Function|undefined}
 */

function makeGetterFn(body) {
  try {
    /* eslint-disable no-new-func */
    return new Function('scope', 'return ' + body + ';');
    /* eslint-enable no-new-func */
  } catch (e) {
    if (process.env.NODE_ENV !== 'production') {
      /* istanbul ignore if */
      if (e.toString().match(/unsafe-eval|CSP/)) {
        warn('It seems you are using the default build of Vue.js in an environment ' + 'with Content Security Policy that prohibits unsafe-eval. ' + 'Use the CSP-compliant build instead: ' + 'http://vuejs.org/guide/installation.html#CSP-compliant-build');
      } else {
        warn('Invalid expression. ' + 'Generated function body: ' + body);
      }
    }
    return noop;
  }
}

/**
 * Compile a setter function for the expression.
 *
 * @param {String} exp
 * @return {Function|undefined}
 */

function compileSetter(exp) {
  var path = parsePath(exp);
  if (path) {
    return function (scope, val) {
      setPath(scope, path, val);
    };
  } else {
    process.env.NODE_ENV !== 'production' && warn('Invalid setter expression: ' + exp);
  }
}

/**
 * Parse an expression into re-written getter/setters.
 *
 * @param {String} exp
 * @param {Boolean} needSet
 * @return {Function}
 */

function parseExpression$1(exp, needSet) {
  exp = exp.trim();
  // try cache
  var hit = expressionCache.get(exp);
  if (hit) {
    if (needSet && !hit.set) {
      hit.set = compileSetter(hit.exp);
    }
    return hit;
  }
  var res = { exp: exp };
  res.get = isSimplePath(exp) && exp.indexOf('[') < 0
  // optimized super simple getter
  ? makeGetterFn('scope.' + exp)
  // dynamic getter
  : compileGetter(exp);
  if (needSet) {
    res.set = compileSetter(exp);
  }
  expressionCache.put(exp, res);
  return res;
}

/**
 * Check if an expression is a simple path.
 *
 * @param {String} exp
 * @return {Boolean}
 */

function isSimplePath(exp) {
  return pathTestRE.test(exp) &&
  // don't treat literal values as paths
  !literalValueRE$1.test(exp) &&
  // Math constants e.g. Math.PI, Math.E etc.
  exp.slice(0, 5) !== 'Math.';
}

var expression = Object.freeze({
  parseExpression: parseExpression$1,
  isSimplePath: isSimplePath
});

// we have two separate queues: one for directive updates
// and one for user watcher registered via $watch().
// we want to guarantee directive updates to be called
// before user watchers so that when user watchers are
// triggered, the DOM would have already been in updated
// state.

var queue = [];
var userQueue = [];
var has = {};
var circular = {};
var waiting = false;

/**
 * Reset the batcher's state.
 */

function resetBatcherState() {
  queue.length = 0;
  userQueue.length = 0;
  has = {};
  circular = {};
  waiting = false;
}

/**
 * Flush both queues and run the watchers.
 */

function flushBatcherQueue() {
  var _again = true;

  _function: while (_again) {
    _again = false;

    runBatcherQueue(queue);
    runBatcherQueue(userQueue);
    // user watchers triggered more watchers,
    // keep flushing until it depletes
    if (queue.length) {
      _again = true;
      continue _function;
    }
    // dev tool hook
    /* istanbul ignore if */
    if (devtools && config.devtools) {
      devtools.emit('flush');
    }
    resetBatcherState();
  }
}

/**
 * Run the watchers in a single queue.
 *
 * @param {Array} queue
 */

function runBatcherQueue(queue) {
  // do not cache length because more watchers might be pushed
  // as we run existing watchers
  for (var i = 0; i < queue.length; i++) {
    var watcher = queue[i];
    var id = watcher.id;
    has[id] = null;
    watcher.run();
    // in dev build, check and stop circular updates.
    if (process.env.NODE_ENV !== 'production' && has[id] != null) {
      circular[id] = (circular[id] || 0) + 1;
      if (circular[id] > config._maxUpdateCount) {
        warn('You may have an infinite update loop for watcher ' + 'with expression "' + watcher.expression + '"', watcher.vm);
        break;
      }
    }
  }
  queue.length = 0;
}

/**
 * Push a watcher into the watcher queue.
 * Jobs with duplicate IDs will be skipped unless it's
 * pushed when the queue is being flushed.
 *
 * @param {Watcher} watcher
 *   properties:
 *   - {Number} id
 *   - {Function} run
 */

function pushWatcher(watcher) {
  var id = watcher.id;
  if (has[id] == null) {
    // push watcher into appropriate queue
    var q = watcher.user ? userQueue : queue;
    has[id] = q.length;
    q.push(watcher);
    // queue the flush
    if (!waiting) {
      waiting = true;
      nextTick(flushBatcherQueue);
    }
  }
}

var uid$2 = 0;

/**
 * A watcher parses an expression, collects dependencies,
 * and fires callback when the expression value changes.
 * This is used for both the $watch() api and directives.
 *
 * @param {Vue} vm
 * @param {String|Function} expOrFn
 * @param {Function} cb
 * @param {Object} options
 *                 - {Array} filters
 *                 - {Boolean} twoWay
 *                 - {Boolean} deep
 *                 - {Boolean} user
 *                 - {Boolean} sync
 *                 - {Boolean} lazy
 *                 - {Function} [preProcess]
 *                 - {Function} [postProcess]
 * @constructor
 */
function Watcher(vm, expOrFn, cb, options) {
  // mix in options
  if (options) {
    extend(this, options);
  }
  var isFn = typeof expOrFn === 'function';
  this.vm = vm;
  vm._watchers.push(this);
  this.expression = expOrFn;
  this.cb = cb;
  this.id = ++uid$2; // uid for batching
  this.active = true;
  this.dirty = this.lazy; // for lazy watchers
  this.deps = [];
  this.newDeps = [];
  this.depIds = new _Set();
  this.newDepIds = new _Set();
  this.prevError = null; // for async error stacks
  // parse expression for getter/setter
  if (isFn) {
    this.getter = expOrFn;
    this.setter = undefined;
  } else {
    var res = parseExpression$1(expOrFn, this.twoWay);
    this.getter = res.get;
    this.setter = res.set;
  }
  this.value = this.lazy ? undefined : this.get();
  // state for avoiding false triggers for deep and Array
  // watchers during vm._digest()
  this.queued = this.shallow = false;
}

/**
 * Evaluate the getter, and re-collect dependencies.
 */

Watcher.prototype.get = function () {
  this.beforeGet();
  var scope = this.scope || this.vm;
  var value;
  try {
    value = this.getter.call(scope, scope);
  } catch (e) {
    if (process.env.NODE_ENV !== 'production' && config.warnExpressionErrors) {
      warn('Error when evaluating expression ' + '"' + this.expression + '": ' + e.toString(), this.vm);
    }
  }
  // "touch" every property so they are all tracked as
  // dependencies for deep watching
  if (this.deep) {
    traverse(value);
  }
  if (this.preProcess) {
    value = this.preProcess(value);
  }
  if (this.filters) {
    value = scope._applyFilters(value, null, this.filters, false);
  }
  if (this.postProcess) {
    value = this.postProcess(value);
  }
  this.afterGet();
  return value;
};

/**
 * Set the corresponding value with the setter.
 *
 * @param {*} value
 */

Watcher.prototype.set = function (value) {
  var scope = this.scope || this.vm;
  if (this.filters) {
    value = scope._applyFilters(value, this.value, this.filters, true);
  }
  try {
    this.setter.call(scope, scope, value);
  } catch (e) {
    if (process.env.NODE_ENV !== 'production' && config.warnExpressionErrors) {
      warn('Error when evaluating setter ' + '"' + this.expression + '": ' + e.toString(), this.vm);
    }
  }
  // two-way sync for v-for alias
  var forContext = scope.$forContext;
  if (forContext && forContext.alias === this.expression) {
    if (forContext.filters) {
      process.env.NODE_ENV !== 'production' && warn('It seems you are using two-way binding on ' + 'a v-for alias (' + this.expression + '), and the ' + 'v-for has filters. This will not work properly. ' + 'Either remove the filters or use an array of ' + 'objects and bind to object properties instead.', this.vm);
      return;
    }
    forContext._withLock(function () {
      if (scope.$key) {
        // original is an object
        forContext.rawValue[scope.$key] = value;
      } else {
        forContext.rawValue.$set(scope.$index, value);
      }
    });
  }
};

/**
 * Prepare for dependency collection.
 */

Watcher.prototype.beforeGet = function () {
  Dep.target = this;
};

/**
 * Add a dependency to this directive.
 *
 * @param {Dep} dep
 */

Watcher.prototype.addDep = function (dep) {
  var id = dep.id;
  if (!this.newDepIds.has(id)) {
    this.newDepIds.add(id);
    this.newDeps.push(dep);
    if (!this.depIds.has(id)) {
      dep.addSub(this);
    }
  }
};

/**
 * Clean up for dependency collection.
 */

Watcher.prototype.afterGet = function () {
  Dep.target = null;
  var i = this.deps.length;
  while (i--) {
    var dep = this.deps[i];
    if (!this.newDepIds.has(dep.id)) {
      dep.removeSub(this);
    }
  }
  var tmp = this.depIds;
  this.depIds = this.newDepIds;
  this.newDepIds = tmp;
  this.newDepIds.clear();
  tmp = this.deps;
  this.deps = this.newDeps;
  this.newDeps = tmp;
  this.newDeps.length = 0;
};

/**
 * Subscriber interface.
 * Will be called when a dependency changes.
 *
 * @param {Boolean} shallow
 */

Watcher.prototype.update = function (shallow) {
  if (this.lazy) {
    this.dirty = true;
  } else if (this.sync || !config.async) {
    this.run();
  } else {
    // if queued, only overwrite shallow with non-shallow,
    // but not the other way around.
    this.shallow = this.queued ? shallow ? this.shallow : false : !!shallow;
    this.queued = true;
    // record before-push error stack in debug mode
    /* istanbul ignore if */
    if (process.env.NODE_ENV !== 'production' && config.debug) {
      this.prevError = new Error('[vue] async stack trace');
    }
    pushWatcher(this);
  }
};

/**
 * Batcher job interface.
 * Will be called by the batcher.
 */

Watcher.prototype.run = function () {
  if (this.active) {
    var value = this.get();
    if (value !== this.value ||
    // Deep watchers and watchers on Object/Arrays should fire even
    // when the value is the same, because the value may
    // have mutated; but only do so if this is a
    // non-shallow update (caused by a vm digest).
    (isObject(value) || this.deep) && !this.shallow) {
      // set new value
      var oldValue = this.value;
      this.value = value;
      // in debug + async mode, when a watcher callbacks
      // throws, we also throw the saved before-push error
      // so the full cross-tick stack trace is available.
      var prevError = this.prevError;
      /* istanbul ignore if */
      if (process.env.NODE_ENV !== 'production' && config.debug && prevError) {
        this.prevError = null;
        try {
          this.cb.call(this.vm, value, oldValue);
        } catch (e) {
          nextTick(function () {
            throw prevError;
          }, 0);
          throw e;
        }
      } else {
        this.cb.call(this.vm, value, oldValue);
      }
    }
    this.queued = this.shallow = false;
  }
};

/**
 * Evaluate the value of the watcher.
 * This only gets called for lazy watchers.
 */

Watcher.prototype.evaluate = function () {
  // avoid overwriting another watcher that is being
  // collected.
  var current = Dep.target;
  this.value = this.get();
  this.dirty = false;
  Dep.target = current;
};

/**
 * Depend on all deps collected by this watcher.
 */

Watcher.prototype.depend = function () {
  var i = this.deps.length;
  while (i--) {
    this.deps[i].depend();
  }
};

/**
 * Remove self from all dependencies' subcriber list.
 */

Watcher.prototype.teardown = function () {
  if (this.active) {
    // remove self from vm's watcher list
    // this is a somewhat expensive operation so we skip it
    // if the vm is being destroyed or is performing a v-for
    // re-render (the watcher list is then filtered by v-for).
    if (!this.vm._isBeingDestroyed && !this.vm._vForRemoving) {
      this.vm._watchers.$remove(this);
    }
    var i = this.deps.length;
    while (i--) {
      this.deps[i].removeSub(this);
    }
    this.active = false;
    this.vm = this.cb = this.value = null;
  }
};

/**
 * Recrusively traverse an object to evoke all converted
 * getters, so that every nested property inside the object
 * is collected as a "deep" dependency.
 *
 * @param {*} val
 */

var seenObjects = new _Set();
function traverse(val, seen) {
  var i = undefined,
      keys = undefined;
  if (!seen) {
    seen = seenObjects;
    seen.clear();
  }
  var isA = isArray(val);
  var isO = isObject(val);
  if ((isA || isO) && Object.isExtensible(val)) {
    if (val.__ob__) {
      var depId = val.__ob__.dep.id;
      if (seen.has(depId)) {
        return;
      } else {
        seen.add(depId);
      }
    }
    if (isA) {
      i = val.length;
      while (i--) traverse(val[i], seen);
    } else if (isO) {
      keys = Object.keys(val);
      i = keys.length;
      while (i--) traverse(val[keys[i]], seen);
    }
  }
}

var text$1 = {

  bind: function bind() {
    this.attr = this.el.nodeType === 3 ? 'data' : 'textContent';
  },

  update: function update(value) {
    this.el[this.attr] = _toString(value);
  }
};

var templateCache = new Cache(1000);
var idSelectorCache = new Cache(1000);

var map = {
  efault: [0, '', ''],
  legend: [1, '<fieldset>', '</fieldset>'],
  tr: [2, '<table><tbody>', '</tbody></table>'],
  col: [2, '<table><tbody></tbody><colgroup>', '</colgroup></table>']
};

map.td = map.th = [3, '<table><tbody><tr>', '</tr></tbody></table>'];

map.option = map.optgroup = [1, '<select multiple="multiple">', '</select>'];

map.thead = map.tbody = map.colgroup = map.caption = map.tfoot = [1, '<table>', '</table>'];

map.g = map.defs = map.symbol = map.use = map.image = map.text = map.circle = map.ellipse = map.line = map.path = map.polygon = map.polyline = map.rect = [1, '<svg ' + 'xmlns="http://www.w3.org/2000/svg" ' + 'xmlns:xlink="http://www.w3.org/1999/xlink" ' + 'xmlns:ev="http://www.w3.org/2001/xml-events"' + 'version="1.1">', '</svg>'];

/**
 * Check if a node is a supported template node with a
 * DocumentFragment content.
 *
 * @param {Node} node
 * @return {Boolean}
 */

function isRealTemplate(node) {
  return isTemplate(node) && isFragment(node.content);
}

var tagRE$1 = /<([\w:-]+)/;
var entityRE = /&#?\w+?;/;
var commentRE = /<!--/;

/**
 * Convert a string template to a DocumentFragment.
 * Determines correct wrapping by tag types. Wrapping
 * strategy found in jQuery & component/domify.
 *
 * @param {String} templateString
 * @param {Boolean} raw
 * @return {DocumentFragment}
 */

function stringToFragment(templateString, raw) {
  // try a cache hit first
  var cacheKey = raw ? templateString : templateString.trim();
  var hit = templateCache.get(cacheKey);
  if (hit) {
    return hit;
  }

  var frag = document.createDocumentFragment();
  var tagMatch = templateString.match(tagRE$1);
  var entityMatch = entityRE.test(templateString);
  var commentMatch = commentRE.test(templateString);

  if (!tagMatch && !entityMatch && !commentMatch) {
    // text only, return a single text node.
    frag.appendChild(document.createTextNode(templateString));
  } else {
    var tag = tagMatch && tagMatch[1];
    var wrap = map[tag] || map.efault;
    var depth = wrap[0];
    var prefix = wrap[1];
    var suffix = wrap[2];
    var node = document.createElement('div');

    node.innerHTML = prefix + templateString + suffix;
    while (depth--) {
      node = node.lastChild;
    }

    var child;
    /* eslint-disable no-cond-assign */
    while (child = node.firstChild) {
      /* eslint-enable no-cond-assign */
      frag.appendChild(child);
    }
  }
  if (!raw) {
    trimNode(frag);
  }
  templateCache.put(cacheKey, frag);
  return frag;
}

/**
 * Convert a template node to a DocumentFragment.
 *
 * @param {Node} node
 * @return {DocumentFragment}
 */

function nodeToFragment(node) {
  // if its a template tag and the browser supports it,
  // its content is already a document fragment. However, iOS Safari has
  // bug when using directly cloned template content with touch
  // events and can cause crashes when the nodes are removed from DOM, so we
  // have to treat template elements as string templates. (#2805)
  /* istanbul ignore if */
  if (isRealTemplate(node)) {
    return stringToFragment(node.innerHTML);
  }
  // script template
  if (node.tagName === 'SCRIPT') {
    return stringToFragment(node.textContent);
  }
  // normal node, clone it to avoid mutating the original
  var clonedNode = cloneNode(node);
  var frag = document.createDocumentFragment();
  var child;
  /* eslint-disable no-cond-assign */
  while (child = clonedNode.firstChild) {
    /* eslint-enable no-cond-assign */
    frag.appendChild(child);
  }
  trimNode(frag);
  return frag;
}

// Test for the presence of the Safari template cloning bug
// https://bugs.webkit.org/showug.cgi?id=137755
var hasBrokenTemplate = (function () {
  /* istanbul ignore else */
  if (inBrowser) {
    var a = document.createElement('div');
    a.innerHTML = '<template>1</template>';
    return !a.cloneNode(true).firstChild.innerHTML;
  } else {
    return false;
  }
})();

// Test for IE10/11 textarea placeholder clone bug
var hasTextareaCloneBug = (function () {
  /* istanbul ignore else */
  if (inBrowser) {
    var t = document.createElement('textarea');
    t.placeholder = 't';
    return t.cloneNode(true).value === 't';
  } else {
    return false;
  }
})();

/**
 * 1. Deal with Safari cloning nested <template> bug by
 *    manually cloning all template instances.
 * 2. Deal with IE10/11 textarea placeholder bug by setting
 *    the correct value after cloning.
 *
 * @param {Element|DocumentFragment} node
 * @return {Element|DocumentFragment}
 */

function cloneNode(node) {
  /* istanbul ignore if */
  if (!node.querySelectorAll) {
    return node.cloneNode();
  }
  var res = node.cloneNode(true);
  var i, original, cloned;
  /* istanbul ignore if */
  if (hasBrokenTemplate) {
    var tempClone = res;
    if (isRealTemplate(node)) {
      node = node.content;
      tempClone = res.content;
    }
    original = node.querySelectorAll('template');
    if (original.length) {
      cloned = tempClone.querySelectorAll('template');
      i = cloned.length;
      while (i--) {
        cloned[i].parentNode.replaceChild(cloneNode(original[i]), cloned[i]);
      }
    }
  }
  /* istanbul ignore if */
  if (hasTextareaCloneBug) {
    if (node.tagName === 'TEXTAREA') {
      res.value = node.value;
    } else {
      original = node.querySelectorAll('textarea');
      if (original.length) {
        cloned = res.querySelectorAll('textarea');
        i = cloned.length;
        while (i--) {
          cloned[i].value = original[i].value;
        }
      }
    }
  }
  return res;
}

/**
 * Process the template option and normalizes it into a
 * a DocumentFragment that can be used as a partial or a
 * instance template.
 *
 * @param {*} template
 *        Possible values include:
 *        - DocumentFragment object
 *        - Node object of type Template
 *        - id selector: '#some-template-id'
 *        - template string: '<div><span>{{msg}}</span></div>'
 * @param {Boolean} shouldClone
 * @param {Boolean} raw
 *        inline HTML interpolation. Do not check for id
 *        selector and keep whitespace in the string.
 * @return {DocumentFragment|undefined}
 */

function parseTemplate(template, shouldClone, raw) {
  var node, frag;

  // if the template is already a document fragment,
  // do nothing
  if (isFragment(template)) {
    trimNode(template);
    return shouldClone ? cloneNode(template) : template;
  }

  if (typeof template === 'string') {
    // id selector
    if (!raw && template.charAt(0) === '#') {
      // id selector can be cached too
      frag = idSelectorCache.get(template);
      if (!frag) {
        node = document.getElementById(template.slice(1));
        if (node) {
          frag = nodeToFragment(node);
          // save selector to cache
          idSelectorCache.put(template, frag);
        }
      }
    } else {
      // normal string template
      frag = stringToFragment(template, raw);
    }
  } else if (template.nodeType) {
    // a direct node
    frag = nodeToFragment(template);
  }

  return frag && shouldClone ? cloneNode(frag) : frag;
}

var template = Object.freeze({
  cloneNode: cloneNode,
  parseTemplate: parseTemplate
});

var html = {

  bind: function bind() {
    // a comment node means this is a binding for
    // {{{ inline unescaped html }}}
    if (this.el.nodeType === 8) {
      // hold nodes
      this.nodes = [];
      // replace the placeholder with proper anchor
      this.anchor = createAnchor('v-html');
      replace(this.el, this.anchor);
    }
  },

  update: function update(value) {
    value = _toString(value);
    if (this.nodes) {
      this.swap(value);
    } else {
      this.el.innerHTML = value;
    }
  },

  swap: function swap(value) {
    // remove old nodes
    var i = this.nodes.length;
    while (i--) {
      remove(this.nodes[i]);
    }
    // convert new value to a fragment
    // do not attempt to retrieve from id selector
    var frag = parseTemplate(value, true, true);
    // save a reference to these nodes so we can remove later
    this.nodes = toArray(frag.childNodes);
    before(frag, this.anchor);
  }
};

/**
 * Abstraction for a partially-compiled fragment.
 * Can optionally compile content with a child scope.
 *
 * @param {Function} linker
 * @param {Vue} vm
 * @param {DocumentFragment} frag
 * @param {Vue} [host]
 * @param {Object} [scope]
 * @param {Fragment} [parentFrag]
 */
function Fragment(linker, vm, frag, host, scope, parentFrag) {
  this.children = [];
  this.childFrags = [];
  this.vm = vm;
  this.scope = scope;
  this.inserted = false;
  this.parentFrag = parentFrag;
  if (parentFrag) {
    parentFrag.childFrags.push(this);
  }
  this.unlink = linker(vm, frag, host, scope, this);
  var single = this.single = frag.childNodes.length === 1 &&
  // do not go single mode if the only node is an anchor
  !frag.childNodes[0].__v_anchor;
  if (single) {
    this.node = frag.childNodes[0];
    this.before = singleBefore;
    this.remove = singleRemove;
  } else {
    this.node = createAnchor('fragment-start');
    this.end = createAnchor('fragment-end');
    this.frag = frag;
    prepend(this.node, frag);
    frag.appendChild(this.end);
    this.before = multiBefore;
    this.remove = multiRemove;
  }
  this.node.__v_frag = this;
}

/**
 * Call attach/detach for all components contained within
 * this fragment. Also do so recursively for all child
 * fragments.
 *
 * @param {Function} hook
 */

Fragment.prototype.callHook = function (hook) {
  var i, l;
  for (i = 0, l = this.childFrags.length; i < l; i++) {
    this.childFrags[i].callHook(hook);
  }
  for (i = 0, l = this.children.length; i < l; i++) {
    hook(this.children[i]);
  }
};

/**
 * Insert fragment before target, single node version
 *
 * @param {Node} target
 * @param {Boolean} withTransition
 */

function singleBefore(target, withTransition) {
  this.inserted = true;
  var method = withTransition !== false ? beforeWithTransition : before;
  method(this.node, target, this.vm);
  if (inDoc(this.node)) {
    this.callHook(attach);
  }
}

/**
 * Remove fragment, single node version
 */

function singleRemove() {
  this.inserted = false;
  var shouldCallRemove = inDoc(this.node);
  var self = this;
  this.beforeRemove();
  removeWithTransition(this.node, this.vm, function () {
    if (shouldCallRemove) {
      self.callHook(detach);
    }
    self.destroy();
  });
}

/**
 * Insert fragment before target, multi-nodes version
 *
 * @param {Node} target
 * @param {Boolean} withTransition
 */

function multiBefore(target, withTransition) {
  this.inserted = true;
  var vm = this.vm;
  var method = withTransition !== false ? beforeWithTransition : before;
  mapNodeRange(this.node, this.end, function (node) {
    method(node, target, vm);
  });
  if (inDoc(this.node)) {
    this.callHook(attach);
  }
}

/**
 * Remove fragment, multi-nodes version
 */

function multiRemove() {
  this.inserted = false;
  var self = this;
  var shouldCallRemove = inDoc(this.node);
  this.beforeRemove();
  removeNodeRange(this.node, this.end, this.vm, this.frag, function () {
    if (shouldCallRemove) {
      self.callHook(detach);
    }
    self.destroy();
  });
}

/**
 * Prepare the fragment for removal.
 */

Fragment.prototype.beforeRemove = function () {
  var i, l;
  for (i = 0, l = this.childFrags.length; i < l; i++) {
    // call the same method recursively on child
    // fragments, depth-first
    this.childFrags[i].beforeRemove(false);
  }
  for (i = 0, l = this.children.length; i < l; i++) {
    // Call destroy for all contained instances,
    // with remove:false and defer:true.
    // Defer is necessary because we need to
    // keep the children to call detach hooks
    // on them.
    this.children[i].$destroy(false, true);
  }
  var dirs = this.unlink.dirs;
  for (i = 0, l = dirs.length; i < l; i++) {
    // disable the watchers on all the directives
    // so that the rendered content stays the same
    // during removal.
    dirs[i]._watcher && dirs[i]._watcher.teardown();
  }
};

/**
 * Destroy the fragment.
 */

Fragment.prototype.destroy = function () {
  if (this.parentFrag) {
    this.parentFrag.childFrags.$remove(this);
  }
  this.node.__v_frag = null;
  this.unlink();
};

/**
 * Call attach hook for a Vue instance.
 *
 * @param {Vue} child
 */

function attach(child) {
  if (!child._isAttached && inDoc(child.$el)) {
    child._callHook('attached');
  }
}

/**
 * Call detach hook for a Vue instance.
 *
 * @param {Vue} child
 */

function detach(child) {
  if (child._isAttached && !inDoc(child.$el)) {
    child._callHook('detached');
  }
}

var linkerCache = new Cache(5000);

/**
 * A factory that can be used to create instances of a
 * fragment. Caches the compiled linker if possible.
 *
 * @param {Vue} vm
 * @param {Element|String} el
 */
function FragmentFactory(vm, el) {
  this.vm = vm;
  var template;
  var isString = typeof el === 'string';
  if (isString || isTemplate(el) && !el.hasAttribute('v-if')) {
    template = parseTemplate(el, true);
  } else {
    template = document.createDocumentFragment();
    template.appendChild(el);
  }
  this.template = template;
  // linker can be cached, but only for components
  var linker;
  var cid = vm.constructor.cid;
  if (cid > 0) {
    var cacheId = cid + (isString ? el : getOuterHTML(el));
    linker = linkerCache.get(cacheId);
    if (!linker) {
      linker = compile(template, vm.$options, true);
      linkerCache.put(cacheId, linker);
    }
  } else {
    linker = compile(template, vm.$options, true);
  }
  this.linker = linker;
}

/**
 * Create a fragment instance with given host and scope.
 *
 * @param {Vue} host
 * @param {Object} scope
 * @param {Fragment} parentFrag
 */

FragmentFactory.prototype.create = function (host, scope, parentFrag) {
  var frag = cloneNode(this.template);
  return new Fragment(this.linker, this.vm, frag, host, scope, parentFrag);
};

var ON = 700;
var MODEL = 800;
var BIND = 850;
var TRANSITION = 1100;
var EL = 1500;
var COMPONENT = 1500;
var PARTIAL = 1750;
var IF = 2100;
var FOR = 2200;
var SLOT = 2300;

var uid$3 = 0;

var vFor = {

  priority: FOR,
  terminal: true,

  params: ['track-by', 'stagger', 'enter-stagger', 'leave-stagger'],

  bind: function bind() {
    if (process.env.NODE_ENV !== 'production' && this.el.hasAttribute('v-if')) {
      warn('<' + this.el.tagName.toLowerCase() + ' v-for="' + this.expression + '" v-if="' + this.el.getAttribute('v-if') + '">: ' + 'Using v-if and v-for on the same element is not recommended - ' + 'consider filtering the source Array instead.', this.vm);
    }

    // support "item in/of items" syntax
    var inMatch = this.expression.match(/(.*) (?:in|of) (.*)/);
    if (inMatch) {
      var itMatch = inMatch[1].match(/\((.*),(.*)\)/);
      if (itMatch) {
        this.iterator = itMatch[1].trim();
        this.alias = itMatch[2].trim();
      } else {
        this.alias = inMatch[1].trim();
      }
      this.expression = inMatch[2];
    }

    if (!this.alias) {
      process.env.NODE_ENV !== 'production' && warn('Invalid v-for expression "' + this.descriptor.raw + '": ' + 'alias is required.', this.vm);
      return;
    }

    // uid as a cache identifier
    this.id = '__v-for__' + ++uid$3;

    // check if this is an option list,
    // so that we know if we need to update the <select>'s
    // v-model when the option list has changed.
    // because v-model has a lower priority than v-for,
    // the v-model is not bound here yet, so we have to
    // retrive it in the actual updateModel() function.
    var tag = this.el.tagName;
    this.isOption = (tag === 'OPTION' || tag === 'OPTGROUP') && this.el.parentNode.tagName === 'SELECT';

    // setup anchor nodes
    this.start = createAnchor('v-for-start');
    this.end = createAnchor('v-for-end');
    replace(this.el, this.end);
    before(this.start, this.end);

    // cache
    this.cache = Object.create(null);

    // fragment factory
    this.factory = new FragmentFactory(this.vm, this.el);
  },

  update: function update(data) {
    this.diff(data);
    this.updateRef();
    this.updateModel();
  },

  /**
   * Diff, based on new data and old data, determine the
   * minimum amount of DOM manipulations needed to make the
   * DOM reflect the new data Array.
   *
   * The algorithm diffs the new data Array by storing a
   * hidden reference to an owner vm instance on previously
   * seen data. This allows us to achieve O(n) which is
   * better than a levenshtein distance based algorithm,
   * which is O(m * n).
   *
   * @param {Array} data
   */

  diff: function diff(data) {
    // check if the Array was converted from an Object
    var item = data[0];
    var convertedFromObject = this.fromObject = isObject(item) && hasOwn(item, '$key') && hasOwn(item, '$value');

    var trackByKey = this.params.trackBy;
    var oldFrags = this.frags;
    var frags = this.frags = new Array(data.length);
    var alias = this.alias;
    var iterator = this.iterator;
    var start = this.start;
    var end = this.end;
    var inDocument = inDoc(start);
    var init = !oldFrags;
    var i, l, frag, key, value, primitive;

    // First pass, go through the new Array and fill up
    // the new frags array. If a piece of data has a cached
    // instance for it, we reuse it. Otherwise build a new
    // instance.
    for (i = 0, l = data.length; i < l; i++) {
      item = data[i];
      key = convertedFromObject ? item.$key : null;
      value = convertedFromObject ? item.$value : item;
      primitive = !isObject(value);
      frag = !init && this.getCachedFrag(value, i, key);
      if (frag) {
        // reusable fragment
        frag.reused = true;
        // update $index
        frag.scope.$index = i;
        // update $key
        if (key) {
          frag.scope.$key = key;
        }
        // update iterator
        if (iterator) {
          frag.scope[iterator] = key !== null ? key : i;
        }
        // update data for track-by, object repeat &
        // primitive values.
        if (trackByKey || convertedFromObject || primitive) {
          withoutConversion(function () {
            frag.scope[alias] = value;
          });
        }
      } else {
        // new instance
        frag = this.create(value, alias, i, key);
        frag.fresh = !init;
      }
      frags[i] = frag;
      if (init) {
        frag.before(end);
      }
    }

    // we're done for the initial render.
    if (init) {
      return;
    }

    // Second pass, go through the old fragments and
    // destroy those who are not reused (and remove them
    // from cache)
    var removalIndex = 0;
    var totalRemoved = oldFrags.length - frags.length;
    // when removing a large number of fragments, watcher removal
    // turns out to be a perf bottleneck, so we batch the watcher
    // removals into a single filter call!
    this.vm._vForRemoving = true;
    for (i = 0, l = oldFrags.length; i < l; i++) {
      frag = oldFrags[i];
      if (!frag.reused) {
        this.deleteCachedFrag(frag);
        this.remove(frag, removalIndex++, totalRemoved, inDocument);
      }
    }
    this.vm._vForRemoving = false;
    if (removalIndex) {
      this.vm._watchers = this.vm._watchers.filter(function (w) {
        return w.active;
      });
    }

    // Final pass, move/insert new fragments into the
    // right place.
    var targetPrev, prevEl, currentPrev;
    var insertionIndex = 0;
    for (i = 0, l = frags.length; i < l; i++) {
      frag = frags[i];
      // this is the frag that we should be after
      targetPrev = frags[i - 1];
      prevEl = targetPrev ? targetPrev.staggerCb ? targetPrev.staggerAnchor : targetPrev.end || targetPrev.node : start;
      if (frag.reused && !frag.staggerCb) {
        currentPrev = findPrevFrag(frag, start, this.id);
        if (currentPrev !== targetPrev && (!currentPrev ||
        // optimization for moving a single item.
        // thanks to suggestions by @livoras in #1807
        findPrevFrag(currentPrev, start, this.id) !== targetPrev)) {
          this.move(frag, prevEl);
        }
      } else {
        // new instance, or still in stagger.
        // insert with updated stagger index.
        this.insert(frag, insertionIndex++, prevEl, inDocument);
      }
      frag.reused = frag.fresh = false;
    }
  },

  /**
   * Create a new fragment instance.
   *
   * @param {*} value
   * @param {String} alias
   * @param {Number} index
   * @param {String} [key]
   * @return {Fragment}
   */

  create: function create(value, alias, index, key) {
    var host = this._host;
    // create iteration scope
    var parentScope = this._scope || this.vm;
    var scope = Object.create(parentScope);
    // ref holder for the scope
    scope.$refs = Object.create(parentScope.$refs);
    scope.$els = Object.create(parentScope.$els);
    // make sure point $parent to parent scope
    scope.$parent = parentScope;
    // for two-way binding on alias
    scope.$forContext = this;
    // define scope properties
    // important: define the scope alias without forced conversion
    // so that frozen data structures remain non-reactive.
    withoutConversion(function () {
      defineReactive(scope, alias, value);
    });
    defineReactive(scope, '$index', index);
    if (key) {
      defineReactive(scope, '$key', key);
    } else if (scope.$key) {
      // avoid accidental fallback
      def(scope, '$key', null);
    }
    if (this.iterator) {
      defineReactive(scope, this.iterator, key !== null ? key : index);
    }
    var frag = this.factory.create(host, scope, this._frag);
    frag.forId = this.id;
    this.cacheFrag(value, frag, index, key);
    return frag;
  },

  /**
   * Update the v-ref on owner vm.
   */

  updateRef: function updateRef() {
    var ref = this.descriptor.ref;
    if (!ref) return;
    var hash = (this._scope || this.vm).$refs;
    var refs;
    if (!this.fromObject) {
      refs = this.frags.map(findVmFromFrag);
    } else {
      refs = {};
      this.frags.forEach(function (frag) {
        refs[frag.scope.$key] = findVmFromFrag(frag);
      });
    }
    hash[ref] = refs;
  },

  /**
   * For option lists, update the containing v-model on
   * parent <select>.
   */

  updateModel: function updateModel() {
    if (this.isOption) {
      var parent = this.start.parentNode;
      var model = parent && parent.__v_model;
      if (model) {
        model.forceUpdate();
      }
    }
  },

  /**
   * Insert a fragment. Handles staggering.
   *
   * @param {Fragment} frag
   * @param {Number} index
   * @param {Node} prevEl
   * @param {Boolean} inDocument
   */

  insert: function insert(frag, index, prevEl, inDocument) {
    if (frag.staggerCb) {
      frag.staggerCb.cancel();
      frag.staggerCb = null;
    }
    var staggerAmount = this.getStagger(frag, index, null, 'enter');
    if (inDocument && staggerAmount) {
      // create an anchor and insert it synchronously,
      // so that we can resolve the correct order without
      // worrying about some elements not inserted yet
      var anchor = frag.staggerAnchor;
      if (!anchor) {
        anchor = frag.staggerAnchor = createAnchor('stagger-anchor');
        anchor.__v_frag = frag;
      }
      after(anchor, prevEl);
      var op = frag.staggerCb = cancellable(function () {
        frag.staggerCb = null;
        frag.before(anchor);
        remove(anchor);
      });
      setTimeout(op, staggerAmount);
    } else {
      var target = prevEl.nextSibling;
      /* istanbul ignore if */
      if (!target) {
        // reset end anchor position in case the position was messed up
        // by an external drag-n-drop library.
        after(this.end, prevEl);
        target = this.end;
      }
      frag.before(target);
    }
  },

  /**
   * Remove a fragment. Handles staggering.
   *
   * @param {Fragment} frag
   * @param {Number} index
   * @param {Number} total
   * @param {Boolean} inDocument
   */

  remove: function remove(frag, index, total, inDocument) {
    if (frag.staggerCb) {
      frag.staggerCb.cancel();
      frag.staggerCb = null;
      // it's not possible for the same frag to be removed
      // twice, so if we have a pending stagger callback,
      // it means this frag is queued for enter but removed
      // before its transition started. Since it is already
      // destroyed, we can just leave it in detached state.
      return;
    }
    var staggerAmount = this.getStagger(frag, index, total, 'leave');
    if (inDocument && staggerAmount) {
      var op = frag.staggerCb = cancellable(function () {
        frag.staggerCb = null;
        frag.remove();
      });
      setTimeout(op, staggerAmount);
    } else {
      frag.remove();
    }
  },

  /**
   * Move a fragment to a new position.
   * Force no transition.
   *
   * @param {Fragment} frag
   * @param {Node} prevEl
   */

  move: function move(frag, prevEl) {
    // fix a common issue with Sortable:
    // if prevEl doesn't have nextSibling, this means it's
    // been dragged after the end anchor. Just re-position
    // the end anchor to the end of the container.
    /* istanbul ignore if */
    if (!prevEl.nextSibling) {
      this.end.parentNode.appendChild(this.end);
    }
    frag.before(prevEl.nextSibling, false);
  },

  /**
   * Cache a fragment using track-by or the object key.
   *
   * @param {*} value
   * @param {Fragment} frag
   * @param {Number} index
   * @param {String} [key]
   */

  cacheFrag: function cacheFrag(value, frag, index, key) {
    var trackByKey = this.params.trackBy;
    var cache = this.cache;
    var primitive = !isObject(value);
    var id;
    if (key || trackByKey || primitive) {
      id = getTrackByKey(index, key, value, trackByKey);
      if (!cache[id]) {
        cache[id] = frag;
      } else if (trackByKey !== '$index') {
        process.env.NODE_ENV !== 'production' && this.warnDuplicate(value);
      }
    } else {
      id = this.id;
      if (hasOwn(value, id)) {
        if (value[id] === null) {
          value[id] = frag;
        } else {
          process.env.NODE_ENV !== 'production' && this.warnDuplicate(value);
        }
      } else if (Object.isExtensible(value)) {
        def(value, id, frag);
      } else if (process.env.NODE_ENV !== 'production') {
        warn('Frozen v-for objects cannot be automatically tracked, make sure to ' + 'provide a track-by key.');
      }
    }
    frag.raw = value;
  },

  /**
   * Get a cached fragment from the value/index/key
   *
   * @param {*} value
   * @param {Number} index
   * @param {String} key
   * @return {Fragment}
   */

  getCachedFrag: function getCachedFrag(value, index, key) {
    var trackByKey = this.params.trackBy;
    var primitive = !isObject(value);
    var frag;
    if (key || trackByKey || primitive) {
      var id = getTrackByKey(index, key, value, trackByKey);
      frag = this.cache[id];
    } else {
      frag = value[this.id];
    }
    if (frag && (frag.reused || frag.fresh)) {
      process.env.NODE_ENV !== 'production' && this.warnDuplicate(value);
    }
    return frag;
  },

  /**
   * Delete a fragment from cache.
   *
   * @param {Fragment} frag
   */

  deleteCachedFrag: function deleteCachedFrag(frag) {
    var value = frag.raw;
    var trackByKey = this.params.trackBy;
    var scope = frag.scope;
    var index = scope.$index;
    // fix #948: avoid accidentally fall through to
    // a parent repeater which happens to have $key.
    var key = hasOwn(scope, '$key') && scope.$key;
    var primitive = !isObject(value);
    if (trackByKey || key || primitive) {
      var id = getTrackByKey(index, key, value, trackByKey);
      this.cache[id] = null;
    } else {
      value[this.id] = null;
      frag.raw = null;
    }
  },

  /**
   * Get the stagger amount for an insertion/removal.
   *
   * @param {Fragment} frag
   * @param {Number} index
   * @param {Number} total
   * @param {String} type
   */

  getStagger: function getStagger(frag, index, total, type) {
    type = type + 'Stagger';
    var trans = frag.node.__v_trans;
    var hooks = trans && trans.hooks;
    var hook = hooks && (hooks[type] || hooks.stagger);
    return hook ? hook.call(frag, index, total) : index * parseInt(this.params[type] || this.params.stagger, 10);
  },

  /**
   * Pre-process the value before piping it through the
   * filters. This is passed to and called by the watcher.
   */

  _preProcess: function _preProcess(value) {
    // regardless of type, store the un-filtered raw value.
    this.rawValue = value;
    return value;
  },

  /**
   * Post-process the value after it has been piped through
   * the filters. This is passed to and called by the watcher.
   *
   * It is necessary for this to be called during the
   * watcher's dependency collection phase because we want
   * the v-for to update when the source Object is mutated.
   */

  _postProcess: function _postProcess(value) {
    if (isArray(value)) {
      return value;
    } else if (isPlainObject(value)) {
      // convert plain object to array.
      var keys = Object.keys(value);
      var i = keys.length;
      var res = new Array(i);
      var key;
      while (i--) {
        key = keys[i];
        res[i] = {
          $key: key,
          $value: value[key]
        };
      }
      return res;
    } else {
      if (typeof value === 'number' && !isNaN(value)) {
        value = range(value);
      }
      return value || [];
    }
  },

  unbind: function unbind() {
    if (this.descriptor.ref) {
      (this._scope || this.vm).$refs[this.descriptor.ref] = null;
    }
    if (this.frags) {
      var i = this.frags.length;
      var frag;
      while (i--) {
        frag = this.frags[i];
        this.deleteCachedFrag(frag);
        frag.destroy();
      }
    }
  }
};

/**
 * Helper to find the previous element that is a fragment
 * anchor. This is necessary because a destroyed frag's
 * element could still be lingering in the DOM before its
 * leaving transition finishes, but its inserted flag
 * should have been set to false so we can skip them.
 *
 * If this is a block repeat, we want to make sure we only
 * return frag that is bound to this v-for. (see #929)
 *
 * @param {Fragment} frag
 * @param {Comment|Text} anchor
 * @param {String} id
 * @return {Fragment}
 */

function findPrevFrag(frag, anchor, id) {
  var el = frag.node.previousSibling;
  /* istanbul ignore if */
  if (!el) return;
  frag = el.__v_frag;
  while ((!frag || frag.forId !== id || !frag.inserted) && el !== anchor) {
    el = el.previousSibling;
    /* istanbul ignore if */
    if (!el) return;
    frag = el.__v_frag;
  }
  return frag;
}

/**
 * Create a range array from given number.
 *
 * @param {Number} n
 * @return {Array}
 */

function range(n) {
  var i = -1;
  var ret = new Array(Math.floor(n));
  while (++i < n) {
    ret[i] = i;
  }
  return ret;
}

/**
 * Get the track by key for an item.
 *
 * @param {Number} index
 * @param {String} key
 * @param {*} value
 * @param {String} [trackByKey]
 */

function getTrackByKey(index, key, value, trackByKey) {
  return trackByKey ? trackByKey === '$index' ? index : trackByKey.charAt(0).match(/\w/) ? getPath(value, trackByKey) : value[trackByKey] : key || value;
}

if (process.env.NODE_ENV !== 'production') {
  vFor.warnDuplicate = function (value) {
    warn('Duplicate value found in v-for="' + this.descriptor.raw + '": ' + JSON.stringify(value) + '. Use track-by="$index" if ' + 'you are expecting duplicate values.', this.vm);
  };
}

/**
 * Find a vm from a fragment.
 *
 * @param {Fragment} frag
 * @return {Vue|undefined}
 */

function findVmFromFrag(frag) {
  var node = frag.node;
  // handle multi-node frag
  if (frag.end) {
    while (!node.__vue__ && node !== frag.end && node.nextSibling) {
      node = node.nextSibling;
    }
  }
  return node.__vue__;
}

var vIf = {

  priority: IF,
  terminal: true,

  bind: function bind() {
    var el = this.el;
    if (!el.__vue__) {
      // check else block
      var next = el.nextElementSibling;
      if (next && getAttr(next, 'v-else') !== null) {
        remove(next);
        this.elseEl = next;
      }
      // check main block
      this.anchor = createAnchor('v-if');
      replace(el, this.anchor);
    } else {
      process.env.NODE_ENV !== 'production' && warn('v-if="' + this.expression + '" cannot be ' + 'used on an instance root element.', this.vm);
      this.invalid = true;
    }
  },

  update: function update(value) {
    if (this.invalid) return;
    if (value) {
      if (!this.frag) {
        this.insert();
      }
    } else {
      this.remove();
    }
  },

  insert: function insert() {
    if (this.elseFrag) {
      this.elseFrag.remove();
      this.elseFrag = null;
    }
    // lazy init factory
    if (!this.factory) {
      this.factory = new FragmentFactory(this.vm, this.el);
    }
    this.frag = this.factory.create(this._host, this._scope, this._frag);
    this.frag.before(this.anchor);
  },

  remove: function remove() {
    if (this.frag) {
      this.frag.remove();
      this.frag = null;
    }
    if (this.elseEl && !this.elseFrag) {
      if (!this.elseFactory) {
        this.elseFactory = new FragmentFactory(this.elseEl._context || this.vm, this.elseEl);
      }
      this.elseFrag = this.elseFactory.create(this._host, this._scope, this._frag);
      this.elseFrag.before(this.anchor);
    }
  },

  unbind: function unbind() {
    if (this.frag) {
      this.frag.destroy();
    }
    if (this.elseFrag) {
      this.elseFrag.destroy();
    }
  }
};

var show = {

  bind: function bind() {
    // check else block
    var next = this.el.nextElementSibling;
    if (next && getAttr(next, 'v-else') !== null) {
      this.elseEl = next;
    }
  },

  update: function update(value) {
    this.apply(this.el, value);
    if (this.elseEl) {
      this.apply(this.elseEl, !value);
    }
  },

  apply: function apply(el, value) {
    if (inDoc(el)) {
      applyTransition(el, value ? 1 : -1, toggle, this.vm);
    } else {
      toggle();
    }
    function toggle() {
      el.style.display = value ? '' : 'none';
    }
  }
};

var text$2 = {

  bind: function bind() {
    var self = this;
    var el = this.el;
    var isRange = el.type === 'range';
    var lazy = this.params.lazy;
    var number = this.params.number;
    var debounce = this.params.debounce;

    // handle composition events.
    //   http://blog.evanyou.me/2014/01/03/composition-event/
    // skip this for Android because it handles composition
    // events quite differently. Android doesn't trigger
    // composition events for language input methods e.g.
    // Chinese, but instead triggers them for spelling
    // suggestions... (see Discussion/#162)
    var composing = false;
    if (!isAndroid && !isRange) {
      this.on('compositionstart', function () {
        composing = true;
      });
      this.on('compositionend', function () {
        composing = false;
        // in IE11 the "compositionend" event fires AFTER
        // the "input" event, so the input handler is blocked
        // at the end... have to call it here.
        //
        // #1327: in lazy mode this is unecessary.
        if (!lazy) {
          self.listener();
        }
      });
    }

    // prevent messing with the input when user is typing,
    // and force update on blur.
    this.focused = false;
    if (!isRange && !lazy) {
      this.on('focus', function () {
        self.focused = true;
      });
      this.on('blur', function () {
        self.focused = false;
        // do not sync value after fragment removal (#2017)
        if (!self._frag || self._frag.inserted) {
          self.rawListener();
        }
      });
    }

    // Now attach the main listener
    this.listener = this.rawListener = function () {
      if (composing || !self._bound) {
        return;
      }
      var val = number || isRange ? toNumber(el.value) : el.value;
      self.set(val);
      // force update on next tick to avoid lock & same value
      // also only update when user is not typing
      nextTick(function () {
        if (self._bound && !self.focused) {
          self.update(self._watcher.value);
        }
      });
    };

    // apply debounce
    if (debounce) {
      this.listener = _debounce(this.listener, debounce);
    }

    // Support jQuery events, since jQuery.trigger() doesn't
    // trigger native events in some cases and some plugins
    // rely on $.trigger()
    //
    // We want to make sure if a listener is attached using
    // jQuery, it is also removed with jQuery, that's why
    // we do the check for each directive instance and
    // store that check result on itself. This also allows
    // easier test coverage control by unsetting the global
    // jQuery variable in tests.
    this.hasjQuery = typeof jQuery === 'function';
    if (this.hasjQuery) {
      var method = jQuery.fn.on ? 'on' : 'bind';
      jQuery(el)[method]('change', this.rawListener);
      if (!lazy) {
        jQuery(el)[method]('input', this.listener);
      }
    } else {
      this.on('change', this.rawListener);
      if (!lazy) {
        this.on('input', this.listener);
      }
    }

    // IE9 doesn't fire input event on backspace/del/cut
    if (!lazy && isIE9) {
      this.on('cut', function () {
        nextTick(self.listener);
      });
      this.on('keyup', function (e) {
        if (e.keyCode === 46 || e.keyCode === 8) {
          self.listener();
        }
      });
    }

    // set initial value if present
    if (el.hasAttribute('value') || el.tagName === 'TEXTAREA' && el.value.trim()) {
      this.afterBind = this.listener;
    }
  },

  update: function update(value) {
    // #3029 only update when the value changes. This prevent
    // browsers from overwriting values like selectionStart
    value = _toString(value);
    if (value !== this.el.value) this.el.value = value;
  },

  unbind: function unbind() {
    var el = this.el;
    if (this.hasjQuery) {
      var method = jQuery.fn.off ? 'off' : 'unbind';
      jQuery(el)[method]('change', this.listener);
      jQuery(el)[method]('input', this.listener);
    }
  }
};

var radio = {

  bind: function bind() {
    var self = this;
    var el = this.el;

    this.getValue = function () {
      // value overwrite via v-bind:value
      if (el.hasOwnProperty('_value')) {
        return el._value;
      }
      var val = el.value;
      if (self.params.number) {
        val = toNumber(val);
      }
      return val;
    };

    this.listener = function () {
      self.set(self.getValue());
    };
    this.on('change', this.listener);

    if (el.hasAttribute('checked')) {
      this.afterBind = this.listener;
    }
  },

  update: function update(value) {
    this.el.checked = looseEqual(value, this.getValue());
  }
};

var select = {

  bind: function bind() {
    var _this = this;

    var self = this;
    var el = this.el;

    // method to force update DOM using latest value.
    this.forceUpdate = function () {
      if (self._watcher) {
        self.update(self._watcher.get());
      }
    };

    // check if this is a multiple select
    var multiple = this.multiple = el.hasAttribute('multiple');

    // attach listener
    this.listener = function () {
      var value = getValue(el, multiple);
      value = self.params.number ? isArray(value) ? value.map(toNumber) : toNumber(value) : value;
      self.set(value);
    };
    this.on('change', this.listener);

    // if has initial value, set afterBind
    var initValue = getValue(el, multiple, true);
    if (multiple && initValue.length || !multiple && initValue !== null) {
      this.afterBind = this.listener;
    }

    // All major browsers except Firefox resets
    // selectedIndex with value -1 to 0 when the element
    // is appended to a new parent, therefore we have to
    // force a DOM update whenever that happens...
    this.vm.$on('hook:attached', function () {
      nextTick(_this.forceUpdate);
    });
    if (!inDoc(el)) {
      nextTick(this.forceUpdate);
    }
  },

  update: function update(value) {
    var el = this.el;
    el.selectedIndex = -1;
    var multi = this.multiple && isArray(value);
    var options = el.options;
    var i = options.length;
    var op, val;
    while (i--) {
      op = options[i];
      val = op.hasOwnProperty('_value') ? op._value : op.value;
      /* eslint-disable eqeqeq */
      op.selected = multi ? indexOf$1(value, val) > -1 : looseEqual(value, val);
      /* eslint-enable eqeqeq */
    }
  },

  unbind: function unbind() {
    /* istanbul ignore next */
    this.vm.$off('hook:attached', this.forceUpdate);
  }
};

/**
 * Get select value
 *
 * @param {SelectElement} el
 * @param {Boolean} multi
 * @param {Boolean} init
 * @return {Array|*}
 */

function getValue(el, multi, init) {
  var res = multi ? [] : null;
  var op, val, selected;
  for (var i = 0, l = el.options.length; i < l; i++) {
    op = el.options[i];
    selected = init ? op.hasAttribute('selected') : op.selected;
    if (selected) {
      val = op.hasOwnProperty('_value') ? op._value : op.value;
      if (multi) {
        res.push(val);
      } else {
        return val;
      }
    }
  }
  return res;
}

/**
 * Native Array.indexOf uses strict equal, but in this
 * case we need to match string/numbers with custom equal.
 *
 * @param {Array} arr
 * @param {*} val
 */

function indexOf$1(arr, val) {
  var i = arr.length;
  while (i--) {
    if (looseEqual(arr[i], val)) {
      return i;
    }
  }
  return -1;
}

var checkbox = {

  bind: function bind() {
    var self = this;
    var el = this.el;

    this.getValue = function () {
      return el.hasOwnProperty('_value') ? el._value : self.params.number ? toNumber(el.value) : el.value;
    };

    function getBooleanValue() {
      var val = el.checked;
      if (val && el.hasOwnProperty('_trueValue')) {
        return el._trueValue;
      }
      if (!val && el.hasOwnProperty('_falseValue')) {
        return el._falseValue;
      }
      return val;
    }

    this.listener = function () {
      var model = self._watcher.get();
      if (isArray(model)) {
        var val = self.getValue();
        var i = indexOf(model, val);
        if (el.checked) {
          if (i < 0) {
            self.set(model.concat(val));
          }
        } else if (i > -1) {
          self.set(model.slice(0, i).concat(model.slice(i + 1)));
        }
      } else {
        self.set(getBooleanValue());
      }
    };

    this.on('change', this.listener);
    if (el.hasAttribute('checked')) {
      this.afterBind = this.listener;
    }
  },

  update: function update(value) {
    var el = this.el;
    if (isArray(value)) {
      el.checked = indexOf(value, this.getValue()) > -1;
    } else {
      if (el.hasOwnProperty('_trueValue')) {
        el.checked = looseEqual(value, el._trueValue);
      } else {
        el.checked = !!value;
      }
    }
  }
};

var handlers = {
  text: text$2,
  radio: radio,
  select: select,
  checkbox: checkbox
};

var model = {

  priority: MODEL,
  twoWay: true,
  handlers: handlers,
  params: ['lazy', 'number', 'debounce'],

  /**
   * Possible elements:
   *   <select>
   *   <textarea>
   *   <input type="*">
   *     - text
   *     - checkbox
   *     - radio
   *     - number
   */

  bind: function bind() {
    // friendly warning...
    this.checkFilters();
    if (this.hasRead && !this.hasWrite) {
      process.env.NODE_ENV !== 'production' && warn('It seems you are using a read-only filter with ' + 'v-model="' + this.descriptor.raw + '". ' + 'You might want to use a two-way filter to ensure correct behavior.', this.vm);
    }
    var el = this.el;
    var tag = el.tagName;
    var handler;
    if (tag === 'INPUT') {
      handler = handlers[el.type] || handlers.text;
    } else if (tag === 'SELECT') {
      handler = handlers.select;
    } else if (tag === 'TEXTAREA') {
      handler = handlers.text;
    } else {
      process.env.NODE_ENV !== 'production' && warn('v-model does not support element type: ' + tag, this.vm);
      return;
    }
    el.__v_model = this;
    handler.bind.call(this);
    this.update = handler.update;
    this._unbind = handler.unbind;
  },

  /**
   * Check read/write filter stats.
   */

  checkFilters: function checkFilters() {
    var filters = this.filters;
    if (!filters) return;
    var i = filters.length;
    while (i--) {
      var filter = resolveAsset(this.vm.$options, 'filters', filters[i].name);
      if (typeof filter === 'function' || filter.read) {
        this.hasRead = true;
      }
      if (filter.write) {
        this.hasWrite = true;
      }
    }
  },

  unbind: function unbind() {
    this.el.__v_model = null;
    this._unbind && this._unbind();
  }
};

// keyCode aliases
var keyCodes = {
  esc: 27,
  tab: 9,
  enter: 13,
  space: 32,
  'delete': [8, 46],
  up: 38,
  left: 37,
  right: 39,
  down: 40
};

function keyFilter(handler, keys) {
  var codes = keys.map(function (key) {
    var charCode = key.charCodeAt(0);
    if (charCode > 47 && charCode < 58) {
      return parseInt(key, 10);
    }
    if (key.length === 1) {
      charCode = key.toUpperCase().charCodeAt(0);
      if (charCode > 64 && charCode < 91) {
        return charCode;
      }
    }
    return keyCodes[key];
  });
  codes = [].concat.apply([], codes);
  return function keyHandler(e) {
    if (codes.indexOf(e.keyCode) > -1) {
      return handler.call(this, e);
    }
  };
}

function stopFilter(handler) {
  return function stopHandler(e) {
    e.stopPropagation();
    return handler.call(this, e);
  };
}

function preventFilter(handler) {
  return function preventHandler(e) {
    e.preventDefault();
    return handler.call(this, e);
  };
}

function selfFilter(handler) {
  return function selfHandler(e) {
    if (e.target === e.currentTarget) {
      return handler.call(this, e);
    }
  };
}

var on$1 = {

  priority: ON,
  acceptStatement: true,
  keyCodes: keyCodes,

  bind: function bind() {
    // deal with iframes
    if (this.el.tagName === 'IFRAME' && this.arg !== 'load') {
      var self = this;
      this.iframeBind = function () {
        on(self.el.contentWindow, self.arg, self.handler, self.modifiers.capture);
      };
      this.on('load', this.iframeBind);
    }
  },

  update: function update(handler) {
    // stub a noop for v-on with no value,
    // e.g. @mousedown.prevent
    if (!this.descriptor.raw) {
      handler = function () {};
    }

    if (typeof handler !== 'function') {
      process.env.NODE_ENV !== 'production' && warn('v-on:' + this.arg + '="' + this.expression + '" expects a function value, ' + 'got ' + handler, this.vm);
      return;
    }

    // apply modifiers
    if (this.modifiers.stop) {
      handler = stopFilter(handler);
    }
    if (this.modifiers.prevent) {
      handler = preventFilter(handler);
    }
    if (this.modifiers.self) {
      handler = selfFilter(handler);
    }
    // key filter
    var keys = Object.keys(this.modifiers).filter(function (key) {
      return key !== 'stop' && key !== 'prevent' && key !== 'self' && key !== 'capture';
    });
    if (keys.length) {
      handler = keyFilter(handler, keys);
    }

    this.reset();
    this.handler = handler;

    if (this.iframeBind) {
      this.iframeBind();
    } else {
      on(this.el, this.arg, this.handler, this.modifiers.capture);
    }
  },

  reset: function reset() {
    var el = this.iframeBind ? this.el.contentWindow : this.el;
    if (this.handler) {
      off(el, this.arg, this.handler);
    }
  },

  unbind: function unbind() {
    this.reset();
  }
};

var prefixes = ['-webkit-', '-moz-', '-ms-'];
var camelPrefixes = ['Webkit', 'Moz', 'ms'];
var importantRE = /!important;?$/;
var propCache = Object.create(null);

var testEl = null;

var style = {

  deep: true,

  update: function update(value) {
    if (typeof value === 'string') {
      this.el.style.cssText = value;
    } else if (isArray(value)) {
      this.handleObject(value.reduce(extend, {}));
    } else {
      this.handleObject(value || {});
    }
  },

  handleObject: function handleObject(value) {
    // cache object styles so that only changed props
    // are actually updated.
    var cache = this.cache || (this.cache = {});
    var name, val;
    for (name in cache) {
      if (!(name in value)) {
        this.handleSingle(name, null);
        delete cache[name];
      }
    }
    for (name in value) {
      val = value[name];
      if (val !== cache[name]) {
        cache[name] = val;
        this.handleSingle(name, val);
      }
    }
  },

  handleSingle: function handleSingle(prop, value) {
    prop = normalize(prop);
    if (!prop) return; // unsupported prop
    // cast possible numbers/booleans into strings
    if (value != null) value += '';
    if (value) {
      var isImportant = importantRE.test(value) ? 'important' : '';
      if (isImportant) {
        /* istanbul ignore if */
        if (process.env.NODE_ENV !== 'production') {
          warn('It\'s probably a bad idea to use !important with inline rules. ' + 'This feature will be deprecated in a future version of Vue.');
        }
        value = value.replace(importantRE, '').trim();
        this.el.style.setProperty(prop.kebab, value, isImportant);
      } else {
        this.el.style[prop.camel] = value;
      }
    } else {
      this.el.style[prop.camel] = '';
    }
  }

};

/**
 * Normalize a CSS property name.
 * - cache result
 * - auto prefix
 * - camelCase -> dash-case
 *
 * @param {String} prop
 * @return {String}
 */

function normalize(prop) {
  if (propCache[prop]) {
    return propCache[prop];
  }
  var res = prefix(prop);
  propCache[prop] = propCache[res] = res;
  return res;
}

/**
 * Auto detect the appropriate prefix for a CSS property.
 * https://gist.github.com/paulirish/523692
 *
 * @param {String} prop
 * @return {String}
 */

function prefix(prop) {
  prop = hyphenate(prop);
  var camel = camelize(prop);
  var upper = camel.charAt(0).toUpperCase() + camel.slice(1);
  if (!testEl) {
    testEl = document.createElement('div');
  }
  var i = prefixes.length;
  var prefixed;
  if (camel !== 'filter' && camel in testEl.style) {
    return {
      kebab: prop,
      camel: camel
    };
  }
  while (i--) {
    prefixed = camelPrefixes[i] + upper;
    if (prefixed in testEl.style) {
      return {
        kebab: prefixes[i] + prop,
        camel: prefixed
      };
    }
  }
}

// xlink
var xlinkNS = 'http://www.w3.org/1999/xlink';
var xlinkRE = /^xlink:/;

// check for attributes that prohibit interpolations
var disallowedInterpAttrRE = /^v-|^:|^@|^(?:is|transition|transition-mode|debounce|track-by|stagger|enter-stagger|leave-stagger)$/;
// these attributes should also set their corresponding properties
// because they only affect the initial state of the element
var attrWithPropsRE = /^(?:value|checked|selected|muted)$/;
// these attributes expect enumrated values of "true" or "false"
// but are not boolean attributes
var enumeratedAttrRE = /^(?:draggable|contenteditable|spellcheck)$/;

// these attributes should set a hidden property for
// binding v-model to object values
var modelProps = {
  value: '_value',
  'true-value': '_trueValue',
  'false-value': '_falseValue'
};

var bind$1 = {

  priority: BIND,

  bind: function bind() {
    var attr = this.arg;
    var tag = this.el.tagName;
    // should be deep watch on object mode
    if (!attr) {
      this.deep = true;
    }
    // handle interpolation bindings
    var descriptor = this.descriptor;
    var tokens = descriptor.interp;
    if (tokens) {
      // handle interpolations with one-time tokens
      if (descriptor.hasOneTime) {
        this.expression = tokensToExp(tokens, this._scope || this.vm);
      }

      // only allow binding on native attributes
      if (disallowedInterpAttrRE.test(attr) || attr === 'name' && (tag === 'PARTIAL' || tag === 'SLOT')) {
        process.env.NODE_ENV !== 'production' && warn(attr + '="' + descriptor.raw + '": ' + 'attribute interpolation is not allowed in Vue.js ' + 'directives and special attributes.', this.vm);
        this.el.removeAttribute(attr);
        this.invalid = true;
      }

      /* istanbul ignore if */
      if (process.env.NODE_ENV !== 'production') {
        var raw = attr + '="' + descriptor.raw + '": ';
        // warn src
        if (attr === 'src') {
          warn(raw + 'interpolation in "src" attribute will cause ' + 'a 404 request. Use v-bind:src instead.', this.vm);
        }

        // warn style
        if (attr === 'style') {
          warn(raw + 'interpolation in "style" attribute will cause ' + 'the attribute to be discarded in Internet Explorer. ' + 'Use v-bind:style instead.', this.vm);
        }
      }
    }
  },

  update: function update(value) {
    if (this.invalid) {
      return;
    }
    var attr = this.arg;
    if (this.arg) {
      this.handleSingle(attr, value);
    } else {
      this.handleObject(value || {});
    }
  },

  // share object handler with v-bind:class
  handleObject: style.handleObject,

  handleSingle: function handleSingle(attr, value) {
    var el = this.el;
    var interp = this.descriptor.interp;
    if (this.modifiers.camel) {
      attr = camelize(attr);
    }
    if (!interp && attrWithPropsRE.test(attr) && attr in el) {
      var attrValue = attr === 'value' ? value == null // IE9 will set input.value to "null" for null...
      ? '' : value : value;

      if (el[attr] !== attrValue) {
        el[attr] = attrValue;
      }
    }
    // set model props
    var modelProp = modelProps[attr];
    if (!interp && modelProp) {
      el[modelProp] = value;
      // update v-model if present
      var model = el.__v_model;
      if (model) {
        model.listener();
      }
    }
    // do not set value attribute for textarea
    if (attr === 'value' && el.tagName === 'TEXTAREA') {
      el.removeAttribute(attr);
      return;
    }
    // update attribute
    if (enumeratedAttrRE.test(attr)) {
      el.setAttribute(attr, value ? 'true' : 'false');
    } else if (value != null && value !== false) {
      if (attr === 'class') {
        // handle edge case #1960:
        // class interpolation should not overwrite Vue transition class
        if (el.__v_trans) {
          value += ' ' + el.__v_trans.id + '-transition';
        }
        setClass(el, value);
      } else if (xlinkRE.test(attr)) {
        el.setAttributeNS(xlinkNS, attr, value === true ? '' : value);
      } else {
        el.setAttribute(attr, value === true ? '' : value);
      }
    } else {
      el.removeAttribute(attr);
    }
  }
};

var el = {

  priority: EL,

  bind: function bind() {
    /* istanbul ignore if */
    if (!this.arg) {
      return;
    }
    var id = this.id = camelize(this.arg);
    var refs = (this._scope || this.vm).$els;
    if (hasOwn(refs, id)) {
      refs[id] = this.el;
    } else {
      defineReactive(refs, id, this.el);
    }
  },

  unbind: function unbind() {
    var refs = (this._scope || this.vm).$els;
    if (refs[this.id] === this.el) {
      refs[this.id] = null;
    }
  }
};

var ref = {
  bind: function bind() {
    process.env.NODE_ENV !== 'production' && warn('v-ref:' + this.arg + ' must be used on a child ' + 'component. Found on <' + this.el.tagName.toLowerCase() + '>.', this.vm);
  }
};

var cloak = {
  bind: function bind() {
    var el = this.el;
    this.vm.$once('pre-hook:compiled', function () {
      el.removeAttribute('v-cloak');
    });
  }
};

// logic control
// two-way binding
// event handling
// attributes
// ref & el
// cloak
// must export plain object
var directives = {
  text: text$1,
  html: html,
  'for': vFor,
  'if': vIf,
  show: show,
  model: model,
  on: on$1,
  bind: bind$1,
  el: el,
  ref: ref,
  cloak: cloak
};

var vClass = {

  deep: true,

  update: function update(value) {
    if (!value) {
      this.cleanup();
    } else if (typeof value === 'string') {
      this.setClass(value.trim().split(/\s+/));
    } else {
      this.setClass(normalize$1(value));
    }
  },

  setClass: function setClass(value) {
    this.cleanup(value);
    for (var i = 0, l = value.length; i < l; i++) {
      var val = value[i];
      if (val) {
        apply(this.el, val, addClass);
      }
    }
    this.prevKeys = value;
  },

  cleanup: function cleanup(value) {
    var prevKeys = this.prevKeys;
    if (!prevKeys) return;
    var i = prevKeys.length;
    while (i--) {
      var key = prevKeys[i];
      if (!value || value.indexOf(key) < 0) {
        apply(this.el, key, removeClass);
      }
    }
  }
};

/**
 * Normalize objects and arrays (potentially containing objects)
 * into array of strings.
 *
 * @param {Object|Array<String|Object>} value
 * @return {Array<String>}
 */

function normalize$1(value) {
  var res = [];
  if (isArray(value)) {
    for (var i = 0, l = value.length; i < l; i++) {
      var _key = value[i];
      if (_key) {
        if (typeof _key === 'string') {
          res.push(_key);
        } else {
          for (var k in _key) {
            if (_key[k]) res.push(k);
          }
        }
      }
    }
  } else if (isObject(value)) {
    for (var key in value) {
      if (value[key]) res.push(key);
    }
  }
  return res;
}

/**
 * Add or remove a class/classes on an element
 *
 * @param {Element} el
 * @param {String} key The class name. This may or may not
 *                     contain a space character, in such a
 *                     case we'll deal with multiple class
 *                     names at once.
 * @param {Function} fn
 */

function apply(el, key, fn) {
  key = key.trim();
  if (key.indexOf(' ') === -1) {
    fn(el, key);
    return;
  }
  // The key contains one or more space characters.
  // Since a class name doesn't accept such characters, we
  // treat it as multiple classes.
  var keys = key.split(/\s+/);
  for (var i = 0, l = keys.length; i < l; i++) {
    fn(el, keys[i]);
  }
}

var component = {

  priority: COMPONENT,

  params: ['keep-alive', 'transition-mode', 'inline-template'],

  /**
   * Setup. Two possible usages:
   *
   * - static:
   *   <comp> or <div v-component="comp">
   *
   * - dynamic:
   *   <component :is="view">
   */

  bind: function bind() {
    if (!this.el.__vue__) {
      // keep-alive cache
      this.keepAlive = this.params.keepAlive;
      if (this.keepAlive) {
        this.cache = {};
      }
      // check inline-template
      if (this.params.inlineTemplate) {
        // extract inline template as a DocumentFragment
        this.inlineTemplate = extractContent(this.el, true);
      }
      // component resolution related state
      this.pendingComponentCb = this.Component = null;
      // transition related state
      this.pendingRemovals = 0;
      this.pendingRemovalCb = null;
      // create a ref anchor
      this.anchor = createAnchor('v-component');
      replace(this.el, this.anchor);
      // remove is attribute.
      // this is removed during compilation, but because compilation is
      // cached, when the component is used elsewhere this attribute
      // will remain at link time.
      this.el.removeAttribute('is');
      this.el.removeAttribute(':is');
      // remove ref, same as above
      if (this.descriptor.ref) {
        this.el.removeAttribute('v-ref:' + hyphenate(this.descriptor.ref));
      }
      // if static, build right now.
      if (this.literal) {
        this.setComponent(this.expression);
      }
    } else {
      process.env.NODE_ENV !== 'production' && warn('cannot mount component "' + this.expression + '" ' + 'on already mounted element: ' + this.el);
    }
  },

  /**
   * Public update, called by the watcher in the dynamic
   * literal scenario, e.g. <component :is="view">
   */

  update: function update(value) {
    if (!this.literal) {
      this.setComponent(value);
    }
  },

  /**
   * Switch dynamic components. May resolve the component
   * asynchronously, and perform transition based on
   * specified transition mode. Accepts a few additional
   * arguments specifically for vue-router.
   *
   * The callback is called when the full transition is
   * finished.
   *
   * @param {String} value
   * @param {Function} [cb]
   */

  setComponent: function setComponent(value, cb) {
    this.invalidatePending();
    if (!value) {
      // just remove current
      this.unbuild(true);
      this.remove(this.childVM, cb);
      this.childVM = null;
    } else {
      var self = this;
      this.resolveComponent(value, function () {
        self.mountComponent(cb);
      });
    }
  },

  /**
   * Resolve the component constructor to use when creating
   * the child vm.
   *
   * @param {String|Function} value
   * @param {Function} cb
   */

  resolveComponent: function resolveComponent(value, cb) {
    var self = this;
    this.pendingComponentCb = cancellable(function (Component) {
      self.ComponentName = Component.options.name || (typeof value === 'string' ? value : null);
      self.Component = Component;
      cb();
    });
    this.vm._resolveComponent(value, this.pendingComponentCb);
  },

  /**
   * Create a new instance using the current constructor and
   * replace the existing instance. This method doesn't care
   * whether the new component and the old one are actually
   * the same.
   *
   * @param {Function} [cb]
   */

  mountComponent: function mountComponent(cb) {
    // actual mount
    this.unbuild(true);
    var self = this;
    var activateHooks = this.Component.options.activate;
    var cached = this.getCached();
    var newComponent = this.build();
    if (activateHooks && !cached) {
      this.waitingFor = newComponent;
      callActivateHooks(activateHooks, newComponent, function () {
        if (self.waitingFor !== newComponent) {
          return;
        }
        self.waitingFor = null;
        self.transition(newComponent, cb);
      });
    } else {
      // update ref for kept-alive component
      if (cached) {
        newComponent._updateRef();
      }
      this.transition(newComponent, cb);
    }
  },

  /**
   * When the component changes or unbinds before an async
   * constructor is resolved, we need to invalidate its
   * pending callback.
   */

  invalidatePending: function invalidatePending() {
    if (this.pendingComponentCb) {
      this.pendingComponentCb.cancel();
      this.pendingComponentCb = null;
    }
  },

  /**
   * Instantiate/insert a new child vm.
   * If keep alive and has cached instance, insert that
   * instance; otherwise build a new one and cache it.
   *
   * @param {Object} [extraOptions]
   * @return {Vue} - the created instance
   */

  build: function build(extraOptions) {
    var cached = this.getCached();
    if (cached) {
      return cached;
    }
    if (this.Component) {
      // default options
      var options = {
        name: this.ComponentName,
        el: cloneNode(this.el),
        template: this.inlineTemplate,
        // make sure to add the child with correct parent
        // if this is a transcluded component, its parent
        // should be the transclusion host.
        parent: this._host || this.vm,
        // if no inline-template, then the compiled
        // linker can be cached for better performance.
        _linkerCachable: !this.inlineTemplate,
        _ref: this.descriptor.ref,
        _asComponent: true,
        _isRouterView: this._isRouterView,
        // if this is a transcluded component, context
        // will be the common parent vm of this instance
        // and its host.
        _context: this.vm,
        // if this is inside an inline v-for, the scope
        // will be the intermediate scope created for this
        // repeat fragment. this is used for linking props
        // and container directives.
        _scope: this._scope,
        // pass in the owner fragment of this component.
        // this is necessary so that the fragment can keep
        // track of its contained components in order to
        // call attach/detach hooks for them.
        _frag: this._frag
      };
      // extra options
      // in 1.0.0 this is used by vue-router only
      /* istanbul ignore if */
      if (extraOptions) {
        extend(options, extraOptions);
      }
      var child = new this.Component(options);
      if (this.keepAlive) {
        this.cache[this.Component.cid] = child;
      }
      /* istanbul ignore if */
      if (process.env.NODE_ENV !== 'production' && this.el.hasAttribute('transition') && child._isFragment) {
        warn('Transitions will not work on a fragment instance. ' + 'Template: ' + child.$options.template, child);
      }
      return child;
    }
  },

  /**
   * Try to get a cached instance of the current component.
   *
   * @return {Vue|undefined}
   */

  getCached: function getCached() {
    return this.keepAlive && this.cache[this.Component.cid];
  },

  /**
   * Teardown the current child, but defers cleanup so
   * that we can separate the destroy and removal steps.
   *
   * @param {Boolean} defer
   */

  unbuild: function unbuild(defer) {
    if (this.waitingFor) {
      if (!this.keepAlive) {
        this.waitingFor.$destroy();
      }
      this.waitingFor = null;
    }
    var child = this.childVM;
    if (!child || this.keepAlive) {
      if (child) {
        // remove ref
        child._inactive = true;
        child._updateRef(true);
      }
      return;
    }
    // the sole purpose of `deferCleanup` is so that we can
    // "deactivate" the vm right now and perform DOM removal
    // later.
    child.$destroy(false, defer);
  },

  /**
   * Remove current destroyed child and manually do
   * the cleanup after removal.
   *
   * @param {Function} cb
   */

  remove: function remove(child, cb) {
    var keepAlive = this.keepAlive;
    if (child) {
      // we may have a component switch when a previous
      // component is still being transitioned out.
      // we want to trigger only one lastest insertion cb
      // when the existing transition finishes. (#1119)
      this.pendingRemovals++;
      this.pendingRemovalCb = cb;
      var self = this;
      child.$remove(function () {
        self.pendingRemovals--;
        if (!keepAlive) child._cleanup();
        if (!self.pendingRemovals && self.pendingRemovalCb) {
          self.pendingRemovalCb();
          self.pendingRemovalCb = null;
        }
      });
    } else if (cb) {
      cb();
    }
  },

  /**
   * Actually swap the components, depending on the
   * transition mode. Defaults to simultaneous.
   *
   * @param {Vue} target
   * @param {Function} [cb]
   */

  transition: function transition(target, cb) {
    var self = this;
    var current = this.childVM;
    // for devtool inspection
    if (current) current._inactive = true;
    target._inactive = false;
    this.childVM = target;
    switch (self.params.transitionMode) {
      case 'in-out':
        target.$before(self.anchor, function () {
          self.remove(current, cb);
        });
        break;
      case 'out-in':
        self.remove(current, function () {
          target.$before(self.anchor, cb);
        });
        break;
      default:
        self.remove(current);
        target.$before(self.anchor, cb);
    }
  },

  /**
   * Unbind.
   */

  unbind: function unbind() {
    this.invalidatePending();
    // Do not defer cleanup when unbinding
    this.unbuild();
    // destroy all keep-alive cached instances
    if (this.cache) {
      for (var key in this.cache) {
        this.cache[key].$destroy();
      }
      this.cache = null;
    }
  }
};

/**
 * Call activate hooks in order (asynchronous)
 *
 * @param {Array} hooks
 * @param {Vue} vm
 * @param {Function} cb
 */

function callActivateHooks(hooks, vm, cb) {
  var total = hooks.length;
  var called = 0;
  hooks[0].call(vm, next);
  function next() {
    if (++called >= total) {
      cb();
    } else {
      hooks[called].call(vm, next);
    }
  }
}

var propBindingModes = config._propBindingModes;
var empty = {};

// regexes
var identRE$1 = /^[$_a-zA-Z]+[\w$]*$/;
var settablePathRE = /^[A-Za-z_$][\w$]*(\.[A-Za-z_$][\w$]*|\[[^\[\]]+\])*$/;

/**
 * Compile props on a root element and return
 * a props link function.
 *
 * @param {Element|DocumentFragment} el
 * @param {Array} propOptions
 * @param {Vue} vm
 * @return {Function} propsLinkFn
 */

function compileProps(el, propOptions, vm) {
  var props = [];
  var propsData = vm.$options.propsData;
  var names = Object.keys(propOptions);
  var i = names.length;
  var options, name, attr, value, path, parsed, prop;
  while (i--) {
    name = names[i];
    options = propOptions[name] || empty;

    if (process.env.NODE_ENV !== 'production' && name === '$data') {
      warn('Do not use $data as prop.', vm);
      continue;
    }

    // props could contain dashes, which will be
    // interpreted as minus calculations by the parser
    // so we need to camelize the path here
    path = camelize(name);
    if (!identRE$1.test(path)) {
      process.env.NODE_ENV !== 'production' && warn('Invalid prop key: "' + name + '". Prop keys ' + 'must be valid identifiers.', vm);
      continue;
    }

    prop = {
      name: name,
      path: path,
      options: options,
      mode: propBindingModes.ONE_WAY,
      raw: null
    };

    attr = hyphenate(name);
    // first check dynamic version
    if ((value = getBindAttr(el, attr)) === null) {
      if ((value = getBindAttr(el, attr + '.sync')) !== null) {
        prop.mode = propBindingModes.TWO_WAY;
      } else if ((value = getBindAttr(el, attr + '.once')) !== null) {
        prop.mode = propBindingModes.ONE_TIME;
      }
    }
    if (value !== null) {
      // has dynamic binding!
      prop.raw = value;
      parsed = parseDirective(value);
      value = parsed.expression;
      prop.filters = parsed.filters;
      // check binding type
      if (isLiteral(value) && !parsed.filters) {
        // for expressions containing literal numbers and
        // booleans, there's no need to setup a prop binding,
        // so we can optimize them as a one-time set.
        prop.optimizedLiteral = true;
      } else {
        prop.dynamic = true;
        // check non-settable path for two-way bindings
        if (process.env.NODE_ENV !== 'production' && prop.mode === propBindingModes.TWO_WAY && !settablePathRE.test(value)) {
          prop.mode = propBindingModes.ONE_WAY;
          warn('Cannot bind two-way prop with non-settable ' + 'parent path: ' + value, vm);
        }
      }
      prop.parentPath = value;

      // warn required two-way
      if (process.env.NODE_ENV !== 'production' && options.twoWay && prop.mode !== propBindingModes.TWO_WAY) {
        warn('Prop "' + name + '" expects a two-way binding type.', vm);
      }
    } else if ((value = getAttr(el, attr)) !== null) {
      // has literal binding!
      prop.raw = value;
    } else if (propsData && (value = propsData[name] || propsData[path]) !== null) {
      // has propsData
      prop.raw = value;
    } else if (process.env.NODE_ENV !== 'production') {
      // check possible camelCase prop usage
      var lowerCaseName = path.toLowerCase();
      value = /[A-Z\-]/.test(name) && (el.getAttribute(lowerCaseName) || el.getAttribute(':' + lowerCaseName) || el.getAttribute('v-bind:' + lowerCaseName) || el.getAttribute(':' + lowerCaseName + '.once') || el.getAttribute('v-bind:' + lowerCaseName + '.once') || el.getAttribute(':' + lowerCaseName + '.sync') || el.getAttribute('v-bind:' + lowerCaseName + '.sync'));
      if (value) {
        warn('Possible usage error for prop `' + lowerCaseName + '` - ' + 'did you mean `' + attr + '`? HTML is case-insensitive, remember to use ' + 'kebab-case for props in templates.', vm);
      } else if (options.required && (!propsData || !(name in propsData) && !(path in propsData))) {
        // warn missing required
        warn('Missing required prop: ' + name, vm);
      }
    }
    // push prop
    props.push(prop);
  }
  return makePropsLinkFn(props);
}

/**
 * Build a function that applies props to a vm.
 *
 * @param {Array} props
 * @return {Function} propsLinkFn
 */

function makePropsLinkFn(props) {
  return function propsLinkFn(vm, scope) {
    // store resolved props info
    vm._props = {};
    var inlineProps = vm.$options.propsData;
    var i = props.length;
    var prop, path, options, value, raw;
    while (i--) {
      prop = props[i];
      raw = prop.raw;
      path = prop.path;
      options = prop.options;
      vm._props[path] = prop;
      if (inlineProps && hasOwn(inlineProps, path)) {
        initProp(vm, prop, inlineProps[path]);
      }if (raw === null) {
        // initialize absent prop
        initProp(vm, prop, undefined);
      } else if (prop.dynamic) {
        // dynamic prop
        if (prop.mode === propBindingModes.ONE_TIME) {
          // one time binding
          value = (scope || vm._context || vm).$get(prop.parentPath);
          initProp(vm, prop, value);
        } else {
          if (vm._context) {
            // dynamic binding
            vm._bindDir({
              name: 'prop',
              def: propDef,
              prop: prop
            }, null, null, scope); // el, host, scope
          } else {
              // root instance
              initProp(vm, prop, vm.$get(prop.parentPath));
            }
        }
      } else if (prop.optimizedLiteral) {
        // optimized literal, cast it and just set once
        var stripped = stripQuotes(raw);
        value = stripped === raw ? toBoolean(toNumber(raw)) : stripped;
        initProp(vm, prop, value);
      } else {
        // string literal, but we need to cater for
        // Boolean props with no value, or with same
        // literal value (e.g. disabled="disabled")
        // see https://github.com/vuejs/vue-loader/issues/182
        value = options.type === Boolean && (raw === '' || raw === hyphenate(prop.name)) ? true : raw;
        initProp(vm, prop, value);
      }
    }
  };
}

/**
 * Process a prop with a rawValue, applying necessary coersions,
 * default values & assertions and call the given callback with
 * processed value.
 *
 * @param {Vue} vm
 * @param {Object} prop
 * @param {*} rawValue
 * @param {Function} fn
 */

function processPropValue(vm, prop, rawValue, fn) {
  var isSimple = prop.dynamic && isSimplePath(prop.parentPath);
  var value = rawValue;
  if (value === undefined) {
    value = getPropDefaultValue(vm, prop);
  }
  value = coerceProp(prop, value, vm);
  var coerced = value !== rawValue;
  if (!assertProp(prop, value, vm)) {
    value = undefined;
  }
  if (isSimple && !coerced) {
    withoutConversion(function () {
      fn(value);
    });
  } else {
    fn(value);
  }
}

/**
 * Set a prop's initial value on a vm and its data object.
 *
 * @param {Vue} vm
 * @param {Object} prop
 * @param {*} value
 */

function initProp(vm, prop, value) {
  processPropValue(vm, prop, value, function (value) {
    defineReactive(vm, prop.path, value);
  });
}

/**
 * Update a prop's value on a vm.
 *
 * @param {Vue} vm
 * @param {Object} prop
 * @param {*} value
 */

function updateProp(vm, prop, value) {
  processPropValue(vm, prop, value, function (value) {
    vm[prop.path] = value;
  });
}

/**
 * Get the default value of a prop.
 *
 * @param {Vue} vm
 * @param {Object} prop
 * @return {*}
 */

function getPropDefaultValue(vm, prop) {
  // no default, return undefined
  var options = prop.options;
  if (!hasOwn(options, 'default')) {
    // absent boolean value defaults to false
    return options.type === Boolean ? false : undefined;
  }
  var def = options['default'];
  // warn against non-factory defaults for Object & Array
  if (isObject(def)) {
    process.env.NODE_ENV !== 'production' && warn('Invalid default value for prop "' + prop.name + '": ' + 'Props with type Object/Array must use a factory function ' + 'to return the default value.', vm);
  }
  // call factory function for non-Function types
  return typeof def === 'function' && options.type !== Function ? def.call(vm) : def;
}

/**
 * Assert whether a prop is valid.
 *
 * @param {Object} prop
 * @param {*} value
 * @param {Vue} vm
 */

function assertProp(prop, value, vm) {
  if (!prop.options.required && ( // non-required
  prop.raw === null || // abscent
  value == null) // null or undefined
  ) {
      return true;
    }
  var options = prop.options;
  var type = options.type;
  var valid = !type;
  var expectedTypes = [];
  if (type) {
    if (!isArray(type)) {
      type = [type];
    }
    for (var i = 0; i < type.length && !valid; i++) {
      var assertedType = assertType(value, type[i]);
      expectedTypes.push(assertedType.expectedType);
      valid = assertedType.valid;
    }
  }
  if (!valid) {
    if (process.env.NODE_ENV !== 'production') {
      warn('Invalid prop: type check failed for prop "' + prop.name + '".' + ' Expected ' + expectedTypes.map(formatType).join(', ') + ', got ' + formatValue(value) + '.', vm);
    }
    return false;
  }
  var validator = options.validator;
  if (validator) {
    if (!validator(value)) {
      process.env.NODE_ENV !== 'production' && warn('Invalid prop: custom validator check failed for prop "' + prop.name + '".', vm);
      return false;
    }
  }
  return true;
}

/**
 * Force parsing value with coerce option.
 *
 * @param {*} value
 * @param {Object} options
 * @return {*}
 */

function coerceProp(prop, value, vm) {
  var coerce = prop.options.coerce;
  if (!coerce) {
    return value;
  }
  if (typeof coerce === 'function') {
    return coerce(value);
  } else {
    process.env.NODE_ENV !== 'production' && warn('Invalid coerce for prop "' + prop.name + '": expected function, got ' + typeof coerce + '.', vm);
    return value;
  }
}

/**
 * Assert the type of a value
 *
 * @param {*} value
 * @param {Function} type
 * @return {Object}
 */

function assertType(value, type) {
  var valid;
  var expectedType;
  if (type === String) {
    expectedType = 'string';
    valid = typeof value === expectedType;
  } else if (type === Number) {
    expectedType = 'number';
    valid = typeof value === expectedType;
  } else if (type === Boolean) {
    expectedType = 'boolean';
    valid = typeof value === expectedType;
  } else if (type === Function) {
    expectedType = 'function';
    valid = typeof value === expectedType;
  } else if (type === Object) {
    expectedType = 'object';
    valid = isPlainObject(value);
  } else if (type === Array) {
    expectedType = 'array';
    valid = isArray(value);
  } else {
    valid = value instanceof type;
  }
  return {
    valid: valid,
    expectedType: expectedType
  };
}

/**
 * Format type for output
 *
 * @param {String} type
 * @return {String}
 */

function formatType(type) {
  return type ? type.charAt(0).toUpperCase() + type.slice(1) : 'custom type';
}

/**
 * Format value
 *
 * @param {*} value
 * @return {String}
 */

function formatValue(val) {
  return Object.prototype.toString.call(val).slice(8, -1);
}

var bindingModes = config._propBindingModes;

var propDef = {

  bind: function bind() {
    var child = this.vm;
    var parent = child._context;
    // passed in from compiler directly
    var prop = this.descriptor.prop;
    var childKey = prop.path;
    var parentKey = prop.parentPath;
    var twoWay = prop.mode === bindingModes.TWO_WAY;

    var parentWatcher = this.parentWatcher = new Watcher(parent, parentKey, function (val) {
      updateProp(child, prop, val);
    }, {
      twoWay: twoWay,
      filters: prop.filters,
      // important: props need to be observed on the
      // v-for scope if present
      scope: this._scope
    });

    // set the child initial value.
    initProp(child, prop, parentWatcher.value);

    // setup two-way binding
    if (twoWay) {
      // important: defer the child watcher creation until
      // the created hook (after data observation)
      var self = this;
      child.$once('pre-hook:created', function () {
        self.childWatcher = new Watcher(child, childKey, function (val) {
          parentWatcher.set(val);
        }, {
          // ensure sync upward before parent sync down.
          // this is necessary in cases e.g. the child
          // mutates a prop array, then replaces it. (#1683)
          sync: true
        });
      });
    }
  },

  unbind: function unbind() {
    this.parentWatcher.teardown();
    if (this.childWatcher) {
      this.childWatcher.teardown();
    }
  }
};

var queue$1 = [];
var queued = false;

/**
 * Push a job into the queue.
 *
 * @param {Function} job
 */

function pushJob(job) {
  queue$1.push(job);
  if (!queued) {
    queued = true;
    nextTick(flush);
  }
}

/**
 * Flush the queue, and do one forced reflow before
 * triggering transitions.
 */

function flush() {
  // Force layout
  var f = document.documentElement.offsetHeight;
  for (var i = 0; i < queue$1.length; i++) {
    queue$1[i]();
  }
  queue$1 = [];
  queued = false;
  // dummy return, so js linters don't complain about
  // unused variable f
  return f;
}

var TYPE_TRANSITION = 'transition';
var TYPE_ANIMATION = 'animation';
var transDurationProp = transitionProp + 'Duration';
var animDurationProp = animationProp + 'Duration';

/**
 * If a just-entered element is applied the
 * leave class while its enter transition hasn't started yet,
 * and the transitioned property has the same value for both
 * enter/leave, then the leave transition will be skipped and
 * the transitionend event never fires. This function ensures
 * its callback to be called after a transition has started
 * by waiting for double raf.
 *
 * It falls back to setTimeout on devices that support CSS
 * transitions but not raf (e.g. Android 4.2 browser) - since
 * these environments are usually slow, we are giving it a
 * relatively large timeout.
 */

var raf = inBrowser && window.requestAnimationFrame;
var waitForTransitionStart = raf
/* istanbul ignore next */
? function (fn) {
  raf(function () {
    raf(fn);
  });
} : function (fn) {
  setTimeout(fn, 50);
};

/**
 * A Transition object that encapsulates the state and logic
 * of the transition.
 *
 * @param {Element} el
 * @param {String} id
 * @param {Object} hooks
 * @param {Vue} vm
 */
function Transition(el, id, hooks, vm) {
  this.id = id;
  this.el = el;
  this.enterClass = hooks && hooks.enterClass || id + '-enter';
  this.leaveClass = hooks && hooks.leaveClass || id + '-leave';
  this.hooks = hooks;
  this.vm = vm;
  // async state
  this.pendingCssEvent = this.pendingCssCb = this.cancel = this.pendingJsCb = this.op = this.cb = null;
  this.justEntered = false;
  this.entered = this.left = false;
  this.typeCache = {};
  // check css transition type
  this.type = hooks && hooks.type;
  /* istanbul ignore if */
  if (process.env.NODE_ENV !== 'production') {
    if (this.type && this.type !== TYPE_TRANSITION && this.type !== TYPE_ANIMATION) {
      warn('invalid CSS transition type for transition="' + this.id + '": ' + this.type, vm);
    }
  }
  // bind
  var self = this;['enterNextTick', 'enterDone', 'leaveNextTick', 'leaveDone'].forEach(function (m) {
    self[m] = bind(self[m], self);
  });
}

var p$1 = Transition.prototype;

/**
 * Start an entering transition.
 *
 * 1. enter transition triggered
 * 2. call beforeEnter hook
 * 3. add enter class
 * 4. insert/show element
 * 5. call enter hook (with possible explicit js callback)
 * 6. reflow
 * 7. based on transition type:
 *    - transition:
 *        remove class now, wait for transitionend,
 *        then done if there's no explicit js callback.
 *    - animation:
 *        wait for animationend, remove class,
 *        then done if there's no explicit js callback.
 *    - no css transition:
 *        done now if there's no explicit js callback.
 * 8. wait for either done or js callback, then call
 *    afterEnter hook.
 *
 * @param {Function} op - insert/show the element
 * @param {Function} [cb]
 */

p$1.enter = function (op, cb) {
  this.cancelPending();
  this.callHook('beforeEnter');
  this.cb = cb;
  addClass(this.el, this.enterClass);
  op();
  this.entered = false;
  this.callHookWithCb('enter');
  if (this.entered) {
    return; // user called done synchronously.
  }
  this.cancel = this.hooks && this.hooks.enterCancelled;
  pushJob(this.enterNextTick);
};

/**
 * The "nextTick" phase of an entering transition, which is
 * to be pushed into a queue and executed after a reflow so
 * that removing the class can trigger a CSS transition.
 */

p$1.enterNextTick = function () {
  var _this = this;

  // prevent transition skipping
  this.justEntered = true;
  waitForTransitionStart(function () {
    _this.justEntered = false;
  });
  var enterDone = this.enterDone;
  var type = this.getCssTransitionType(this.enterClass);
  if (!this.pendingJsCb) {
    if (type === TYPE_TRANSITION) {
      // trigger transition by removing enter class now
      removeClass(this.el, this.enterClass);
      this.setupCssCb(transitionEndEvent, enterDone);
    } else if (type === TYPE_ANIMATION) {
      this.setupCssCb(animationEndEvent, enterDone);
    } else {
      enterDone();
    }
  } else if (type === TYPE_TRANSITION) {
    removeClass(this.el, this.enterClass);
  }
};

/**
 * The "cleanup" phase of an entering transition.
 */

p$1.enterDone = function () {
  this.entered = true;
  this.cancel = this.pendingJsCb = null;
  removeClass(this.el, this.enterClass);
  this.callHook('afterEnter');
  if (this.cb) this.cb();
};

/**
 * Start a leaving transition.
 *
 * 1. leave transition triggered.
 * 2. call beforeLeave hook
 * 3. add leave class (trigger css transition)
 * 4. call leave hook (with possible explicit js callback)
 * 5. reflow if no explicit js callback is provided
 * 6. based on transition type:
 *    - transition or animation:
 *        wait for end event, remove class, then done if
 *        there's no explicit js callback.
 *    - no css transition:
 *        done if there's no explicit js callback.
 * 7. wait for either done or js callback, then call
 *    afterLeave hook.
 *
 * @param {Function} op - remove/hide the element
 * @param {Function} [cb]
 */

p$1.leave = function (op, cb) {
  this.cancelPending();
  this.callHook('beforeLeave');
  this.op = op;
  this.cb = cb;
  addClass(this.el, this.leaveClass);
  this.left = false;
  this.callHookWithCb('leave');
  if (this.left) {
    return; // user called done synchronously.
  }
  this.cancel = this.hooks && this.hooks.leaveCancelled;
  // only need to handle leaveDone if
  // 1. the transition is already done (synchronously called
  //    by the user, which causes this.op set to null)
  // 2. there's no explicit js callback
  if (this.op && !this.pendingJsCb) {
    // if a CSS transition leaves immediately after enter,
    // the transitionend event never fires. therefore we
    // detect such cases and end the leave immediately.
    if (this.justEntered) {
      this.leaveDone();
    } else {
      pushJob(this.leaveNextTick);
    }
  }
};

/**
 * The "nextTick" phase of a leaving transition.
 */

p$1.leaveNextTick = function () {
  var type = this.getCssTransitionType(this.leaveClass);
  if (type) {
    var event = type === TYPE_TRANSITION ? transitionEndEvent : animationEndEvent;
    this.setupCssCb(event, this.leaveDone);
  } else {
    this.leaveDone();
  }
};

/**
 * The "cleanup" phase of a leaving transition.
 */

p$1.leaveDone = function () {
  this.left = true;
  this.cancel = this.pendingJsCb = null;
  this.op();
  removeClass(this.el, this.leaveClass);
  this.callHook('afterLeave');
  if (this.cb) this.cb();
  this.op = null;
};

/**
 * Cancel any pending callbacks from a previously running
 * but not finished transition.
 */

p$1.cancelPending = function () {
  this.op = this.cb = null;
  var hasPending = false;
  if (this.pendingCssCb) {
    hasPending = true;
    off(this.el, this.pendingCssEvent, this.pendingCssCb);
    this.pendingCssEvent = this.pendingCssCb = null;
  }
  if (this.pendingJsCb) {
    hasPending = true;
    this.pendingJsCb.cancel();
    this.pendingJsCb = null;
  }
  if (hasPending) {
    removeClass(this.el, this.enterClass);
    removeClass(this.el, this.leaveClass);
  }
  if (this.cancel) {
    this.cancel.call(this.vm, this.el);
    this.cancel = null;
  }
};

/**
 * Call a user-provided synchronous hook function.
 *
 * @param {String} type
 */

p$1.callHook = function (type) {
  if (this.hooks && this.hooks[type]) {
    this.hooks[type].call(this.vm, this.el);
  }
};

/**
 * Call a user-provided, potentially-async hook function.
 * We check for the length of arguments to see if the hook
 * expects a `done` callback. If true, the transition's end
 * will be determined by when the user calls that callback;
 * otherwise, the end is determined by the CSS transition or
 * animation.
 *
 * @param {String} type
 */

p$1.callHookWithCb = function (type) {
  var hook = this.hooks && this.hooks[type];
  if (hook) {
    if (hook.length > 1) {
      this.pendingJsCb = cancellable(this[type + 'Done']);
    }
    hook.call(this.vm, this.el, this.pendingJsCb);
  }
};

/**
 * Get an element's transition type based on the
 * calculated styles.
 *
 * @param {String} className
 * @return {Number}
 */

p$1.getCssTransitionType = function (className) {
  /* istanbul ignore if */
  if (!transitionEndEvent ||
  // skip CSS transitions if page is not visible -
  // this solves the issue of transitionend events not
  // firing until the page is visible again.
  // pageVisibility API is supported in IE10+, same as
  // CSS transitions.
  document.hidden ||
  // explicit js-only transition
  this.hooks && this.hooks.css === false ||
  // element is hidden
  isHidden(this.el)) {
    return;
  }
  var type = this.type || this.typeCache[className];
  if (type) return type;
  var inlineStyles = this.el.style;
  var computedStyles = window.getComputedStyle(this.el);
  var transDuration = inlineStyles[transDurationProp] || computedStyles[transDurationProp];
  if (transDuration && transDuration !== '0s') {
    type = TYPE_TRANSITION;
  } else {
    var animDuration = inlineStyles[animDurationProp] || computedStyles[animDurationProp];
    if (animDuration && animDuration !== '0s') {
      type = TYPE_ANIMATION;
    }
  }
  if (type) {
    this.typeCache[className] = type;
  }
  return type;
};

/**
 * Setup a CSS transitionend/animationend callback.
 *
 * @param {String} event
 * @param {Function} cb
 */

p$1.setupCssCb = function (event, cb) {
  this.pendingCssEvent = event;
  var self = this;
  var el = this.el;
  var onEnd = this.pendingCssCb = function (e) {
    if (e.target === el) {
      off(el, event, onEnd);
      self.pendingCssEvent = self.pendingCssCb = null;
      if (!self.pendingJsCb && cb) {
        cb();
      }
    }
  };
  on(el, event, onEnd);
};

/**
 * Check if an element is hidden - in that case we can just
 * skip the transition alltogether.
 *
 * @param {Element} el
 * @return {Boolean}
 */

function isHidden(el) {
  if (/svg$/.test(el.namespaceURI)) {
    // SVG elements do not have offset(Width|Height)
    // so we need to check the client rect
    var rect = el.getBoundingClientRect();
    return !(rect.width || rect.height);
  } else {
    return !(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
  }
}

var transition$1 = {

  priority: TRANSITION,

  update: function update(id, oldId) {
    var el = this.el;
    // resolve on owner vm
    var hooks = resolveAsset(this.vm.$options, 'transitions', id);
    id = id || 'v';
    oldId = oldId || 'v';
    el.__v_trans = new Transition(el, id, hooks, this.vm);
    removeClass(el, oldId + '-transition');
    addClass(el, id + '-transition');
  }
};

var internalDirectives = {
  style: style,
  'class': vClass,
  component: component,
  prop: propDef,
  transition: transition$1
};

// special binding prefixes
var bindRE = /^v-bind:|^:/;
var onRE = /^v-on:|^@/;
var dirAttrRE = /^v-([^:]+)(?:$|:(.*)$)/;
var modifierRE = /\.[^\.]+/g;
var transitionRE = /^(v-bind:|:)?transition$/;

// default directive priority
var DEFAULT_PRIORITY = 1000;
var DEFAULT_TERMINAL_PRIORITY = 2000;

/**
 * Compile a template and return a reusable composite link
 * function, which recursively contains more link functions
 * inside. This top level compile function would normally
 * be called on instance root nodes, but can also be used
 * for partial compilation if the partial argument is true.
 *
 * The returned composite link function, when called, will
 * return an unlink function that tearsdown all directives
 * created during the linking phase.
 *
 * @param {Element|DocumentFragment} el
 * @param {Object} options
 * @param {Boolean} partial
 * @return {Function}
 */

function compile(el, options, partial) {
  // link function for the node itself.
  var nodeLinkFn = partial || !options._asComponent ? compileNode(el, options) : null;
  // link function for the childNodes
  var childLinkFn = !(nodeLinkFn && nodeLinkFn.terminal) && !isScript(el) && el.hasChildNodes() ? compileNodeList(el.childNodes, options) : null;

  /**
   * A composite linker function to be called on a already
   * compiled piece of DOM, which instantiates all directive
   * instances.
   *
   * @param {Vue} vm
   * @param {Element|DocumentFragment} el
   * @param {Vue} [host] - host vm of transcluded content
   * @param {Object} [scope] - v-for scope
   * @param {Fragment} [frag] - link context fragment
   * @return {Function|undefined}
   */

  return function compositeLinkFn(vm, el, host, scope, frag) {
    // cache childNodes before linking parent, fix #657
    var childNodes = toArray(el.childNodes);
    // link
    var dirs = linkAndCapture(function compositeLinkCapturer() {
      if (nodeLinkFn) nodeLinkFn(vm, el, host, scope, frag);
      if (childLinkFn) childLinkFn(vm, childNodes, host, scope, frag);
    }, vm);
    return makeUnlinkFn(vm, dirs);
  };
}

/**
 * Apply a linker to a vm/element pair and capture the
 * directives created during the process.
 *
 * @param {Function} linker
 * @param {Vue} vm
 */

function linkAndCapture(linker, vm) {
  /* istanbul ignore if */
  if (process.env.NODE_ENV === 'production') {
    // reset directives before every capture in production
    // mode, so that when unlinking we don't need to splice
    // them out (which turns out to be a perf hit).
    // they are kept in development mode because they are
    // useful for Vue's own tests.
    vm._directives = [];
  }
  var originalDirCount = vm._directives.length;
  linker();
  var dirs = vm._directives.slice(originalDirCount);
  sortDirectives(dirs);
  for (var i = 0, l = dirs.length; i < l; i++) {
    dirs[i]._bind();
  }
  return dirs;
}

/**
 * sort directives by priority (stable sort)
 *
 * @param {Array} dirs
 */
function sortDirectives(dirs) {
  if (dirs.length === 0) return;

  var groupedMap = {};
  var i, j, k, l;
  var index = 0;
  var priorities = [];
  for (i = 0, j = dirs.length; i < j; i++) {
    var dir = dirs[i];
    var priority = dir.descriptor.def.priority || DEFAULT_PRIORITY;
    var array = groupedMap[priority];
    if (!array) {
      array = groupedMap[priority] = [];
      priorities.push(priority);
    }
    array.push(dir);
  }

  priorities.sort(function (a, b) {
    return a > b ? -1 : a === b ? 0 : 1;
  });
  for (i = 0, j = priorities.length; i < j; i++) {
    var group = groupedMap[priorities[i]];
    for (k = 0, l = group.length; k < l; k++) {
      dirs[index++] = group[k];
    }
  }
}

/**
 * Linker functions return an unlink function that
 * tearsdown all directives instances generated during
 * the process.
 *
 * We create unlink functions with only the necessary
 * information to avoid retaining additional closures.
 *
 * @param {Vue} vm
 * @param {Array} dirs
 * @param {Vue} [context]
 * @param {Array} [contextDirs]
 * @return {Function}
 */

function makeUnlinkFn(vm, dirs, context, contextDirs) {
  function unlink(destroying) {
    teardownDirs(vm, dirs, destroying);
    if (context && contextDirs) {
      teardownDirs(context, contextDirs);
    }
  }
  // expose linked directives
  unlink.dirs = dirs;
  return unlink;
}

/**
 * Teardown partial linked directives.
 *
 * @param {Vue} vm
 * @param {Array} dirs
 * @param {Boolean} destroying
 */

function teardownDirs(vm, dirs, destroying) {
  var i = dirs.length;
  while (i--) {
    dirs[i]._teardown();
    if (process.env.NODE_ENV !== 'production' && !destroying) {
      vm._directives.$remove(dirs[i]);
    }
  }
}

/**
 * Compile link props on an instance.
 *
 * @param {Vue} vm
 * @param {Element} el
 * @param {Object} props
 * @param {Object} [scope]
 * @return {Function}
 */

function compileAndLinkProps(vm, el, props, scope) {
  var propsLinkFn = compileProps(el, props, vm);
  var propDirs = linkAndCapture(function () {
    propsLinkFn(vm, scope);
  }, vm);
  return makeUnlinkFn(vm, propDirs);
}

/**
 * Compile the root element of an instance.
 *
 * 1. attrs on context container (context scope)
 * 2. attrs on the component template root node, if
 *    replace:true (child scope)
 *
 * If this is a fragment instance, we only need to compile 1.
 *
 * @param {Element} el
 * @param {Object} options
 * @param {Object} contextOptions
 * @return {Function}
 */

function compileRoot(el, options, contextOptions) {
  var containerAttrs = options._containerAttrs;
  var replacerAttrs = options._replacerAttrs;
  var contextLinkFn, replacerLinkFn;

  // only need to compile other attributes for
  // non-fragment instances
  if (el.nodeType !== 11) {
    // for components, container and replacer need to be
    // compiled separately and linked in different scopes.
    if (options._asComponent) {
      // 2. container attributes
      if (containerAttrs && contextOptions) {
        contextLinkFn = compileDirectives(containerAttrs, contextOptions);
      }
      if (replacerAttrs) {
        // 3. replacer attributes
        replacerLinkFn = compileDirectives(replacerAttrs, options);
      }
    } else {
      // non-component, just compile as a normal element.
      replacerLinkFn = compileDirectives(el.attributes, options);
    }
  } else if (process.env.NODE_ENV !== 'production' && containerAttrs) {
    // warn container directives for fragment instances
    var names = containerAttrs.filter(function (attr) {
      // allow vue-loader/vueify scoped css attributes
      return attr.name.indexOf('_v-') < 0 &&
      // allow event listeners
      !onRE.test(attr.name) &&
      // allow slots
      attr.name !== 'slot';
    }).map(function (attr) {
      return '"' + attr.name + '"';
    });
    if (names.length) {
      var plural = names.length > 1;

      var componentName = options.el.tagName.toLowerCase();
      if (componentName === 'component' && options.name) {
        componentName += ':' + options.name;
      }

      warn('Attribute' + (plural ? 's ' : ' ') + names.join(', ') + (plural ? ' are' : ' is') + ' ignored on component ' + '<' + componentName + '> because ' + 'the component is a fragment instance: ' + 'http://vuejs.org/guide/components.html#Fragment-Instance');
    }
  }

  options._containerAttrs = options._replacerAttrs = null;
  return function rootLinkFn(vm, el, scope) {
    // link context scope dirs
    var context = vm._context;
    var contextDirs;
    if (context && contextLinkFn) {
      contextDirs = linkAndCapture(function () {
        contextLinkFn(context, el, null, scope);
      }, context);
    }

    // link self
    var selfDirs = linkAndCapture(function () {
      if (replacerLinkFn) replacerLinkFn(vm, el);
    }, vm);

    // return the unlink function that tearsdown context
    // container directives.
    return makeUnlinkFn(vm, selfDirs, context, contextDirs);
  };
}

/**
 * Compile a node and return a nodeLinkFn based on the
 * node type.
 *
 * @param {Node} node
 * @param {Object} options
 * @return {Function|null}
 */

function compileNode(node, options) {
  var type = node.nodeType;
  if (type === 1 && !isScript(node)) {
    return compileElement(node, options);
  } else if (type === 3 && node.data.trim()) {
    return compileTextNode(node, options);
  } else {
    return null;
  }
}

/**
 * Compile an element and return a nodeLinkFn.
 *
 * @param {Element} el
 * @param {Object} options
 * @return {Function|null}
 */

function compileElement(el, options) {
  // preprocess textareas.
  // textarea treats its text content as the initial value.
  // just bind it as an attr directive for value.
  if (el.tagName === 'TEXTAREA') {
    // a textarea which has v-pre attr should skip complie.
    if (getAttr(el, 'v-pre') !== null) {
      return skip;
    }
    var tokens = parseText(el.value);
    if (tokens) {
      el.setAttribute(':value', tokensToExp(tokens));
      el.value = '';
    }
  }
  var linkFn;
  var hasAttrs = el.hasAttributes();
  var attrs = hasAttrs && toArray(el.attributes);
  // check terminal directives (for & if)
  if (hasAttrs) {
    linkFn = checkTerminalDirectives(el, attrs, options);
  }
  // check element directives
  if (!linkFn) {
    linkFn = checkElementDirectives(el, options);
  }
  // check component
  if (!linkFn) {
    linkFn = checkComponent(el, options);
  }
  // normal directives
  if (!linkFn && hasAttrs) {
    linkFn = compileDirectives(attrs, options);
  }
  return linkFn;
}

/**
 * Compile a textNode and return a nodeLinkFn.
 *
 * @param {TextNode} node
 * @param {Object} options
 * @return {Function|null} textNodeLinkFn
 */

function compileTextNode(node, options) {
  // skip marked text nodes
  if (node._skip) {
    return removeText;
  }

  var tokens = parseText(node.wholeText);
  if (!tokens) {
    return null;
  }

  // mark adjacent text nodes as skipped,
  // because we are using node.wholeText to compile
  // all adjacent text nodes together. This fixes
  // issues in IE where sometimes it splits up a single
  // text node into multiple ones.
  var next = node.nextSibling;
  while (next && next.nodeType === 3) {
    next._skip = true;
    next = next.nextSibling;
  }

  var frag = document.createDocumentFragment();
  var el, token;
  for (var i = 0, l = tokens.length; i < l; i++) {
    token = tokens[i];
    el = token.tag ? processTextToken(token, options) : document.createTextNode(token.value);
    frag.appendChild(el);
  }
  return makeTextNodeLinkFn(tokens, frag, options);
}

/**
 * Linker for an skipped text node.
 *
 * @param {Vue} vm
 * @param {Text} node
 */

function removeText(vm, node) {
  remove(node);
}

/**
 * Process a single text token.
 *
 * @param {Object} token
 * @param {Object} options
 * @return {Node}
 */

function processTextToken(token, options) {
  var el;
  if (token.oneTime) {
    el = document.createTextNode(token.value);
  } else {
    if (token.html) {
      el = document.createComment('v-html');
      setTokenType('html');
    } else {
      // IE will clean up empty textNodes during
      // frag.cloneNode(true), so we have to give it
      // something here...
      el = document.createTextNode(' ');
      setTokenType('text');
    }
  }
  function setTokenType(type) {
    if (token.descriptor) return;
    var parsed = parseDirective(token.value);
    token.descriptor = {
      name: type,
      def: directives[type],
      expression: parsed.expression,
      filters: parsed.filters
    };
  }
  return el;
}

/**
 * Build a function that processes a textNode.
 *
 * @param {Array<Object>} tokens
 * @param {DocumentFragment} frag
 */

function makeTextNodeLinkFn(tokens, frag) {
  return function textNodeLinkFn(vm, el, host, scope) {
    var fragClone = frag.cloneNode(true);
    var childNodes = toArray(fragClone.childNodes);
    var token, value, node;
    for (var i = 0, l = tokens.length; i < l; i++) {
      token = tokens[i];
      value = token.value;
      if (token.tag) {
        node = childNodes[i];
        if (token.oneTime) {
          value = (scope || vm).$eval(value);
          if (token.html) {
            replace(node, parseTemplate(value, true));
          } else {
            node.data = _toString(value);
          }
        } else {
          vm._bindDir(token.descriptor, node, host, scope);
        }
      }
    }
    replace(el, fragClone);
  };
}

/**
 * Compile a node list and return a childLinkFn.
 *
 * @param {NodeList} nodeList
 * @param {Object} options
 * @return {Function|undefined}
 */

function compileNodeList(nodeList, options) {
  var linkFns = [];
  var nodeLinkFn, childLinkFn, node;
  for (var i = 0, l = nodeList.length; i < l; i++) {
    node = nodeList[i];
    nodeLinkFn = compileNode(node, options);
    childLinkFn = !(nodeLinkFn && nodeLinkFn.terminal) && node.tagName !== 'SCRIPT' && node.hasChildNodes() ? compileNodeList(node.childNodes, options) : null;
    linkFns.push(nodeLinkFn, childLinkFn);
  }
  return linkFns.length ? makeChildLinkFn(linkFns) : null;
}

/**
 * Make a child link function for a node's childNodes.
 *
 * @param {Array<Function>} linkFns
 * @return {Function} childLinkFn
 */

function makeChildLinkFn(linkFns) {
  return function childLinkFn(vm, nodes, host, scope, frag) {
    var node, nodeLinkFn, childrenLinkFn;
    for (var i = 0, n = 0, l = linkFns.length; i < l; n++) {
      node = nodes[n];
      nodeLinkFn = linkFns[i++];
      childrenLinkFn = linkFns[i++];
      // cache childNodes before linking parent, fix #657
      var childNodes = toArray(node.childNodes);
      if (nodeLinkFn) {
        nodeLinkFn(vm, node, host, scope, frag);
      }
      if (childrenLinkFn) {
        childrenLinkFn(vm, childNodes, host, scope, frag);
      }
    }
  };
}

/**
 * Check for element directives (custom elements that should
 * be resovled as terminal directives).
 *
 * @param {Element} el
 * @param {Object} options
 */

function checkElementDirectives(el, options) {
  var tag = el.tagName.toLowerCase();
  if (commonTagRE.test(tag)) {
    return;
  }
  var def = resolveAsset(options, 'elementDirectives', tag);
  if (def) {
    return makeTerminalNodeLinkFn(el, tag, '', options, def);
  }
}

/**
 * Check if an element is a component. If yes, return
 * a component link function.
 *
 * @param {Element} el
 * @param {Object} options
 * @return {Function|undefined}
 */

function checkComponent(el, options) {
  var component = checkComponentAttr(el, options);
  if (component) {
    var ref = findRef(el);
    var descriptor = {
      name: 'component',
      ref: ref,
      expression: component.id,
      def: internalDirectives.component,
      modifiers: {
        literal: !component.dynamic
      }
    };
    var componentLinkFn = function componentLinkFn(vm, el, host, scope, frag) {
      if (ref) {
        defineReactive((scope || vm).$refs, ref, null);
      }
      vm._bindDir(descriptor, el, host, scope, frag);
    };
    componentLinkFn.terminal = true;
    return componentLinkFn;
  }
}

/**
 * Check an element for terminal directives in fixed order.
 * If it finds one, return a terminal link function.
 *
 * @param {Element} el
 * @param {Array} attrs
 * @param {Object} options
 * @return {Function} terminalLinkFn
 */

function checkTerminalDirectives(el, attrs, options) {
  // skip v-pre
  if (getAttr(el, 'v-pre') !== null) {
    return skip;
  }
  // skip v-else block, but only if following v-if
  if (el.hasAttribute('v-else')) {
    var prev = el.previousElementSibling;
    if (prev && prev.hasAttribute('v-if')) {
      return skip;
    }
  }

  var attr, name, value, modifiers, matched, dirName, rawName, arg, def, termDef;
  for (var i = 0, j = attrs.length; i < j; i++) {
    attr = attrs[i];
    name = attr.name.replace(modifierRE, '');
    if (matched = name.match(dirAttrRE)) {
      def = resolveAsset(options, 'directives', matched[1]);
      if (def && def.terminal) {
        if (!termDef || (def.priority || DEFAULT_TERMINAL_PRIORITY) > termDef.priority) {
          termDef = def;
          rawName = attr.name;
          modifiers = parseModifiers(attr.name);
          value = attr.value;
          dirName = matched[1];
          arg = matched[2];
        }
      }
    }
  }

  if (termDef) {
    return makeTerminalNodeLinkFn(el, dirName, value, options, termDef, rawName, arg, modifiers);
  }
}

function skip() {}
skip.terminal = true;

/**
 * Build a node link function for a terminal directive.
 * A terminal link function terminates the current
 * compilation recursion and handles compilation of the
 * subtree in the directive.
 *
 * @param {Element} el
 * @param {String} dirName
 * @param {String} value
 * @param {Object} options
 * @param {Object} def
 * @param {String} [rawName]
 * @param {String} [arg]
 * @param {Object} [modifiers]
 * @return {Function} terminalLinkFn
 */

function makeTerminalNodeLinkFn(el, dirName, value, options, def, rawName, arg, modifiers) {
  var parsed = parseDirective(value);
  var descriptor = {
    name: dirName,
    arg: arg,
    expression: parsed.expression,
    filters: parsed.filters,
    raw: value,
    attr: rawName,
    modifiers: modifiers,
    def: def
  };
  // check ref for v-for, v-if and router-view
  if (dirName === 'for' || dirName === 'router-view') {
    descriptor.ref = findRef(el);
  }
  var fn = function terminalNodeLinkFn(vm, el, host, scope, frag) {
    if (descriptor.ref) {
      defineReactive((scope || vm).$refs, descriptor.ref, null);
    }
    vm._bindDir(descriptor, el, host, scope, frag);
  };
  fn.terminal = true;
  return fn;
}

/**
 * Compile the directives on an element and return a linker.
 *
 * @param {Array|NamedNodeMap} attrs
 * @param {Object} options
 * @return {Function}
 */

function compileDirectives(attrs, options) {
  var i = attrs.length;
  var dirs = [];
  var attr, name, value, rawName, rawValue, dirName, arg, modifiers, dirDef, tokens, matched;
  while (i--) {
    attr = attrs[i];
    name = rawName = attr.name;
    value = rawValue = attr.value;
    tokens = parseText(value);
    // reset arg
    arg = null;
    // check modifiers
    modifiers = parseModifiers(name);
    name = name.replace(modifierRE, '');

    // attribute interpolations
    if (tokens) {
      value = tokensToExp(tokens);
      arg = name;
      pushDir('bind', directives.bind, tokens);
      // warn against mixing mustaches with v-bind
      if (process.env.NODE_ENV !== 'production') {
        if (name === 'class' && Array.prototype.some.call(attrs, function (attr) {
          return attr.name === ':class' || attr.name === 'v-bind:class';
        })) {
          warn('class="' + rawValue + '": Do not mix mustache interpolation ' + 'and v-bind for "class" on the same element. Use one or the other.', options);
        }
      }
    } else

      // special attribute: transition
      if (transitionRE.test(name)) {
        modifiers.literal = !bindRE.test(name);
        pushDir('transition', internalDirectives.transition);
      } else

        // event handlers
        if (onRE.test(name)) {
          arg = name.replace(onRE, '');
          pushDir('on', directives.on);
        } else

          // attribute bindings
          if (bindRE.test(name)) {
            dirName = name.replace(bindRE, '');
            if (dirName === 'style' || dirName === 'class') {
              pushDir(dirName, internalDirectives[dirName]);
            } else {
              arg = dirName;
              pushDir('bind', directives.bind);
            }
          } else

            // normal directives
            if (matched = name.match(dirAttrRE)) {
              dirName = matched[1];
              arg = matched[2];

              // skip v-else (when used with v-show)
              if (dirName === 'else') {
                continue;
              }

              dirDef = resolveAsset(options, 'directives', dirName, true);
              if (dirDef) {
                pushDir(dirName, dirDef);
              }
            }
  }

  /**
   * Push a directive.
   *
   * @param {String} dirName
   * @param {Object|Function} def
   * @param {Array} [interpTokens]
   */

  function pushDir(dirName, def, interpTokens) {
    var hasOneTimeToken = interpTokens && hasOneTime(interpTokens);
    var parsed = !hasOneTimeToken && parseDirective(value);
    dirs.push({
      name: dirName,
      attr: rawName,
      raw: rawValue,
      def: def,
      arg: arg,
      modifiers: modifiers,
      // conversion from interpolation strings with one-time token
      // to expression is differed until directive bind time so that we
      // have access to the actual vm context for one-time bindings.
      expression: parsed && parsed.expression,
      filters: parsed && parsed.filters,
      interp: interpTokens,
      hasOneTime: hasOneTimeToken
    });
  }

  if (dirs.length) {
    return makeNodeLinkFn(dirs);
  }
}

/**
 * Parse modifiers from directive attribute name.
 *
 * @param {String} name
 * @return {Object}
 */

function parseModifiers(name) {
  var res = Object.create(null);
  var match = name.match(modifierRE);
  if (match) {
    var i = match.length;
    while (i--) {
      res[match[i].slice(1)] = true;
    }
  }
  return res;
}

/**
 * Build a link function for all directives on a single node.
 *
 * @param {Array} directives
 * @return {Function} directivesLinkFn
 */

function makeNodeLinkFn(directives) {
  return function nodeLinkFn(vm, el, host, scope, frag) {
    // reverse apply because it's sorted low to high
    var i = directives.length;
    while (i--) {
      vm._bindDir(directives[i], el, host, scope, frag);
    }
  };
}

/**
 * Check if an interpolation string contains one-time tokens.
 *
 * @param {Array} tokens
 * @return {Boolean}
 */

function hasOneTime(tokens) {
  var i = tokens.length;
  while (i--) {
    if (tokens[i].oneTime) return true;
  }
}

function isScript(el) {
  return el.tagName === 'SCRIPT' && (!el.hasAttribute('type') || el.getAttribute('type') === 'text/javascript');
}

var specialCharRE = /[^\w\-:\.]/;

/**
 * Process an element or a DocumentFragment based on a
 * instance option object. This allows us to transclude
 * a template node/fragment before the instance is created,
 * so the processed fragment can then be cloned and reused
 * in v-for.
 *
 * @param {Element} el
 * @param {Object} options
 * @return {Element|DocumentFragment}
 */

function transclude(el, options) {
  // extract container attributes to pass them down
  // to compiler, because they need to be compiled in
  // parent scope. we are mutating the options object here
  // assuming the same object will be used for compile
  // right after this.
  if (options) {
    options._containerAttrs = extractAttrs(el);
  }
  // for template tags, what we want is its content as
  // a documentFragment (for fragment instances)
  if (isTemplate(el)) {
    el = parseTemplate(el);
  }
  if (options) {
    if (options._asComponent && !options.template) {
      options.template = '<slot></slot>';
    }
    if (options.template) {
      options._content = extractContent(el);
      el = transcludeTemplate(el, options);
    }
  }
  if (isFragment(el)) {
    // anchors for fragment instance
    // passing in `persist: true` to avoid them being
    // discarded by IE during template cloning
    prepend(createAnchor('v-start', true), el);
    el.appendChild(createAnchor('v-end', true));
  }
  return el;
}

/**
 * Process the template option.
 * If the replace option is true this will swap the $el.
 *
 * @param {Element} el
 * @param {Object} options
 * @return {Element|DocumentFragment}
 */

function transcludeTemplate(el, options) {
  var template = options.template;
  var frag = parseTemplate(template, true);
  if (frag) {
    var replacer = frag.firstChild;
    if (!replacer) {
      return frag;
    }
    var tag = replacer.tagName && replacer.tagName.toLowerCase();
    if (options.replace) {
      /* istanbul ignore if */
      if (el === document.body) {
        process.env.NODE_ENV !== 'production' && warn('You are mounting an instance with a template to ' + '<body>. This will replace <body> entirely. You ' + 'should probably use `replace: false` here.');
      }
      // there are many cases where the instance must
      // become a fragment instance: basically anything that
      // can create more than 1 root nodes.
      if (
      // multi-children template
      frag.childNodes.length > 1 ||
      // non-element template
      replacer.nodeType !== 1 ||
      // single nested component
      tag === 'component' || resolveAsset(options, 'components', tag) || hasBindAttr(replacer, 'is') ||
      // element directive
      resolveAsset(options, 'elementDirectives', tag) ||
      // for block
      replacer.hasAttribute('v-for') ||
      // if block
      replacer.hasAttribute('v-if')) {
        return frag;
      } else {
        options._replacerAttrs = extractAttrs(replacer);
        mergeAttrs(el, replacer);
        return replacer;
      }
    } else {
      el.appendChild(frag);
      return el;
    }
  } else {
    process.env.NODE_ENV !== 'production' && warn('Invalid template option: ' + template);
  }
}

/**
 * Helper to extract a component container's attributes
 * into a plain object array.
 *
 * @param {Element} el
 * @return {Array}
 */

function extractAttrs(el) {
  if (el.nodeType === 1 && el.hasAttributes()) {
    return toArray(el.attributes);
  }
}

/**
 * Merge the attributes of two elements, and make sure
 * the class names are merged properly.
 *
 * @param {Element} from
 * @param {Element} to
 */

function mergeAttrs(from, to) {
  var attrs = from.attributes;
  var i = attrs.length;
  var name, value;
  while (i--) {
    name = attrs[i].name;
    value = attrs[i].value;
    if (!to.hasAttribute(name) && !specialCharRE.test(name)) {
      to.setAttribute(name, value);
    } else if (name === 'class' && !parseText(value) && (value = value.trim())) {
      value.split(/\s+/).forEach(function (cls) {
        addClass(to, cls);
      });
    }
  }
}

/**
 * Scan and determine slot content distribution.
 * We do this during transclusion instead at compile time so that
 * the distribution is decoupled from the compilation order of
 * the slots.
 *
 * @param {Element|DocumentFragment} template
 * @param {Element} content
 * @param {Vue} vm
 */

function resolveSlots(vm, content) {
  if (!content) {
    return;
  }
  var contents = vm._slotContents = Object.create(null);
  var el, name;
  for (var i = 0, l = content.children.length; i < l; i++) {
    el = content.children[i];
    /* eslint-disable no-cond-assign */
    if (name = el.getAttribute('slot')) {
      (contents[name] || (contents[name] = [])).push(el);
    }
    /* eslint-enable no-cond-assign */
    if (process.env.NODE_ENV !== 'production' && getBindAttr(el, 'slot')) {
      warn('The "slot" attribute must be static.', vm.$parent);
    }
  }
  for (name in contents) {
    contents[name] = extractFragment(contents[name], content);
  }
  if (content.hasChildNodes()) {
    var nodes = content.childNodes;
    if (nodes.length === 1 && nodes[0].nodeType === 3 && !nodes[0].data.trim()) {
      return;
    }
    contents['default'] = extractFragment(content.childNodes, content);
  }
}

/**
 * Extract qualified content nodes from a node list.
 *
 * @param {NodeList} nodes
 * @return {DocumentFragment}
 */

function extractFragment(nodes, parent) {
  var frag = document.createDocumentFragment();
  nodes = toArray(nodes);
  for (var i = 0, l = nodes.length; i < l; i++) {
    var node = nodes[i];
    if (isTemplate(node) && !node.hasAttribute('v-if') && !node.hasAttribute('v-for')) {
      parent.removeChild(node);
      node = parseTemplate(node, true);
    }
    frag.appendChild(node);
  }
  return frag;
}



var compiler = Object.freeze({
	compile: compile,
	compileAndLinkProps: compileAndLinkProps,
	compileRoot: compileRoot,
	transclude: transclude,
	resolveSlots: resolveSlots
});

function stateMixin (Vue) {
  /**
   * Accessor for `$data` property, since setting $data
   * requires observing the new object and updating
   * proxied properties.
   */

  Object.defineProperty(Vue.prototype, '$data', {
    get: function get() {
      return this._data;
    },
    set: function set(newData) {
      if (newData !== this._data) {
        this._setData(newData);
      }
    }
  });

  /**
   * Setup the scope of an instance, which contains:
   * - observed data
   * - computed properties
   * - user methods
   * - meta properties
   */

  Vue.prototype._initState = function () {
    this._initProps();
    this._initMeta();
    this._initMethods();
    this._initData();
    this._initComputed();
  };

  /**
   * Initialize props.
   */

  Vue.prototype._initProps = function () {
    var options = this.$options;
    var el = options.el;
    var props = options.props;
    if (props && !el) {
      process.env.NODE_ENV !== 'production' && warn('Props will not be compiled if no `el` option is ' + 'provided at instantiation.', this);
    }
    // make sure to convert string selectors into element now
    el = options.el = query(el);
    this._propsUnlinkFn = el && el.nodeType === 1 && props
    // props must be linked in proper scope if inside v-for
    ? compileAndLinkProps(this, el, props, this._scope) : null;
  };

  /**
   * Initialize the data.
   */

  Vue.prototype._initData = function () {
    var dataFn = this.$options.data;
    var data = this._data = dataFn ? dataFn() : {};
    if (!isPlainObject(data)) {
      data = {};
      process.env.NODE_ENV !== 'production' && warn('data functions should return an object.', this);
    }
    var props = this._props;
    // proxy data on instance
    var keys = Object.keys(data);
    var i, key;
    i = keys.length;
    while (i--) {
      key = keys[i];
      // there are two scenarios where we can proxy a data key:
      // 1. it's not already defined as a prop
      // 2. it's provided via a instantiation option AND there are no
      //    template prop present
      if (!props || !hasOwn(props, key)) {
        this._proxy(key);
      } else if (process.env.NODE_ENV !== 'production') {
        warn('Data field "' + key + '" is already defined ' + 'as a prop. To provide default value for a prop, use the "default" ' + 'prop option; if you want to pass prop values to an instantiation ' + 'call, use the "propsData" option.', this);
      }
    }
    // observe data
    observe(data, this);
  };

  /**
   * Swap the instance's $data. Called in $data's setter.
   *
   * @param {Object} newData
   */

  Vue.prototype._setData = function (newData) {
    newData = newData || {};
    var oldData = this._data;
    this._data = newData;
    var keys, key, i;
    // unproxy keys not present in new data
    keys = Object.keys(oldData);
    i = keys.length;
    while (i--) {
      key = keys[i];
      if (!(key in newData)) {
        this._unproxy(key);
      }
    }
    // proxy keys not already proxied,
    // and trigger change for changed values
    keys = Object.keys(newData);
    i = keys.length;
    while (i--) {
      key = keys[i];
      if (!hasOwn(this, key)) {
        // new property
        this._proxy(key);
      }
    }
    oldData.__ob__.removeVm(this);
    observe(newData, this);
    this._digest();
  };

  /**
   * Proxy a property, so that
   * vm.prop === vm._data.prop
   *
   * @param {String} key
   */

  Vue.prototype._proxy = function (key) {
    if (!isReserved(key)) {
      // need to store ref to self here
      // because these getter/setters might
      // be called by child scopes via
      // prototype inheritance.
      var self = this;
      Object.defineProperty(self, key, {
        configurable: true,
        enumerable: true,
        get: function proxyGetter() {
          return self._data[key];
        },
        set: function proxySetter(val) {
          self._data[key] = val;
        }
      });
    }
  };

  /**
   * Unproxy a property.
   *
   * @param {String} key
   */

  Vue.prototype._unproxy = function (key) {
    if (!isReserved(key)) {
      delete this[key];
    }
  };

  /**
   * Force update on every watcher in scope.
   */

  Vue.prototype._digest = function () {
    for (var i = 0, l = this._watchers.length; i < l; i++) {
      this._watchers[i].update(true); // shallow updates
    }
  };

  /**
   * Setup computed properties. They are essentially
   * special getter/setters
   */

  function noop() {}
  Vue.prototype._initComputed = function () {
    var computed = this.$options.computed;
    if (computed) {
      for (var key in computed) {
        var userDef = computed[key];
        var def = {
          enumerable: true,
          configurable: true
        };
        if (typeof userDef === 'function') {
          def.get = makeComputedGetter(userDef, this);
          def.set = noop;
        } else {
          def.get = userDef.get ? userDef.cache !== false ? makeComputedGetter(userDef.get, this) : bind(userDef.get, this) : noop;
          def.set = userDef.set ? bind(userDef.set, this) : noop;
        }
        Object.defineProperty(this, key, def);
      }
    }
  };

  function makeComputedGetter(getter, owner) {
    var watcher = new Watcher(owner, getter, null, {
      lazy: true
    });
    return function computedGetter() {
      if (watcher.dirty) {
        watcher.evaluate();
      }
      if (Dep.target) {
        watcher.depend();
      }
      return watcher.value;
    };
  }

  /**
   * Setup instance methods. Methods must be bound to the
   * instance since they might be passed down as a prop to
   * child components.
   */

  Vue.prototype._initMethods = function () {
    var methods = this.$options.methods;
    if (methods) {
      for (var key in methods) {
        this[key] = bind(methods[key], this);
      }
    }
  };

  /**
   * Initialize meta information like $index, $key & $value.
   */

  Vue.prototype._initMeta = function () {
    var metas = this.$options._meta;
    if (metas) {
      for (var key in metas) {
        defineReactive(this, key, metas[key]);
      }
    }
  };
}

var eventRE = /^v-on:|^@/;

function eventsMixin (Vue) {
  /**
   * Setup the instance's option events & watchers.
   * If the value is a string, we pull it from the
   * instance's methods by name.
   */

  Vue.prototype._initEvents = function () {
    var options = this.$options;
    if (options._asComponent) {
      registerComponentEvents(this, options.el);
    }
    registerCallbacks(this, '$on', options.events);
    registerCallbacks(this, '$watch', options.watch);
  };

  /**
   * Register v-on events on a child component
   *
   * @param {Vue} vm
   * @param {Element} el
   */

  function registerComponentEvents(vm, el) {
    var attrs = el.attributes;
    var name, value, handler;
    for (var i = 0, l = attrs.length; i < l; i++) {
      name = attrs[i].name;
      if (eventRE.test(name)) {
        name = name.replace(eventRE, '');
        // force the expression into a statement so that
        // it always dynamically resolves the method to call (#2670)
        // kinda ugly hack, but does the job.
        value = attrs[i].value;
        if (isSimplePath(value)) {
          value += '.apply(this, $arguments)';
        }
        handler = (vm._scope || vm._context).$eval(value, true);
        handler._fromParent = true;
        vm.$on(name.replace(eventRE), handler);
      }
    }
  }

  /**
   * Register callbacks for option events and watchers.
   *
   * @param {Vue} vm
   * @param {String} action
   * @param {Object} hash
   */

  function registerCallbacks(vm, action, hash) {
    if (!hash) return;
    var handlers, key, i, j;
    for (key in hash) {
      handlers = hash[key];
      if (isArray(handlers)) {
        for (i = 0, j = handlers.length; i < j; i++) {
          register(vm, action, key, handlers[i]);
        }
      } else {
        register(vm, action, key, handlers);
      }
    }
  }

  /**
   * Helper to register an event/watch callback.
   *
   * @param {Vue} vm
   * @param {String} action
   * @param {String} key
   * @param {Function|String|Object} handler
   * @param {Object} [options]
   */

  function register(vm, action, key, handler, options) {
    var type = typeof handler;
    if (type === 'function') {
      vm[action](key, handler, options);
    } else if (type === 'string') {
      var methods = vm.$options.methods;
      var method = methods && methods[handler];
      if (method) {
        vm[action](key, method, options);
      } else {
        process.env.NODE_ENV !== 'production' && warn('Unknown method: "' + handler + '" when ' + 'registering callback for ' + action + ': "' + key + '".', vm);
      }
    } else if (handler && type === 'object') {
      register(vm, action, key, handler.handler, handler);
    }
  }

  /**
   * Setup recursive attached/detached calls
   */

  Vue.prototype._initDOMHooks = function () {
    this.$on('hook:attached', onAttached);
    this.$on('hook:detached', onDetached);
  };

  /**
   * Callback to recursively call attached hook on children
   */

  function onAttached() {
    if (!this._isAttached) {
      this._isAttached = true;
      this.$children.forEach(callAttach);
    }
  }

  /**
   * Iterator to call attached hook
   *
   * @param {Vue} child
   */

  function callAttach(child) {
    if (!child._isAttached && inDoc(child.$el)) {
      child._callHook('attached');
    }
  }

  /**
   * Callback to recursively call detached hook on children
   */

  function onDetached() {
    if (this._isAttached) {
      this._isAttached = false;
      this.$children.forEach(callDetach);
    }
  }

  /**
   * Iterator to call detached hook
   *
   * @param {Vue} child
   */

  function callDetach(child) {
    if (child._isAttached && !inDoc(child.$el)) {
      child._callHook('detached');
    }
  }

  /**
   * Trigger all handlers for a hook
   *
   * @param {String} hook
   */

  Vue.prototype._callHook = function (hook) {
    this.$emit('pre-hook:' + hook);
    var handlers = this.$options[hook];
    if (handlers) {
      for (var i = 0, j = handlers.length; i < j; i++) {
        handlers[i].call(this);
      }
    }
    this.$emit('hook:' + hook);
  };
}

function noop$1() {}

/**
 * A directive links a DOM element with a piece of data,
 * which is the result of evaluating an expression.
 * It registers a watcher with the expression and calls
 * the DOM update function when a change is triggered.
 *
 * @param {Object} descriptor
 *                 - {String} name
 *                 - {Object} def
 *                 - {String} expression
 *                 - {Array<Object>} [filters]
 *                 - {Object} [modifiers]
 *                 - {Boolean} literal
 *                 - {String} attr
 *                 - {String} arg
 *                 - {String} raw
 *                 - {String} [ref]
 *                 - {Array<Object>} [interp]
 *                 - {Boolean} [hasOneTime]
 * @param {Vue} vm
 * @param {Node} el
 * @param {Vue} [host] - transclusion host component
 * @param {Object} [scope] - v-for scope
 * @param {Fragment} [frag] - owner fragment
 * @constructor
 */
function Directive(descriptor, vm, el, host, scope, frag) {
  this.vm = vm;
  this.el = el;
  // copy descriptor properties
  this.descriptor = descriptor;
  this.name = descriptor.name;
  this.expression = descriptor.expression;
  this.arg = descriptor.arg;
  this.modifiers = descriptor.modifiers;
  this.filters = descriptor.filters;
  this.literal = this.modifiers && this.modifiers.literal;
  // private
  this._locked = false;
  this._bound = false;
  this._listeners = null;
  // link context
  this._host = host;
  this._scope = scope;
  this._frag = frag;
  // store directives on node in dev mode
  if (process.env.NODE_ENV !== 'production' && this.el) {
    this.el._vue_directives = this.el._vue_directives || [];
    this.el._vue_directives.push(this);
  }
}

/**
 * Initialize the directive, mixin definition properties,
 * setup the watcher, call definition bind() and update()
 * if present.
 */

Directive.prototype._bind = function () {
  var name = this.name;
  var descriptor = this.descriptor;

  // remove attribute
  if ((name !== 'cloak' || this.vm._isCompiled) && this.el && this.el.removeAttribute) {
    var attr = descriptor.attr || 'v-' + name;
    this.el.removeAttribute(attr);
  }

  // copy def properties
  var def = descriptor.def;
  if (typeof def === 'function') {
    this.update = def;
  } else {
    extend(this, def);
  }

  // setup directive params
  this._setupParams();

  // initial bind
  if (this.bind) {
    this.bind();
  }
  this._bound = true;

  if (this.literal) {
    this.update && this.update(descriptor.raw);
  } else if ((this.expression || this.modifiers) && (this.update || this.twoWay) && !this._checkStatement()) {
    // wrapped updater for context
    var dir = this;
    if (this.update) {
      this._update = function (val, oldVal) {
        if (!dir._locked) {
          dir.update(val, oldVal);
        }
      };
    } else {
      this._update = noop$1;
    }
    var preProcess = this._preProcess ? bind(this._preProcess, this) : null;
    var postProcess = this._postProcess ? bind(this._postProcess, this) : null;
    var watcher = this._watcher = new Watcher(this.vm, this.expression, this._update, // callback
    {
      filters: this.filters,
      twoWay: this.twoWay,
      deep: this.deep,
      preProcess: preProcess,
      postProcess: postProcess,
      scope: this._scope
    });
    // v-model with inital inline value need to sync back to
    // model instead of update to DOM on init. They would
    // set the afterBind hook to indicate that.
    if (this.afterBind) {
      this.afterBind();
    } else if (this.update) {
      this.update(watcher.value);
    }
  }
};

/**
 * Setup all param attributes, e.g. track-by,
 * transition-mode, etc...
 */

Directive.prototype._setupParams = function () {
  if (!this.params) {
    return;
  }
  var params = this.params;
  // swap the params array with a fresh object.
  this.params = Object.create(null);
  var i = params.length;
  var key, val, mappedKey;
  while (i--) {
    key = hyphenate(params[i]);
    mappedKey = camelize(key);
    val = getBindAttr(this.el, key);
    if (val != null) {
      // dynamic
      this._setupParamWatcher(mappedKey, val);
    } else {
      // static
      val = getAttr(this.el, key);
      if (val != null) {
        this.params[mappedKey] = val === '' ? true : val;
      }
    }
  }
};

/**
 * Setup a watcher for a dynamic param.
 *
 * @param {String} key
 * @param {String} expression
 */

Directive.prototype._setupParamWatcher = function (key, expression) {
  var self = this;
  var called = false;
  var unwatch = (this._scope || this.vm).$watch(expression, function (val, oldVal) {
    self.params[key] = val;
    // since we are in immediate mode,
    // only call the param change callbacks if this is not the first update.
    if (called) {
      var cb = self.paramWatchers && self.paramWatchers[key];
      if (cb) {
        cb.call(self, val, oldVal);
      }
    } else {
      called = true;
    }
  }, {
    immediate: true,
    user: false
  });(this._paramUnwatchFns || (this._paramUnwatchFns = [])).push(unwatch);
};

/**
 * Check if the directive is a function caller
 * and if the expression is a callable one. If both true,
 * we wrap up the expression and use it as the event
 * handler.
 *
 * e.g. on-click="a++"
 *
 * @return {Boolean}
 */

Directive.prototype._checkStatement = function () {
  var expression = this.expression;
  if (expression && this.acceptStatement && !isSimplePath(expression)) {
    var fn = parseExpression$1(expression).get;
    var scope = this._scope || this.vm;
    var handler = function handler(e) {
      scope.$event = e;
      fn.call(scope, scope);
      scope.$event = null;
    };
    if (this.filters) {
      handler = scope._applyFilters(handler, null, this.filters);
    }
    this.update(handler);
    return true;
  }
};

/**
 * Set the corresponding value with the setter.
 * This should only be used in two-way directives
 * e.g. v-model.
 *
 * @param {*} value
 * @public
 */

Directive.prototype.set = function (value) {
  /* istanbul ignore else */
  if (this.twoWay) {
    this._withLock(function () {
      this._watcher.set(value);
    });
  } else if (process.env.NODE_ENV !== 'production') {
    warn('Directive.set() can only be used inside twoWay' + 'directives.');
  }
};

/**
 * Execute a function while preventing that function from
 * triggering updates on this directive instance.
 *
 * @param {Function} fn
 */

Directive.prototype._withLock = function (fn) {
  var self = this;
  self._locked = true;
  fn.call(self);
  nextTick(function () {
    self._locked = false;
  });
};

/**
 * Convenience method that attaches a DOM event listener
 * to the directive element and autometically tears it down
 * during unbind.
 *
 * @param {String} event
 * @param {Function} handler
 * @param {Boolean} [useCapture]
 */

Directive.prototype.on = function (event, handler, useCapture) {
  on(this.el, event, handler, useCapture);(this._listeners || (this._listeners = [])).push([event, handler]);
};

/**
 * Teardown the watcher and call unbind.
 */

Directive.prototype._teardown = function () {
  if (this._bound) {
    this._bound = false;
    if (this.unbind) {
      this.unbind();
    }
    if (this._watcher) {
      this._watcher.teardown();
    }
    var listeners = this._listeners;
    var i;
    if (listeners) {
      i = listeners.length;
      while (i--) {
        off(this.el, listeners[i][0], listeners[i][1]);
      }
    }
    var unwatchFns = this._paramUnwatchFns;
    if (unwatchFns) {
      i = unwatchFns.length;
      while (i--) {
        unwatchFns[i]();
      }
    }
    if (process.env.NODE_ENV !== 'production' && this.el) {
      this.el._vue_directives.$remove(this);
    }
    this.vm = this.el = this._watcher = this._listeners = null;
  }
};

function lifecycleMixin (Vue) {
  /**
   * Update v-ref for component.
   *
   * @param {Boolean} remove
   */

  Vue.prototype._updateRef = function (remove) {
    var ref = this.$options._ref;
    if (ref) {
      var refs = (this._scope || this._context).$refs;
      if (remove) {
        if (refs[ref] === this) {
          refs[ref] = null;
        }
      } else {
        refs[ref] = this;
      }
    }
  };

  /**
   * Transclude, compile and link element.
   *
   * If a pre-compiled linker is available, that means the
   * passed in element will be pre-transcluded and compiled
   * as well - all we need to do is to call the linker.
   *
   * Otherwise we need to call transclude/compile/link here.
   *
   * @param {Element} el
   */

  Vue.prototype._compile = function (el) {
    var options = this.$options;

    // transclude and init element
    // transclude can potentially replace original
    // so we need to keep reference; this step also injects
    // the template and caches the original attributes
    // on the container node and replacer node.
    var original = el;
    el = transclude(el, options);
    this._initElement(el);

    // handle v-pre on root node (#2026)
    if (el.nodeType === 1 && getAttr(el, 'v-pre') !== null) {
      return;
    }

    // root is always compiled per-instance, because
    // container attrs and props can be different every time.
    var contextOptions = this._context && this._context.$options;
    var rootLinker = compileRoot(el, options, contextOptions);

    // resolve slot distribution
    resolveSlots(this, options._content);

    // compile and link the rest
    var contentLinkFn;
    var ctor = this.constructor;
    // component compilation can be cached
    // as long as it's not using inline-template
    if (options._linkerCachable) {
      contentLinkFn = ctor.linker;
      if (!contentLinkFn) {
        contentLinkFn = ctor.linker = compile(el, options);
      }
    }

    // link phase
    // make sure to link root with prop scope!
    var rootUnlinkFn = rootLinker(this, el, this._scope);
    var contentUnlinkFn = contentLinkFn ? contentLinkFn(this, el) : compile(el, options)(this, el);

    // register composite unlink function
    // to be called during instance destruction
    this._unlinkFn = function () {
      rootUnlinkFn();
      // passing destroying: true to avoid searching and
      // splicing the directives
      contentUnlinkFn(true);
    };

    // finally replace original
    if (options.replace) {
      replace(original, el);
    }

    this._isCompiled = true;
    this._callHook('compiled');
  };

  /**
   * Initialize instance element. Called in the public
   * $mount() method.
   *
   * @param {Element} el
   */

  Vue.prototype._initElement = function (el) {
    if (isFragment(el)) {
      this._isFragment = true;
      this.$el = this._fragmentStart = el.firstChild;
      this._fragmentEnd = el.lastChild;
      // set persisted text anchors to empty
      if (this._fragmentStart.nodeType === 3) {
        this._fragmentStart.data = this._fragmentEnd.data = '';
      }
      this._fragment = el;
    } else {
      this.$el = el;
    }
    this.$el.__vue__ = this;
    this._callHook('beforeCompile');
  };

  /**
   * Create and bind a directive to an element.
   *
   * @param {Object} descriptor - parsed directive descriptor
   * @param {Node} node   - target node
   * @param {Vue} [host] - transclusion host component
   * @param {Object} [scope] - v-for scope
   * @param {Fragment} [frag] - owner fragment
   */

  Vue.prototype._bindDir = function (descriptor, node, host, scope, frag) {
    this._directives.push(new Directive(descriptor, this, node, host, scope, frag));
  };

  /**
   * Teardown an instance, unobserves the data, unbind all the
   * directives, turn off all the event listeners, etc.
   *
   * @param {Boolean} remove - whether to remove the DOM node.
   * @param {Boolean} deferCleanup - if true, defer cleanup to
   *                                 be called later
   */

  Vue.prototype._destroy = function (remove, deferCleanup) {
    if (this._isBeingDestroyed) {
      if (!deferCleanup) {
        this._cleanup();
      }
      return;
    }

    var destroyReady;
    var pendingRemoval;

    var self = this;
    // Cleanup should be called either synchronously or asynchronoysly as
    // callback of this.$remove(), or if remove and deferCleanup are false.
    // In any case it should be called after all other removing, unbinding and
    // turning of is done
    var cleanupIfPossible = function cleanupIfPossible() {
      if (destroyReady && !pendingRemoval && !deferCleanup) {
        self._cleanup();
      }
    };

    // remove DOM element
    if (remove && this.$el) {
      pendingRemoval = true;
      this.$remove(function () {
        pendingRemoval = false;
        cleanupIfPossible();
      });
    }

    this._callHook('beforeDestroy');
    this._isBeingDestroyed = true;
    var i;
    // remove self from parent. only necessary
    // if parent is not being destroyed as well.
    var parent = this.$parent;
    if (parent && !parent._isBeingDestroyed) {
      parent.$children.$remove(this);
      // unregister ref (remove: true)
      this._updateRef(true);
    }
    // destroy all children.
    i = this.$children.length;
    while (i--) {
      this.$children[i].$destroy();
    }
    // teardown props
    if (this._propsUnlinkFn) {
      this._propsUnlinkFn();
    }
    // teardown all directives. this also tearsdown all
    // directive-owned watchers.
    if (this._unlinkFn) {
      this._unlinkFn();
    }
    i = this._watchers.length;
    while (i--) {
      this._watchers[i].teardown();
    }
    // remove reference to self on $el
    if (this.$el) {
      this.$el.__vue__ = null;
    }

    destroyReady = true;
    cleanupIfPossible();
  };

  /**
   * Clean up to ensure garbage collection.
   * This is called after the leave transition if there
   * is any.
   */

  Vue.prototype._cleanup = function () {
    if (this._isDestroyed) {
      return;
    }
    // remove self from owner fragment
    // do it in cleanup so that we can call $destroy with
    // defer right when a fragment is about to be removed.
    if (this._frag) {
      this._frag.children.$remove(this);
    }
    // remove reference from data ob
    // frozen object may not have observer.
    if (this._data && this._data.__ob__) {
      this._data.__ob__.removeVm(this);
    }
    // Clean up references to private properties and other
    // instances. preserve reference to _data so that proxy
    // accessors still work. The only potential side effect
    // here is that mutating the instance after it's destroyed
    // may affect the state of other components that are still
    // observing the same object, but that seems to be a
    // reasonable responsibility for the user rather than
    // always throwing an error on them.
    this.$el = this.$parent = this.$root = this.$children = this._watchers = this._context = this._scope = this._directives = null;
    // call the last hook...
    this._isDestroyed = true;
    this._callHook('destroyed');
    // turn off all instance listeners.
    this.$off();
  };
}

function miscMixin (Vue) {
  /**
   * Apply a list of filter (descriptors) to a value.
   * Using plain for loops here because this will be called in
   * the getter of any watcher with filters so it is very
   * performance sensitive.
   *
   * @param {*} value
   * @param {*} [oldValue]
   * @param {Array} filters
   * @param {Boolean} write
   * @return {*}
   */

  Vue.prototype._applyFilters = function (value, oldValue, filters, write) {
    var filter, fn, args, arg, offset, i, l, j, k;
    for (i = 0, l = filters.length; i < l; i++) {
      filter = filters[write ? l - i - 1 : i];
      fn = resolveAsset(this.$options, 'filters', filter.name, true);
      if (!fn) continue;
      fn = write ? fn.write : fn.read || fn;
      if (typeof fn !== 'function') continue;
      args = write ? [value, oldValue] : [value];
      offset = write ? 2 : 1;
      if (filter.args) {
        for (j = 0, k = filter.args.length; j < k; j++) {
          arg = filter.args[j];
          args[j + offset] = arg.dynamic ? this.$get(arg.value) : arg.value;
        }
      }
      value = fn.apply(this, args);
    }
    return value;
  };

  /**
   * Resolve a component, depending on whether the component
   * is defined normally or using an async factory function.
   * Resolves synchronously if already resolved, otherwise
   * resolves asynchronously and caches the resolved
   * constructor on the factory.
   *
   * @param {String|Function} value
   * @param {Function} cb
   */

  Vue.prototype._resolveComponent = function (value, cb) {
    var factory;
    if (typeof value === 'function') {
      factory = value;
    } else {
      factory = resolveAsset(this.$options, 'components', value, true);
    }
    /* istanbul ignore if */
    if (!factory) {
      return;
    }
    // async component factory
    if (!factory.options) {
      if (factory.resolved) {
        // cached
        cb(factory.resolved);
      } else if (factory.requested) {
        // pool callbacks
        factory.pendingCallbacks.push(cb);
      } else {
        factory.requested = true;
        var cbs = factory.pendingCallbacks = [cb];
        factory.call(this, function resolve(res) {
          if (isPlainObject(res)) {
            res = Vue.extend(res);
          }
          // cache resolved
          factory.resolved = res;
          // invoke callbacks
          for (var i = 0, l = cbs.length; i < l; i++) {
            cbs[i](res);
          }
        }, function reject(reason) {
          process.env.NODE_ENV !== 'production' && warn('Failed to resolve async component' + (typeof value === 'string' ? ': ' + value : '') + '. ' + (reason ? '\nReason: ' + reason : ''));
        });
      }
    } else {
      // normal component
      cb(factory);
    }
  };
}

var filterRE$1 = /[^|]\|[^|]/;

function dataAPI (Vue) {
  /**
   * Get the value from an expression on this vm.
   *
   * @param {String} exp
   * @param {Boolean} [asStatement]
   * @return {*}
   */

  Vue.prototype.$get = function (exp, asStatement) {
    var res = parseExpression$1(exp);
    if (res) {
      if (asStatement) {
        var self = this;
        return function statementHandler() {
          self.$arguments = toArray(arguments);
          var result = res.get.call(self, self);
          self.$arguments = null;
          return result;
        };
      } else {
        try {
          return res.get.call(this, this);
        } catch (e) {}
      }
    }
  };

  /**
   * Set the value from an expression on this vm.
   * The expression must be a valid left-hand
   * expression in an assignment.
   *
   * @param {String} exp
   * @param {*} val
   */

  Vue.prototype.$set = function (exp, val) {
    var res = parseExpression$1(exp, true);
    if (res && res.set) {
      res.set.call(this, this, val);
    }
  };

  /**
   * Delete a property on the VM
   *
   * @param {String} key
   */

  Vue.prototype.$delete = function (key) {
    del(this._data, key);
  };

  /**
   * Watch an expression, trigger callback when its
   * value changes.
   *
   * @param {String|Function} expOrFn
   * @param {Function} cb
   * @param {Object} [options]
   *                 - {Boolean} deep
   *                 - {Boolean} immediate
   * @return {Function} - unwatchFn
   */

  Vue.prototype.$watch = function (expOrFn, cb, options) {
    var vm = this;
    var parsed;
    if (typeof expOrFn === 'string') {
      parsed = parseDirective(expOrFn);
      expOrFn = parsed.expression;
    }
    var watcher = new Watcher(vm, expOrFn, cb, {
      deep: options && options.deep,
      sync: options && options.sync,
      filters: parsed && parsed.filters,
      user: !options || options.user !== false
    });
    if (options && options.immediate) {
      cb.call(vm, watcher.value);
    }
    return function unwatchFn() {
      watcher.teardown();
    };
  };

  /**
   * Evaluate a text directive, including filters.
   *
   * @param {String} text
   * @param {Boolean} [asStatement]
   * @return {String}
   */

  Vue.prototype.$eval = function (text, asStatement) {
    // check for filters.
    if (filterRE$1.test(text)) {
      var dir = parseDirective(text);
      // the filter regex check might give false positive
      // for pipes inside strings, so it's possible that
      // we don't get any filters here
      var val = this.$get(dir.expression, asStatement);
      return dir.filters ? this._applyFilters(val, null, dir.filters) : val;
    } else {
      // no filter
      return this.$get(text, asStatement);
    }
  };

  /**
   * Interpolate a piece of template text.
   *
   * @param {String} text
   * @return {String}
   */

  Vue.prototype.$interpolate = function (text) {
    var tokens = parseText(text);
    var vm = this;
    if (tokens) {
      if (tokens.length === 1) {
        return vm.$eval(tokens[0].value) + '';
      } else {
        return tokens.map(function (token) {
          return token.tag ? vm.$eval(token.value) : token.value;
        }).join('');
      }
    } else {
      return text;
    }
  };

  /**
   * Log instance data as a plain JS object
   * so that it is easier to inspect in console.
   * This method assumes console is available.
   *
   * @param {String} [path]
   */

  Vue.prototype.$log = function (path) {
    var data = path ? getPath(this._data, path) : this._data;
    if (data) {
      data = clean(data);
    }
    // include computed fields
    if (!path) {
      var key;
      for (key in this.$options.computed) {
        data[key] = clean(this[key]);
      }
      if (this._props) {
        for (key in this._props) {
          data[key] = clean(this[key]);
        }
      }
    }
    console.log(data);
  };

  /**
   * "clean" a getter/setter converted object into a plain
   * object copy.
   *
   * @param {Object} - obj
   * @return {Object}
   */

  function clean(obj) {
    return JSON.parse(JSON.stringify(obj));
  }
}

function domAPI (Vue) {
  /**
   * Convenience on-instance nextTick. The callback is
   * auto-bound to the instance, and this avoids component
   * modules having to rely on the global Vue.
   *
   * @param {Function} fn
   */

  Vue.prototype.$nextTick = function (fn) {
    nextTick(fn, this);
  };

  /**
   * Append instance to target
   *
   * @param {Node} target
   * @param {Function} [cb]
   * @param {Boolean} [withTransition] - defaults to true
   */

  Vue.prototype.$appendTo = function (target, cb, withTransition) {
    return insert(this, target, cb, withTransition, append, appendWithTransition);
  };

  /**
   * Prepend instance to target
   *
   * @param {Node} target
   * @param {Function} [cb]
   * @param {Boolean} [withTransition] - defaults to true
   */

  Vue.prototype.$prependTo = function (target, cb, withTransition) {
    target = query(target);
    if (target.hasChildNodes()) {
      this.$before(target.firstChild, cb, withTransition);
    } else {
      this.$appendTo(target, cb, withTransition);
    }
    return this;
  };

  /**
   * Insert instance before target
   *
   * @param {Node} target
   * @param {Function} [cb]
   * @param {Boolean} [withTransition] - defaults to true
   */

  Vue.prototype.$before = function (target, cb, withTransition) {
    return insert(this, target, cb, withTransition, beforeWithCb, beforeWithTransition);
  };

  /**
   * Insert instance after target
   *
   * @param {Node} target
   * @param {Function} [cb]
   * @param {Boolean} [withTransition] - defaults to true
   */

  Vue.prototype.$after = function (target, cb, withTransition) {
    target = query(target);
    if (target.nextSibling) {
      this.$before(target.nextSibling, cb, withTransition);
    } else {
      this.$appendTo(target.parentNode, cb, withTransition);
    }
    return this;
  };

  /**
   * Remove instance from DOM
   *
   * @param {Function} [cb]
   * @param {Boolean} [withTransition] - defaults to true
   */

  Vue.prototype.$remove = function (cb, withTransition) {
    if (!this.$el.parentNode) {
      return cb && cb();
    }
    var inDocument = this._isAttached && inDoc(this.$el);
    // if we are not in document, no need to check
    // for transitions
    if (!inDocument) withTransition = false;
    var self = this;
    var realCb = function realCb() {
      if (inDocument) self._callHook('detached');
      if (cb) cb();
    };
    if (this._isFragment) {
      removeNodeRange(this._fragmentStart, this._fragmentEnd, this, this._fragment, realCb);
    } else {
      var op = withTransition === false ? removeWithCb : removeWithTransition;
      op(this.$el, this, realCb);
    }
    return this;
  };

  /**
   * Shared DOM insertion function.
   *
   * @param {Vue} vm
   * @param {Element} target
   * @param {Function} [cb]
   * @param {Boolean} [withTransition]
   * @param {Function} op1 - op for non-transition insert
   * @param {Function} op2 - op for transition insert
   * @return vm
   */

  function insert(vm, target, cb, withTransition, op1, op2) {
    target = query(target);
    var targetIsDetached = !inDoc(target);
    var op = withTransition === false || targetIsDetached ? op1 : op2;
    var shouldCallHook = !targetIsDetached && !vm._isAttached && !inDoc(vm.$el);
    if (vm._isFragment) {
      mapNodeRange(vm._fragmentStart, vm._fragmentEnd, function (node) {
        op(node, target, vm);
      });
      cb && cb();
    } else {
      op(vm.$el, target, vm, cb);
    }
    if (shouldCallHook) {
      vm._callHook('attached');
    }
    return vm;
  }

  /**
   * Check for selectors
   *
   * @param {String|Element} el
   */

  function query(el) {
    return typeof el === 'string' ? document.querySelector(el) : el;
  }

  /**
   * Append operation that takes a callback.
   *
   * @param {Node} el
   * @param {Node} target
   * @param {Vue} vm - unused
   * @param {Function} [cb]
   */

  function append(el, target, vm, cb) {
    target.appendChild(el);
    if (cb) cb();
  }

  /**
   * InsertBefore operation that takes a callback.
   *
   * @param {Node} el
   * @param {Node} target
   * @param {Vue} vm - unused
   * @param {Function} [cb]
   */

  function beforeWithCb(el, target, vm, cb) {
    before(el, target);
    if (cb) cb();
  }

  /**
   * Remove operation that takes a callback.
   *
   * @param {Node} el
   * @param {Vue} vm - unused
   * @param {Function} [cb]
   */

  function removeWithCb(el, vm, cb) {
    remove(el);
    if (cb) cb();
  }
}

function eventsAPI (Vue) {
  /**
   * Listen on the given `event` with `fn`.
   *
   * @param {String} event
   * @param {Function} fn
   */

  Vue.prototype.$on = function (event, fn) {
    (this._events[event] || (this._events[event] = [])).push(fn);
    modifyListenerCount(this, event, 1);
    return this;
  };

  /**
   * Adds an `event` listener that will be invoked a single
   * time then automatically removed.
   *
   * @param {String} event
   * @param {Function} fn
   */

  Vue.prototype.$once = function (event, fn) {
    var self = this;
    function on() {
      self.$off(event, on);
      fn.apply(this, arguments);
    }
    on.fn = fn;
    this.$on(event, on);
    return this;
  };

  /**
   * Remove the given callback for `event` or all
   * registered callbacks.
   *
   * @param {String} event
   * @param {Function} fn
   */

  Vue.prototype.$off = function (event, fn) {
    var cbs;
    // all
    if (!arguments.length) {
      if (this.$parent) {
        for (event in this._events) {
          cbs = this._events[event];
          if (cbs) {
            modifyListenerCount(this, event, -cbs.length);
          }
        }
      }
      this._events = {};
      return this;
    }
    // specific event
    cbs = this._events[event];
    if (!cbs) {
      return this;
    }
    if (arguments.length === 1) {
      modifyListenerCount(this, event, -cbs.length);
      this._events[event] = null;
      return this;
    }
    // specific handler
    var cb;
    var i = cbs.length;
    while (i--) {
      cb = cbs[i];
      if (cb === fn || cb.fn === fn) {
        modifyListenerCount(this, event, -1);
        cbs.splice(i, 1);
        break;
      }
    }
    return this;
  };

  /**
   * Trigger an event on self.
   *
   * @param {String|Object} event
   * @return {Boolean} shouldPropagate
   */

  Vue.prototype.$emit = function (event) {
    var isSource = typeof event === 'string';
    event = isSource ? event : event.name;
    var cbs = this._events[event];
    var shouldPropagate = isSource || !cbs;
    if (cbs) {
      cbs = cbs.length > 1 ? toArray(cbs) : cbs;
      // this is a somewhat hacky solution to the question raised
      // in #2102: for an inline component listener like <comp @test="doThis">,
      // the propagation handling is somewhat broken. Therefore we
      // need to treat these inline callbacks differently.
      var hasParentCbs = isSource && cbs.some(function (cb) {
        return cb._fromParent;
      });
      if (hasParentCbs) {
        shouldPropagate = false;
      }
      var args = toArray(arguments, 1);
      for (var i = 0, l = cbs.length; i < l; i++) {
        var cb = cbs[i];
        var res = cb.apply(this, args);
        if (res === true && (!hasParentCbs || cb._fromParent)) {
          shouldPropagate = true;
        }
      }
    }
    return shouldPropagate;
  };

  /**
   * Recursively broadcast an event to all children instances.
   *
   * @param {String|Object} event
   * @param {...*} additional arguments
   */

  Vue.prototype.$broadcast = function (event) {
    var isSource = typeof event === 'string';
    event = isSource ? event : event.name;
    // if no child has registered for this event,
    // then there's no need to broadcast.
    if (!this._eventsCount[event]) return;
    var children = this.$children;
    var args = toArray(arguments);
    if (isSource) {
      // use object event to indicate non-source emit
      // on children
      args[0] = { name: event, source: this };
    }
    for (var i = 0, l = children.length; i < l; i++) {
      var child = children[i];
      var shouldPropagate = child.$emit.apply(child, args);
      if (shouldPropagate) {
        child.$broadcast.apply(child, args);
      }
    }
    return this;
  };

  /**
   * Recursively propagate an event up the parent chain.
   *
   * @param {String} event
   * @param {...*} additional arguments
   */

  Vue.prototype.$dispatch = function (event) {
    var shouldPropagate = this.$emit.apply(this, arguments);
    if (!shouldPropagate) return;
    var parent = this.$parent;
    var args = toArray(arguments);
    // use object event to indicate non-source emit
    // on parents
    args[0] = { name: event, source: this };
    while (parent) {
      shouldPropagate = parent.$emit.apply(parent, args);
      parent = shouldPropagate ? parent.$parent : null;
    }
    return this;
  };

  /**
   * Modify the listener counts on all parents.
   * This bookkeeping allows $broadcast to return early when
   * no child has listened to a certain event.
   *
   * @param {Vue} vm
   * @param {String} event
   * @param {Number} count
   */

  var hookRE = /^hook:/;
  function modifyListenerCount(vm, event, count) {
    var parent = vm.$parent;
    // hooks do not get broadcasted so no need
    // to do bookkeeping for them
    if (!parent || !count || hookRE.test(event)) return;
    while (parent) {
      parent._eventsCount[event] = (parent._eventsCount[event] || 0) + count;
      parent = parent.$parent;
    }
  }
}

function lifecycleAPI (Vue) {
  /**
   * Set instance target element and kick off the compilation
   * process. The passed in `el` can be a selector string, an
   * existing Element, or a DocumentFragment (for block
   * instances).
   *
   * @param {Element|DocumentFragment|string} el
   * @public
   */

  Vue.prototype.$mount = function (el) {
    if (this._isCompiled) {
      process.env.NODE_ENV !== 'production' && warn('$mount() should be called only once.', this);
      return;
    }
    el = query(el);
    if (!el) {
      el = document.createElement('div');
    }
    this._compile(el);
    this._initDOMHooks();
    if (inDoc(this.$el)) {
      this._callHook('attached');
      ready.call(this);
    } else {
      this.$once('hook:attached', ready);
    }
    return this;
  };

  /**
   * Mark an instance as ready.
   */

  function ready() {
    this._isAttached = true;
    this._isReady = true;
    this._callHook('ready');
  }

  /**
   * Teardown the instance, simply delegate to the internal
   * _destroy.
   *
   * @param {Boolean} remove
   * @param {Boolean} deferCleanup
   */

  Vue.prototype.$destroy = function (remove, deferCleanup) {
    this._destroy(remove, deferCleanup);
  };

  /**
   * Partially compile a piece of DOM and return a
   * decompile function.
   *
   * @param {Element|DocumentFragment} el
   * @param {Vue} [host]
   * @param {Object} [scope]
   * @param {Fragment} [frag]
   * @return {Function}
   */

  Vue.prototype.$compile = function (el, host, scope, frag) {
    return compile(el, this.$options, true)(this, el, host, scope, frag);
  };
}

/**
 * The exposed Vue constructor.
 *
 * API conventions:
 * - public API methods/properties are prefixed with `$`
 * - internal methods/properties are prefixed with `_`
 * - non-prefixed properties are assumed to be proxied user
 *   data.
 *
 * @constructor
 * @param {Object} [options]
 * @public
 */

function Vue(options) {
  this._init(options);
}

// install internals
initMixin(Vue);
stateMixin(Vue);
eventsMixin(Vue);
lifecycleMixin(Vue);
miscMixin(Vue);

// install instance APIs
dataAPI(Vue);
domAPI(Vue);
eventsAPI(Vue);
lifecycleAPI(Vue);

var slot = {

  priority: SLOT,
  params: ['name'],

  bind: function bind() {
    // this was resolved during component transclusion
    var name = this.params.name || 'default';
    var content = this.vm._slotContents && this.vm._slotContents[name];
    if (!content || !content.hasChildNodes()) {
      this.fallback();
    } else {
      this.compile(content.cloneNode(true), this.vm._context, this.vm);
    }
  },

  compile: function compile(content, context, host) {
    if (content && context) {
      if (this.el.hasChildNodes() && content.childNodes.length === 1 && content.childNodes[0].nodeType === 1 && content.childNodes[0].hasAttribute('v-if')) {
        // if the inserted slot has v-if
        // inject fallback content as the v-else
        var elseBlock = document.createElement('template');
        elseBlock.setAttribute('v-else', '');
        elseBlock.innerHTML = this.el.innerHTML;
        // the else block should be compiled in child scope
        elseBlock._context = this.vm;
        content.appendChild(elseBlock);
      }
      var scope = host ? host._scope : this._scope;
      this.unlink = context.$compile(content, host, scope, this._frag);
    }
    if (content) {
      replace(this.el, content);
    } else {
      remove(this.el);
    }
  },

  fallback: function fallback() {
    this.compile(extractContent(this.el, true), this.vm);
  },

  unbind: function unbind() {
    if (this.unlink) {
      this.unlink();
    }
  }
};

var partial = {

  priority: PARTIAL,

  params: ['name'],

  // watch changes to name for dynamic partials
  paramWatchers: {
    name: function name(value) {
      vIf.remove.call(this);
      if (value) {
        this.insert(value);
      }
    }
  },

  bind: function bind() {
    this.anchor = createAnchor('v-partial');
    replace(this.el, this.anchor);
    this.insert(this.params.name);
  },

  insert: function insert(id) {
    var partial = resolveAsset(this.vm.$options, 'partials', id, true);
    if (partial) {
      this.factory = new FragmentFactory(this.vm, partial);
      vIf.insert.call(this);
    }
  },

  unbind: function unbind() {
    if (this.frag) {
      this.frag.destroy();
    }
  }
};

var elementDirectives = {
  slot: slot,
  partial: partial
};

var convertArray = vFor._postProcess;

/**
 * Limit filter for arrays
 *
 * @param {Number} n
 * @param {Number} offset (Decimal expected)
 */

function limitBy(arr, n, offset) {
  offset = offset ? parseInt(offset, 10) : 0;
  n = toNumber(n);
  return typeof n === 'number' ? arr.slice(offset, offset + n) : arr;
}

/**
 * Filter filter for arrays
 *
 * @param {String} search
 * @param {String} [delimiter]
 * @param {String} ...dataKeys
 */

function filterBy(arr, search, delimiter) {
  arr = convertArray(arr);
  if (search == null) {
    return arr;
  }
  if (typeof search === 'function') {
    return arr.filter(search);
  }
  // cast to lowercase string
  search = ('' + search).toLowerCase();
  // allow optional `in` delimiter
  // because why not
  var n = delimiter === 'in' ? 3 : 2;
  // extract and flatten keys
  var keys = Array.prototype.concat.apply([], toArray(arguments, n));
  var res = [];
  var item, key, val, j;
  for (var i = 0, l = arr.length; i < l; i++) {
    item = arr[i];
    val = item && item.$value || item;
    j = keys.length;
    if (j) {
      while (j--) {
        key = keys[j];
        if (key === '$key' && contains(item.$key, search) || contains(getPath(val, key), search)) {
          res.push(item);
          break;
        }
      }
    } else if (contains(item, search)) {
      res.push(item);
    }
  }
  return res;
}

/**
 * Order filter for arrays
 *
 * @param {String|Array<String>|Function} ...sortKeys
 * @param {Number} [order]
 */

function orderBy(arr) {
  var comparator = null;
  var sortKeys = undefined;
  arr = convertArray(arr);

  // determine order (last argument)
  var args = toArray(arguments, 1);
  var order = args[args.length - 1];
  if (typeof order === 'number') {
    order = order < 0 ? -1 : 1;
    args = args.length > 1 ? args.slice(0, -1) : args;
  } else {
    order = 1;
  }

  // determine sortKeys & comparator
  var firstArg = args[0];
  if (!firstArg) {
    return arr;
  } else if (typeof firstArg === 'function') {
    // custom comparator
    comparator = function (a, b) {
      return firstArg(a, b) * order;
    };
  } else {
    // string keys. flatten first
    sortKeys = Array.prototype.concat.apply([], args);
    comparator = function (a, b, i) {
      i = i || 0;
      return i >= sortKeys.length - 1 ? baseCompare(a, b, i) : baseCompare(a, b, i) || comparator(a, b, i + 1);
    };
  }

  function baseCompare(a, b, sortKeyIndex) {
    var sortKey = sortKeys[sortKeyIndex];
    if (sortKey) {
      if (sortKey !== '$key') {
        if (isObject(a) && '$value' in a) a = a.$value;
        if (isObject(b) && '$value' in b) b = b.$value;
      }
      a = isObject(a) ? getPath(a, sortKey) : a;
      b = isObject(b) ? getPath(b, sortKey) : b;
    }
    return a === b ? 0 : a > b ? order : -order;
  }

  // sort on a copy to avoid mutating original array
  return arr.slice().sort(comparator);
}

/**
 * String contain helper
 *
 * @param {*} val
 * @param {String} search
 */

function contains(val, search) {
  var i;
  if (isPlainObject(val)) {
    var keys = Object.keys(val);
    i = keys.length;
    while (i--) {
      if (contains(val[keys[i]], search)) {
        return true;
      }
    }
  } else if (isArray(val)) {
    i = val.length;
    while (i--) {
      if (contains(val[i], search)) {
        return true;
      }
    }
  } else if (val != null) {
    return val.toString().toLowerCase().indexOf(search) > -1;
  }
}

var digitsRE = /(\d{3})(?=\d)/g;

// asset collections must be a plain object.
var filters = {

  orderBy: orderBy,
  filterBy: filterBy,
  limitBy: limitBy,

  /**
   * Stringify value.
   *
   * @param {Number} indent
   */

  json: {
    read: function read(value, indent) {
      return typeof value === 'string' ? value : JSON.stringify(value, null, arguments.length > 1 ? indent : 2);
    },
    write: function write(value) {
      try {
        return JSON.parse(value);
      } catch (e) {
        return value;
      }
    }
  },

  /**
   * 'abc' => 'Abc'
   */

  capitalize: function capitalize(value) {
    if (!value && value !== 0) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1);
  },

  /**
   * 'abc' => 'ABC'
   */

  uppercase: function uppercase(value) {
    return value || value === 0 ? value.toString().toUpperCase() : '';
  },

  /**
   * 'AbC' => 'abc'
   */

  lowercase: function lowercase(value) {
    return value || value === 0 ? value.toString().toLowerCase() : '';
  },

  /**
   * 12345 => $12,345.00
   *
   * @param {String} sign
   * @param {Number} decimals Decimal places
   */

  currency: function currency(value, _currency, decimals) {
    value = parseFloat(value);
    if (!isFinite(value) || !value && value !== 0) return '';
    _currency = _currency != null ? _currency : '$';
    decimals = decimals != null ? decimals : 2;
    var stringified = Math.abs(value).toFixed(decimals);
    var _int = decimals ? stringified.slice(0, -1 - decimals) : stringified;
    var i = _int.length % 3;
    var head = i > 0 ? _int.slice(0, i) + (_int.length > 3 ? ',' : '') : '';
    var _float = decimals ? stringified.slice(-1 - decimals) : '';
    var sign = value < 0 ? '-' : '';
    return sign + _currency + head + _int.slice(i).replace(digitsRE, '$1,') + _float;
  },

  /**
   * 'item' => 'items'
   *
   * @params
   *  an array of strings corresponding to
   *  the single, double, triple ... forms of the word to
   *  be pluralized. When the number to be pluralized
   *  exceeds the length of the args, it will use the last
   *  entry in the array.
   *
   *  e.g. ['single', 'double', 'triple', 'multiple']
   */

  pluralize: function pluralize(value) {
    var args = toArray(arguments, 1);
    var length = args.length;
    if (length > 1) {
      var index = value % 10 - 1;
      return index in args ? args[index] : args[length - 1];
    } else {
      return args[0] + (value === 1 ? '' : 's');
    }
  },

  /**
   * Debounce a handler function.
   *
   * @param {Function} handler
   * @param {Number} delay = 300
   * @return {Function}
   */

  debounce: function debounce(handler, delay) {
    if (!handler) return;
    if (!delay) {
      delay = 300;
    }
    return _debounce(handler, delay);
  }
};

function installGlobalAPI (Vue) {
  /**
   * Vue and every constructor that extends Vue has an
   * associated options object, which can be accessed during
   * compilation steps as `this.constructor.options`.
   *
   * These can be seen as the default options of every
   * Vue instance.
   */

  Vue.options = {
    directives: directives,
    elementDirectives: elementDirectives,
    filters: filters,
    transitions: {},
    components: {},
    partials: {},
    replace: true
  };

  /**
   * Expose useful internals
   */

  Vue.util = util;
  Vue.config = config;
  Vue.set = set;
  Vue['delete'] = del;
  Vue.nextTick = nextTick;

  /**
   * The following are exposed for advanced usage / plugins
   */

  Vue.compiler = compiler;
  Vue.FragmentFactory = FragmentFactory;
  Vue.internalDirectives = internalDirectives;
  Vue.parsers = {
    path: path,
    text: text,
    template: template,
    directive: directive,
    expression: expression
  };

  /**
   * Each instance constructor, including Vue, has a unique
   * cid. This enables us to create wrapped "child
   * constructors" for prototypal inheritance and cache them.
   */

  Vue.cid = 0;
  var cid = 1;

  /**
   * Class inheritance
   *
   * @param {Object} extendOptions
   */

  Vue.extend = function (extendOptions) {
    extendOptions = extendOptions || {};
    var Super = this;
    var isFirstExtend = Super.cid === 0;
    if (isFirstExtend && extendOptions._Ctor) {
      return extendOptions._Ctor;
    }
    var name = extendOptions.name || Super.options.name;
    if (process.env.NODE_ENV !== 'production') {
      if (!/^[a-zA-Z][\w-]*$/.test(name)) {
        warn('Invalid component name: "' + name + '". Component names ' + 'can only contain alphanumeric characaters and the hyphen.');
        name = null;
      }
    }
    var Sub = createClass(name || 'VueComponent');
    Sub.prototype = Object.create(Super.prototype);
    Sub.prototype.constructor = Sub;
    Sub.cid = cid++;
    Sub.options = mergeOptions(Super.options, extendOptions);
    Sub['super'] = Super;
    // allow further extension
    Sub.extend = Super.extend;
    // create asset registers, so extended classes
    // can have their private assets too.
    config._assetTypes.forEach(function (type) {
      Sub[type] = Super[type];
    });
    // enable recursive self-lookup
    if (name) {
      Sub.options.components[name] = Sub;
    }
    // cache constructor
    if (isFirstExtend) {
      extendOptions._Ctor = Sub;
    }
    return Sub;
  };

  /**
   * A function that returns a sub-class constructor with the
   * given name. This gives us much nicer output when
   * logging instances in the console.
   *
   * @param {String} name
   * @return {Function}
   */

  function createClass(name) {
    /* eslint-disable no-new-func */
    return new Function('return function ' + classify(name) + ' (options) { this._init(options) }')();
    /* eslint-enable no-new-func */
  }

  /**
   * Plugin system
   *
   * @param {Object} plugin
   */

  Vue.use = function (plugin) {
    /* istanbul ignore if */
    if (plugin.installed) {
      return;
    }
    // additional parameters
    var args = toArray(arguments, 1);
    args.unshift(this);
    if (typeof plugin.install === 'function') {
      plugin.install.apply(plugin, args);
    } else {
      plugin.apply(null, args);
    }
    plugin.installed = true;
    return this;
  };

  /**
   * Apply a global mixin by merging it into the default
   * options.
   */

  Vue.mixin = function (mixin) {
    Vue.options = mergeOptions(Vue.options, mixin);
  };

  /**
   * Create asset registration methods with the following
   * signature:
   *
   * @param {String} id
   * @param {*} definition
   */

  config._assetTypes.forEach(function (type) {
    Vue[type] = function (id, definition) {
      if (!definition) {
        return this.options[type + 's'][id];
      } else {
        /* istanbul ignore if */
        if (process.env.NODE_ENV !== 'production') {
          if (type === 'component' && (commonTagRE.test(id) || reservedTagRE.test(id))) {
            warn('Do not use built-in or reserved HTML elements as component ' + 'id: ' + id);
          }
        }
        if (type === 'component' && isPlainObject(definition)) {
          if (!definition.name) {
            definition.name = id;
          }
          definition = Vue.extend(definition);
        }
        this.options[type + 's'][id] = definition;
        return definition;
      }
    };
  });

  // expose internal transition API
  extend(Vue.transition, transition);
}

installGlobalAPI(Vue);

Vue.version = '1.0.28';

// devtools global hook
/* istanbul ignore next */
setTimeout(function () {
  if (config.devtools) {
    if (devtools) {
      devtools.emit('init', Vue);
    } else if (process.env.NODE_ENV !== 'production' && inBrowser && /Chrome\/\d+/.test(window.navigator.userAgent)) {
      console.log('Download the Vue Devtools for a better development experience:\n' + 'https://github.com/vuejs/vue-devtools');
    }
  }
}, 0);

module.exports = Vue;
}).call(this,require('_process'))
},{"_process":2}],8:[function(require,module,exports){
var inserted = exports.cache = {}

exports.insert = function (css) {
  if (inserted[css]) return
  inserted[css] = true

  var elem = document.createElement('style')
  elem.setAttribute('type', 'text/css')

  if ('textContent' in elem) {
    elem.textContent = css
  } else {
    elem.styleSheet.cssText = css
  }

  document.getElementsByTagName('head')[0].appendChild(elem)
  return elem
}

},{}],9:[function(require,module,exports){
var __vueify_insert__ = require("vueify/lib/insert-css")
var __vueify_style__ = __vueify_insert__.insert("\n.calendar[_v-d1443772] {\n    width: 300px;\n    padding: 10px;\n    background: #fff;\n    position: absolute;\n    border: 1px solid #DEDEDE;\n    border-radius: 2px;\n    opacity:.95;\n    transition: all .5s ease;\n}\n.calendar-enter[_v-d1443772], .calendar-leave[_v-d1443772] {\n    opacity: 0;\n    -webkit-transform: translate3d(0,-10px, 0);\n            transform: translate3d(0,-10px, 0);\n}\n.calendar[_v-d1443772]:before {\n    position: absolute;\n    left:30px;\n    top: -10px;\n    content: \"\";\n    border:5px solid rgba(0, 0, 0, 0);\n    border-bottom-color: #DEDEDE;\n}\n.calendar[_v-d1443772]:after {jade\n    position: absolute;\n    left:30px;\n    top: -9px;\n    content: \"\";\n    border:5px solid rgba(0, 0, 0, 0);\n    border-bottom-color: #fff;\n}\n.calendar-tools[_v-d1443772]{\n    height:32px;\n    font-size: 20px;\n    line-height: 32px;\n    color:#5e7a88;\n}\n.calendar-tools .float.left[_v-d1443772]{\n    float:left;\n}\n.calendar-tools .float.right[_v-d1443772]{\n    float:right;\n}\n.calendar-tools input[_v-d1443772]{\n    font-size: 20px;\n    line-height: 32px;\n    color: #5e7a88;\n    width: 70px;\n    text-align: center;\n    border:none;\n    background-color: transparent;\n}\n.calendar-tools span[_v-d1443772]{\n    cursor: pointer;\n}\n.calendar-prev[_v-d1443772]{\n    float:left;\n}\n.calendar-next[_v-d1443772]{\n    float:right;\n}\n \n.calendar table[_v-d1443772] {\n    clear: both;\n    width: 100%;\n    margin-bottom:10px;\n    border-collapse: collapse;\n    color: #444444;\n}\n.calendar td[_v-d1443772] {\n    margin:2px !important;\n    padding:0px 0;\n    width: 14.28571429%;\n    height:34px;\n    text-align: center;\n    vertical-align: middle;\n    font-size:14px;\n    line-height: 125%;\n    cursor: pointer;\n}\n.calendar td.week[_v-d1443772]{\n    pointer-events:none !important;\n    cursor: default !important;    \n}\n.calendar td.disabled[_v-d1443772] {\n    color: #c0c0c0;\n    pointer-events:none !important;\n    cursor: default !important;\n}\n.calendar td span[_v-d1443772]{\n    display:block;\n    height:30px;\n    line-height:30px;\n    margin:2px;\n    border-radius:2px;\n}\n.calendar td span[_v-d1443772]:hover{\n    background:#f3f8fa;\n}\n.calendar td.selected span[_v-d1443772]{\n    background-color: #5e7a88;\n    color: #fff;\n}\n.calendar td.selected span[_v-d1443772]:hover{\n    background-color: #5e7a88;\n    color: #fff;\n}\n.calendar thead td[_v-d1443772] {\n  text-transform: uppercase;\n}\n.calendar .timer[_v-d1443772]{\n    margin:10px 0;\n    text-align: center;\n}\n.calendar .timer input[_v-d1443772]{\n    border-radius: 2px;\n    padding:5px;\n    font-size: 14px;\n    line-height: 18px;\n    color: #5e7a88;\n    width: 50px;\n    text-align: center;\n    border:1px solid #efefef;\n}\n.calendar .timer input[_v-d1443772]:focus{\n    border:1px solid #5e7a88;\n}\n.calendar-button[_v-d1443772]{\n    text-align: center;\n}\n.calendar-button span[_v-d1443772]{\n    cursor: pointer;\n    display: inline-block;\n    min-height: 1em;\n    min-width: 5em;\n    vertical-align: baseline;\n    background:#5e7a88;\n    color:#fff;\n    margin: 0 .25em 0 0;\n    padding: .6em 2em;\n    font-size: 1em;\n    line-height: 1em;\n    text-align: center;\n    border-radius: .3em;\n}\n.calendar-button span.cancel[_v-d1443772]{\n    background:#efefef;\n    color:#666;\n}\n.calendar .lunar[_v-d1443772]{\n     font-size:11px;\n     line-height: 150%;\n     color:#aaa;   \n}\n.calendar td.selected .lunar[_v-d1443772]{\n     color:#fff;   \n}\n")
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: {
        show: {
            type: Boolean,
            twoWay: true,
            default: false
        },
        type: {
            type: String,
            default: "date"
        },
        value: {
            type: String,
            twoWay: true,
            default: ""
        },
        x: {
            type: Number,
            default: 0
        },
        y: {
            type: Number,
            default: 0
        },
        begin: {
            type: String,
            twoWay: true,
            default: ""
        },
        end: {
            type: String,
            default: ""
        },
        range: {
            type: Boolean,
            default: false
        },
        rangeBegin: {
            type: Array,
            default: Array
        },
        rangeEnd: {
            type: Array,
            default: Array
        },
        sep: {
            type: String,
            twoWay: true,
            default: ""
        },
        weeks: {
            type: Array,
            default: function _default() {
                return ['日', '一', '二', '三', '四', '五', '六'];
            }
        },
        months: {
            type: Array,
            default: function _default() {
                return ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
            }
        }
    },
    data: function data() {
        return {
            year: 0,
            month: 0,
            day: 0,
            hour: 0,
            minute: 0,
            second: 0,
            days: [],
            today: [],
            currentMonth: Number,
            monthString: ""
        };
    },
    created: function created() {
        var _this = this;

        this.init();
        // 延迟绑定事件，防止关闭
        window.setTimeout(function () {
            document.addEventListener('click', function (e) {
                e.stopPropagation();
                _this.cancel();
            }, false);
        }, 500);
    },

    // 测试用
    watch: {
        // year(val, old) {
        //     console.log("new %s old %s time:%s", val, old, +new Date)
        // },
        show: function show() {
            this.init();
        },
        value: function value() {
            this.init();
        }
    },
    methods: {
        // 日期补零
        zero: function zero(n) {
            return n < 10 ? '0' + n : n;
        },

        // 初始化一些东西
        init: function init() {
            var now = new Date();
            if (this.value != "") {
                if (this.value.indexOf("-") != -1) this.sep = "-";
                if (this.value.indexOf(".") != -1) this.sep = ".";
                if (this.value.indexOf("/") != -1) this.sep = "/";
                if (this.type == "date") {
                    var split = this.value.split(this.sep);
                    this.year = parseInt(split[0]);
                    this.month = parseInt(split[1]) - 1;
                    this.day = parseInt(split[2]);
                } else if (this.type == "datetime") {
                    var split = this.value.split(" ");
                    var splitDate = split[0].split(this.sep);
                    this.year = parseInt(splitDate[0]);
                    this.month = parseInt(splitDate[1]) - 1;
                    this.day = parseInt(splitDate[2]);
                    if (split.length > 1) {
                        var splitTime = split[1].split(":");
                        this.hour = splitTime[0];
                        this.minute = splitTime[1];
                        this.second = splitTime[2];
                    }
                }
                if (this.range) {
                    var split = this.value.split(" ~ ");
                    if (split.length > 1) {
                        var beginSplit = split[0].split(this.sep);
                        var endSplit = split[1].split(this.sep);
                        this.rangeBegin = [parseInt(beginSplit[0]), parseInt(beginSplit[1] - 1), parseInt(beginSplit[2])];
                        this.rangeEnd = [parseInt(endSplit[0]), parseInt(endSplit[1] - 1), parseInt(endSplit[2])];
                    }
                }
            } else {
                if (this.sep == "") this.sep = "/";
                this.year = now.getFullYear();
                this.month = now.getMonth();
                this.day = now.getDate();
                this.hour = this.zero(now.getHours());
                this.minute = this.zero(now.getMinutes());
                this.second = this.zero(now.getSeconds());
                if (this.range) {
                    this.rangeBegin = Array;
                    this.rangeEnd = Array;
                }
            }
            this.monthString = this.months[this.month];
            this.render(this.year, this.month);
        },

        // 渲染日期
        render: function render(y, m) {
            if (!this.range) {
                this.rangeBegin = [];
                this.rangeEnd = [];
            }
            var firstDayOfMonth = new Date(y, m, 1).getDay(); //当月第一天
            var lastDateOfMonth = new Date(y, m + 1, 0).getDate(); //当月最后一天
            var lastDayOfLastMonth = new Date(y, m, 0).getDate(); //最后一月的最后一天
            this.year = y;
            this.currentMonth = this.months[m];
            var seletSplit = this.value.split(" ")[0].split(this.sep);
            var i,
                line = 0,
                temp = [];
            for (i = 1; i <= lastDateOfMonth; i++) {
                var dow = new Date(y, m, i).getDay();
                // 第一行
                if (dow == 0) {
                    temp[line] = [];
                } else if (i == 1) {
                    temp[line] = [];
                    var k = lastDayOfLastMonth - firstDayOfMonth + 1;
                    for (var j = 0; j < firstDayOfMonth; j++) {
                        temp[line].push({
                            day: k,
                            disabled: true
                        });
                        k++;
                    }
                }

                // 如果是日期范围
                if (this.range) {
                    var options = {
                        day: i
                    };
                    if (this.rangeBegin.length > 0) {
                        var beginTime = Number(new Date(this.rangeBegin[0], this.rangeBegin[1], this.rangeBegin[2]));
                        var endTime = Number(new Date(this.rangeEnd[0], this.rangeEnd[1], this.rangeEnd[2]));
                        var thisTime = Number(new Date(this.year, this.month, i));
                        if (beginTime <= thisTime && endTime >= thisTime) {
                            options.selected = true;
                        }
                    }
                    temp[line].push(options);
                } else {
                    // 单选模式
                    var chk = new Date();
                    var chkY = chk.getFullYear();
                    var chkM = chk.getMonth();
                    // 匹配上次选中的日期
                    if (parseInt(seletSplit[0]) == this.year && parseInt(seletSplit[1]) - 1 == this.month && parseInt(seletSplit[2]) == i) {
                        temp[line].push({
                            day: i,
                            selected: true
                        });
                        this.today = [line, temp[line].length - 1];
                    }
                    // 没有默认值的时候显示选中今天日期
                    else if (chkY == this.year && chkM == this.month && i == this.day && this.value == "") {
                            temp[line].push({
                                day: i,
                                selected: true
                            });
                            this.today = [line, temp[line].length - 1];
                        } else {
                            // 设置可选范围
                            // console.log(this.begin,this.end);
                            var options = {
                                day: i,
                                selected: false
                            };
                            if (this.begin != "") {
                                var beginSplit = this.begin.split(this.sep);
                                var beginTime = Number(new Date(parseInt(beginSplit[0]), parseInt(beginSplit[1]) - 1, parseInt(beginSplit[2])));
                                if (beginTime > Number(new Date(this.year, this.month, i))) options.disabled = true;
                            }
                            if (this.end != "") {
                                var endSplit = this.end.split(this.sep);
                                var endTime = Number(new Date(parseInt(endSplit[0]), parseInt(endSplit[1]) - 1, parseInt(endSplit[2])));
                                if (endTime < Number(new Date(this.year, this.month, i))) options.disabled = true;
                            }
                            temp[line].push(options);
                        }
                }
                // 最后一行
                if (dow == 6) {
                    line++;
                } else if (i == lastDateOfMonth) {
                    var k = 1;
                    for (dow; dow < 6; dow++) {
                        temp[line].push({
                            day: k,
                            disabled: true
                        });
                        k++;
                    }
                }
            } //end for
            this.days = temp;
        },

        // 上月
        prev: function prev(e) {
            e.stopPropagation();
            if (this.month == 0) {
                this.month = 11;
                this.year = parseInt(this.year) - 1;
            } else {
                this.month = parseInt(this.month) - 1;
            }
            this.monthString = this.months[this.month];
            this.render(this.year, this.month);
        },

        //  下月
        next: function next(e) {
            e.stopPropagation();
            if (this.month == 11) {
                this.month = 0;
                this.year = parseInt(this.year) + 1;
            } else {
                this.month = parseInt(this.month) + 1;
            }
            this.monthString = this.months[this.month];
            this.render(this.year, this.month);
        },

        // 选中日期
        select: function select(k1, k2, e) {
            if (e != undefined) e.stopPropagation();
            // 日期范围
            if (this.range) {
                if (this.rangeBegin.length == 0 || this.rangeEndTemp != 0) {
                    this.rangeBegin = [this.year, this.month, this.days[k1][k2].day, this.hour, this.minute, this.second];
                    this.rangeBeginTemp = this.rangeBegin;
                    this.rangeEnd = [this.year, this.month, this.days[k1][k2].day, this.hour, this.minute, this.second];
                    this.rangeEndTemp = 0;
                } else {
                    this.rangeEnd = [this.year, this.month, this.days[k1][k2].day, this.hour, this.minute, this.second];
                    this.rangeEndTemp = 1;
                    // 判断结束日期小于开始日期则自动颠倒过来
                    if (+new Date(this.rangeEnd[0], this.rangeEnd[1], this.rangeEnd[2]) < +new Date(this.rangeBegin[0], this.rangeBegin[1], this.rangeBegin[2])) {
                        this.rangeBegin = this.rangeEnd;
                        this.rangeEnd = this.rangeBeginTemp;
                    }
                }
                this.render(this.year, this.month);
            } else {
                // 取消上次选中
                if (this.today.length > 0) {
                    this.days[this.today[0]][this.today[1]].selected = false;
                }
                // 设置当前选中天
                this.days[k1][k2].selected = true;
                this.day = this.days[k1][k2].day;
                this.today = [k1, k2];
                if (this.type == 'date') {
                    this.value = this.year + this.sep + this.zero(this.month + 1) + this.sep + this.zero(this.days[k1][k2].day);
                    this.show = false;
                }
            }
        },

        // 多选的时候提交
        ok: function ok() {
            // 只有有日期的时候才执行
            if (this.type != "time") {
                var isSelected = false;
                this.days.forEach(function (v) {
                    v.forEach(function (vv) {
                        if (vv.selected) {
                            isSelected = true;
                        }
                    });
                });
                if (!isSelected) return false;
            }

            if (this.range) {
                this.value = this.output(this.rangeBegin) + " ~ " + this.output(this.rangeEnd);
            } else {
                this.value = this.output([this.year, this.month, this.day, parseInt(this.hour), parseInt(this.minute), parseInt(this.second)]);
            }
            this.show = false;
        },

        // 隐藏控件
        cancel: function cancel() {
            this.show = false;
        },

        // 格式化输出
        output: function output(args) {
            if (this.type == 'time') {
                return this.zero(args[3]) + ":" + this.zero(args[4]) + ":" + this.zero(args[5]);
            }
            if (this.type == 'datetime') {
                return args[0] + this.sep + this.zero(args[1] + 1) + this.sep + this.zero(args[2]) + " " + this.zero(args[3]) + ":" + this.zero(args[4]) + ":" + this.zero(args[5]);
            }
            if (this.type == 'date') {
                return args[0] + this.sep + this.zero(args[1] + 1) + this.sep + this.zero(args[2]);
            }
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div @click.stop=\"\" class=\"calendar\" v-show=\"show\" :style=\"{'left':x+'px','top':y+'px'}\" transition=\"calendar\" transition-mode=\"out-in\" _v-d1443772=\"\">\n    <div v-if=\"type!='time'\" _v-d1443772=\"\">\n        <div class=\"calendar-tools\" _v-d1443772=\"\">\n            <span class=\"calendar-prev\" @click=\"prev\" _v-d1443772=\"\">\n                <svg width=\"16\" height=\"16\" viewBox=\"0 0 16 16\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" _v-d1443772=\"\"><g class=\"transform-group\" _v-d1443772=\"\"><g transform=\"scale(0.015625, 0.015625)\" _v-d1443772=\"\"><path d=\"M671.968 912c-12.288 0-24.576-4.672-33.952-14.048L286.048 545.984c-18.752-18.72-18.752-49.12 0-67.872l351.968-352c18.752-18.752 49.12-18.752 67.872 0 18.752 18.72 18.752 49.12 0 67.872l-318.016 318.048 318.016 318.016c18.752 18.752 18.752 49.12 0 67.872C696.544 907.328 684.256 912 671.968 912z\" fill=\"#5e7a88\" _v-d1443772=\"\"></path></g></g></svg>\n            </span>\n            <span class=\"calendar-next\" @click=\"next\" _v-d1443772=\"\">\n                <svg width=\"16\" height=\"16\" viewBox=\"0 0 16 16\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" _v-d1443772=\"\"><g class=\"transform-group\" _v-d1443772=\"\"><g transform=\"scale(0.015625, 0.015625)\" _v-d1443772=\"\"><path d=\"M761.056 532.128c0.512-0.992 1.344-1.824 1.792-2.848 8.8-18.304 5.92-40.704-9.664-55.424L399.936 139.744c-19.264-18.208-49.632-17.344-67.872 1.888-18.208 19.264-17.376 49.632 1.888 67.872l316.96 299.84-315.712 304.288c-19.072 18.4-19.648 48.768-1.248 67.872 9.408 9.792 21.984 14.688 34.56 14.688 12 0 24-4.48 33.312-13.44l350.048-337.376c0.672-0.672 0.928-1.6 1.6-2.304 0.512-0.48 1.056-0.832 1.568-1.344C757.76 538.88 759.2 535.392 761.056 532.128z\" fill=\"#5e7a88\" _v-d1443772=\"\"></path></g></g></svg>\n            </span>\n            <div class=\"text center\" _v-d1443772=\"\">\n                <input type=\"text\" v-model=\"year\" value=\"{{year}}\" @change=\"render(year,month)\" min=\"1970\" max=\"2100\" maxlength=\"4\" _v-d1443772=\"\">\n                 / \n                {{monthString}}\n            </div>\n        </div>\n        <table cellpadding=\"5\" _v-d1443772=\"\">\n        <thead _v-d1443772=\"\">\n            <tr _v-d1443772=\"\">\n                <td v-for=\"week in weeks\" class=\"week\" _v-d1443772=\"\">{{week}}</td>\n            </tr>\n         </thead>\n        <tbody _v-d1443772=\"\"><tr v-for=\"(k1,day) in days\" _v-d1443772=\"\">\n            <td v-for=\"(k2,child) in day\" :class=\"{'selected':child.selected,'disabled':child.disabled}\" @click=\"select(k1,k2,$event)\" @touchstart=\"select(k1,k2,$event)\" _v-d1443772=\"\">\n            <span _v-d1443772=\"\">{{child.day}}</span>\n            <div class=\"lunar\" v-if=\"showLunar\" _v-d1443772=\"\">{{child.lunar}}</div>\n            </td>\n        </tr>\n        </tbody></table>\n    </div>\n    <div class=\"calendar-time\" v-show=\"type=='datetime'||type=='time'\" _v-d1443772=\"\">\n\n        <div class=\"timer\" _v-d1443772=\"\">\n            <input type=\"text\" v-model=\"hour\" value=\"{{hour}}\" min=\"0\" max=\"23\" maxlength=\"2\" _v-d1443772=\"\">\n            时\n            <input type=\"text\" v-model=\"minute\" value=\"{{minute}}\" min=\"0\" max=\"59\" maxlength=\"2\" _v-d1443772=\"\">\n            分\n            <input type=\"text\" v-model=\"second\" value=\"{{second}}\" min=\"0\" max=\"59\" maxlength=\"2\" _v-d1443772=\"\">\n            秒\n        </div>\n    </div>\n    <div class=\"calendar-button\" v-show=\"type=='datetime'||type=='time'||range\" _v-d1443772=\"\">\n        <span @click=\"ok\" _v-d1443772=\"\">确定</span>\n        <span @click=\"cancel\" class=\"cancel\" _v-d1443772=\"\">取消</span>\n    </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.dispose(function () {
    __vueify_insert__.cache["\n.calendar[_v-d1443772] {\n    width: 300px;\n    padding: 10px;\n    background: #fff;\n    position: absolute;\n    border: 1px solid #DEDEDE;\n    border-radius: 2px;\n    opacity:.95;\n    transition: all .5s ease;\n}\n.calendar-enter[_v-d1443772], .calendar-leave[_v-d1443772] {\n    opacity: 0;\n    -webkit-transform: translate3d(0,-10px, 0);\n            transform: translate3d(0,-10px, 0);\n}\n.calendar[_v-d1443772]:before {\n    position: absolute;\n    left:30px;\n    top: -10px;\n    content: \"\";\n    border:5px solid rgba(0, 0, 0, 0);\n    border-bottom-color: #DEDEDE;\n}\n.calendar[_v-d1443772]:after {jade\n    position: absolute;\n    left:30px;\n    top: -9px;\n    content: \"\";\n    border:5px solid rgba(0, 0, 0, 0);\n    border-bottom-color: #fff;\n}\n.calendar-tools[_v-d1443772]{\n    height:32px;\n    font-size: 20px;\n    line-height: 32px;\n    color:#5e7a88;\n}\n.calendar-tools .float.left[_v-d1443772]{\n    float:left;\n}\n.calendar-tools .float.right[_v-d1443772]{\n    float:right;\n}\n.calendar-tools input[_v-d1443772]{\n    font-size: 20px;\n    line-height: 32px;\n    color: #5e7a88;\n    width: 70px;\n    text-align: center;\n    border:none;\n    background-color: transparent;\n}\n.calendar-tools span[_v-d1443772]{\n    cursor: pointer;\n}\n.calendar-prev[_v-d1443772]{\n    float:left;\n}\n.calendar-next[_v-d1443772]{\n    float:right;\n}\n \n.calendar table[_v-d1443772] {\n    clear: both;\n    width: 100%;\n    margin-bottom:10px;\n    border-collapse: collapse;\n    color: #444444;\n}\n.calendar td[_v-d1443772] {\n    margin:2px !important;\n    padding:0px 0;\n    width: 14.28571429%;\n    height:34px;\n    text-align: center;\n    vertical-align: middle;\n    font-size:14px;\n    line-height: 125%;\n    cursor: pointer;\n}\n.calendar td.week[_v-d1443772]{\n    pointer-events:none !important;\n    cursor: default !important;    \n}\n.calendar td.disabled[_v-d1443772] {\n    color: #c0c0c0;\n    pointer-events:none !important;\n    cursor: default !important;\n}\n.calendar td span[_v-d1443772]{\n    display:block;\n    height:30px;\n    line-height:30px;\n    margin:2px;\n    border-radius:2px;\n}\n.calendar td span[_v-d1443772]:hover{\n    background:#f3f8fa;\n}\n.calendar td.selected span[_v-d1443772]{\n    background-color: #5e7a88;\n    color: #fff;\n}\n.calendar td.selected span[_v-d1443772]:hover{\n    background-color: #5e7a88;\n    color: #fff;\n}\n.calendar thead td[_v-d1443772] {\n  text-transform: uppercase;\n}\n.calendar .timer[_v-d1443772]{\n    margin:10px 0;\n    text-align: center;\n}\n.calendar .timer input[_v-d1443772]{\n    border-radius: 2px;\n    padding:5px;\n    font-size: 14px;\n    line-height: 18px;\n    color: #5e7a88;\n    width: 50px;\n    text-align: center;\n    border:1px solid #efefef;\n}\n.calendar .timer input[_v-d1443772]:focus{\n    border:1px solid #5e7a88;\n}\n.calendar-button[_v-d1443772]{\n    text-align: center;\n}\n.calendar-button span[_v-d1443772]{\n    cursor: pointer;\n    display: inline-block;\n    min-height: 1em;\n    min-width: 5em;\n    vertical-align: baseline;\n    background:#5e7a88;\n    color:#fff;\n    margin: 0 .25em 0 0;\n    padding: .6em 2em;\n    font-size: 1em;\n    line-height: 1em;\n    text-align: center;\n    border-radius: .3em;\n}\n.calendar-button span.cancel[_v-d1443772]{\n    background:#efefef;\n    color:#666;\n}\n.calendar .lunar[_v-d1443772]{\n     font-size:11px;\n     line-height: 150%;\n     color:#aaa;   \n}\n.calendar td.selected .lunar[_v-d1443772]{\n     color:#fff;   \n}\n"] = false
    document.head.removeChild(__vueify_style__)
  })
  if (!module.hot.data) {
    hotAPI.createRecord("_v-d1443772", module.exports)
  } else {
    hotAPI.update("_v-d1443772", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3,"vueify/lib/insert-css":8}],10:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

exports.getcookie = getcookie;
exports.errorMsg = errorMsg;
exports.responseErrorMsg = responseErrorMsg;
exports.tiaojian = tiaojian;
exports.checkBox = checkBox;
exports.initTiaojian = initTiaojian;
/**
 * Created by lbbniu on 16/9/18.
 */
function getcookie(name, nounescape) {
    var cookie_start = document.cookie.indexOf(name);
    var cookie_end = document.cookie.indexOf(";", cookie_start);
    if (cookie_start == -1) {
        return '';
    } else {
        var v = document.cookie.substring(cookie_start + name.length + 1, cookie_end > cookie_start ? cookie_end : document.cookie.length);
        return !nounescape ? unescape(v) : v;
    }
}

function errorMsg(errors) {
    for (var d in errors) {
        var msg = errors[d][0];
        layer.msg(msg);
        break;
    }
}
function responseErrorMsg(response) {
    var data = response.data;
    if (data.status_code == 401) {
        location.href = "/login";
    } else if (data.status_code == 403) {
        layer.msg("没有权限");
    } else {
        layer.msg(data.messsage);
    }
}

/**
 * 顶部条件的筛选功能
 * @param key
 * @param value  当前点击的条件值
 * @param arr    此类添加的总列表
 * @param getArr 加入筛选的数组
 * @param callback 回调函数
 * @param field  判断字段
 */
function tiaojian(key, value, arr, getArr, callback) {
    var field = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : 'key_field';

    setTimeout(function () {
        joinTxt("searchList");
    }, 1000);
    if (value[field] == -1) {
        if (value.checked == false) {
            $.each(arr, function (i, v) {
                v.checked = false;
            });
            value.checked = true;
            getArr.splice(0, getArr.length);
            if (key) storage.setStorage(key, getArr);
            if (typeof callback == 'function') {
                callback(1);
                return true;
            }
        }
        return true;
    }
    $.each(arr, function (i, v) {
        if (v[field] == -1) v.checked = false;
    });
    var index = getArr.indexOf(value[field]);
    if (index > -1) {
        getArr.splice(index, 1);
        value.checked = false;
        if (getArr.length < 1) {
            $.each(arr, function (i, v) {
                if (v[field] == -1) v.checked = true;
            });
        }
    } else {
        getArr.push(value[field]);
        value.checked = true;
    }
    if (key) storage.setStorage(key, getArr);
    return false;
}
/**
 * 顶部筛选复选框的处理
 * @param value
 * @param arr
 * @returns {boolean}
 */
function checkBox(key, value, arr) {
    var index = arr.indexOf(value);
    if (index > -1) {
        arr.splice(index, 1);
    } else {
        arr.push(value);
    }
    if (key) storage.setStorage(key, arr);
    return false;
}

/**
 *
 * @param sArr
 * @param tArr
 * @returns {boolean}
 */
function initTiaojian(sArr, tArr) {
    var field = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'key_field';

    setTimeout(function () {
        joinTxt("searchList");
    }, 1000);
    if ((typeof tArr === "undefined" ? "undefined" : _typeof(tArr)) == 'object' && tArr.length > 0) {
        sArr.forEach(function (e) {
            if (e[field] == -1) {
                e.checked = false;
            }
            if (tArr.indexOf(e[field]) > -1) {
                e.checked = true;
            }
        });
    }
}

var storage = {};
var uzStorage = function uzStorage() {
    return window.localStorage;
};
storage.setStorage = function (key, value) {
    if (arguments.length === 2) {
        var v = value;
        if ((typeof v === "undefined" ? "undefined" : _typeof(v)) == 'object') {
            v = JSON.stringify(v);
            v = 'obj-' + v;
        } else {
            v = 'str-' + v;
        }
        var ls = uzStorage();
        if (ls) {
            ls.setItem(key, v);
        }
    }
};
storage.getStorage = function (key) {
    var ls = uzStorage();
    if (ls) {
        var v = ls.getItem(key);
        if (!v) {
            return;
        }
        if (v.indexOf('obj-') === 0) {
            v = v.slice(4);
            return JSON.parse(v);
        } else if (v.indexOf('str-') === 0) {
            return v.slice(4);
        }
    }
};
storage.rmStorage = function (key) {
    var ls = uzStorage();
    if (ls && key) {
        ls.removeItem(key);
    }
};
storage.clearStorage = function () {
    var ls = uzStorage();
    if (ls) {
        ls.clear();
    }
};

exports.storage = storage;

},{}],11:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {
            //                    navbar: [
            //                        {name: '人员管理', url: '/app_users/1', admin:false,child: [
            //                            {name: '患者列表', url: '/app_users/1', admin: false},
            //                            {name: '医生管理', url: '/doctor/1', admin: false},
            //                            {name: '客服管理', url: '/service/1', admin: false}
            //                        ]},
            //                        {name: '财务管理', url: '/shop_deal/1', admin:false,child: [
            //                            {name: '商城订单', url: '/shop_deal/1', admin: false},
            //                            {name: '发货列表', url: '/send_list/1', admin: false},
            //                            //{name: '划价收费', url: '/charge_price/1', admin: false},
            //                            {name: '网诊预约订单', url: '/drug_manage/1', admin: false},
            //                            {name: '药费订单', url: '/drug_medicinal/1', admin: false},
            //                            {name: '充值订单', url: '/drug_pay/1', admin: false},
            //                        ]},
            //                        {name: '数据管理', url: '/count_manage', admin: false, child: [
            //                            {name: '经营统计', url: '/count_manage', admin: false},
            //                            {name: '患者统计', url: '/count_family', admin: false},
            //                            {name: '医师统计', url: '/count_doc', admin: false},
            //                            {name: '疗效统计', url: '/count_curative_detail/1', admin: false},
            //                            //{name: '疗效统计', url: '/count_curative/1', admin: false},
            //                            {name: '收入统计', url: '', admin: false},
            //                            {name: '商城统计', url: '/count_mall', admin: false},
            //                            {name: '方案统计', url: '/count_lnquiry', admin: false}
            //                        ]},
            //                        {
            //                            name: '内容管理', url: '/lnquiry_list/1', admin: false, child: [
            //                            {name: '问诊单', url: '/lnquiry_list/1', admin: false},
            //                            {name: '题库', url: '/question_list/1', admin: false},
            //                            {name: '方案处理', url: '/proposed_law/1', admin: false},
            //                            {name: '诊疗管理', url: '/chat_admin/1', admin: false},
            //                            {name: '评价管理', url: '/comment_admin/1', admin: false},
            //                            {name: '科室管理', url: '/section_admin/1', admin: false},
            //                        ]},
            //                        {name: '优惠码管理', url: '/promocode_list/1', admin: false, child: [
            //                            {name: '优惠码列表', url: '/promocode_list/1', admin: false},
            //                            {name: '优惠码添加', url: '/promocode_add', admin: false},
            //                            {name: '活动管理', url: '/promocode_mobile/1', admin: false}
            //                        ]},
            //                        {name: '管理员管理', url: '/adm_user/1', admin: true, child: [
            //                            {name: '管理员列表', url: '/adm_user/1', admin: true},
            //                            {name: '权限管理', url: '/adm_pri', admin: true},
            //                            {name: '数据同步', url: '/adm_sync', admin: true},
            //                            {name: '登录日志', url: '/adm_log/1', admin: true}
            //                        ]}
            //
            //                    ],
            user: User,
            navbar: Menu,
            search: '',
            total: 0
        };
    },
    created: function created() {
        this.getNotReadCount();
    },
    ready: function ready() {},

    events: {
        count: function count() {
            this.getNotReadCount();
        }
    },
    replace: false,
    methods: {
        url: function url() {
            location.href = '/admin/agreement';
        },
        logout: function logout() {
            location.href = '/logout';
        },
        getNotReadCount: function getNotReadCount() {
            this.$http.get('operation/count').then(function (res) {

                console.log(res);

                this.$set('total', res.data);
            });
        },
        gosearch: function gosearch() {
            this.search = this.search.replace(/(^\s*)|(\s*$)/g, "");
            if (this.search == '') {
                layer.msg('输入内容不能为空！');
                return false;
            }
            location.href = "/admin/search?" + encodeURI(this.search);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"header\">\n  <div class=\"container\">\n    <div class=\"col-xs-8\"><a v-link=\"{ path: '/app_users' }\" class=\"logo\"></a>\n      <ul role=\"navigation\" class=\"nav navbar-nav\">\n        <li v-for=\"nav in navbar\"><a v-link=\"{ path: nav.url }\">{{ nav.name }}</a>\n          <ul>\n            <li v-for=\"ch in nav.child\"><a v-if=\"ch.name == '协议管理'\" @click=\"url()\">{{ ch.name }}</a><a v-else=\"v-else\" v-link=\"{ path: ch.url }\">{{ ch.name }}</a></li>\n          </ul>\n        </li>\n      </ul>\n    </div>\n    <div class=\"col-xs-4 col-lg-2 text-right\">\n      <div class=\"user\"><a><i class=\"icon-user\"></i><span class=\"username\">{{user.user_name}}</span></a>\n        <ul class=\"list-unstyled\">\n          <li><a v-link=\"{ path: '/user_center' }\">登录日志</a></li>\n          <li><a v-on:click=\"logout()\">退出</a></li>\n        </ul>\n      </div><a v-link=\"{ path: '/operation_list' }\" class=\"icon-bell\"><b v-if=\"total>0\" @yidu=\"getNotReadCount\">{{total}}</b></a>\n    </div>\n  </div>\n</div>\n<router-view></router-view>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-f0e55a62", module.exports)
  } else {
    hotAPI.update("_v-f0e55a62", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],12:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _PopAuthsave = require('./module/PopAuthsave.vue');

var _PopAuthsave2 = _interopRequireDefault(_PopAuthsave);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        PopAuthsave: _PopAuthsave2.default
    },
    created: function created() {
        this.getAuth(1);
    },
    ready: function ready() {
        headNav(5);
    },
    data: function data() {
        return {
            auths: [],
            id: 0,
            cur: 0,
            all: 0,
            msg: ''
        };
    },

    events: {
        refreshList: function refreshList() {
            this.getAuth(this.cur);
        }
    },
    methods: {
        getAuth: function getAuth(page) {
            this.$http({ url: 'auth', method: 'get', params: { page: page } }).then(function (res) {
                this.$set('auths', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getAuth(data);
        },
        del: function del(id) {
            var vue = this;
            layer.confirm('您确定删除它和他的子权限？', {
                btn: ['确定', '取消']
            }, function () {
                vue.$http.delete('auth/' + id).then(function (res) {
                    if (res.data.status == 1) {
                        layer.msg(res.data.msg);
                        vue.$dispatch("refreshList");
                        vue.$dispatch('count');
                    } else {
                        layer.msg(res.data.msg);
                    }
                });
            }, function () {});
        },
        edit: function edit(id) {
            this.$set('id', id);
            $("#authsave").modal("show");
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">权限管理</div>\n    <div class=\"pull-right\"><a onclick=\"itemPop(undefined,'auth')\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>新增权限</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <table class=\"table table-bordered\">\n    <thead>\n      <tr>\n        <th>id</th>\n        <th>展示名</th>\n        <th>权限名</th>\n        <th>描述</th>\n        <th>操作</th>\n      </tr>\n    </thead>\n    <tbody>\n      <tr v-for=\"auth in auths\">\n        <td>{{auth.id}}</td>\n        <td>{{auth.display_name}}</td>\n        <td>{{auth.name}}</td>\n        <td>{{auth.description}}</td>\n        <td><span v-on:click=\"edit(auth.id)\">修改</span><span v-on:click=\"del(auth.id)\">删除</span></td>\n      </tr>\n    </tbody>\n  </table>\n  <nav>\n    <ul class=\"pagination\">\n      <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n    </ul>\n  </nav>\n  <pop-auth></pop-auth>\n  <pop-authsave :id.sync=\"id\"></pop-authsave>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-13cc352a", module.exports)
  } else {
    hotAPI.update("_v-13cc352a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/PopAuthsave.vue":56,"vue":7,"vue-hot-reload-api":3}],13:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _disease_common = require('./module/disease_common.vue');

var _disease_common2 = _interopRequireDefault(_disease_common);

var _disease_com_add = require('./module/disease_com_add.vue');

var _disease_com_add2 = _interopRequireDefault(_disease_com_add);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        diseasecommon: _disease_common2.default,
        diseasecomadd: _disease_com_add2.default
    },
    data: function data() {
        return {
            cur: 0,
            all: 0,
            data: {},
            id: 0
        };
    },
    created: function created() {
        this.getData();
    },

    events: {
        update: function update() {
            this.getData();
        }
    },
    ready: function ready() {
        headNav(5);
    },

    methods: {
        deletes: function deletes(id) {
            this.$http({ url: 'disease_common/' + id, method: 'delete' }).then(function (res) {
                if (res.data.status) {
                    this.getData();
                }
            });
        },
        add: function add() {
            $('#diseasecomadd').modal('show');
        },
        detail: function detail(id) {
            this.id = id;
            $('#diseasecommon').modal('show');
        },
        getData: function getData() {
            this.$http({ url: 'disease_common', method: 'GET' }).then(function (res) {
                this.$set('data', res.data.data);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">常见疾病</div>\n    <div class=\"pull-right\"><a @click=\"add()\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加疾病</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th>疾病名称</th>\n          <th>图标</th>\n          <th>操作功能</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.disease.name}}</td>\n          <td><img v-bind:src=\"val.icon\" style=\"width:120px;\"></td>\n          <td><span @click=\"detail(val.id)\">修改</span><span @click=\"deletes(val.id)\" style=\"color:red;\">删除</span></td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <diseasecommon :id.sync=\"id\"></diseasecommon>\n  <diseasecomadd></diseasecomadd>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-380ba62c", module.exports)
  } else {
    hotAPI.update("_v-380ba62c", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/disease_com_add.vue":76,"./module/disease_common.vue":77,"vue":7,"vue-hot-reload-api":3}],14:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {
            data: [],
            cur: 0,
            all: 0,
            total: 0,
            id: 0
        };
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getData();
    },
    ready: function ready() {
        headNav(5);
    },

    methods: {
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'job_field', method: 'GET', params: { page: this.page } }).then(function (res) {
                this.$set('data', res.data.data);
                this.$set('cur', res.data.current_page);
                this.$set('all', res.data.last_page);
                this.$set('total', res.data.total);
            });
        },
        listen: function listen(data) {
            this.getLogs(data);
            this.$router.go({ name: 'adm_log', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">接口失败日志</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th>接口的描述</th>\n          <th>接口编码</th>\n          <th style=\"width:50%\">返回数据</th>\n          <th>备注</th>\n          <th>请求时间</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"log in data\">\n          <td>{{log.title}}</td>\n          <td>{{log.code}}</td>\n          <td>{{log.return}}</td>\n          <td>{{log.remarks}}</td>\n          <td>{{log.created_at}}</td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n      </ul>\n    </nav>\n    <pop-logdetail :id.sync=\"id\"></pop-logdetail>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-5cb0aae8", module.exports)
  } else {
    hotAPI.update("_v-5cb0aae8", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],15:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {
            logs: [],
            cur: 0,
            all: 0,
            msg: '',
            id: 0
        };
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getLogs();
    },
    ready: function ready() {
        headNav(4);
    },

    methods: {
        getLogs: function getLogs() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'logs', method: 'GET', params: { page: this.page } }).then(function (res) {
                this.$set('logs', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getLogs(data);
            this.$router.go({ name: 'adm_log', params: { id: data } });
        },
        detail: function detail(id) {
            this.$set('id', id);
            $("#logdetail").modal("show");
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">登录日志</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th>登录账号</th>\n          <th>真实姓名</th>\n          <th>权限组</th>\n          <th>上次登录时间</th>\n          <th>操作功能</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"log in logs\">\n          <td>{{log.user_name}}</td>\n          <td>{{log.user_realname}}</td>\n          <td>{{log.group_name}}</td>\n          <td>{{log.created_at}}</td>\n          <td><span @click=\"detail(log.id)\">查看登录详情</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n      </ul>\n    </nav>\n    <pop-logdetail :id.sync=\"id\"></pop-logdetail>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-4bfe95b2", module.exports)
  } else {
    hotAPI.update("_v-4bfe95b2", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],16:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _PopUsergroupedit = require('./module/PopUsergroupedit.vue');

var _PopUsergroupedit2 = _interopRequireDefault(_PopUsergroupedit);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.datas = [];
exports.default = {
    components: {
        PopUsergroupedit: _PopUsergroupedit2.default
    },
    created: function created() {
        this.getAuth(1);
        this.getAuthJson();
    },
    ready: function ready() {
        headNav(4);
    },
    data: function data() {
        return {
            auths: [],
            cur: 0,
            all: 0,
            id: 0,
            getAuths: []
        };
    },

    events: {
        refreshList: function refreshList() {
            this.getAuth(this.cur);
        }
    },
    methods: {
        getAuthJson: function getAuthJson() {
            var self = this;
            this.$http.get('getJson').then(function (res) {
                var data = res.data.permissions;
                $('#priTree').jstree({
                    'plugins': ["wholerow", "checkbox"], 'core': {
                        'data': data
                    }
                }).on("changed.jstree", function (e, data) {
                    window.datas = data.selected;
                });
            });
        },
        getAuth: function getAuth(page) {
            this.$http({ url: 'role', method: 'get', params: { page: page } }).then(function (res) {
                this.$set('auths', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getAuth(data);
        },
        del: function del(id) {
            var vue = this;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                vue.$http.delete('role/' + id).then(function (res) {
                    if (res.data.status == 1) {
                        layer.msg(res.data.msg);
                        vue.$dispatch("refreshList");
                    } else {
                        layer.msg(res.data.msg);
                    }
                });
            }, function () {});
        },
        edit: function edit(id) {
            this.$set('id', id);
            $("#usergroupedit").modal("show");
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">权限管理</div>\n    <div class=\"pull-right\"><a v-link=\"{ path: 'auth' }\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>预览权限</span></a>\n      <!--a.btn.btn-primary.btn-sm(onclick=\"itemPop(#{i},'auth')\")\n      i.icon-plus\n      span 新增权限\n      --><a onclick=\"itemPop(undefined,'usergroup')\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>新增用户组</span></a>\n    </div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <!--.bg_cor-->\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th>#</th>\n          <th>用户组</th>\n          <th>描述</th>\n          <th>操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"auth in auths\">\n          <td>{{auth.id}}</td>\n          <td>{{auth.display_name}}</td>\n          <td>{{auth.description}}</td>\n          <td>\n            <!--i.icon-exit--><span v-on:click=\"edit(auth.id)\">修改</span><span v-on:click=\"del(auth.id)\">删除</span>\n          </td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <nav>\n    <ul class=\"pagination\">\n      <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n    </ul>\n  </nav>\n  <pop-auth></pop-auth>\n  <pop-usergroup></pop-usergroup>\n  <pop-usergroupedit :id.sync=\"id\"></pop-usergroupedit>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-24d23b95", module.exports)
  } else {
    hotAPI.update("_v-24d23b95", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/PopUsergroupedit.vue":67,"vue":7,"vue-hot-reload-api":3}],17:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    ready: function ready() {
        headNav(5);
    },
    data: function data() {
        return {};
    },


    methods: {
        enter: function enter(route) {
            var _this = this;
            var confirm = layer.confirm('您确定您的操作吗？', {
                btn: ['确定', '取消'],
                skin: 'layui-layer-molv'
            }, function () {
                _this.doctor_sync(route);
                layer.close(confirm);
            }, function () {});
        },

        doctor_sync: function doctor_sync(route) {
            this.$http.get('sync/' + route).then(function (res) {});
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">数据同步</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"box_css\">\n    <div class=\"syt\">\n      <form role=\"form\" class=\"form-horizontal\">\n        <div class=\"form-group\">\n          <div class=\"col-sm-10\">\n            <!--label.la(@click=\"enter('inquiry')\")\n            i.icon-folder-download\n            | 测试队列\n            -->\n            <label @click=\"enter('doctor')\" class=\"la\"><i class=\"icon-folder-download\"></i>同步医生数据</label>\n            <label @click=\"enter('scheduling')\" class=\"la\"><i class=\"icon-folder-download\"></i>同步医生排班</label>\n            <label @click=\"enter('clinique')\" class=\"la\"><i class=\"icon-folder-download\"></i>同步诊所信息</label>\n          </div>\n        </div>\n      </form>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-2ab6c09d", module.exports)
  } else {
    hotAPI.update("_v-2ab6c09d", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],18:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getTelephone(1);
    },
    ready: function ready() {
        headNav(5);
    },
    data: function data() {
        return {
            data: {}
        };
    },

    events: {
        telphone: function telphone() {
            this.getTelephone();
        }
    },
    methods: {
        getTelephone: function getTelephone() {
            this.$http.get('tel/getTelephone').then(function (res) {
                if (res.data.status) {
                    this.data = res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        del: function del(telId) {
            var vue = this;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                vue.$http.delete("tel/delTelephone/" + telId).then(function (res) {
                    if (res.data.status == 1) {
                        layer.msg(res.data.msg);
                        vue.$dispatch("telphone");
                    } else {
                        layer.msg(res.data.msg);
                    }
                });
            }, function () {
                layer.msg('取消成功!');
            });
        },
        updatestatus: function updatestatus(id) {
            this.$http.get('tel/updatestatus/' + id).then(function (res) {
                layer.msg(res.data.msg);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">客服手机号列表</div>\n    <div class=\"pull-right\"><a onclick=\"itemPop(undefined,'telephone')\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加客服手机号</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-2\">客服姓名</th>\n          <th class=\"col-sm-2\">诊所</th>\n          <th class=\"col-sm-2\">客服手机号</th>\n          <th class=\"col-sm-2\">创建时间</th>\n          <th class=\"col-sm-1\">客服状态</th>\n          <th class=\"col-sm-2\">操作功能</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"d in data\">\n          <td>{{d.id}}</td>\n          <td>{{d.kname}}</td>\n          <td>{{d.clinique?d.clinique.name:''}}</td>\n          <td>{{d.telephone}}</td>\n          <td>{{d.created_at}}</td>\n          <td>\n            <select v-model=\"d.status\" @change=\"updatestatus(d.id)\" class=\"form-control\">\n              <option value=\"0\">不在线</option>\n              <option value=\"1\">在线</option>\n            </select>\n          </td>\n          <td><span v-on:click=\"del(d.id)\" style=\"color:red\">删除</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <pop-telephone></pop-telephone>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-3bc60df2", module.exports)
  } else {
    hotAPI.update("_v-3bc60df2", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],19:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getUsers(1);
    },
    ready: function ready() {
        headNav(5);
    },
    data: function data() {
        return {
            users: [],
            cur: 0,
            all: 0,
            msg: '',
            userid: 0,
            groupid: 0
        };
    },

    events: {
        admuser: function admuser() {
            this.getUsers(this.cur);
        }
    },
    methods: {
        getUsers: function getUsers() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'user/index', method: 'GET', params: { page: this.page } }).then(function (res) {
                this.$set('users', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        del: function del(userId) {
            var vue = this;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                vue.$http.delete("user/" + userId).then(function (res) {
                    if (res.data.status == 1) {
                        layer.msg(res.data.msg);
                        vue.$dispatch("admuser");
                    } else {
                        layer.msg(res.data.msg);
                    }
                });
            }, function () {
                layer.msg('取消成功!');
            });
        },
        pwd: function pwd(id) {
            this.$http.get('user/pwd/' + id).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg("密码初始化为 123456");
                } else {
                    layer.msg("更新失败");
                }
            });
        },
        edit: function edit(userid) {
            this.$set('userid', userid);
            $("#userinfo").modal("show");
        },
        userGroup: function userGroup(id) {
            this.$set('groupid', id);
            $("#groupedit").modal("show");
        },
        forbidden: function forbidden(id) {
            if (id == 1) {
                layer.msg("不能冻结超级管理员!");return;
            }
            this.$http.get('user/forbidden/' + id).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$dispatch("admuser");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getUsers(data);
            this.$router.go({ name: 'adm_user', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">管理员列表</div>\n    <div class=\"pull-right\"><a onclick=\"itemPop(undefined,'useradd')\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加用户</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-2\">登录账号</th>\n          <th class=\"col-sm-1\">真实姓名</th>\n          <th class=\"col-sm-4\">权限组</th>\n          <th class=\"col-sm-5\">操作功能</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"user in users\">\n          <td>{{user.user_name}}</td>\n          <td>{{user.user_realname}}</td>\n          <td>{{user.group_name}}</td>\n          <td><span @click=\"edit(user.user_id)\">修改</span><span @click=\"userGroup(user.user_id)\">设定操作组</span>\n            <!--span 权限分配--><span v-on:click=\"pwd(user.user_id)\">密码初始化</span><span v-on:click=\"del(user.user_id)\" style=\"color:red\">删除</span><span v-on:click=\"forbidden(user.user_id)\">冻结</span>\n          </td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n      </ul>\n    </nav>\n    <pop-useradd></pop-useradd>\n    <pop-userinfo :userid.sync=\"userid\"></pop-userinfo>\n    <pop-groupedit :groupid.sync=\"groupid\"></pop-groupedit>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-a2e90c66", module.exports)
  } else {
    hotAPI.update("_v-a2e90c66", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],20:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.ue = UE.getEditor('container');
        this.ue2 = UE.getEditor('container2');
    },
    mounted: function mounted() {},
    ready: function ready() {
        headNav(4);

        this.getData();
    },
    data: function data() {
        return {
            ue: '',
            ue2: '',
            data: {},
            showPreview: ''
        };
    },

    methods: {
        getData: function getData() {
            var self = this;
            self.$http({ url: 'configs/agreement', method: 'get' }).then(function (res) {
                self.$set('data', res.data.data);
                self.ue.ready(function () {
                    self.ue.setContent(self.data[0].value);
                });
                self.ue2.ready(function () {
                    self.ue2.setContent(self.data[1].value);
                });
            });
        },
        edit: function edit(id, type) {
            var data = {};
            data.id = id;
            if (type == 'app') {
                data.value = this.ue.getContent();
            } else {
                data.value = this.ue2.getContent();
            }
            this.$http.post('configs/agreementedit', data).then(function (res) {
                if (res.data.status) {
                    layer.msg('修改成功');
                    this.getData();
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">用户协议11111</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <form role=\"form\" style=\"margin:0 auto;\" class=\"form-horizontal\">\n    <div class=\"form-group\">\n      <label class=\"col-sm-1 control-label\">app协议：</label>\n      <div style=\"margin-left:20px\" class=\"col-sm-10\">\n        <div id=\"container\"></div><a type=\"button\" @click=\"edit(data[0].id, 'app')\" class=\"btn btn-primary\">修改</a>\n      </div>\n      <label class=\"col-sm-1 control-label\">微信协议：</label>\n      <div style=\"margin-left:20px\" class=\"col-sm-10\">\n        <div id=\"container2\"></div><a type=\"button\" @click=\"edit(data[1].id, 'wechat')\" class=\"btn btn-primary\">修改</a>\n        <!--sss-->\n      </div>\n    </div>\n  </form>\n  <div v-html=\"showPreview\"></div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-25812d4d", module.exports)
  } else {
    hotAPI.update("_v-25812d4d", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],21:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getAllUsers();
    },
    ready: function ready() {
        headNav(0);
    },
    data: function data() {
        return {
            users: {},
            cur: 0,
            all: 0,
            total: 0,
            mobile: '',
            sex: '',
            name: '',
            searchs: {}
        };
    },

    events: {
        update: function update() {
            this.getAllUsers(this.cur);
        }
    },
    methods: {
        getAllUsers: function getAllUsers() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.searchs.sex = this.sex;
            this.searchs.name = this.name;
            this.searchs.mobile = this.mobile;
            this.$http({
                url: 'appuser/list',
                method: 'GET',
                params: { page: this.page, search: this.searchs }
            }).then(function (res) {
                this.$set('users', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
            this.$router.go({ name: 'app_users', params: { id: this.page } });
        },
        userDetail: function userDetail(id) {
            this.$router.go({ name: 'user_detail', params: { id: id } });
        },
        listen: function listen(data) {
            this.getAllUsers(data);
            this.$router.go({ name: 'app_users', params: { id: data } });
        },
        exportData: function exportData() {
            var title = '用户管理';
            var head = ['序号', '患者昵称', '真实姓名', '性别', '年龄', '联系方式', '城市', '来源'];
            var width = {
                'A': 10,
                'B': 10,
                'C': 10,
                'D': 10,
                'E': 10,
                'F': 20,
                'G': 20,
                'H': 10
            };
            this.searchs.sex = this.sex;
            this.searchs.name = this.name;
            this.searchs.mobile = this.mobile;
            this.$http.post('exports/exports', { title: title, head: head, search: this.searchs, width: width, type: 'app_user', export: true }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">患者列表</div>\n      <div class=\"pull-right\"><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <select v-model=\"sex\" v-on:change=\"getAllUsers(1)\" class=\"form-control\">\n            <option value=\"\" selected=\"selected\">请选择性别</option>\n            <option value=\"0\">未知</option>\n            <option value=\"1\">男</option>\n            <option value=\"2\">女</option>\n          </select>\n        </div>\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"name\" placeholder=\"输入患者姓名搜索\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"mobile\" placeholder=\"输入手机号搜索\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getAllUsers(1)\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-1\">患者昵称</th>\n          <th class=\"col-sm-1\">真实姓名</th>\n          <th class=\"col-sm-1\">性别</th>\n          <th class=\"col-sm-1\">年龄</th>\n          <th class=\"col-sm-1\">联系方式</th>\n          <th class=\"col-sm-1\">城市</th>\n          <th class=\"col-sm-1\">来源</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,user) in users\">\n          <td>{{index+1}}</td>\n          <td>{{user.nickname}}</td>\n          <td>{{user.realname}}</td>\n          <td>{{user.sex}}</td>\n          <td>{{user.age}}</td>\n          <td>{{user.mobile}}</td>\n          <td>{{user.province}}{{user.city}}{{user.area}}</td>\n          <td>{{user.source}}</td>\n          <td><span @click=\"userDetail(user.id,0)\">查看</span></td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-419139ad", module.exports)
  } else {
    hotAPI.update("_v-419139ad", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],22:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        console.log(1);
        this.ctype = this.$route.params.ctype;
        this.id = this.$route.params.id;
        this.family_id = this.$route.params.family_id;
        this.getData();
    },
    ready: function ready() {
        headNav(0);
    },
    data: function data() {
        return {
            data: {},
            ctype: 0,
            id: 0,
            family_id: 0
        };
    },


    methods: {
        getData: function getData() {
            this.$http({
                url: 'card_detail',
                method: 'get',
                params: { ctype: this.ctype, id: this.id, family_id: this.family_id }
            }).then(function (res) {
                this.$set('data', res.data.data);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">问诊单详情</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <!--/ctype 1:个性化问诊单|2:普通问诊单|3:处方|4:已回答个性化问诊单-->\n  <form role=\"form\" v-if=\"ctype ==2\" class=\"form-horizontal\">\n    <div class=\"form-group\">\n      <div class=\"col-sm-8\">\n        <label>病名:</label><span>{{data.user_clinic_title}}</span>\n      </div>\n      <div class=\"col-sm-8\">\n        <label>描述:</label><span>{{data.user_clinic_content}}</span>\n      </div>\n    </div>\n  </form>\n  <form role=\"form\" v-if=\"ctype ==1\" class=\"form-horizontal\">\n    <div v-for=\"ddd in data.options\" class=\"form-group sel_ti\">\n      <label v-if=\"ddd.type =='radio'\" class=\"col-sm-2 control-label\">单选题 ：</label>\n      <label v-if=\"ddd.type =='checkbox'\" class=\"col-sm-2 control-label\">复选题 ：</label>\n      <label v-if=\"ddd.type =='text'\" class=\"col-sm-2 control-label\">问答题 ：</label>\n      <label v-if=\"ddd.type =='photo'\" class=\"col-sm-2 control-label\">图片题 ：</label>\n      <div class=\"itemss\"><span>{{ddd.title}}</span></div>\n      <template v-if=\"ddd.type == 'checkbox' || ddd.type =='radio'\" track-by=\"$index\">\n        <p v-for=\"answer in ddd.option\" class=\"result\"><span> {{answer.val}}</span></p>\n      </template>\n      <template v-if=\"ddd.type == 'photo'\">\n        <p v-for=\"val in ddd.option\" class=\"result\"><img v-bind:src=\"val\"></p>\n      </template>\n    </div>\n  </form>\n  <form role=\"form\" v-if=\"ctype ==3\" class=\"form-horizontal\">\n    <label class=\"modal-title\">处方 {{data.recipe_head}} 副</label>\n    <div class=\"form-group\">\n      <div class=\"col-sm-8\"><span v-for=\"val in data.recipe\">\n          <p>{{val.name}} {{val.g}}g  {{val.other}}</p></span></div>\n      <div class=\"col-sm-8\">\n        <label>医嘱:</label><span>{{data.recipe_remark}}</span>\n      </div>\n    </div>\n  </form>\n  <form role=\"form\" v-if=\"ctype ==4\" class=\"form-horizontal\">\n    <div v-for=\"ddd in data.options\" class=\"form-group sel_ti\">\n      <label v-if=\"ddd.type =='radio'\" class=\"col-sm-2 control-label\">单选题 ：</label>\n      <label v-if=\"ddd.type =='checkbox'\" class=\"col-sm-2 control-label\">复选题 ：</label>\n      <label v-if=\"ddd.type =='text'\" class=\"col-sm-2 control-label\">问答题 ：</label>\n      <label v-if=\"ddd.type =='photo'\" class=\"col-sm-2 control-label\">图片题 ：</label>\n      <div class=\"itemss\"><span>{{ddd.title}}</span></div>\n      <template v-if=\"ddd.type == 'checkbox' || ddd.type =='radio'\" track-by=\"$index\">\n        <p v-for=\"answer in ddd.option\" class=\"result\"><span> {{answer.val}}</span></p>\n      </template>\n      <template v-if=\"ddd.type == 'photo'\">\n        <p v-for=\"val in ddd.option\" class=\"result\"><img v-bind:src=\"val\"></p>\n      </template>\n    </div>\n  </form>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-0603ec3a", module.exports)
  } else {
    hotAPI.update("_v-0603ec3a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],23:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _price_detail = require('./module/price_detail.vue');

var _price_detail2 = _interopRequireDefault(_price_detail);

var _refund = require('./module/refund.vue');

var _refund2 = _interopRequireDefault(_refund);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        price_detail: _price_detail2.default,
        refund: _refund2.default
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getDate();
    },
    ready: function ready() {
        headNav(0);
    },
    data: function data() {
        return {
            id: 0,
            data: {},
            order: {},
            can_price: 0,
            cur: 0,
            all: 0,
            total: 0,
            userName: '',
            doctorName: '',
            registration_no: '',
            val: {
                user: '',
                doctor: '',
                admin: '',
                recipe_head: '',
                recipe: ''

            },
            date_time: ''
        };
    },

    events: {
        update: function update() {
            this.getDate();
        }
    },
    methods: {
        remark: function remark(id, content) {
            this.$http({
                url: 'recipe/refund_remark/' + id,
                method: "PUT",
                params: { remark: content }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    this.getDate(this.cur);
                }
            });
            layer.closeAll();
        },
        layOpen: function layOpen(id) {
            var vue = this;
            layer.open({
                title: '<b>添加备注</b>',
                type: 1,
                area: ['500px', '300px'],
                fixed: false, //不固定
                scrollbar: false, //禁止出现滚动条
                btn: ['保存并关闭', '直接关闭'],
                maxmin: true,
                content: '<textarea id="remark" class="layer_open" ></textarea>',
                yes: function yes() {
                    var content = $("#remark").val();
                    vue.remark(id, content);
                },
                btn2: function btn2(index, layero) {
                    layer.close(index);
                }
            });
        },
        family_detail: function family_detail(fid) {
            window.open('/admin/family_detail/' + fid);
        },
        refund: function refund(order) {
            this.order = order;
            $('#refund').modal('show');
        },
        _send: function _send(id, send, is_price) {
            if (is_price == 0) {
                layer.msg('药方未划价');
            }
            var vue = this;
            if (send == 0) {
                var msg = '是否确定发送？';
            } else {
                var msg = '是否确定取消发送？';
            }
            var data = {};
            data.send = 1;
            layer.confirm(msg, {
                btn: ['确定', '取消'] //按钮
            }, function () {
                vue.$http({ url: 'prescription/setprice/' + id, method: "PUT", params: { data: data } }).then(function (res) {
                    layer.msg(res.data.msg);
                    if (res.data.status) {
                        this.getDate();
                    }
                });
            });
        },
        getDate: function getDate() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({
                url: 'prescription/pricelist',
                method: 'GET',
                params: {
                    can_price: this.can_price,
                    page: this.page,
                    userName: this.userName,
                    doctorName: this.doctorName,
                    registration_no: this.registration_no,
                    date_time: this.date_time
                }
            }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        detail: function detail(val) {
            this.val = val;
            $('#price_detail').modal('show');
        },
        listen: function listen(data) {
            this.getDate(data);
            this.$router.go({ name: 'charge_price', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">划价收费</div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dd class=\"row\">\n        <div style=\"margin-bottom:0;\" class=\"form-group clearfix\">\n          <div style=\"float:left\"><span></span>患者：</div>\n          <div style=\"float:left;width:20%;margin:0 20px;\" class=\"div\">\n            <input type=\"text\" v-model=\"userName\" class=\"form-control\">\n          </div>\n          <div style=\"float:left\"><span></span>医师：</div>\n          <div style=\"float:left;width:20%;margin:0 20px;\" class=\"div\">\n            <input type=\"text\" v-model=\"doctorName\" class=\"form-control\">\n          </div>\n          <div style=\"float:left;\">\n            <div class=\"input-group\">\n              <div @click=\"getDate(1)\" class=\"input-group-btn\">\n                <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n              </div>\n            </div>\n          </div>\n        </div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th style=\"width:5%\" class=\"col-sm-1\">序号</th>\n          <th style=\"width:5%\" class=\"col-sm-1\">患者</th>\n          <th style=\"width:5%\" class=\"col-sm-1\">医师</th>\n          <th style=\"width:5%\" class=\"col-sm-1\">药材费</th>\n          <th style=\"width:5%\" class=\"col-sm-1\">调剂费</th>\n          <th class=\"col-sm-1\">订单状态</th>\n          <!--th.col-sm-1 药方状态-->\n          <!--th.col-sm-1(style=\"width:10%\") 状态操作时间-->\n          <!--th.col-sm-1(style=\"width:5%\") 划价人-->\n          <!--th.col-sm-1(style=\"width:5%\") 退款人-->\n          <!--th.col-sm-1 退款备注-->\n          <th style=\"width:10%\" class=\"col-sm-1\">开方时间</th>\n          <th style=\"width:10%\" class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,val) in data\">\n          <td>{{page*10 +index-9}}</td>\n          <td>{{val.user.realname}}</td>\n          <td>{{val.doctor.name}}</td>\n          <td>{{val.medicine_price}}</td>\n          <td>{{val.dispensing_price}}</td>\n          <td>{{val.order.status}}</td>\n          <!--td {{val.priceStatus}}-->\n          <!--td {{val.is_price == 0 ? '' : val.price_time}}-->\n          <!--td {{val.price_time}}-->\n          <!--td {{val.admin.user_name}}-->\n          <!--td {{val.refund_admin_name}}-->\n          <!--td {{val.refund_remark}}-->\n          <td>{{val.created_at}}</td>\n          <td><span @click=\"detail(val)\">查看</span>\n            <!--span(v-if=\"val.send == 0\",@click=\"_send(val.id,val.send,val.is_price)\") 未发送-->\n            <!--span(v-else) 已发送-->\n            <!--span(@click=\"refund(val.order)\") 退款-->\n            <!--span(@click=\"layOpen(val.id)\") 退款备注-->\n          </td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <price_detail :val.sync=\"val\"></price_detail>\n  <refund :order.sync=\"order\"></refund>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-774d7bea", module.exports)
  } else {
    hotAPI.update("_v-774d7bea", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/price_detail.vue":84,"./module/refund.vue":86,"vue":7,"vue-hot-reload-api":3}],24:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _chat_detail = require('./module/chat_detail.vue');

var _chat_detail2 = _interopRequireDefault(_chat_detail);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        chat_detail: _chat_detail2.default
    },
    created: function created() {
        this.page = this.$route.params.id;
    },
    ready: function ready() {
        headNav(3);
    },
    data: function data() {
        return {
            id: 0,
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            search: {},
            order_code: '',
            doctor: '',
            user: '',
            nextCheck: []
        };
    },

    events: {
        update: function update() {
            this.getDate();
        }
    },
    watch: {
        search: {
            handler: function handler(val, oldVal) {
                this.getDate(1);
            },
            deep: true
        }
    },
    methods: {
        getDate: function getDate() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.search.user = this.user;
            this.search.order_code = this.order_code;
            this.search.doctor = this.doctor;
            this.$http({
                url: 'clinic/list',
                method: 'GET',
                params: { page: this.page, search: this.search }
            }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        exportData: function exportData() {
            this.searchs.name = this.name;
            this.searchs.type = this.type;
            this.$http({
                url: 'export/export',
                method: 'GET',
                params: { search: this.searchs }
            }).then(function (res) {
                if (res.data.status == 1) {
                    location.href = "/api/upload/download/" + res.data.name;
                }
            });
        },
        detail: function detail(id) {
            this.id = id;
            $('#chat_detail').modal('show');
        },
        listen: function listen(data) {
            this.getDate(data);
            this.$router.go({ name: 'chat_admin', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">诊疗管理</div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dd class=\"row\">\n        <!--.form-group(style=\"margin-bottom:0;\")\n        div(style=\"float:left;line-height:30px;\")\n            span\n            | 订单编号：\n        .col-sm-2\n            input.form-control(type=\"text\",v-model=\"order_code\")\n        -->\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float:left;line-height:30px;\"><span></span>医生：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"text\" v-model=\"doctor\" class=\"form-control\">\n          </div>\n        </div>\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float:left;line-height:30px;\"><span></span>患者：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"text\" v-model=\"user\" class=\"form-control\">\n          </div>\n        </div>\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <div @click=\"getDate(1)\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n      </dd>\n    </dl>\n    <dl>\n      <dd class=\"row\">\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float:left;line-height:30px;\"><span></span>类型：</div>\n          <div class=\"col-sm-1\">\n            <select v-model=\"search.first\" class=\"form-control\">\n              <option value=\"\" selected=\"selected\">不限</option>\n              <option value=\"0\">初诊</option>\n              <option value=\"1\">复诊</option>\n            </select>\n          </div>\n        </div>\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float:left;line-height:30px;\"><span></span>诊断方式：</div>\n          <div class=\"col-sm-1\">\n            <select v-model=\"search.type\" class=\"form-control\">\n              <option value=\"\" selected=\"selected\">不限</option>\n              <option value=\"0\">网诊</option>\n              <option value=\"1\">门诊</option>\n            </select>\n          </div>\n        </div>\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float:left;line-height:30px;\"><span></span>状态：</div>\n          <div class=\"col-sm-1\">\n            <select v-model=\"search.status\" class=\"form-control\">\n              <!--0:诊疗未开始[比如门诊预约未到时间] 5:诊疗中 9:追问中 10:诊疗结束-->\n              <option value=\"\" selected=\"selected\">不限</option>\n              <option value=\"0\">诊疗未开始</option>\n              <option value=\"5\">诊疗中</option>\n              <option value=\"9\">追问中</option>\n              <option value=\"10\">诊疗结束</option>\n            </select>\n          </div>\n        </div>\n      </dd>\n    </dl>\n    <dl>\n      <dd class=\"row\">\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float:left;line-height:30px;\"><span></span>诊疗日期：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"date\" v-model=\"search.created_at\" class=\"form-control\">\n          </div>\n        </div>\n        <!--.form-group(style=\"margin-bottom:0;\")-->\n        <!--    div(style=\"float:left;line-height:30px;\")-->\n        <!--        span-->\n        <!--        | 是否抓药：-->\n        <!--    .col-sm-1-->\n        <!--        select.form-control(v-model=\"search.is_prescription\")-->\n        <!--            option(value=3 selected) 不限-->\n        <!--            option(value=1) 是-->\n        <!--            option(value=0) 否-->\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span>\n          <!--sss-->\n        </div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th style=\"width:14%\" class=\"col-sm-1\">订单编号</th>\n          <th class=\"col-sm-1\">患者</th>\n          <th class=\"col-sm-1\">就诊医师</th>\n          <th class=\"col-sm-1\">类型</th>\n          <th class=\"col-sm-1\">诊断方式</th>\n          <th style=\"width:12%\" class=\"col-sm-1\">就诊时间</th>\n          <th class=\"col-sm-1\">状态</th>\n          <!--th.col-sm-1 是否抓药-->\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,cli) in data\">\n          <td>{{cli.bespeak.order.order_sn}}</td>\n          <td>{{cli.user.realname}}</td>\n          <td>{{cli.doctor.name}}</td>\n          <td>{{cli.type}}</td>\n          <td>{{cli.first}}</td>\n          <td>{{cli.created_at}}</td>\n          <td>{{cli.status}}</td>\n          <!--td {{cli.is_prescription}}-->\n          <td><span @click=\"detail(cli.id)\">查看详情</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <chat_detail :id.sync=\"id\"></chat_detail>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-1a682df5", module.exports)
  } else {
    hotAPI.update("_v-1a682df5", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/chat_detail.vue":72,"vue":7,"vue-hot-reload-api":3}],25:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _clinique = require('./module/clinique.vue');

var _clinique2 = _interopRequireDefault(_clinique);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        clinique: _clinique2.default
    },
    data: function data() {
        //页面用到的数据
        return {
            cur: 0,
            all: 0,
            data: {},
            val: {
                content: {}
            }
        };
    },
    created: function created() {
        //实例创建后调用
        headNav(4);
        this.getData();
    },

    events: {
        userupdate: function userupdate() {
            this.getData();
        }
    },
    methods: {
        getData: function getData() {
            this.$http({ url: 'clinique/index', method: 'GET' }).then(function (res) {
                this.$set('data', res.data.data);
            });
        },
        save: function save(val) {
            this.$set('val', val);
            $("#clinique").modal("show");
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">诊所管理</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"item_list\">\n    <div class=\"list\">\n      <div class=\"user_table_box table-responsive\">\n        <table class=\"table table-bordered\">\n          <thead>\n            <tr>\n              <th>门店名</th>\n              <th>所在城市</th>\n              <th>电话</th>\n              <th>地址</th>\n              <th>经度</th>\n              <th>纬度</th>\n              <th>操作</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"val in data\">\n              <td>{{val.name}}</td>\n              <td>{{val.address}}</td>\n              <td>{{val.telephone}}</td>\n              <td>{{val.content.address}}</td>\n              <td>{{val.content.longitude}}</td>\n              <td>{{val.content.latitude}}</td>\n              <td><span @click=\"save(val)\">修改</span></td>\n            </tr>\n          </tbody>\n        </table>\n      </div>\n    </div>\n    <clinique :val.sync=\"val\"></clinique>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-162cde93", module.exports)
  } else {
    hotAPI.update("_v-162cde93", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/clinique.vue":74,"vue":7,"vue-hot-reload-api":3}],26:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getData(1);
    },
    ready: function ready() {
        headNav(3);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            search: {
                doctor: '',
                name: '',
                disease: '',
                condition: '',
                start: '',
                end: ''
            }
        };
    },

    methods: {
        save_type: function save_type(id, status) {
            this.$http({ url: 'comment/save/' + id, method: 'put', params: { status: status } }).then(function (res) {
                if (res.data.status == 0) {
                    layer.msg(res.data.msg);
                }
            });
        },
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'comment/comment', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'comment_admin', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">评论管理</div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dd class=\"row\">\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float: left;line-height: 30px;margin-left: 10px;\"><span></span>病名：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"text\" v-model=\"search.disease\" class=\"form-control\">\n          </div>\n        </div>\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float: left;line-height: 30px;margin-left: 10px;\"><span></span>医生：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"text\" v-model=\"search.doctor\" class=\"form-control\">\n          </div>\n        </div>\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float: left;line-height: 30px;margin-left: 10px;\"><span></span>患者：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"text\" v-model=\"search.name\" class=\"form-control\">\n            <!--ss sss-->\n          </div>\n        </div>\n      </dd>\n    </dl>\n    <dl>\n      <dd class=\"row\">\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float: left;line-height: 30px;margin-left: 10px;\"><span></span>疗效：</div>\n          <div class=\"col-sm-1\">\n            <select v-model=\"search.condition\" @change=\"getData(1)\" class=\"form-control\">\n              <option value=\"\" selected=\"selected\">全部</option>\n              <!--1:痊愈: 2:有效 3：无效 4：恶化-->\n              <option value=\"1\">痊愈</option>\n              <option value=\"2\">有效</option>\n              <option value=\"3\">无效</option>\n              <option value=\"4\">恶化</option>\n            </select>\n          </div>\n        </div>\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float: left;line-height: 30px;margin-left: 10px;\"><span></span>开始时间：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"date\" v-model=\"search.start\" class=\"form-control\">\n          </div>\n        </div>\n        <div style=\"margin-bottom:0;\" class=\"form-group\">\n          <div style=\"float: left;line-height: 30px;margin-left: 10px;\"><span></span>结束时间：</div>\n          <div class=\"col-sm-2\">\n            <input type=\"date\" v-model=\"search.end\" class=\"form-control\">\n          </div>\n        </div>\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <div @click=\"getData(1)\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">病名</th>\n          <th class=\"col-sm-1\">患者</th>\n          <th class=\"col-sm-1\">医生</th>\n          <th style=\"width:120px\" class=\"col-sm-1\">评价时间</th>\n          <th class=\"col-sm-1\">疗效</th>\n          <th class=\"col-sm-1\">态度（星）</th>\n          <th class=\"col-sm-3\">评价详情</th>\n          <th class=\"col-sm-1\">审核状态</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.disease}}</td>\n          <td>{{val.user.realname}}</td>\n          <td>{{val.doctor.name}}</td>\n          <td>{{val.created_at}}</td>\n          <td>{{val.condition}}</td>\n          <td>\n            <div v-bind:class=\"val.manner\"></div>\n          </td>\n          <td>{{val.content}}</td>\n          <td>\n            <select v-model=\"val.status\" @change=\"save_type(val.id, val.status)\">\n              <option value=\"0\">未审核</option>\n              <option value=\"1\">审核通过</option>\n              <option value=\"2\">审核拒绝</option>\n            </select>\n          </td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-6020a4b2", module.exports)
  } else {
    hotAPI.update("_v-6020a4b2", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],27:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getData();
    },
    ready: function ready() {
        headNav(2);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            title: ['病名', '总计（例）', '医生（位）', '痊愈数', '好转数', '无效数', '恶化数'],
            time: {
                startTime: '',
                endTime: ''
            },
            name: ''
        };
    },

    events: {
        update: function update() {
            this.getData();
        }
    },
    methods: {
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'count/curative', method: 'GET', params: { page: this.page, name: this.name, time: this.time } }).then(function (res) {
                this.$set('data', res.data.data.data);
                this.$set('cur', res.data.data.current_page);
                this.$set('all', res.data.data.last_page);
                this.$set('total', res.data.data.total);
            });
        },
        exports: function exports() {
            var title = '疗效统计';
            var width = { 'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 10, 'I': 10 };
            var obj = {};
            for (var i = 0; i < this.data.length; i++) {
                var name = this.data[i].name;
                var num = this.data[i].num;
                var doc_count = this.data[i].doc_count;
                var cure = this.data[i].cure ? this.data[i].cure : 0;
                var valid = this.data[i].valid ? this.data[i].valid : 0;
                var invalid = this.data[i].invalid ? this.data[i].invalid : 0;
                var worsen = this.data[i].worsen ? this.data[i].worsen : 0;
                obj[i] = [name, num, doc_count, cure, valid, invalid, worsen];
            }
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: this.title, data: obj, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'count_curative', params: { id: data } });
        },
        detail: function detail(id) {
            this.$router.go({ name: 'count_curative_detail', params: { id: id, page: 1 } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">疗效统计</div>\n      <div class=\"pull-right\"><a @click=\"exports()\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>导出表格</span></a></div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"name\" placeholder=\"输入疾病名称\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"time.startTime\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"time.endTime\" class=\"form-control auto_inp\">\n            <div @click=\"getData()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">病名</th>\n          <th class=\"col-sm-1\">总计（例）</th>\n          <th class=\"col-sm-1\">医生（位）</th>\n          <th class=\"col-sm-1\">痊愈数</th>\n          <th class=\"col-sm-1\">好转数</th>\n          <th class=\"col-sm-1\">无效数</th>\n          <th class=\"col-sm-1\">恶化数</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.name}}</td>\n          <td>{{val.num}}</td>\n          <td>{{val.doc_count ? val.doc_count : 0}}</td>\n          <td>{{val.cure ? val.cure: 0}}</td>\n          <td> {{val.valid ? val.valid : 0}}</td>\n          <td> {{val.invalid ? val.invalid : 0}}</td>\n          <td> {{val.worsen ? val.worsen : 0}}</td>\n          <td><span @click=\"detail(val.id)\">查看</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-0d58affc", module.exports)
  } else {
    hotAPI.update("_v-0d58affc", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],28:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _disease_count = require('./module/disease_count.vue');

var _disease_count2 = _interopRequireDefault(_disease_count);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    created: function created() {
        this.page = this.$route.params.id; ///
        this.getData();
    },

    components: {
        disease_count: _disease_count2.default
    },
    ready: function ready() {
        headNav(2);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            sort: 1,
            doctor_id: 0,
            total: 0,
            title: ['医生', '总计', '痊愈数', '好转数', '无效数', '恶化数'],
            time: {
                startTime: '',
                endTime: ''
            }
        };
    },

    events: {
        update: function update() {
            this.getData();
        }
    },
    methods: {
        detail: function detail(id) {
            this.doctor_id = id;
            $('#disease_count').modal("show");
        },
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'count/comment', method: 'GET', params: { page: this.page, sort: this.sort, time: this.time } }).then(function (res) {
                this.$set('data', res.data.data.data);
                this.$set('cur', res.data.data.current_page);
                this.$set('all', res.data.data.last_page);
                this.$set('total', res.data.data.total);
            });
        },
        exports: function exports() {
            var title = '疗效统计';
            var width = { 'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 10, 'I': 10 };
            var obj = {};
            for (var i = 0; i < this.data.length; i++) {
                var name = this.data[i].name;
                var num = this.data[i].num;
                var doc_count = this.data[i].doc_count;
                var cure = this.data[i].cure ? this.data[i].cure : 0;
                var valid = this.data[i].valid ? this.data[i].valid : 0;
                var invalid = this.data[i].invalid ? this.data[i].invalid : 0;
                var worsen = this.data[i].worsen ? this.data[i].worsen : 0;
                obj[i] = [name, num, doc_count, cure, valid, invalid, worsen];
            }
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: this.title, data: obj, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'count_curative_detail', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">疗效统计详情</div>\n      <div class=\"pull-right\">\n        <!--a.btn.btn-primary.btn-sm(@click=\"exports()\")\n        i.icon-plus\n        span 导出表格\n        -->\n      </div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <!--.form-group(style=\"margin-bottom:0;\")\n        div(style=\"float:left\") 治愈数：\n        .col-sm-1\n            select.form-control(v-model=\"sort\")\n                option(value=1 selected) 不限\n                option(value=2) 按正序排序\n                option(value=3) 按倒序排序\n        -->\n        <div class=\"col-sm-1\">起始时间：</div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"time.startTime\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-1\">结束时间：</div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"time.endTime\" class=\"form-control auto_inp\">\n            <div @click=\"getData()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div style=\"margin-left: 20px;\" class=\"col-sm-2\"><span>共 {{total}} 条记录</span>\n          <!--ss-->\n        </div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">医生</th>\n          <th class=\"col-sm-1\">总计</th>\n          <th class=\"col-sm-1\">痊愈数</th>\n          <th class=\"col-sm-1\">明显好转</th>\n          <th class=\"col-sm-1\">好转</th>\n          <th class=\"col-sm-1\">没变化</th>\n          <!--th.col-sm-1 操作-->\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.name}}</td>\n          <td>{{val.total}}</td>\n          <td>{{val.recovery ? val.recovery: 0}}</td>\n          <td> {{val.better ? val.better : 0}}</td>\n          <td> {{val.good ? val.good : 0}}</td>\n          <td> {{val.noChange ? val.noChange : 0}}</td>\n          <!--tdspan(@click=\"detail(val.id)\") 查看\n          -->\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <disease_count :doctor_id.sync=\"doctor_id\"></disease_count>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-1d0ae144", module.exports)
  } else {
    hotAPI.update("_v-1d0ae144", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/disease_count.vue":78,"vue":7,"vue-hot-reload-api":3}],29:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getDate();
    },
    ready: function ready() {
        headNav(2); //
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            title: ['医师', '预约人数', '接诊数', '转诊数', '现场看诊数', '远程问诊数', '抓药数', '代煎量', '快递量'],
            time: {
                startTime: '',
                endTime: ''
            },
            name: ''
        };
    },

    watch: {},
    events: {
        update: function update() {
            this.getDate();
        }
    },
    methods: {
        getDate: function getDate() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'count/doctor', method: 'GET', params: { page: this.page, name: this.name, time: this.time } }).then(function (res) {
                this.$set('data', res.data.data.data);
                this.$set('cur', res.data.data.current_page);
                this.$set('all', res.data.data.last_page);
                this.$set('total', res.data.data.total);
            });
        },
        exports: function exports() {
            var title = '医师统计';
            var width = { 'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 10, 'I': 10 };
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: this.title, data: this.data, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getDate(data);
            this.$router.go({ name: 'count_doc', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">医师统计</div>\n      <div class=\"pull-right\">\n        <!--a.btn.btn-primary.btn-sm(@click=\"exports()\")\n        i.icon-plus\n        span 导出表格\n        -->\n      </div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"name\" placeholder=\"输入医生姓名\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"time.startTime\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"time.endTime\" class=\"form-control auto_inp\">\n            <div @click=\"getDate()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div style=\"margin-left: 20px;\" class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">医师</th>\n          <th class=\"col-sm-1\">预约人数</th>\n          <th class=\"col-sm-1\">接诊数</th>\n          <!--th.col-sm-1 转诊数-->\n          <th class=\"col-sm-1\">现场看诊数</th>\n          <th class=\"col-sm-1\">远程问诊数</th>\n          <th class=\"col-sm-1\">抓药数</th>\n          <th class=\"col-sm-1\">代煎量</th>\n          <th class=\"col-sm-1\">快递量</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.doctor}}</td>\n          <td>{{val.bespeaks}}</td>\n          <td>{{val.accept}}</td>\n          <!--td-->\n          <td>{{val.clinic}}</td>\n          <td>{{val.web}}</td>\n          <td>{{val.medicine}}</td>\n          <td>{{val.tisane}}</td>\n          <td>{{val.express}}</td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-62e8924b", module.exports)
  } else {
    hotAPI.update("_v-62e8924b", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],30:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getDate();
    },
    ready: function ready() {
        headNav(2);
        _init_area();
    },
    data: function data() {
        return {
            id: 0,
            man: [],
            woman: [],
            max: 0,
            sumMan: 0,
            sumWoman: 0,
            area: {}
        };
    },

    methods: {
        initIChart: function initIChart() {
            var data = [{
                name: '男' + this.sumMan + '人',
                value: this.man,
                color: '#47b2c8'
            }, {
                name: '女' + this.sumWoman + '人',
                value: this.woman,
                color: '#db6086'
            }];
            var sumMan = this.sumMan;
            var sumWoman = this.sumWoman;
            var max = this.max;
            var chart = new iChart.BarMulti2D({
                render: 'first',
                data: data,
                labels: ["60岁以上", "45-60岁", "31-45岁", "19-30岁", "13-18岁", "0-12岁"],
                title: {
                    text: '患者年龄/性别分布图',
                    color: '#585757'
                },
                sub_option: {
                    border: {
                        enable: true,
                        color: '#fcfcfc'
                    },
                    listeners: {
                        parseText: function parseText(r, t) {
                            return t + "人" + (t * 100 / (sumWoman + sumMan)).toFixed(2) + "%";
                        }
                    }
                },
                width: 1200,
                height: 600,
                background_color: '#ffffff',
                legend: {
                    enable: true,
                    background_color: null,
                    border: {
                        enable: false
                    }
                },
                coordinate: {
                    scale: [{
                        position: 'bottom',
                        start_scale: 0,
                        end_scale: max,
                        scale_space: max / 10
                    }],
                    background_color: null,
                    axis: {
                        width: 0
                    },
                    width: 800,
                    height: 400
                }
            });
            chart.draw();
        },
        getDate: function getDate() {
            var search = {};
            if ($("#s_province").val() != '省份') {
                search.province = $("#s_province").val();
            }
            if ($("#s_city").val() != '地级市') {
                search.city = $("#s_city").val();
            }
            if ($("#s_county").val() != '市、县级市') {
                search.area = $("#s_county").val();
            }
            this.$http({
                url: 'count/user',
                method: 'GET',
                params: { search: search }
            }).then(function (res) {
                this.$set('man', res.data.data[0]);
                this.$set('woman', res.data.data[1]);
                this.$set('max', res.data.data[2]);
                this.$set('sumMan', res.data.data[3]);
                this.$set('sumWoman', res.data.data[4]);
                this.initIChart();
            });
        },
        exportData: function exportData() {
            this.searchs.name = this.name;
            this.searchs.type = this.type;
            this.$http({
                url: 'export/export',
                method: 'GET',
                params: { search: this.searchs }
            }).then(function (res) {
                if (res.data.status == 1) {
                    location.href = "/api/upload/download/" + res.data.name;
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">患者统计</div>\n    </div>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <div class=\"search_box\">\n      <dl>\n        <dd class=\"row\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-1 control-label\"><span></span>选择地区：</label>\n            <div class=\"col-sm-1\">\n              <select id=\"s_province\" name=\"s_province\" class=\"form-control\"></select>\n            </div>\n            <div class=\"col-sm-1\">\n              <select id=\"s_city\" name=\"s_city\" class=\"form-control\"></select>\n            </div>\n            <div class=\"col-sm-1\">\n              <select id=\"s_county\" name=\"s_county\" class=\"form-control\"></select>\n            </div>\n            <div class=\"col-sm-1\">\n              <div class=\"input-group\">\n                <div @click=\"getDate()\" class=\"input-group-btn\">\n                  <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n                </div>\n              </div>\n            </div>\n          </div>\n        </dd>\n      </dl>\n    </div>\n    <div id=\"first\"></div>\n    <div id=\"two\"></div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-12592fa1", module.exports)
  } else {
    hotAPI.update("_v-12592fa1", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],31:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getData();
    },
    ready: function ready() {
        headNav(2);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            cur_total: 10,
            count: {},
            title: ['医师', '诊费', '挂号费', '药费', '代煎费', '快递费', '退款'],

            startTime: '',
            endTime: '',
            name: ''
        };
    },

    watch: {
        time: {
            handler: function handler(val, oldVal) {
                this.getData();
            },
            deep: true
        }
    },
    events: {
        update: function update() {
            this.getData();
        }
    },
    methods: {
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({
                url: 'count/income', method: 'GET', params: {
                    name: this.name,
                    page: this.page,
                    startTime: this.startTime,
                    endTime: this.endTime
                    // cur_total: this.cur_total
                }
            }).then(function (res) {
                this.$set('data', res.data.data.data);
                this.$set('cur', res.data.data.current_page);
                this.$set('all', res.data.data.last_page);
                this.$set('total', res.data.data.total);
            });
        },
        exports: function exports() {
            var title = '收入统计';
            var width = { 'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10 };
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: this.title, data: this.data, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'count_income', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">收入统计</div>\n      <div class=\"pull-right\">\n        <!--a.btn.btn-primary.btn-sm(@click=\"exports()\")\n        i.icon-plus\n        span 导出表格\n        -->\n      </div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"name\" placeholder=\"输入医生姓名\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" name=\"startTime\" v-model=\"startTime\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" name=\"endTime\" v-model=\"endTime\" class=\"form-control auto_inp\">\n            <div @click=\"getData()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <!--.col-sm-2\n        .input-group\n            select.form-control(v-model=\"cur_total\")\n                option(value=10) 10条每页\n                option(value=20) 20条每页\n                option(value=50) 50条每页\n                option(value=100) 100条每页\n        \n        -->\n        <div style=\"margin-left:6%\" class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <!--p-->\n  <!--    span(style=\"margin-left:10px\") 平台总收入-->\n  <!--        i(style=\"color:blue;font-style: normal;\") {{count.total}}-->\n  <!--    span(style=\"margin-left:20px\") 诊费总收入-->\n  <!--        i(style=\"color:blue;font-style: normal;\") {{count.clinic_amount}}-->\n  <!--    span(style=\"margin-left:20px\") 挂号费总收入-->\n  <!--        i(style=\"color:blue;font-style: normal;\") {{count.reg_amount}}-->\n  <!--    span(style=\"margin-left:20px\") 药费总收入-->\n  <!--        i(style=\"color:blue;font-style: normal;\") {{count.medicinal_amount}}-->\n  <!--    span(style=\"margin-left:20px\") 代煎总收入-->\n  <!--        i(style=\"color:blue;font-style: normal;\") {{count.replace_amount}}-->\n  <!--    span(style=\"margin-left:20px\") 快递总收入-->\n  <!--        i(style=\"color:blue;font-style: normal;\") {{count.express_amount}}-->\n  <!--    span(style=\"margin-left:20px\") 总退款-->\n  <!--        i(style=\"color:blue;font-style: normal;\") {{count.refund_amount}}\n  //ssssss\n  //sssss\n  -->\n  <div style=\"margin-top:10px;\" class=\"user_table_box table-responsive\">\n    <!--ssssssss-->\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">医师</th>\n          <th class=\"col-sm-1\">门诊</th>\n          <th class=\"col-sm-1\">网诊</th>\n          <th class=\"col-sm-1\">药费</th>\n          <!--th.col-sm-1 代煎费-->\n          <!--th.col-sm-1 快递费-->\n          <th class=\"col-sm-1\">退款</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.name}}</td>\n          <td>{{val.clinic}}</td>\n          <td>{{val.web}}</td>\n          <td>{{val.medicine}}</td>\n          <!--td  {{val.replace_amount}}-->\n          <!--td  {{val.express_amount}}-->\n          <td> {{val.refund_amount}}</td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-b4386934", module.exports)
  } else {
    hotAPI.update("_v-b4386934", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],32:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getData();
    },
    ready: function ready() {
        headNav(2);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            title: ['序号', '商品', '医生', '成人男', '成人女', '儿童', '总计'], //2
            good_id: 1
        };
    },

    watch: {
        time: {
            handler: function handler(val) {
                this.getData();
            },
            deep: true
        },
        good_id: {
            handler: function handler(val) {
                this.getData();
            },
            deep: true
        }
    },
    events: {
        lnquirycount: function lnquirycount() {
            this.getData();
        }
    },
    methods: {
        getData: function getData() {
            this.$http({ url: 'count/lnquiry', method: 'GET', params: { time: this.time, goods_id: this.good_id } }).then(function (res) {
                if (res.data.errcode != 200) {
                    layer.msg(res.data.msg);
                    return;
                }
                this.$set('data', res.data.data);
            });
        },
        exports: function exports() {
            var title = '三伏贴方案统计';
            if (this.good_id == 1) {
                title = '至阳' + title;
            } else {
                title = '个性' + title;
            }
            var width = { 'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10 };
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: this.title, data: this.data, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">方案统计</div>\n    <div class=\"pull-right\"><a @click=\"exports()\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>导出表格</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <dl>\n    <dd class=\"row\">\n      <div class=\"form-group\">\n        <div style=\"float: left;margin-left: 10px;line-height: 30px;\">商品:</div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"good_id\" class=\"form-control\">\n            <option v-bind:value=\"1\">至阳三伏贴</option>\n            <option v-bind:value=\"2\">个性三伏贴</option>\n          </select>\n        </div>\n      </div>\n    </dd>\n  </dl>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-1\">商品</th>\n          <th class=\"col-sm-1\">医生</th>\n          <th class=\"col-sm-1\">成人男</th>\n          <th class=\"col-sm-1\">成人女</th>\n          <th class=\"col-sm-1\">儿童</th>\n          <th class=\"col-sm-1\">总计</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(i,val) in data\">\n          <td>{{i+1}}</td>\n          <td>{{val.goods_name}}</td>\n          <td>{{val.doctor_name}}</td>\n          <td>{{val.sum_man}}</td>\n          <td>{{val.sum_women}}</td>\n          <td>{{val.sum_child}}</td>\n          <td>{{val.total}}</td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-b241cbc6", module.exports)
  } else {
    hotAPI.update("_v-b241cbc6", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],33:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getData();
        this.getTotalIncome(1);
    },
    ready: function ready() {
        headNav(2);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            title: ['日期', '商品', '当天收入(元)'],
            time: {
                startTime: '',
                endTime: ''
            },
            good_id: 1,
            total_income: 0
        };
    },

    watch: {
        time: {
            handler: function handler(val) {
                this.getData();
            },
            deep: true
        },
        good_id: {
            handler: function handler(val) {
                this.getData();
                this.getTotalIncome(val);
            },
            deep: true
        }
    },
    events: {
        mallcount: function mallcount() {
            this.getData();
        },
        malltotal: function malltotal() {
            this.getTotalIncome();
        }
    },
    methods: {
        getData: function getData() {
            this.$http({ url: 'count/mall', method: 'GET', params: { time: this.time, good_id: this.good_id } }).then(function (res) {
                if (res.data.errcode != 200) {
                    layer.msg(res.data.msg);
                    return;
                }
                this.$set('data', res.data.data);
            });
        },
        getTotalIncome: function getTotalIncome(id) {
            this.$http.get('count/total/' + id).then(function (res) {
                this.total_income = res.data.data;
            });
        },
        exports: function exports() {
            var title = '三伏贴经营统计';
            if (this.good_id == 1) {
                title = '至阳' + title;
            } else {
                title = '个性' + title;
            }
            var width = { 'A': 10, 'B': 10, 'C': 10, 'D': 10 };
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: this.title, data: this.data, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">商城统计</div>\n    <div class=\"pull-right\"><a @click=\"exports()\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>导出表格</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <dl>\n    <dd class=\"row\">\n      <div class=\"form-group\">\n        <div style=\"margin-left: 10px;float: left;line-height: 30px;\">商品:</div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"good_id\" class=\"form-control\">\n            <option v-bind:value=\"1\">至阳三伏贴</option>\n            <option v-bind:value=\"2\">个性三伏贴</option>\n          </select>\n        </div>\n      </div>\n    </dd>\n    <dd style=\"margin:10px 0\" class=\"row\">\n      <div class=\"form-group\">\n        <div style=\"float: left;line-height: 30px;\">开始时间:</div>\n        <div class=\"col-sm-2\">\n          <input type=\"date\" v-model=\"time.startTime\" class=\"form-control\">\n        </div>\n        <div style=\"margin-left: 10px;float: left;line-height: 30px;\">结束时间:</div>\n        <div class=\"col-sm-2\">\n          <input type=\"date\" v-model=\"time.endTime\" class=\"form-control\">\n        </div>\n        <div style=\"margin-left: 10px;float: left;line-height: 30px;\">总计:</div>\n        <div style=\"line-height:30px\" class=\"col-sm-2\"><span>共 {{total_income}} 元</span></div>\n      </div>\n    </dd>\n  </dl>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-1\">日期</th>\n          <th class=\"col-sm-1\">商品</th>\n          <th class=\"col-sm-1\">当天收入(元)</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(i,val) in data\">\n          <td>{{i+1}}</td>\n          <td>{{val.time}}</td>\n          <td>{{val.goods_name}}</td>\n          <td>{{val.income}}</td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-61bcb0de", module.exports)
  } else {
    hotAPI.update("_v-61bcb0de", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],34:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getCount();
        this.getData();
    },
    ready: function ready() {
        headNav(2);
    },
    data: function data() {
        return {
            count: {},
            data: {},
            cur: 0,
            all: 0,
            title: ['日期', '预约人数', '接诊数', '转诊数', '现场看诊数', '远程问诊数', '抓药数', '代煎量', '快递量'],
            time: {
                startTime: '',
                endTime: ''
            }
        };
    },

    watch: {
        time: {
            handler: function handler(val, oldVal) {
                this.getData();
            },
            deep: true
        }
    },
    events: {
        update: function update() {
            this.getData();
        }
    },
    methods: {
        getCount: function getCount() {
            this.$http({ url: 'count/deal', method: 'GET' }).then(function (res) {
                this.$set('count', res.data.data);
            });
        },
        getData: function getData() {
            this.$http({ url: 'count/manage', method: 'GET', params: { time: this.time } }).then(function (res) {
                if (res.data.errcode != 200) {
                    layer.msg(res.data.msg);
                    return;
                }
                this.$set('data', res.data.data);
            });
        },
        exports: function exports() {
            //
            var title = '经营统计';
            var width = { 'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 10, 'I': 10 };
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: this.title, data: this.data, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">经营统计</div>\n    <!--.pull-right-->\n    <!--    a.btn.btn-primary.btn-sm(@click=\"exports()\")-->\n    <!--        i.icon-plus-->\n    <!--        span 导出表格-->\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">平台总收入</th>\n          <th class=\"col-sm-1\">门诊总收入</th>\n          <th class=\"col-sm-1\">在线咨询总收入</th>\n          <th class=\"col-sm-1\">药方总收入</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr>\n          <td>{{count.total}}</td>\n          <td>{{count.clinic_amount}}</td>\n          <td>{{count.net_amount}}</td>\n          <td>{{count.recipe_amount}}</td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <dl>\n    <dd class=\"row clearfix\">\n      <div style=\"margin-bottom:0px;float:left;\" class=\"form-group clearfix\">\n        <div style=\"float:left;line-height:30px;padding-left:30px;\"><span></span>开始时间：</div>\n        <div style=\"width:60%\" class=\"col-sm-2\">\n          <input type=\"date\" v-model=\"time.startTime\" class=\"form-control\">\n        </div>\n      </div>\n      <div style=\"margin-bottom:0px;float:left;\" class=\"form-group clearfix\">\n        <div style=\"float:left;line-height:30px;padding-left:30px;\"><span></span>结束时间：</div>\n        <div style=\"width:60%\" class=\"col-sm-2\">\n          <input type=\"date\" v-model=\"time.endTime\" class=\"form-control\">\n        </div>\n      </div>\n    </dd>\n  </dl>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">日期</th>\n          <th class=\"col-sm-1\">预约人数</th>\n          <th class=\"col-sm-1\">接诊数</th>\n          <!--th.col-sm-1 转诊数-->\n          <th class=\"col-sm-1\">现场看诊数</th>\n          <th class=\"col-sm-1\">远程问诊数</th>\n          <th class=\"col-sm-1\">抓药数</th>\n          <th class=\"col-sm-1\">代煎量</th>\n          <th class=\"col-sm-1\">快递量</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.time}}</td>\n          <td>{{val.sub}}</td>\n          <td>{{val.accept}}</td>\n          <!--td {{val.transfer}}-->\n          <td>{{val.people}}</td>\n          <td>{{val.net}}</td>\n          <td>{{val.medicinal}}</td>\n          <td>{{val.help}}</td>\n          <td>{{val.express}}</td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-77688922", module.exports)
  } else {
    hotAPI.update("_v-77688922", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],35:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _disease = require("./module/disease.vue");

var _disease2 = _interopRequireDefault(_disease);

var _section_add = require("./module/section_add.vue");

var _section_add2 = _interopRequireDefault(_section_add);

var _section_update = require("./module/section_update.vue");

var _section_update2 = _interopRequireDefault(_section_update);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        disease: _disease2.default,
        sectionadd: _section_add2.default,
        sectionupdate: _section_update2.default
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getData(1);
    },
    ready: function ready() {
        headNav(4);
    },

    events: {
        add: function add() {
            this.getData(this.cur);
        }
    },
    data: function data() {
        return {
            data: {},
            val: {},
            disease: {},
            cur: 0,
            all: 0,
            id: 0
        };
    },

    methods: {
        save: function save(id, status) {
            var data = {};
            data.id = id;
            data.status = status;
            data.type = 'save';
            this.$http.put('section/update', data).then(function (res) {
                if (res.data.status) {
                    layer.msg('操作成功');
                    this.getData();
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        updates: function updates(val) {
            this.$set('val', val);
            $('#section_update').modal('show');
        },
        deletes: function deletes(id) {
            this.$http({
                url: 'section/del/' + id,
                method: 'delete'
            }).then(function (res) {
                if (res.data.status == 1) {
                    this.getData();
                }
            });
        },
        add: function add() {
            $('#sectionadd').modal('show');
        },
        sort: function sort(id, _sort) {
            this.$http({
                url: 'section/' + id,
                method: 'PUT',
                params: {
                    type: 'sort',
                    sort: _sort
                }
            }).then(function (res) {
                if (res.data.status == 1) {
                    this.getData();
                }
            });
        },
        show: function show(val) {
            this.val = val;
            $("#disease").modal("show");
        },
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'section/index', method: 'GET', params: { page: this.page } }).then(function (res) {
                this.$set('data', res.data.data);
                this.$set('cur', res.data.meta.pagination.current_page);
                this.$set('all', res.data.meta.pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'section_admin', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">疾病管理</div>\n    <div class=\"pull-right\"><a @click=\"add()\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加科室</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-1\">名称</th>\n          <!--th.col-sm-1 排序-->\n          <th class=\"col-sm-1\">是否展示</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{cur*10-9 + $index}}</td>\n          <td>{{val.name}}</td>\n          <td>\n            <select v-model=\"val.status\" v-on:change=\"save(val.id,val.status)\" class=\"form-control\">\n              <option value=\"1\">是</option>\n              <option value=\"0\">否</option>\n            </select>\n          </td>\n          <!--td\n          span(@click=\"sort(val.id,1)\") ↑\n          span(@click=\"sort(val.id,-1)\") ↓\n          -->\n          <td><span @click=\"show(val)\">查看疾病</span><span @click=\"updates(val)\">修改</span><span @click=\"deletes(val.id)\" style=\"color:red;\">删除</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <disease :val.sync=\"val\"></disease>\n  <sectionadd></sectionadd>\n  <sectionupdate :val.sync=\"val\"></sectionupdate>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-269b278f", module.exports)
  } else {
    hotAPI.update("_v-269b278f", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/disease.vue":75,"./module/section_add.vue":88,"./module/section_update.vue":89,"vue":7,"vue-hot-reload-api":3}],36:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _export_check = require("./module/export_check.vue");

var _export_check2 = _interopRequireDefault(_export_check);

var _add_section = require("./module/add_section.vue");

var _add_section2 = _interopRequireDefault(_add_section);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    created: function created() {
        this.id = this.$route.params.id;

        this.$http.get('doctor/show/' + this.id).then(function (res) {
            this.$set('data', res.data.data);
            this.$set('diseases', res.data.data.diseases);
            this.$set('sections', res.data.data.sections);
            this.$set('schedules', res.data.data.schedules);
            this.$set('cliniques', res.data.data.cliniques);
            this.$set('leave', res.data.data.leave);

            this.$nextTick(function () {

                this.uploadFile();
            });
        });
    },

    components: {
        exportcheck: _export_check2.default,
        addsection: _add_section2.default
    },
    ready: function ready() {
        headNav(0);
    },
    data: function data() {
        return {
            id: 0,
            aid: 0,
            title: 0,
            data: {},
            leave: {},
            diseases: {},
            sections: {},
            schedules: {},
            cliniques: {},
            item: {},
            cur: 0,
            all: 0,
            total: 0,
            page: 1,
            time: ''
        };
    },

    events: {
        refreshList: function refreshList() {
            this.getDate();
        }
    },
    watch: {},
    methods: {
        getDate: function getDate() {
            this.$http.get('doctor/show/' + this.id).then(function (res) {
                this.$set('data', res.data.data);
                this.$set('diseases', res.data.data.diseases);
                this.$set('sections', res.data.data.sections);
                this.$set('schedules', res.data.data.schedules);
                this.$set('cliniques', res.data.data.cliniques);
                this.$set('leave', res.data.data.leave);
            });
        },
        doctorEdit: function doctorEdit() {
            var data = {};
            data.web_amount = this.data.web_amount * 100;
            data.video_amount = this.data.video_amount * 100;
            data.title = this.title;
            data.intro = this.data.intro;
            data.head_img_L = this.data.head_img_L;
            data.level = this.data.level;
            data.diy_level = this.data.diy_level;
            data.use_diy = $('input[name="use_diy"]:checked').val();
            data.read_recipe = $('input[name="read_recipe"]:checked').val();
            this.$http.put('doctor/update/' + this.id, data).then(function (res) {
                layer.msg(res.data.msg);
            });
        },
        adDisease: function adDisease() {
            $("#exportcheck").modal("show");
        },
        addSection: function addSection() {
            $("#addsection").modal("show");
        },
        delDisease: function delDisease(id, type) {
            var data = {};
            data.type = type;
            this.$http.put('doctor/deldisease/' + this.id + "/" + id, data).then(function (res) {
                if (res.data.status) {
                    this.getDate();
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        titleEdit: function titleEdit(titleId) {
            this.title = titleId;
        },
        save: function save(id, status) {
            var data = {};
            data.status = status;
            this.$http.put('doctor/leave/' + id, data).then(function (res) {
                layer.msg(res.data.msg);
            });
        },
        uploadFile: function uploadFile() {
            var index = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            var vue = this;
            layui.use('upload', function () {
                layui.upload({
                    url: '/api/upload/img',
                    elem: '.test' //指定原始元素，默认直接查找class="layui-upload-file"
                    , method: 'post',
                    before: function before(input) {},
                    success: function success(res) {
                        vue.data.head_img_L = res.data;
                        $('.doc_headimg').attr('src', res.data);
                        $('.doc_headimg').show();
                    }
                });
            });
        }
    }
    //sss

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">医生管理 &gt; 医生详情\n      <label></label>\n    </div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">头像：</label>\n        <div class=\"col-sm-2\">\n          <div class=\"sel_box\">\n            <!--/.img-face-t(v-bind:style=\"{backgroundImage:'url(' +data.photoSUrl+')' }\")--><img v-bind:src=\"data.head_img_L\" style=\"width:120px;height:120px;\" class=\"doc_headimg\">\n          </div>\n        </div>\n        <div class=\"col-sm-3\">\n          <input name=\"file\" type=\"file\" class=\"test form-control layui-upload-file\">\n        </div>\n        <div class=\"col-sm-3\">\n          <p>医生姓名： {{data.name}}</p>\n          <p>手机号： {{data.mobile}}</p>\n          <p>地  区： {{data.address}}</p>\n          <p>网诊诊费：\n            <input v-model=\"data.web_amount\">\n          </p>\n          <p>视频问医：\n            <input v-model=\"data.video_amount\">\n          </p>\n          <p>职称：\n            <select v-model=\"data.titleId\" v-on:change=\"titleEdit(data.titleId)\" class=\"form-control\">\n              <option v-for=\"t in data.title\" v-bind:value=\"t.id\">{{t.name}}</option>\n            </select>\n          </p>\n          <p>介绍信息：\n            <textarea style=\"width: 500px; height: 300px;\" v-model=\"data.intro\"></textarea>\n          </p>\n          <p>患者推荐指数：\n            </p><p>患者正常推荐指数:\n              </p><p class=\"star_{{data.level}}\"></p>\n              <p>{{data.level}}</p>\n            <p></p>\n            <p>后台自定义推荐指数:\n              </p><p class=\"star_{{data.diy_level}}\"></p>\n              <p>\n                <select v-model=\"data.diy_level\" class=\"form-control\">\n                  <option v-bind:value=\"1\">1</option>\n                  <option v-bind:value=\"2\">2</option>\n                  <option v-bind:value=\"3\">3</option>\n                  <option v-bind:value=\"4\">4</option>\n                  <option v-bind:value=\"5\">5</option>\n                </select>\n              </p>\n            <p></p>\n            <p>是否使用后台自定义方式:\n              <input type=\"radio\" name=\"use_diy\" v-bind:checked=\"data.use_diy==1\" v-bind:value=\"1\"><span>否</span>\n              <input type=\"radio\" name=\"use_diy\" v-bind:checked=\"data.use_diy==0\" v-bind:value=\"0\"><span>是</span>\n            </p>\n          <p></p>\n          <p>是否可以在聊天中查看患者门诊处方及病历:\n            <input type=\"radio\" name=\"read_recipe\" v-bind:checked=\"data.read_recipe==0\" v-bind:value=\"0\"><span>否</span>\n            <input type=\"radio\" name=\"read_recipe\" v-bind:checked=\"data.read_recipe==1\" v-bind:value=\"1\"><span>是</span>\n          </p>\n          <button type=\"button\" @click=\"doctorEdit(data)\" style=\"margin-top:10px\" class=\"btn btn-primary\">保存</button>\n        </div>\n      </div>\n    </form>\n    <ul class=\"nav nav-tabs\">\n      <li class=\"active\"><a href=\"#tab1\" role=\"tab\" data-toggle=\"tab\">个人信息</a></li>\n      <li><a href=\"#tab6\" role=\"tab\" data-toggle=\"tab\">医生所属科室</a></li>\n      <li><a href=\"#tab2\" role=\"tab\" data-toggle=\"tab\">医生擅长</a></li>\n      <li><a href=\"#tab3\" role=\"tab\" data-toggle=\"tab\">医生排班</a></li>\n      <li><a href=\"#tab4\" role=\"tab\" data-toggle=\"tab\">出诊诊所</a></li>\n      <!--li\n      a(href=\"#tab5\" role=\"tab\" data-toggle=\"tab\") 休息记录\n      \n      -->\n    </ul>\n    <div class=\"tab-content\">\n      <div id=\"tab1\" class=\"tab-pane fade in active\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <!--.form-group\n          label.col-sm-1.control-label(for='') 介绍信息：\n          .col-sm-10\n              textarea(style=\"width: 500px; height: 300px;\",v-model=\"data.intro\")\n              button.btn.btn-primary(type='button',@click=\"doctorEdit(data)\",style=\"margin-top:10px\") 保存\n          -->\n          <div class=\"form-group\">\n            <label for=\"\" style=\"float:none\" class=\"col-sm-1 control-label\">认证信息：</label>\n            <div class=\"col_box\">\n              <div class=\"labels\">\n                <div class=\"sel_box clearfix\">\n                  <div class=\"ys_title\">医师执业证书：</div>\n                  <div v-for=\"img in data.profession_auth\" track-by=\"$index\" class=\"img-face2\"><img v-bind:src=\"img\"></div>\n                </div>\n              </div>\n            </div>\n            <div class=\"col_box\">\n              <div class=\"labels\">\n                <div class=\"sel_box clearfix\">\n                  <div class=\"ys_title\">医师资格证书：</div>\n                  <div v-for=\"img in data.qualification_auth\" track-by=\"$index\" class=\"img-face2\"><img v-bind:src=\"img\"></div>\n                </div>\n              </div>\n            </div>\n            <div class=\"col_box\">\n              <div class=\"labels\">\n                <div class=\"sel_box clearfix\">\n                  <div class=\"ys_title\">专业技术资格证书：</div>\n                  <div v-if=\"data.major_qualification_auth\" class=\"img-face2\"><img v-bind:src=\"data.major_qualification_auth\"></div>\n                </div>\n              </div>\n            </div>\n          </div>\n        </form>\n      </div>\n      <div id=\"tab6\" class=\"tab-pane fade\">\n        <div class=\"col-sm-2\">\n          <button type=\"button\" @click=\"addSection()\" class=\"btn btn-primary\">添加</button>\n        </div>\n        <table class=\"table table-bordered check_list\">\n          <thead>\n            <tr>\n              <th class=\"col-sm-1\">序号</th>\n              <th class=\"col-sm-1\">名称</th>\n              <th class=\"col-sm-1\">操作</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"val in sections\">\n              <td>{{$index +1}}</td>\n              <td>{{val.name}}</td>\n              <td><span @click=\"delDisease(val.id, 'section')\" class=\"doc_red\">删除</span></td>\n            </tr>\n          </tbody>\n        </table>\n      </div>\n      <div id=\"tab2\" class=\"tab-pane fade\">\n        <div class=\"col-sm-2\">\n          <button type=\"button\" @click=\"adDisease()\" class=\"btn btn-primary\">添加</button>\n        </div>\n        <table class=\"table table-bordered check_list\">\n          <thead>\n            <tr>\n              <th class=\"col-sm-1\">序号</th>\n              <th class=\"col-sm-1\">名称</th>\n              <th class=\"col-sm-1\">操作</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"val in diseases\">\n              <td>{{$index +1}}</td>\n              <td>{{val.name}}</td>\n              <td><span @click=\"delDisease(val.id, 'disease')\" class=\"doc_red\">删除</span></td>\n            </tr>\n          </tbody>\n        </table>\n      </div>\n      <div id=\"tab3\" class=\"tab-pane fade\">\n        <div class=\"col-sm-2\"></div>\n        <table class=\"table table-bordered check_list\">\n          <thead>\n            <tr>\n              <th class=\"col-sm-1\">序号</th>\n              <th class=\"col-sm-1\">出诊开始时间</th>\n              <th class=\"col-sm-1\">出诊结束时间</th>\n              <th class=\"col-sm-1\">出诊诊所</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"val in schedules\">\n              <td>{{$index +1}}</td>\n              <td>{{val.start_time}}</td>\n              <td>{{val.end_time}}</td>\n              <td>{{val.clinque.name}}</td>\n            </tr>\n          </tbody>\n        </table>\n      </div>\n      <div id=\"tab4\" class=\"tab-pane fade\">\n        <div class=\"col-sm-2\"></div>\n        <table class=\"table table-bordered check_list\">\n          <thead>\n            <tr>\n              <th class=\"col-sm-1\">序号</th>\n              <th class=\"col-sm-1\">出诊诊所</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"val in cliniques\">\n              <td>{{$index +1}}</td>\n              <td>{{val.name}}</td>\n            </tr>\n          </tbody>\n        </table>\n      </div>\n      <div id=\"tab5\" class=\"tab-pane fade\">\n        <div class=\"col-sm-2\"></div>\n        <table class=\"table table-bordered check_list\">\n          <thead>\n            <tr>\n              <th class=\"col-sm-1\">序号</th>\n              <th class=\"col-sm-1\">开始时间</th>\n              <th class=\"col-sm-1\">结束时间</th>\n              <th class=\"col-sm-1\">休息天数</th>\n              <th class=\"col-sm-1\">操作</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"val in leave\">\n              <td>{{$index +1}}</td>\n              <td>{{val.start_time}}</td>\n              <td>{{val.end_time}}</td>\n              <td>{{val.day}}</td>\n              <td>\n                <select v-model=\"val.status\" v-on:change=\"save(val.id,val.status)\" class=\"form-control\">\n                  <option value=\"0\">待审核</option>\n                  <option value=\"1\">审核通过</option>\n                  <option value=\"2\">审核不通过</option>\n                </select>\n              </td>\n            </tr>\n          </tbody>\n        </table>\n      </div>\n    </div>\n    <exportcheck :id.sync=\"id\"></exportcheck>\n    <addsection :id.sync=\"id\"></addsection>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-74288f85", module.exports)
  } else {
    hotAPI.update("_v-74288f85", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/add_section.vue":69,"./module/export_check.vue":79,"vue":7,"vue-hot-reload-api":3}],37:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getDate();
    },
    ready: function ready() {
        headNav(0);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            status: 3,
            source: '',
            name: '',
            mobile: ''
        };
    },

    events: {
        update: function update() {
            this.getDate();
        }
    },
    methods: {
        save: function save(id, status, type) {
            var _this = this;
            var index = layer.confirm('请确认您的操作？', {
                btn: ['确定执行', '取消']
            }, function (index, layero) {
                var params = {};
                if (type == 'status') {
                    params.status = status;
                } else if (type == 'web') {
                    params.web = status;
                } else if (type == 'clinic') {
                    params.clinic = status;
                }
                _this.$http({ url: 'doctor/update/' + id,
                    method: 'PUT',
                    params: params
                }).then(function (res) {
                    layer.msg(res.data.msg);
                    if (res.data.errcode == 200) {
                        this.getDate();
                    }
                });
                layer.close(index);
            }, function (index) {
                layer.close(index);
            });
        },
        getDate: function getDate() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'doctor/list', method: 'GET', params: { page: this.page, status: this.status, source: this.source, name: this.name, mobile: this.mobile } }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
            this.$router.go({ name: 'doctor', params: { id: this.page } });
        },
        exportData: function exportData() {
            this.searchs.name = this.name;
            this.searchs.type = this.type;
            this.$http({
                url: 'export/export',
                method: 'GET',
                params: { search: this.searchs }
            }).then(function (res) {
                if (res.data.status == 1) {
                    location.href = "/api/upload/download/" + res.data.name;
                }
            });
        },
        doc_detail: function doc_detail(id) {
            this.$router.go({ name: 'doc_detail', params: { id: id } });
        },
        doc_delete: function doc_delete(id) {
            var _this = this;
            var index = layer.confirm('您确定要删除吗？', {
                btn: ['确定执行', '取消']
            }, function (index, layero) {
                var params = {};
                _this.$http({ url: 'doctor/delete/',
                    method: 'post',
                    params: { id: id }
                }).then(function (res) {
                    layer.msg(res.data.msg);
                    if (res.data.errcode == 200) {
                        this.getDate();
                    }
                });
                layer.close(index);
            }, function (index) {
                layer.close(index);
            });
        },
        listen: function listen(data) {
            this.getDate(data);
            this.$router.go({ name: 'doctor', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">医生列表</div>\n      <!--.pull-righta.btn.btn-sm.btn-primary(@click=\"exportData()\") 导出管理\n      -->\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <select v-model=\"status\" @change=\"getDate(1)\" class=\"form-control\">\n            <option value=\"3\" selected=\"selected\">审核状态</option>\n            <option value=\"0\">待审核</option>\n            <option value=\"1\">审核通过</option>\n            <option value=\"2\">审核不通过</option>\n          </select>\n        </div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"source\" @change=\"getDate(1)\" class=\"form-control\">\n            <option value=\"\" selected=\"selected\">来源</option>\n            <option value=\"1\">注册</option>\n            <option value=\"0\">API</option>\n          </select>\n        </div>\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <input id=\"seaItem\" type=\"search\" v-model=\"name\" placeholder=\"输入医生姓名搜索\" class=\"form-control auto_inp\">\n            <div @click=\"getDate(1)\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"mobile\" placeholder=\"输入医生手机号搜索\" class=\"form-control auto_inp\">\n            <div @click=\"getDate(1)\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-1\">头像</th>\n          <th class=\"col-sm-1\">医生姓名</th>\n          <th class=\"col-sm-1\">手机号</th>\n          <th class=\"col-sm-1\">医师资格证</th>\n          <th class=\"col-sm-1\">网诊</th>\n          <th class=\"col-sm-1\">门诊</th>\n          <th class=\"col-sm-1\">来源</th>\n          <th class=\"col-sm-1\">审核</th>\n          <th style=\"width:15%\" class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,doc) in data\">\n          <td>{{page*10 +index-9}}</td>\n          <td><img v-bind:src=\"doc.photoSUrl\" class=\"doc_headimg\"></td>\n          <td>{{doc.name}}</td>\n          <td>{{doc.mobile}}</td>\n          <td v-if=\"doc.source !=2 &amp;&amp; doc.qualification_auth ==1\">未上传</td>\n          <td v-else=\"v-else\">上传</td>\n          <td>{{doc.net_chat}}</td>\n          <td>{{doc.is_clinic}}</td>\n          <td v-if=\"doc.source ==1\">注册</td>\n          <td v-else=\"v-else\">API</td>\n          <td>\n            <select v-model=\"doc.apply\" v-on:change=\"save(doc.id,doc.apply,'status')\" class=\"form-control\">\n              <option value=\"1\">审核通过</option>\n              <option value=\"0\">待审核</option>\n              <option value=\"2\">审核不通过</option>\n            </select>\n          </td>\n          <td><span @click=\"doc_detail(doc.id)\">详情</span>\n            <!--span(@click=\"doc_delete(doc.id)\") 删除--><span v-if=\"doc.web == 1\" @click=\"save(doc.id,0,'web')\" class=\"doc_red\">禁网诊</span><span v-else=\"v-else\" @click=\"save(doc.id,1,'web')\">开通网诊</span>\n            <template v-if=\"doc.source == 0\"><span v-if=\"doc.clinic == 1\" @click=\"save(doc.id,0,'clinic')\" class=\"doc_red\">禁门诊</span><span v-else=\"v-else\" @click=\"save(doc.id,1,'clinic')\">开通门诊</span></template>\n          </td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-4977366c", module.exports)
  } else {
    hotAPI.update("_v-4977366c", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],38:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getDate();
    },
    ready: function ready() {
        headNav(1);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            search: {
                clinic_type: '',
                pay_type: '',
                order_type: 'bespeak',
                user_name: '',
                order_sn: '',
                cur_total: 10
            }
        };
    },

    events: {
        update: function update() {
            this.getDate();
        }
    },
    methods: {
        getDate: function getDate() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'order/bespeak', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        exportData: function exportData() {
            var title = '预约订单管理';
            var head = ['订单编号', '患者', '就诊医师', '订单类型', '应收', '实收', '付款方式', '付款时间',
            //'退款金额',
            '状态', '备注'];
            var width = { 'A': 20, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 20, 'I': 10, 'J': 10, 'K': 20 };
            this.$http({
                url: 'exports/exports',
                method: 'post',
                params: { title: title, head: head, search: this.search, width: width, type: 'drug_manage', export: true }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getDate(data);
            this.$router.go({ name: 'drug_manage', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">预约订单管理</div>\n      <div class=\"pull-right\"><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <!--dt 筛选-->\n      <dd class=\"row\">\n        <div class=\"col-sm-1\"> 订单编号：</div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"text\" v-model=\"search.order_sn\" placeholder=\"\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-1\"> 患者：</div>\n        <div class=\"col-sm-1\">\n          <div class=\"input-group\">\n            <input type=\"text\" v-model=\"search.user_name\" placeholder=\"\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <!--.col-sm-1  医师：-->\n        <!--.col-sm-2\n        .input-group\n            input.form-control.auto_inp(type=\"text\" v-model=\"search.doc_name\" placeholder=\"\")\n        -->\n        <div class=\"col-sm-1\"> 订单类型：</div>\n        <div class=\"col-sm-1\">\n          <select v-model=\"search.clinic_type\" @change=\"getDate(1)\" class=\"form-control\">\n            <option value=\"\">全部</option>\n            <option value=\"2\">网诊</option>\n            <option value=\"1\">门诊</option>\n          </select>\n        </div>\n      </dd>\n    </dl>\n    <dl>\n      <dd class=\"row\">\n        <div class=\"col-sm-1\"> 付款时间：</div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"search.pay_time\" @change=\"getDate(1)\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-1\"> 付款方式：</div>\n        <div class=\"col-sm-1\">\n          <select v-model=\"search.pay_type\" @change=\"getDate(1)\" class=\"form-control\">\n            <option value=\"\">不限</option>\n            <option value=\"0\">未支付</option>\n            <option value=\"1\">微信支付</option>\n          </select>\n        </div>\n        <!--.col-sm-1  来源：-->\n        <!--.col-sm-1\n        select.form-control(v-model=\"search.source\",@change=\"getDate(1)\")\n            option(value=0) 不限\n            option(value=1) 门诊\n            option(value=2) 网诊\n        -->\n        <div class=\"col-sm-1\"></div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <select v-model=\"search.cur_total\" @change=\"getDate(1)\" class=\"form-control\">\n              <option value=\"10\" selected=\"selected\">10条每页</option>\n              <option value=\"20\">20条每页</option>\n              <option value=\"50\">50条每页</option>\n              <option value=\"100\">100条每页</option>\n            </select>\n            <div @click=\"getDate()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th style=\"width:15%;\" class=\"col-sm-1\">订单编号</th>\n          <th class=\"col-sm-1\">患者</th>\n          <th class=\"col-sm-1\">就诊医师</th>\n          <th class=\"col-sm-1\">订单类型</th>\n          <th class=\"col-sm-1\">应收</th>\n          <th class=\"col-sm-1\">实收</th>\n          <th class=\"col-sm-1\">付款方式</th>\n          <th style=\"width:12%;\" class=\"col-sm-1\">付款时间</th>\n          <!--th.col-sm-1 退款金额-->\n          <th class=\"col-sm-1\">状态</th>\n          <th class=\"col-sm-1\">备注</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,val) in data\">\n          <td> {{val.order_sn}}</td>\n          <td>{{val.user.realname}}</td>\n          <td>{{val.bespeak.doctor.name}}</td>\n          <td> {{val.order_type}}</td>\n          <td>{{val.payable_amount}}</td>\n          <td> {{val.pay_amount}}</td>\n          <td> {{val.pay_type}}</td>\n          <td> {{val.pay_time}}</td>\n          <!--td  {{val.refund_amount}}-->\n          <td> {{val.status}}</td>\n          <td>{{val.desc}}</td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-0ba1b667", module.exports)
  } else {
    hotAPI.update("_v-0ba1b667", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],39:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getDate();
    },
    ready: function ready() {
        headNav(1);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            search: {
                order_type: 'recipe',
                pay_type: '',
                source: 0,
                cur_total: 10
            }
        };
    },

    events: {
        update: function update() {
            this.getDate();
        }
    },
    methods: {
        getDate: function getDate() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'order/prescription', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        exportData: function exportData() {
            var title = '药费订单';
            var head = ['订单编号', '患者', '就诊医师', '订单类型', '应收', '实收', '付款方式', '付款时间', '退款金额', '状态', '备注'];
            var width = { 'A': 20, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 20, 'I': 10, 'J': 10, 'K': 20 };
            this.$http({
                url: 'exports/exports',
                method: 'post',
                params: { title: title, head: head, search: this.search, type: 'drug_medicinal', width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getDate(data);
            this.$router.go({ name: 'drug_medicinal', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">药品订单管理1</div>\n      <div class=\"pull-right\"><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <!--dt 筛选-->\n      <dd class=\"row\">\n        <div class=\"col-sm-1\"> 订单编号</div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"text\" v-model=\"search.order_sn\" placeholder=\"\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-1\"> 患者</div>\n        <div class=\"col-sm-1\">\n          <div class=\"input-group\">\n            <input type=\"text\" v-model=\"search.user_name\" placeholder=\"\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <!--.col-sm-1  医师-->\n        <!--.col-sm-2\n        .input-group\n            input.form-control.auto_inp(type=\"text\" v-model=\"search.doc_name\" placeholder=\"\")\n        -->\n      </dd>\n    </dl>\n    <dl>\n      <dd class=\"row\">\n        <div class=\"col-sm-1\"> 付款方式</div>\n        <div class=\"col-sm-1\">\n          <select v-model=\"search.pay_type\" @change=\"getDate(1)\" class=\"form-control\">\n            <option value=\"\">不限</option>\n            <option value=\"0\">未支付</option>\n            <option value=\"1\">微信支付</option>\n          </select>\n        </div>\n        <div class=\"col-sm-1\"> 付款时间</div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"date\" v-model=\"search.pay_time\" @change=\"getDate(1)\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <!--.col-sm-1  来源-->\n        <!--.col-sm-1\n        select.form-control(v-model=\"search.source\")\n            option(value=0) 不限\n            option(value=1) 网诊\n            option(value=2) 门诊\n        -->\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <select v-model=\"search.cur_total\" class=\"form-control\">\n              <option value=\"10\">10条每页</option>\n              <option value=\"20\">20条每页</option>\n              <option value=\"50\">50条每页</option>\n              <option value=\"100\">100条每页</option>\n            </select>\n            <div @click=\"getDate()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th style=\"width:15%;\" class=\"col-sm-1\">订单编号</th>\n          <th class=\"col-sm-1\">患者</th>\n          <th class=\"col-sm-1\">就诊医师</th>\n          <th class=\"col-sm-1\">收费项目</th>\n          <th class=\"col-sm-1\">应收</th>\n          <th class=\"col-sm-1\">实收</th>\n          <th class=\"col-sm-1\">付款方式</th>\n          <th style=\"width:12%;\" class=\"col-sm-1\">付款时间</th>\n          <th class=\"col-sm-1\">退款金额</th>\n          <th class=\"col-sm-1\">状态</th>\n          <th class=\"col-sm-1\">备注</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,val) in data\">\n          <td> {{val.order_sn}}</td>\n          <td>{{val.clinic.appuser}}</td>\n          <td>{{val.clinic.doctor}}</td>\n          <td> {{val.order_type}}</td>\n          <td>{{val.payable_amount}}</td>\n          <td> {{val.pay_amount}}</td>\n          <td> {{val.pay_type}}</td>\n          <td> {{val.pay_time}}</td>\n          <td> {{val.refund_amount}}</td>\n          <td> {{val.status}}</td>\n          <td>{{val.note}}</td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-293b1138", module.exports)
  } else {
    hotAPI.update("_v-293b1138", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],40:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getDate();
    },
    ready: function ready() {
        headNav(1);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            search: {
                card_type: 0,
                cur_total: 10
            }
        };
    },

    events: {
        update: function update() {
            this.getDate();
        }
    },
    methods: {
        getDate: function getDate() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({
                url: 'drugs_pay',
                method: 'GET',
                params: { page: this.page, search: this.search }
            }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        exportData: function exportData() {
            var title = '充值订单';
            var head = ['订单编号', '卡号', '订单类型', '所属人', '充值金额', '实付金额', '充值方式', '充值时间', '退款金额', '状态'];
            var width = {
                'A': 15,
                'B': 15,
                'C': 10,
                'D': 10,
                'E': 10,
                'F': 20,
                'G': 20,
                'I': 20
            };
            this.$http({
                url: 'count/exports',
                method: 'GET',
                params: { title: title, head: head, data: this.data, width: width }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        listen: function listen(data) {
            this.getDate(data);
            this.$router.go({ name: 'drug_pay', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">充值订单管理</div>\n      <div class=\"pull-right\"><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dd class=\"row\">\n        <div class=\"col-sm-1\"> 订单编号</div>\n        <div class=\"col-sm-1\">\n          <div class=\"input-group\">\n            <input type=\"text\" v-model=\"search.order_sn\" placeholder=\"\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-1\"> 卡类型</div>\n        <div class=\"col-sm-1\">\n          <select v-model=\"search.card_type\" class=\"form-control\">\n            <option value=\"0\">不限</option>\n            <option value=\"2\">家庭卡</option>\n            <option value=\"1\">VIP卡</option>\n          </select>\n        </div>\n        <div class=\"col-sm-1\"> 卡号</div>\n        <div class=\"col-sm-1\">\n          <div class=\"input-group\">\n            <input type=\"text\" v-model=\"search.number\" placeholder=\"\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-1\"> 持卡人</div>\n        <div class=\"col-sm-1\">\n          <div class=\"input-group\">\n            <input type=\"text\" v-model=\"search.cardholder\" placeholder=\"\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <select v-model=\"search.cur_total\" class=\"form-control\">\n              <option value=\"10\">10条每页</option>\n              <option value=\"20\">20条每页</option>\n              <option value=\"50\">50条每页</option>\n              <option value=\"100\">100条每页</option>\n            </select>\n            <div @click=\"getDate()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th style=\"width:15%;\" class=\"col-sm-1\">订单编号</th>\n          <th class=\"col-sm-1\">卡号</th>\n          <th class=\"col-sm-1\">订单类型</th>\n          <th class=\"col-sm-1\">所属人</th>\n          <th class=\"col-sm-1\">充值金额</th>\n          <th class=\"col-sm-1\">实付金额</th>\n          <th class=\"col-sm-1\">充值方式</th>\n          <th style=\"width:12%;\" class=\"col-sm-1\">充值时间</th>\n          <th class=\"col-sm-1\">退款金额</th>\n          <th class=\"col-sm-1\">状态</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,val) in data\">\n          <td> {{val.order_sn}}</td>\n          <td>{{val.cardNo}}</td>\n          <td>{{val.opration}}</td>\n          <td>{{val.realname}}</td>\n          <td> {{val.goods_amount}}</td>\n          <td> {{val.amount}}</td>\n          <td>{{val.pay_type}}</td>\n          <td>{{val.pay_time}}</td>\n          <td>{{val.refund_amount}}</td>\n          <td> {{val.pay_status}}</td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-c7d832d4", module.exports)
  } else {
    hotAPI.update("_v-c7d832d4", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],41:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    ready: function ready() {
        headNav(4);
    },
    created: function created() {
        this.getData();
    },
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        edit: function edit(id) {
            this.$router.go({ name: 'exam_save', params: { id: id } });
        },
        getData: function getData() {
            this.$http.get('exam').then(function (res) {
                this.$set('data', res.data.data);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">系统问诊单</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"box_css\">\n    <!--a.btn.btn-primary.btn-sm(onclick=\"itemPop(#{i},'addtest')\")-->\n    <!--    span 添加问题-->\n    <div class=\"new_item\">\n      <div class=\"user_table_box table-responsive\">\n        <table class=\"table\">\n          <thead>\n            <tr>\n              <th>序号</th>\n              <th>名称</th>\n              <th>时间</th>\n              <th>操作</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"dd in data\">\n              <td>{{$index +1}}</td>\n              <td>{{dd.title}}</td>\n              <td>{{dd.created_at}}</td>\n              <td><span @click=\"edit(dd.id)\">编辑</span></td>\n            </tr>\n          </tbody>\n        </table>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-dcb232a8", module.exports)
  } else {
    hotAPI.update("_v-dcb232a8", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],42:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _addtest = require('./module/addtest.vue');

var _addtest2 = _interopRequireDefault(_addtest);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        addtest: _addtest2.default
    },
    ready: function ready() {
        headNav(4);
    },
    data: function data() {
        return {
            data: {},
            id: 0,
            sid: 0,
            ddd: {},
            test: []
        };
    },

    events: {
        questionsave: function questionsave() {
            this.getTest(this.id);
        }
    },
    created: function created() {
        this.id = this.$route.params.id;
        this.getTest(this.id);
    },

    methods: {
        //ss
        saveTest: function saveTest(val) {
            var _this = this;
            this.ddd = val;
            $('#addtest').modal('show');
            setTimeout(function () {
                $(".clone").delegate('.icon-minus', 'click', function () {
                    $(this).parents('.form-group').remove();
                });

                $(".clone").delegate('.icon-plus', 'click', function () {
                    $(this).parents('.form-group').clone(false).appendTo($(this).parents('.clone'));
                });
            }, 1000);
            //ssss
        },
        del: function del(id) {
            var vue = this;
            layer.confirm('您确定要删除吗？', {
                btn: ['确定', '取消']
            }, function () {
                vue.$http.delete('exam/' + id).then(function (res) {
                    layer.msg(res.data.msg);
                    if (res.data.status == 1) {
                        vue.$dispatch('questionsave');
                    }
                });
            }, function () {});
        },
        getTest: function getTest(id) {
            this.$http.get('exam/' + id).then(function (res) {
                this.$set('data', res.data.data);
                this.$set('id', res.data.data.id);
                this.$set('test', res.data.data.options);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"box_css\"><a onclick=\"itemPop2(undefined,'addtestt');\" class=\"btn btn-primary btn-sm\"><span>添加问题</span>\n      <!--ssssss--></a>\n    <div class=\"new_item\">\n      <form role=\"form\" class=\"form-horizontal\">\n        <div class=\"form-group\">\n          <label for=\"\" class=\"col-sm-2 control-label\"><span class=\"notice-w\">*</span>问卷标题：</label>\n          <div class=\"col-sm-3\">\n            <input type=\"text\" name=\"title\" v-model=\"data.title\" class=\"form-control\">\n          </div>\n        </div>\n        <div v-for=\"ddd in test\" class=\"form-group sel_ti\">\n          <div class=\"float_box clearfix\">\n            <label v-if=\"ddd.type =='radio'\" style=\"float:left;\" class=\"col-sm-2 control-label\">单选题 ：</label>\n            <label v-if=\"ddd.type =='checkbox'\" style=\"float:left;\" class=\"col-sm-2 control-label\">复选题 ：</label>\n            <label v-if=\"ddd.type =='text'\" style=\"float:left;\" class=\"col-sm-2 control-label\">问答题 ：</label>\n            <label v-if=\"ddd.type =='photo'\" style=\"float:left;\" class=\"col-sm-2 control-label\">图片题 ：</label>\n            <div style=\"float:left;line-height:35px;\" class=\"itemss\"><span>{{ddd.title}}</span></div><i @click=\"del(ddd.id)\" style=\"float:left;line-height:35px;color:red;\" class=\"icon-bin\"></i><i @click=\"saveTest(ddd)\" style=\"margin-left:10px;float:left;line-height:35px;\" class=\"icon-pencil\"></i>\n          </div>\n          <template v-if=\"ddd.type == 'checkbox' || ddd.type =='radio'\" track-by=\"$index\">\n            <p v-for=\"answer in ddd.option\" style=\"padding-left:255px\" class=\"result\"><span> {{answer.val}}</span></p>\n          </template>\n          <template v-if=\"ddd.type == 'photo'\">\n            <div style=\"padding-left:255px;\" class=\"f_box clearfix\">\n              <p v-for=\"val in ddd.option\" style=\"float:left;margin-right:10px;\" class=\"result\"><img v-bind:src=\"val\" style=\"width:160px\"></p>\n            </div>\n          </template>\n        </div>\n      </form>\n    </div>\n  </div>\n  <addtest :ddd.sync=\"ddd\"></addtest>\n  <pop-addtest :id.sync=\"id\"></pop-addtest>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-505bd760", module.exports)
  } else {
    hotAPI.update("_v-505bd760", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/addtest.vue":70,"vue":7,"vue-hot-reload-api":3}],43:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _clinic_detail = require("./module/clinic_detail.vue");

var _clinic_detail2 = _interopRequireDefault(_clinic_detail);

var _save_appuser = require("./module/save_appuser.vue");

var _save_appuser2 = _interopRequireDefault(_save_appuser);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        clinic_detail: _clinic_detail2.default,
        save_appuser: _save_appuser2.default
    },
    created: function created() {
        this.user_id = this.$route.params.id;
    },
    data: function data() {
        return {
            user_id: 0,
            user: {},
            item: {},
            clinic_id: 0
        };
    },

    events: {
        refreshList: function refreshList() {
            this.getUserDetail(this.user_id);
        }
    },
    methods: {
        save: function save() {
            this.$set('item', this.user);
            this.$set('item.save_type', 'family');
            $("#save_appuser").modal("show");
        },
        clinic: function clinic(clinic_id) {
            this.$set('clinic_id', clinic_id);
            $("#clinic_detail").modal("show");
        },
        getUserDetail: function getUserDetail(id) {
            if (id > 0) {
                this.$http.get('appuser/detail/' + id + '/family').then(function (res) {
                    this.$set('user', res.data.data);
                });
            }
        }
    },
    watch: {
        user_id: function user_id(newValue) {
            this.getUserDetail(newValue);
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">患者管理 &gt; 患者详情\n      <label></label>\n    </div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <button @click=\"save()\" class=\"btn btn-primary\"> 修改</button>\n    <!--sssss-->\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">头像：</label>\n        <div class=\"col-sm-2\">\n          <div class=\"sel_box\">\n            <div v-bind:style=\"{backgroundImage:'url(' +user.headimgurl+')' }\" class=\"img-face-t\"></div>\n          </div>\n        </div>\n        <div class=\"col-sm-3\">\n          <p>患者昵称： {{user.nickname}}</p>\n          <p>手机号： {{user.mobile}}</p>\n          <p>性别： {{user.sex ? user.sex : '暂无'}}</p>\n          <p>身份证号： {{user.pincode ? user.pincode : '暂无'}}</p>\n          <p>国籍： {{user.county ? user.county : '暂无'}}</p>\n        </div>\n        <div class=\"col-sm-3\">\n          <p>真实姓名： {{user.realname}}</p>\n          <p>年龄： {{user.age ? user.age : '暂无'}}</p>\n          <p>身高： {{user.height ? user.height : '暂无'}}</p>\n          <p>体重： {{user.weight ? user.weight : '暂无'}}</p>\n          <p>常居住地： {{user.province ? user.province : '暂无'}}{{user.city}}{{user.area}}</p>\n        </div>\n      </div>\n    </form>\n    <ul class=\"nav nav-tabs\">\n      <li class=\"active\"><a href=\"#tab4\" role=\"tab\" data-toggle=\"tab\">关联人</a></li>\n      <li><a href=\"#tab5\" role=\"tab\" data-toggle=\"tab\">就诊记录</a></li>\n      <!--lia(href=\"#tab2\" role=\"tab\" data-toggle=\"tab\") 订单记录\n      -->\n    </ul>\n    <div class=\"tab-content\">\n      <div id=\"tab4\" class=\"tab-pane fade in active\">\n        <form role=\"form\" class=\"form-horizontal user_table_box table-responsive\">\n          <table class=\"table table-bordered check_lis\">\n            <thead>\n              <tr>\n                <th class=\"col-sm-1\">关联人</th>\n                <th class=\"col-sm-1\">是否为VIP/家庭</th>\n              </tr>\n            </thead>\n            <tbody>\n              <tr v-for=\"val in user.users\">\n                <td>{{val.realname}}</td>\n                <td>{{val.pivot.type ==1 ? '会员卡用户' : '普通家庭成员'}}</td>\n              </tr>\n            </tbody>\n          </table>\n        </form>\n      </div>\n      <div id=\"tab5\" class=\"tab-pane fade\">\n        <form role=\"form\" class=\"form-horizontal user_table_box table-responsive\">\n          <table class=\"table table-bordered check_list\">\n            <thead>\n              <tr>\n                <th class=\"col-sm-1\">下单人</th>\n                <th class=\"col-sm-1\">医师</th>\n                <th class=\"col-sm-1\">就诊方式</th>\n                <th class=\"col-sm-1\">类型</th>\n                <th style=\"width:12%\" class=\"col-sm-1\">就诊时间</th>\n                <th class=\"col-sm-1\">诊断（处方名称）</th>\n                <th class=\"col-sm-1\">处方</th>\n                <th class=\"col-sm-1\">剂量</th>\n                <th class=\"col-sm-1\">操作</th>\n              </tr>\n            </thead>\n            <tbody>\n              <tr v-for=\"val in user.clinic\">\n                <td>{{val.userName}}</td>\n                <td>{{val.doctorsName}}</td>\n                <td>{{val.recipe_status ==1 ? '门诊' : '网诊'}}</td>\n                <td>{{val.is_first == 1 ? '复诊' : '初诊'}}</td>\n                <td>{{val.created_at}}</td>\n                <template v-if=\"val.has_one_recipe\">\n                  <td>{{val.has_one_recipe.disease}}</td>\n                  <td><span v-for=\"r in val.has_one_recipe.recipe\">{{r.name}} {{r.dosage}} {{r.other}}</span></td>\n                  <td>{{val.has_one_recipe.recipe_head.sum}}</td>\n                </template>\n                <template v-else=\"v-else\">\n                  <td></td>\n                  <td></td>\n                  <td></td>\n                </template>\n                <td><span @click=\"clinic(val.id)\">详情</span></td>\n              </tr>\n            </tbody>\n          </table>\n        </form>\n      </div>\n    </div>\n  </div>\n  <clinic_detail :clinic_id.sync=\"clinic_id\"></clinic_detail>\n  <save_appuser :item.sync=\"item\"></save_appuser>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-31ce5e4f", module.exports)
  } else {
    hotAPI.update("_v-31ce5e4f", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/clinic_detail.vue":73,"./module/save_appuser.vue":87,"vue":7,"vue-hot-reload-api":3}],44:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _information = require("./module/information.vue");

var _information2 = _interopRequireDefault(_information);

var _area = require("./module/area.vue");

var _area2 = _interopRequireDefault(_area);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        information: _information2.default,
        area: _area2.default
    },
    created: function created() {
        this.getData();
        this.getArea(1);
    },
    ready: function ready() {
        headNav(5);
    },
    data: function data() {
        return {
            sub: {},
            com: {},
            area: {},
            area_val: {},
            cur: 0,
            all: 0,
            val: {}
        };
    },

    events: {
        refreshList: function refreshList() {
            this.getData();
        }
    },
    methods: {
        edit_area: function edit_area(val) {
            this.area_val = val;
            $('#area').modal('show');
        },
        getArea: function getArea(page) {
            this.$http({ url: 'area', params: { page: page } }).then(function (res) {
                this.$set('area', res.data.data.data);
                this.$set('cur', res.data.data.current_page);
                this.$set('all', res.data.data.last_page);
            });
        },
        edit_com: function edit_com(val) {
            this.val = val;
            $('#information').modal("show");
        },
        getData: function getData() {
            this.$http({ url: 'config', method: 'get' }).then(function (res) {
                this.$set('com', res.data.data[0]);
                this.$set('sub', res.data.data[1]);
            });
        },
        listen: function listen(data) {
            this.getArea(data);
            //this.$router.go({name: 'comment_admin', params: {id: data}});
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">数据管理</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <label>评论</label>\n  <table class=\"table table-bordered\">\n    <thead>\n      <tr>\n        <th>痊愈</th>\n        <th>明显好转</th>\n        <th>好转</th>\n        <th>没变化</th>\n        <th>操作</th>\n      </tr>\n    </thead>\n    <tbody>\n      <tr>\n        <td>{{com.info.N1}}</td>\n        <td>{{com.info.N2}}</td>\n        <td>{{com.info.N3}}</td>\n        <td>{{com.info.N4}}</td>\n        <td><span v-on:click=\"edit_com(com)\">修改</span></td>\n      </tr>\n    </tbody>\n  </table>\n  <label>预约</label>\n  <table class=\"table table-bordered\">\n    <thead>\n      <tr>\n        <th>免费取消次数</th>\n        <th>免费取消预约时间N(小时)</th>\n        <th>24h &lt; 实际时间 &lt; N</th>\n        <th>12h &lt; 实际时间 &lt; 24</th>\n        <th>12h &gt; 实际时间</th>\n        <th>实际时间 &gt; 预约时间</th>\n        <th>操作</th>\n      </tr>\n    </thead>\n    <tbody>\n      <tr>\n        <td>{{sub.info.M}}</td>\n        <td>{{sub.info.N}}</td>\n        <td>{{sub.info.N1}}</td>\n        <td>{{sub.info.N2}}</td>\n        <td>{{sub.info.N3}}</td>\n        <td>{{sub.info.N4}}</td>\n        <td><span v-on:click=\"edit_com(sub)\">修改</span></td>\n      </tr>\n    </tbody>\n  </table>\n  <label>邮费</label>\n  <table class=\"table table-bordered\">\n    <thead>\n      <tr>\n        <th>地区</th>\n        <th>首价(元)</th>\n        <th>首重(Kg)</th>\n        <th>续重(元/Kg)</th>\n        <th>操作</th>\n      </tr>\n    </thead>\n    <tbody>\n      <tr v-for=\"val in area\">\n        <td>{{val.name}}</td>\n        <td>{{val.initiate_price}}</td>\n        <td>{{val.initiate_weight}}</td>\n        <td>{{val.continue_price}}</td>\n        <td><span v-on:click=\"edit_area(val)\">修改</span></td>\n      </tr>\n    </tbody>\n  </table>\n  <nav>\n    <ul class=\"pagination\">\n      <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n    </ul>\n  </nav>\n  <information :val.sync=\"val\"></information>\n  <area :area_val.sync=\"area_val\">\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-8a299ea2", module.exports)
  } else {
    hotAPI.update("_v-8a299ea2", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/area.vue":71,"./module/information.vue":81,"vue":7,"vue-hot-reload-api":3}],45:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getQuestions();
    },
    data: function data() {
        return {
            questions: {},
            detail: {
                'type': 0
            },
            checkedIds: []
        };
    },

    methods: {
        getQuestions: function getQuestions() {
            this.$http.get('question/all').then(function (res) {
                this.$set('questions', res.data.questions);
            });
        },
        goback: function goback() {
            this.$router.go('/lnquiry_list');
        },
        save: function save() {
            if (!this.detail.title) {
                layer.msg('标题不能为空');return false;
            }
            if (!this.detail.attention) {
                layer.msg('注意事项不能为空');return false;
            }
            if (!this.detail.explain) {
                layer.msg('说明不能为空');return false;
            }
            if (!this.checkedIds) {
                layer.msg('您还没有选择问题');
                return false;
            }
            //判断必选题选择了吗
            var firstpos = this.checkedIds.indexOf('1');
            var twopos = this.checkedIds.indexOf('33');
            console.log(this.checkedIds);
            console.log(twopos);
            console.log(firstpos);
            if (firstpos == -1 || twopos == -1) {
                layer.msg('备注或必填资料题,您还没有选择,请选择!');
                return false;
            }
            var obj = {};
            var _this = this;
            obj.detail = this.detail;
            obj.detail.checkedIds = this.checkedIds;
            this.$http.post('lnquiry/store/', obj).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg('添加成功');
                    _this.$router.go('/lnquiry_list/1');
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">添加问诊单</div>\n    </div>\n  </div>\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>类型：</span></label>\n        <div class=\"col-sm-1\">\n          <select v-model=\"detail.type\" class=\"form-control\">\n            <option value=\"0\">成人男</option>\n            <option value=\"1\">成人女</option>\n            <option value=\"2\">儿童</option>\n          </select>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>标题：</span></label>\n        <div class=\"col-sm-5\">\n          <input type=\"text\" name=\"title\" placeholder=\"请输入问诊单标题\" v-model=\"detail.title\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>注意事项：</span></label>\n        <div class=\"col-sm-5\">\n          <input type=\"text\" name=\"attention\" placeholder=\"请输入问诊单注意事项\" v-model=\"detail.attention\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>说明：</span></label>\n        <div class=\"col-sm-5\">\n          <textarea name=\"explain\" placeholder=\"请输入问诊单说明\" v-model=\"detail.explain\" class=\"form-control\"></textarea>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>请勾选问诊单问题：</span></label>\n      </div>\n      <div v-for=\"q in questions\" class=\"form-group\">\n        <div class=\"col-sm-6\">\n          <input type=\"checkbox\" value=\"{{q.id}}\" v-model=\"checkedIds\"><span>{{q.type}} {{q.question}}</span>\n        </div>\n        <div v-if=\"q.id == 1 || q.id == 33\" style=\"color:red\" class=\"col-sm-1\">＊</div>\n      </div>\n      <div class=\"form-group\">\n        <div style=\"color:red\" class=\"col-sm-2\">说明: 标记为 ＊ 的问题为必选项</div>\n      </div>\n      <div class=\"form-group btn_box\">\n        <button type=\"button\" @click=\"goback()\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" @click=\"save()\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </form>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-661b29ef", module.exports)
  } else {
    hotAPI.update("_v-661b29ef", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],46:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.id = this.$route.params.id; //324
        this.getInfo(this.id);
    },
    data: function data() {
        return {
            lnquiry: {},
            list: {},
            id: 0
        };
    },

    events: {
        lndetail: function lndetail() {
            this.getInfo(this.id);
        }
    },
    methods: {
        getInfo: function getInfo(id) {
            this.$http.get('lnquiry/' + id).then(function (res) {
                this.$set('lnquiry', res.data.lnquiry);
                this.$set('list', res.data.lnquiry.list);
            });
        },
        goback: function goback() {
            this.$router.go('/lnquiry_list');
        },
        close: function close(id) {
            var vue = this;
            var obj = {};
            obj.qid = id;
            obj.lid = this.id;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消']
            }, function () {
                vue.$http.post('lnquiry/delquestion/', obj).then(function (res) {
                    if (res.data.status) {
                        layer.msg(res.data.msg);
                    }
                    vue.$dispatch('lndetail');
                });
            }, function () {});
        },
        order: function order(id) {
            if (!id) {
                layer.msg('系统错误，请按F5刷新重试');
            }
            var obj = {};
            obj.qid = id;
            obj.lid = this.id;
            obj.order = $('input[name="' + id + 'order"]').val();
            this.$http.post('lnquiry/order/', obj).then(function (res) {
                if (res.data.status) {
                    layer.msg(res.data.msg);
                }
                vue.$dispatch('lndetail');
            });
        },
        edit: function edit(id) {
            this.$router.go({ name: 'question_answer', params: { id: id } }); //2
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">问诊单详情</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>标题：</span></label>\n        <div class=\"col-sm-5\"><span onclick=\"textEdit(this,'{{lnquiry.id}}','title')\">{{lnquiry.title}}</span></div>\n        <div style=\"color:red\" class=\"col-sm-1\">＊</div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>注意事项：</span></label>\n        <div class=\"col-sm-5\"><span onclick=\"txtEdit(this,'{{lnquiry.id}}','attention')\">{{lnquiry.attention}}</span></div>\n        <div style=\"color:red\" class=\"col-sm-1\">＊</div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>说明：</span></label>\n        <div class=\"col-sm-5\"><span onclick=\"txtEdit(this,'{{lnquiry.id}}','explain')\">{{lnquiry.explain}}</span></div>\n        <div style=\"color:red\" class=\"col-sm-1\">＊</div>\n      </div>\n      <div v-for=\"(index,l) in list\" class=\"form-group\">\n        <div @click=\"close(l.id)\" style=\"color:red;opacity:1;\" class=\"close\">×</div>\n        <div @click=\"edit(l.id)\" style=\"color:#402d65;opacity:1;\" class=\"close\">✎</div>\n        <label class=\"col-sm-1 control-label\">问题排序</label>\n        <div class=\"col-sm-1\"><span onclick=\"lnEdit(this,'{{l.id}}','order','{{lnquiry.id}}')\">{{l.order}}</span></div>\n        <div style=\"color:red\" class=\"col-sm-1\">＊</div>\n        <label for=\"\" class=\"col-sm-1 control-label\"></label>\n        <div class=\"col-sm-3\"><span>{{l.question}} (跳转序号{{l.id}})</span></div>\n        <div class=\"form-group\">\n          <label class=\"col-sm-3 control-label\"></label>\n          <div v-for=\"(index,a) in l.answers\" class=\"col-sm-3\"><span v-if=\"a.hrefid\">跳转序号 ==&gt;</span><span v-if=\"a.hrefid\" onclick=\"textEdit(this,'{{a.id}}','hrefid')\">{{a.hrefid}} ＊</span><span v-if=\"a.answer\">答案 {{index+1}} : {{a.answer}}</span></div>\n        </div>\n      </div>\n      <div class=\"form-group\"><span style=\"color:red\">* 点击标题、注意事项、说明、问题排序即可直接修改</span></div>\n    </form>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-d53da11a", module.exports)
  } else {
    hotAPI.update("_v-d53da11a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],47:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    ready: function ready() {
        headNav(3);
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getLnquiries();
    },
    data: function data() {
        return {
            lnquiries: {},
            cur: 0,
            all: 0,
            id: 0
        };
    },

    events: {
        refreshln: function refreshln() {
            this.getLnquiries(this.cur);
        }
    },
    methods: {
        getLnquiries: function getLnquiries() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'lnquiry/index', method: 'GET', params: { page: this.page } }).then(function (res) {
                this.$set('lnquiries', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        checkDetail: function checkDetail(id) {
            this.$router.go({ name: 'lnquiry_detail', params: { id: id } });
        },
        del: function del(id) {
            var vue = this;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消']
            }, function () {
                vue.$http.delete('lnquiry/' + id).then(function (res) {
                    if (res.data.status) {
                        layer.msg(res.data.msg);
                        vue.$dispatch('refreshln');
                    }
                });
            }, function () {});
        },
        listen: function listen(data) {
            this.getLnquiries(data);
            this.$router.go({ name: 'lnquiry_list', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">问诊单列表</div>\n      <div class=\"pull-right\"><a v-link=\"{ path: '/lnquiry_add' }\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加问诊单</span></a></div>\n    </div>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-2\">类型</th>\n          <th class=\"col-sm-2\">创建时间</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"ln in lnquiries\">\n          <td>{{ln.i}}</td>\n          <td>{{ln.type}}</td>\n          <td>{{ln.created_at}}</td>\n          <td><span @click=\"checkDetail(ln.id)\">查看问诊单</span><span @click=\"del(ln.id)\" style=\"color:red\">删除</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-0c6ed880", module.exports)
  } else {
    hotAPI.update("_v-0c6ed880", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],48:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _medicinal_type = require('./module/medicinal_type.vue');

var _medicinal_type2 = _interopRequireDefault(_medicinal_type);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        medicinal_type: _medicinal_type2.default
    },
    data: function data() {
        //页面用到的数据
        return {
            data: {},
            val: {},
            name: '',
            cur: '',
            total: '',
            all: ''
        };
    },
    created: function created() {
        headNav(4);
        this.getData();
    },

    events: {
        userupdate: function userupdate() {
            this.getData();
        }
    },
    methods: {
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'medicine/index', method: 'GET', params: { page: this.page, name: this.name } }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        deletes: function deletes(id) {
            this.$http({ url: 'medicine/del/' + id, method: 'delete' }).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.status) {
                    this.getData();
                }
            });
        },
        save: function save(val) {
            this.$set('val', val);
            $("#medicinal_type").modal("show");
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'medicinal_type', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">中药剂型管理</div>\n    </div>\n  </div>\n  <div class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"name\" placeholder=\"输入药品名称搜索\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getData(1)\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th>序号</th>\n          <th>名称</th>\n          <th>单位</th>\n          <th>价格（元）</th>\n          <th>类型</th>\n          <!--th 操作-->\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{$index+1}}</td>\n          <td>{{val.name}}</td>\n          <td>{{val.unit}}</td>\n          <td>{{val.amount}}</td>\n          <td>{{val.type}}</td>\n          <!--td-->\n          <!--    span(@click=\"save(val)\") 修改-->\n          <!--    span(@click=\"deletes(val.id)\",style=\"color:red;\") 删除-->\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\">\n    <medicinal_type :val.sync=\"val\"></medicinal_type>\n  </paginate>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-2a781f30", module.exports)
  } else {
    hotAPI.update("_v-2a781f30", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/medicinal_type.vue":82,"vue":7,"vue-hot-reload-api":3}],49:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    components: {},
    data: function data() {
        return {
            data: {},
            val: {},
            id: 1,
            doctor_name: '',
            user_name: ''
        };
    },
    created: function created() {
        console.log(1111111111);
        this.id = this.$route.query.id;
        this.doctor_name = this.$route.query.doctor_name;
        this.user_name = this.$route.query.user_name;
        this.getData(this.id);
    },
    ready: function ready() {
        headNav(4);
    },

    filters: {
        msgType: function msgType(val) {
            if (val == 1) {
                return '患者';
            } else if (val == 2) {
                return '医生';
            } else {
                return '系统';
            }
        }
    },
    methods: {
        getData: function getData(id) {
            this.$http({ url: 'message/getMessageDetail/' + id, method: 'GET' }).then(function (res) {
                console.log(2222222222);
                console.log(res);
                this.$set('data', res.data.data);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">聊天详情：{{doctor_name}}医生 — {{user_name}}患者</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">名称</th>\n          <th style=\"width:50%\" class=\"col-sm-2\">发送内容</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index, val) in data\">\n          <td>{{ val.type | msgType }}</td>\n          <td>{{ val.content.text }}</td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-aa12aa54", module.exports)
  } else {
    hotAPI.update("_v-aa12aa54", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],50:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    components: {},
    data: function data() {
        //页面用到的数据
        return {
            cur: 0,
            all: 0,
            total: 0,
            page: 1,
            data: {},
            val: {
                content: {}
            },
            name: ''
        };
    },
    created: function created() {
        //实例创建后调用
        this.page = this.$route.params.id;
        this.getData(1);
    },

    events: {
        userupdate: function userupdate() {
            this.getData();
        }
    },
    methods: {
        getData: function getData(page) {
            console.log('****');
            this.$http({ url: 'message/getMessage', method: 'GET', params: { page: page, name: this.name } }).then(function (res) {
                console.log('888888');
                console.log(res);
                this.$set('data', res.body.data.data);
                var data = res.data.data;
                this.$set('cur', data.current_page);
                this.$set('all', data.last_page);
                this.$set('total', data.total);
            });
        },
        detail: function detail(val) {
            //this.$set('val', val);
            //$("#clinique").modal("show");
            var id = val.id;
            this.$router.push({
                path: '/message',
                query: {
                    id: id
                }
            });
        },
        listen: function listen(data) {
            this.getData(data);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">聊天管理</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"item_list\">\n    <div class=\"list\">\n      <div class=\"search_box\">\n        <dl>\n          <dt>筛选</dt>\n          <dd class=\"row\">\n            <div class=\"col-sm-3\">\n              <div class=\"input-group\">\n                <input type=\"search\" v-model=\"name\" placeholder=\"输入医生姓名搜索\" class=\"form-control auto_inp\">\n                <div v-on:click=\"getData(1)\" class=\"input-group-btn\">\n                  <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n          </dd>\n        </dl>\n      </div>\n      <div class=\"user_table_box table-responsive\">\n        <table class=\"table table-bordered\">\n          <thead>\n            <tr>\n              <th>序号</th>\n              <th>医生姓名</th>\n              <th>患者昵称</th>\n              <th>患者姓名</th>\n              <th>操作</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"(index, val) in data\">\n              <td>{{index+1}}</td>\n              <td>{{val.doctor.name}}</td>\n              <td>{{val.user.nickname}}</td>\n              <td>{{val.user.realname}}</td>\n              <td><span v-link=\"{ path: 'message_detail/', query: { id: val.id, doctor_name: val.doctor.name, user_name: val.user.realname} }\">查看</span></td>\n            </tr>\n          </tbody>\n        </table>\n        <nav>\n          <ul class=\"pagination\">\n            <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n          </ul>\n        </nav>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-3f88e83a", module.exports)
  } else {
    hotAPI.update("_v-3f88e83a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],51:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    created: function created() {
        this.id = this.$route.params.id;
    },
    data: function data() {
        return {
            send: {},
            express_number: '',
            express_company: 0,
            send_status: 0,
            data: {}
        };
    },

    methods: {
        addSend: function addSend(data) {
            this.data.id = this.id;
            this.data.express_number = this.express_number;
            this.data.express_company = this.express_company;
            this.$http.post('deal/addsend', data).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$set('send', '');
                    $("#addlogistics").modal("hide");
                    this.$dispatch("update");
                } else {
                    layer.msg(res.data.msg);
                }
            }, function (res) {
                var data = res.data;
                errorMsg(data.errors);
            });
        },
        goback: function goback() {
            this.$router.go("/send_list");
        }
    },
    watch: {}

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"addlogistics\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">物流状态</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流公司：</span></label>\n            <div class=\"col-sm-10\">\n              <select v-model=\"express_company\" class=\"form-control\">\n                <option value=\"sf\" selected=\"selected\">顺丰快递</option>\n              </select>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流单号：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" placeholder=\"请输入物流单号\" v-model=\"express_number\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" @click=\"addSend(data)\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-23836f40", module.exports)
  } else {
    hotAPI.update("_v-23836f40", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],52:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    created: function created() {
        this.id = this.$route.params.id;
    },
    data: function data() {
        return {
            info: {}
        };
    },


    methods: {
        getDetail: function getDetail(id) {
            if (id > 0) {
                this.$http.get('law/note/' + id).then(function (res) {
                    this.$set('info', res.data.data);
                });
            }
        },
        addNote: function addNote(info) {
            info.id = this.id;
            this.$http.post('law/add', info).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    $("#addnote").modal("hide");
                    this.$dispatch("refreshln");
                }
            });
        }
    },
    watch: {
        id: function id(newValue) {
            this.getDetail(newValue);
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"addnote\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">备注</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <div class=\"col-sm-10\">\n              <textarea v-model=\"info.note\" class=\"form-control\"></textarea>\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" @click=\"addNote(info)\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-2aeefc55", module.exports)
  } else {
    hotAPI.update("_v-2aeefc55", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],53:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    data: function data() {
        return {
            type: 'radio',
            data: {},
            photo: [],
            answer: ''
        };
    },
    ready: function ready() {
        this.uploadFile();
    },

    watch: {
        type: function type(value) {
            if (value == 'text') {
                $('.clone').hide();
                $('#photos').hide();
            } else if (value == 'photo') {
                $('.clone').hide();
                $('#photos').show();
            } else {
                $('.clone').show();
                $('#photos').hide();
            }
        }
    },
    methods: {
        deletes: function deletes(val) {
            this.photo.splice($.inArray(val, this.photo), 1);
        },
        uploadFile: function uploadFile() {
            var vue = this;
            layui.use('upload', function () {
                layui.upload({
                    url: '/api/upload/add',
                    elem: '#tests',
                    method: 'post',
                    success: function success(res) {
                        vue.photo.push(res.data);
                    }
                });
            });
        },
        add: function add() {
            var data = $("form").serializeArray();
            var dd = [];
            for (var i = 0; i < data.length; i++) {
                if (data[i].name == 'answer') {
                    if ($.trim(data[i].value) != '') {
                        dd.push(data[i].value);
                    }
                }
            }
            for (var j = 0; j < dd.length; j++) {
                if ($.trim(dd[j].value) != '') {
                    dd.splice(j, 1);
                }
            }
            if ($.trim(this.data.title) == '') {
                layer.msg('请输入标题');
                return;
            }

            this.data.exam_id = this.id;
            this.data.type = this.type;
            this.data.option = dd;
            if (this.type == 'photo') {
                this.data.option = this.photo;
            }
            if (this.type == 'text') {
                this.data.option = [];
            }
            this.$http.post('exam', this.data).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.status == 1) {
                    location.reload();
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"addtestt\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">添加问题</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">问题类型：</label>\n            <div class=\"col-sm-10\">\n              <select type=\"text\" v-model=\"type\" class=\"form-control\">\n                <option value=\"radio\" selected=\"selected\">单选</option>\n                <option value=\"checkbox\">复选</option>\n                <option value=\"text\">问答</option>\n                <option value=\"photo\">图片</option>\n              </select>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">问题描述：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"data.title\" class=\"form-control\">\n            </div>\n          </div>\n          <div id=\"photos\" style=\"display:none\" class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\">图片上传：</label>\n            <div class=\"col-sm-10\">\n              <input id=\"tests\" type=\"file\" name=\"file\">\n              <template v-for=\"val in photo\"><img v-bind:src=\"val\" style=\"width:120px\"><i @click=\"deletes(val)\" class=\"icon-bin\"></i></template>\n            </div>\n          </div>\n          <div class=\"clone ff\">\n            <div class=\"form-group\">\n              <label class=\"col-sm-2 control-label\"><span>选项：</span><i class=\"icon-minus\"></i><i class=\"icon-plus\"></i></label>\n              <div class=\"col-sm-10\">\n                <input type=\"text\" name=\"answer\" class=\"form-control\">\n              </div>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">是否必填：</label>\n            <div class=\"col-sm-10\">\n              <select name=\"must\" class=\"form-control\">\n                <option value=\"0\">不必填</option>\n                <option value=\"1\">必填</option>\n              </select>\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" @click=\"add()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-ef537e16", module.exports)
  } else {
    hotAPI.update("_v-ef537e16", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],54:[function(require,module,exports){
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"allogistics\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">物流状态</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流公司：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"send.logistics\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流单号：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"send.logistics_number\" class=\"form-control\">\n            </div>\n          </div>\n          <!--.form-group-->\n          <!--    label.col-sm-2.control-label-->\n          <!--        //span(@click=\"getExp(send.deal_id)\") 物流进度：-->\n          <!--        span 物流进度：-->\n          <!--    .col-sm-10(v-if=\"exp.resultcode == 200\")-->\n          <!--        .lists(v-for=\"mes in exp.result.list\")-->\n          <!--            p.time {{mes.datetime}}-->\n          <!--            p {{mes.remark}}-->\n          <!--    .col-sm-10(v-if=\"exp.resultcode != 200\")-->\n          <!--        .lists-->\n          <!--            p {{exp.reason}}-->\n          \n        </form>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-01ed930a", module.exports)
  } else {
    hotAPI.update("_v-01ed930a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],55:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    ready: function ready() {
        this.getOneAuth();
    },
    data: function data() {
        return {
            oneAuths: [],
            auth: {}
        };
    },

    //aaaa
    methods: {
        getOneAuth: function getOneAuth() {
            this.$http.get('auth/0').then(function (res) {
                this.$set('oneAuths', res.data);
            });
        },
        Add: function Add(auth) {
            this.$http.post('auth', auth).then(function (res) {
                layer.msg(res.data.msg);
                this.getOneAuth();
                this.$set('auth.display_name', '');
                this.$set('auth.name', '');
                this.$set('auth.description', '');
                this.$set('auth.pid', 0);
                this.$dispatch('count');
            }, function (res) {
                var data = res.data;
                (0, _common.errorMsg)(data.errors);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"auth\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">添加权限</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">选择权限等级：</label>\n            <div class=\"col-sm-8\">\n              <select v-model=\"auth.pid\" class=\"form-control\">\n                <option selected=\"selected\" value=\"0\">==第一级权限类==</option>\n                <option v-for=\"oneAuth in oneAuths\" v-bind:value=\"oneAuth.id\">{{oneAuth.path}} {{oneAuth.name}}</option>\n              </select>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">展示名：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"auth.display_name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">权限名：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"auth.name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">描述：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"auth.description\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"Add(auth)\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-f1327d54", module.exports)
  } else {
    hotAPI.update("_v-f1327d54", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],56:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    props: ['id'],
    ready: function ready() {
        this.getAuth(this.id);
    },
    data: function data() {
        return {
            auth: {},
            oneAuths: []
        };
    },

    methods: {
        getAuth: function getAuth(uid) {
            if (uid) {
                this.$http.get('auth/' + uid).then(function (res) {
                    this.$set('auth', res.data.permission);
                    $("#authsave").modal("show");
                });
                this.$http.get('auth/0').then(function (res) {
                    this.$set('oneAuths', res.data);
                });
            }
        },
        save: function save(auth) {
            if (auth.id == auth.pid) {
                layer.msg('不能归属于自己！');return;
            }
            this.$http.put('auth/' + auth.id, auth).then(function (res) {
                layer.msg(res.data.msg);
                this.$dispatch("refreshList");
                $('#authsave').modal('hide');
            }, function (res) {
                var data = res.data;
                (0, _common.errorMsg)(data.errors);
            });
        }
    },
    watch: {
        id: function id(newValue, oldValue) {
            this.getAuth(newValue);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"authsave\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改权限</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">当前位置：</label>\n            <div class=\"col-sm-8\"><span>{{auth.fname}}</span></div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">更换位置：</label>\n            <div class=\"col-sm-8\">\n              <select v-model=\"auth.pid\">\n                <option selected=\"selected\" value=\"0\">==第一级权限类==</option>\n                <option v-for=\"oneAuth in oneAuths\" v-bind:value=\"oneAuth.id\">{{oneAuth.path}} {{oneAuth.name}}</option>\n              </select>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">权限名：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"auth.name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">展示名：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"auth.display_name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">描述：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"auth.description\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n        <div class=\"modal-footer\">\n          <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n          <button type=\"button\" v-on:click=\"save(auth)\" class=\"btn btn-primary\">保存</button>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-9918255a", module.exports)
  } else {
    hotAPI.update("_v-9918255a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],57:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    created: function created() {
        this.id = this.$route.params.id;
    },
    data: function data() {
        return {
            info: {}
        };
    },


    methods: {
        getDetail: function getDetail(id) {
            if (id > 0) {
                this.$http.get('deal/sendetail/' + id).then(function (res) {
                    this.$set('info', res.data.data);
                });
            }
        },
        addNote: function addNote(info) {
            info.id = this.id;
            this.$http.post('deal/add', info).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    $("#dealnote").modal("hide");
                    this.$dispatch("update");
                }
            });
        }
    },
    watch: {
        id: function id(newValue) {
            this.getDetail(newValue);
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"dealnote\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">备注</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <div class=\"col-sm-10\">\n              <textarea v-model=\"info.note\" class=\"form-control\"></textarea>\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" @click=\"addNote(info)\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-f4b897e8", module.exports)
  } else {
    hotAPI.update("_v-f4b897e8", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],58:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    props: ['groupid'],
    ready: function ready() {
        this.getRole(this.id);
    },
    data: function data() {
        return {
            roles: {},
            checkedNames: [],
            id: 0
        };
    },

    methods: {
        getRole: function getRole(id) {
            if (id > 0) {
                this.$http.get('user/role/' + id).then(function (res) {
                    this.$set('roles', res.data.roles);
                });
            }
        },
        groupsave: function groupsave() {
            var obj = {};
            obj.check = this.checkedNames;
            this.$http.put('user/saverole/' + this.id, obj).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$dispatch("admuser");
                    $("#groupedit").modal("hide");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }, watch: {
        groupid: function groupid(newValue, oldValue) {
            this.id = newValue;
            this.getRole(newValue);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"groupedit\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">设置用户组</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">所属权限组：</label>\n            <div class=\"col-sm-10\"><span v-for=\"rol  in roles\">\n                <label v-if=\"rol.status == 1\">\n                  <input type=\"checkbox\" v-bind:value=\"rol.id\" v-model=\"checkedNames\" checked=\"checked\">\n                  <label>{{rol.display_name}}</label>\n                </label>\n                <label v-else=\"v-else\">\n                  <input type=\"checkbox\" v-bind:value=\"rol.id\" v-model=\"checkedNames\">\n                  <label>{{rol.display_name}}</label>\n                </label></span></div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"groupsave(rid)\" class=\"btn btn-primary\">修改</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-2142e86a", module.exports)
  } else {
    hotAPI.update("_v-2142e86a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],59:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    props: ['id'],
    ready: function ready() {
        this.getLog(this.id);
    },
    data: function data() {
        return {
            logs: {}
        };
    },

    methods: {
        //aaa
        getLog: function getLog(id) {
            if (id > 0) {
                this.$http.get('logs/' + id).then(function (res) {
                    this.$set('logs', res.data);
                });
            }
        }
    }, watch: {
        id: function id(newValue, oldValue) {
            this.getLog(newValue);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"logdetail\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">日志详情</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" v-for=\"log in logs\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">登录账号：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"log.user_name\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">真实姓名：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"log.user_realname\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">ip：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"log.ip\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">登录地址：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"log.address\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">登录设备：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"log.useragent\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">登录时间：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"log.created_at\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-8e1fdd52", module.exports)
  } else {
    hotAPI.update("_v-8e1fdd52", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],60:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    created: function created() {
        this.id = this.$route.params.id; //
    },
    data: function data() {
        return {
            send: {}
        };
    },

    methods: {
        addSend: function addSend(send) {
            this.$http.put('deal/updatesend/' + this.id, send).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$set('send', '');
                    $("#logisticsupdate").modal("hide");
                    this.$dispatch("update");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        getDealDetail: function getDealDetail(id) {
            if (id > 0) {
                this.$http.get('deal/sendetail/' + id).then(function (res) {
                    this.$set('send', res.data.data);
                });
            }
        },
        goback: function goback() {
            this.$router.go("/send_list");
        }
    },
    watch: {
        id: function id(newValue) {
            this.getDealDetail(newValue);
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"logisticsupdate\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">物流状态</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流公司：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"send.express_company\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流单号：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"send.express_number\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" @click=\"addSend(send)\" class=\"btn btn-primary\">修改</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-e8166344", module.exports)
  } else {
    hotAPI.update("_v-e8166344", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],61:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    created: function created() {},
    data: function data() {
        return {
            password: {}
        };
    },

    methods: {
        getPassword: function getPassword(password) {
            this.$http.post('user/resetpwd/1', password).then(function (res) {
                layer.msg(res.data.msg);
                this.$dispatch("refreshList");
                $("#password").modal("hide");
            }, function (res) {
                var data = res.data;
                (0, _common.errorMsg)(data.errors);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"password\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改密码</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\">原密码：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"password\" v-model=\"password.user_password\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\">新密码：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"password\" v-model=\"password.user_newpassword\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\">确认新密码：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"password\" v-model=\"password.user_newpassword_confirmation\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"getPassword(password)\" class=\"btn btn-primary\">确认修改</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-6ce071c9", module.exports)
  } else {
    hotAPI.update("_v-6ce071c9", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],62:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    created: function created() {
        this.id = this.$route.params.id;
    },
    data: function data() {
        return { //
            points: {},
            relate: {},
            data: {},
            checks: [],
            checkRadio: 0
        };
    },
    ready: function ready() {},

    methods: {
        search: function search() {
            var name = $('input[name="search"]').val();
            $.each(this.points, function (key, val) {
                $('.checked' + val.id).next('span').css({ color: '#333', 'font-size': "14px" });
                if (val.name == name) {
                    $('.checked' + val.id).next('span').css({ 'color': 'red', 'font-size': "20px" });
                }
            });
        },

        addPoint: function addPoint() {
            var obj = {};
            obj.system = 'relate';
            obj.param = { relate: this.checkRadio, point: this.checks };
            this.$http.post('law/update/' + this.id, obj).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.status == 200) {
                    $("#point").modal("hide");
                    this.$dispatch("refreshln");
                }
            });
        },
        addChecks: function addChecks(val, prev) {
            if (typeof prev != 'undefined') {
                this.checks = [];
                $('input:checkbox').prop("checked", false);
                this.checkRadio = 0;
                if (prev == 5) return;
                this.checkRadio = prev + 1;
                for (var i = 0; i < val.length; i++) {
                    this.checks.push(val[i]);
                    $('.checked' + val[i].id).prop('checked', true);
                }
            } else {
                var checksid = [];
                $.each(this.checks, function (index, val) {
                    checksid.push(val.id);
                });
                var index = checksid.indexOf(val.id);
                if (index > -1) {
                    this.checks.splice(index, 1);
                    $('.checked' + val[i].id).prop('checked', false);
                } else {
                    this.checks.push(val);
                    $('.checked' + val.id).prop('checked', true);
                }
            }
        },
        getPoint: function getPoint() {
            this.$http({ url: 'law/point', method: 'GET' }).then(function (res) {
                this.$set('points', res.data);
            });
        },

        getRekate: function getRekate() {
            this.$http({ url: 'law/relate', method: 'GET' }).then(function (res) {
                this.$set('relate', res.data);
            });
        },
        getDetail: function getDetail() {
            this.$http({ url: 'law/show/' + this.id, method: 'GET' }).then(function (res) {
                this.$set('data', res.data);
            });
        }
    },
    watch: {
        id: function id(newValue) {
            if (newValue) {
                this.getPoint();
                this.getRekate();
                this.getDetail();
            }
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"point\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">穴位选择</h4>\n      </div>\n      <div style=\"margin-top:5px;\">\n        <input type=\"text\" name=\"search\" style=\"margin-left: 7px;\"><a @click=\"search()\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span style=\"margin-left:5px\">搜索</span></a>\n      </div>\n      <div @click=\"addChecks(1,5)\" style=\"overflow:hidden\">\n        <input type=\"radio\" name=\"checkName\" style=\"float: left;margin-left: 5px;\"><span style=\"float: left;\">全部取消</span>\n        <button type=\"button\" @click=\"addPoint()\" style=\"float: right;margin-right: 5px;\" class=\"btn btn-primary\">添加</button>\n      </div>\n      <div class=\"modal-body\"><span v-if=\"data.status ==200 &amp;&amp; data.data.point_id != ''\"><span>历史选择:<span>{{data.data.relate}} {{data.data.point_id}}</span></span></span>\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <div v-for=\"item in relate\" @click=\"addChecks(item.point,$index)\" class=\"col-sm-2\">\n              <input type=\"radio\" name=\"checkName\"><span>{{item.name}}</span>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <div v-for=\"point in points\" @click=\"addChecks(point)\" class=\"col-sm-2\">\n              <input type=\"checkbox\" name=\"checkboxName\" class=\"checked{{point.id}}\"><span>{{point.name}}</span>\n            </div>\n          </div>\n        </form>\n        <div style=\"width:100%;overflow: hidden;\">\n          <div v-if=\"checks.length\" v-for=\"item in checks\" track-by=\"$index\" style=\"width:45%;float: left;margin-left: 20px;\"><img v-bind:src=\"item.img\"></div>\n          <!--span {{item.name}}-->\n        </div>\n      </div>\n      <!--.modal-footerbutton.btn.btn-default(type='button', data-dismiss='modal') 取消\n      -->\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-6e3f1dd2", module.exports)
  } else {
    hotAPI.update("_v-6e3f1dd2", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],63:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    created: function created() {
        this.id = this.$route.params.id; //2
    },
    data: function data() {
        return {
            send: {}, //
            exp: {},
            reason: {},
            code: {},
            iid: 2
        };
    },
    ready: function ready() {},

    methods: {
        getSendDetail: function getSendDetail(id) {
            if (id > 0) {
                this.$http.get('promo/detail/' + id).then(function (res) {
                    //
                    this.$set('code', res.data.data);
                });
            }
        },
        daoru: function daoru() {
            var self = this;
            layui.use('upload', function () {
                layui.upload({
                    url: '/api/promo/addfile/' + self.id,
                    title: '发放优惠码',
                    elem: '#aaaa', //指定原始元素，默认直接查找class="layui-upload-file"
                    method: 'post',
                    type: 'file',
                    success: function success(res) {
                        if (res.status == 1) {
                            layer.msg(res.msg);
                            window.location.href = "promocode_mobile";
                        } else {
                            layer.msg(res.msg);
                        }
                    }
                });
            });
        }
    },
    watch: {
        id: function id(newValue) {
            this.getSendDetail(newValue);
            this.daoru();
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"sendcode\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">物流状态</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\"><span>优惠码类型：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" value=\"字母+数字\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\"><span>优惠码长度：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" value=\"6位\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\"><span>优惠码数量：</span></label>\n            <div class=\"col-sm-10\">\n              <input name=\"total\" type=\"text\" v-model=\"code.total\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\"><span>优惠金额：</span></label>\n            <div class=\"col-sm-10\">\n              <input name=\"discount\" type=\"text\" v-model=\"code.discount\" class=\"form-control\">\n            </div>\n          </div>\n          <!--.form-group-->\n          <!--    label.col-sm-2.control-label(for='')-->\n          <!--        span 活动链接：-->\n          <!--    .col-sm-10-->\n          <!--        input.form-control(name=\"url\" type=\"text\", v-model=\"code.url\")-->\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\"><span>开始时间：</span></label>\n            <div class=\"col-sm-10\">\n              <input name=\"url\" type=\"text\" v-model=\"code.start_time\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\"><span>截止时间：</span></label>\n            <div class=\"col-sm-10\">\n              <input name=\"url\" type=\"text\" v-model=\"code.end_time\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\"><span>目标用户：</span></label>\n            <div class=\"col-sm-10\">\n              <input id=\"aaaa\" type=\"file\" name=\"file\">\n            </div>\n          </div>\n        </form>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-2ca933a3", module.exports)
  } else {
    hotAPI.update("_v-2ca933a3", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],64:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    created: function created() {
        this.getClinique();
    },
    data: function data() {
        return {
            user: {},
            cliniques: {}
        };
    },

    methods: {
        addUser: function addUser(user) {
            if (!user.clinique_id) {
                layer.msg('请选择诊所');
                return false;
            }
            if (!user.kname) {
                layer.msg('请输入客服姓名');
                return false;
            }
            if (!user.telephone) {
                layer.msg('请输入客服手机号');
                return false;
            }
            this.$http.post('tel/addTelephone', user).then(function (res) {
                if (res.data.status) {
                    $("#telephone").modal("hide");
                    layer.msg(res.data.msg);
                    this.$dispatch('telphone');
                } else {
                    layer.msg(res.data.msg);
                }
            }, function (res) {
                var data = res.data;
                (0, _common.errorMsg)(data.errors);
            });
        },
        getClinique: function getClinique() {
            this.$http.get('clinique/index').then(function (res) {
                if (res.data.status) {
                    this.cliniques = res.data.data;
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"telephone\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">添加客服</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">诊所：</label>\n            <div class=\"col-sm-4\">\n              <select v-model=\"user.clinique_id\" class=\"form-control\">\n                <option v-for=\"c in cliniques\" v-bind:value=\"c.id\">{{c.name}}</option>\n              </select>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">姓名：</label>\n            <div class=\"col-sm-4\">\n              <input type=\"text\" v-model=\"user.kname\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">手机号：</label>\n            <div class=\"col-sm-4\">\n              <input type=\"text\" v-model=\"user.telephone\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"addUser(user)\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-29f83d46", module.exports)
  } else {
    hotAPI.update("_v-29f83d46", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],65:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    created: function created() {
        this.getUserGroup();
    },
    data: function data() {
        return {
            user: {},
            userGroups: {}
        };
    },

    methods: {
        getUserGroup: function getUserGroup() {
            this.$http.get('user/group').then(function (res) {
                this.userGroups = res.data.roles;
            });
        },
        addUser: function addUser(user) {
            this.$http.post('user/adduser', user).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$set('user', {});
                    this.$dispatch("admuser");
                    this.$dispatch("userupdate");
                    $("#useradd").modal("hide");
                } else {
                    layer.msg(res.data.msg);
                }
            }, function (res) {
                var data = res.data;
                (0, _common.errorMsg)(data.errors);
            });
        },
        reset: function reset() {
            $("#useradd").modal("hide");
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"useradd\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">新建用户</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">账号：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"user.user_name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">真实姓名：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"user.user_realname\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">邮箱：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"user.user_email\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">手机号：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"user.user_phone\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">密码：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"password\" v-model=\"user.user_password\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">确认密码：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"password\" v-model=\"user.user_password_confirmation\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">所属权限组：</label>\n            <div class=\"col-sm-10\">\n              <select v-model=\"user.rid\" class=\"form-control\">\n                <option selected=\"selected\" value=\"0\">暂不加入用户组</option>\n                <option v-for=\"userGroup in userGroups\" v-bind:value=\"userGroup.id\">{{userGroup.display_name}}</option>\n              </select>\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" v-on:click=\"reset()\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"addUser(user)\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-50f0d7b8", module.exports)
  } else {
    hotAPI.update("_v-50f0d7b8", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],66:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    data: function data() {
        return {
            roles: {}
        };
    },

    methods: {
        getRole: function getRole(role) {
            this.$set('roles.auth', window.datas);
            this.$http.post('role', role).then(function (res) {
                layer.msg(res.data.msg);
                this.$dispatch("refreshList");
                this.$dispatch("count");
                $('#usergroup').modal('hide');
            }, function (res) {
                console.log(res);
                (0, _common.errorMsg)(res.data.errors);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"usergroup\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">权限配置</h4>\n      </div>\n      <div class=\"modal-body\">\n        <ul class=\"nav nav-tabs\">\n          <li class=\"active\"><a href=\"#tab1\" role=\"tab\" data-toggle=\"tab\">新增用户组</a></li>\n          <li><a href=\"#tab2\" role=\"tab\" data-toggle=\"tab\">权限配置</a></li>\n        </ul>\n        <div class=\"tab-content\">\n          <div id=\"tab1\" class=\"tab-pane fade in active\">\n            <div class=\"form-group\">\n              <label for=\"\">用户组名称</label>\n              <input type=\"text\" placeholder=\"输入组名称\" v-model=\"roles.name\" class=\"form-control\">\n            </div>\n            <div class=\"form-group\">\n              <label for=\"\">用户组标识</label>\n              <input type=\"text\" placeholder=\"用户组标识\" v-model=\"roles.display_name\" class=\"form-control\">\n            </div>\n            <div class=\"form-group\">\n              <label for=\"\">描述</label>\n              <textarea placeholder=\"除了描述之外的其他功能\" rows=\"3\" v-model=\"roles.description\" class=\"form-control\"></textarea>\n            </div>\n          </div>\n          <div id=\"tab2\" class=\"tab-pane fade\">\n            <div id=\"priTree\" class=\"permission_set\"></div>\n          </div>\n        </div>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"getRole(roles)\" class=\"btn btn-primary\">保存并修改</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-82c61a54", module.exports)
  } else {
    hotAPI.update("_v-82c61a54", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],67:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    props: ['id'],
    created: function created() {
        this.getRole(this.id);
    },
    data: function data() {
        return {
            roles: {},
            data: {},
            datas: {}
        };
    },

    methods: {
        getRole: function getRole(id) {
            if (id > 0) {
                this.$http.get('role/' + id).then(function (res) {
                    this.$set('roles', res.data.data);
                    getRolesTree(this.roles.auth);
                });
            }
        },
        addRole: function addRole(roles) {
            this.data = window.obj;
            this.$set('roles.auth', this.data);
            this.$http.put('role/' + this.id, roles).then(function (res) {
                layer.msg(res.data.msg);
                this.$dispatch("refreshList");
                $("#usergroupedit").modal("hide");
            }, function (res) {
                var data = res.data;
                (0, _common.errorMsg)(data.errors);
            });
        },
        del: function del(id) {
            this.$http.delete('role/' + id).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$dispatch("refreshList");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }, watch: {
        id: function id(newValue, oldValue) {
            this.getRole(newValue);
            this.$set('datas', {});
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"usergroupedit\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">权限配置</h4>\n      </div>\n      <div class=\"modal-body\">\n        <ul class=\"nav nav-tabs\">\n          <li class=\"active\"><a href=\"#tab3\" role=\"tab\" data-toggle=\"tab\">新增用户组</a></li>\n          <li><a href=\"#tab4\" role=\"tab\" data-toggle=\"tab\">权限配置</a></li>\n        </ul>\n        <div class=\"tab-content\">\n          <div id=\"tab3\" class=\"tab-pane fade in active\">\n            <div class=\"form-group\">\n              <label for=\"\">用户组名称</label>\n              <input type=\"text\" placeholder=\"投资团队\" v-model=\"roles.name\" class=\"form-control\">\n            </div>\n            <div class=\"form-group\">\n              <label for=\"\">用户组标识</label>\n              <input type=\"text\" placeholder=\"用户组标识\" v-model=\"roles.display_name\" class=\"form-control\">\n            </div>\n            <div class=\"form-group\">\n              <label for=\"\">描述</label>\n              <textarea placeholder=\"除了描述之外的其他功能\" rows=\"3\" v-model=\"roles.description\" class=\"form-control\"></textarea>\n            </div>\n          </div>\n          <div id=\"tab4\" class=\"tab-pane fade\">\n            <div id=\"priTree1\" class=\"permission_set\"></div>\n          </div>\n        </div>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <!--button.btn.btn-primary(type='button' ,v-on:click=\"del(roles.id)\") 删除组123-->\n        <button type=\"button\" v-on:click=\"addRole(roles)\" class=\"btn btn-primary\">保存并修改</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-17880640", module.exports)
  } else {
    hotAPI.update("_v-17880640", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],68:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _common = require('../../common.js');

exports.default = {
    props: ['userid'],
    created: function created() {
        this.getUserGroup();
    },
    data: function data() {
        return {
            uid: 0,
            user: {},
            userGroups: {}
        };
    },

    methods: {
        getUser: function getUser(id) {
            if (id > 0) {
                this.$http.get('user/' + id).then(function (res) {
                    this.$set('user', res.data.data);
                });
            }
        },
        getUserGroup: function getUserGroup() {
            this.$http.get('user/group').then(function (res) {
                this.userGroups = res.data.roles;
            });
        },
        updateUser: function updateUser(user) {
            this.$http.put('user/' + this.uid, user).then(function (res) {
                var data = res.data;
                if (data.status == 1) {
                    layer.msg(data.msg);
                    this.$dispatch("userupdate");
                    $("#userinfo").modal("hide");
                } else {
                    layer.msg(data.msg);
                }
            }, function (res) {
                var data = res.data;
                (0, _common.errorMsg)(data.errors);
            });
        }
    },
    watch: {
        userid: function userid(newValue, oldValue) {
            this.uid = newValue;
            this.getUser(newValue);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"userinfo\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改用户信息</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">用户名：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"user.user_name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">真实姓名：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"user.user_realname\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">邮箱：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"user.user_email\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">联系电话：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"user.user_phone\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group team\">\n            <label for=\"\" class=\"col-sm-3 control-label\">用户地址：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"user.user_address\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">所属权限组：</label>\n            <div class=\"col-sm-8\">\n              <select v-model=\"user.role_id\" class=\"form-control\">\n                <option value=\"0\">暂不加入用户组</option>\n                <option v-for=\"userGroup in userGroups\" v-bind:value=\"userGroup.id\">{{userGroup.display_name}}</option>\n              </select>\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"updateUser(user)\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-240ecb27", module.exports)
  } else {
    hotAPI.update("_v-240ecb27", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../common.js":10,"vue":7,"vue-hot-reload-api":3}],69:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    data: function data() {
        return {
            data: {},
            allSection: {},
            name: '',
            sid: 0,
            sectionArr: []
        };
    },
    ready: function ready() {
        this.getDisease();
    },

    methods: {
        getDisease: function getDisease() {
            this.$http({ url: 'section/index', method: 'GET', params: { noPage: true } }).then(function (res) {
                this.allSection = res.data.sections;
            });
        },
        checkAttr: function checkAttr(id) {
            var _this = this;
            var index = $.inArray(id, _this.sectionArr);
            if (index == -1) {
                $(".checked" + id).prop("checked", true);
                this.sectionArr.push(id);
            } else {
                $(".checked" + id).prop("checked", false);
                this.sectionArr.splice(index, 1);
            }
            console.log(this.sectionArr);
        },
        store: function store() {
            this.$http({ url: 'doctor/addisease/' + this.id, method: 'PUT', params: { data: this.sectionArr, type: 'section' } }).then(function (res) {
                if (res.data.status == 1) {
                    $("#addsection").modal("hide");
                    this.sectionArr = [];
                    this.$dispatch("refreshList");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"addsection\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <div v-for=\"val in allSection\" @click=\"checkAttr(val.id)\" class=\"col-sm-2\">\n              <input type=\"checkbox\" name=\"checkboxName\" class=\"checked_{{val.id}}\"><span class=\"checked_f{{val.id}}\">{{val.name}}</span>\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" @click=\"store()\" class=\"btn btn-primary\">保存</button>\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-535e21a4", module.exports)
  } else {
    hotAPI.update("_v-535e21a4", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],70:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['ddd'],
    ready: function ready() {
        this.uploadFile();
    },
    data: function data() {
        return {
            data: {},
            answer: ''
        };
    },

    watch: {
        'ddd.type': function dddType(value) {
            if (value == 'text') {
                $('.clone').hide();
                $('#photo').hide();
            } else if (value == 'photo') {
                $('.clone').hide();
                $('#photo').show();
            } else {
                $('.clone').show();
                $('#photo').hide();
            }
        }
    },
    methods: {
        uploadFile: function uploadFile() {
            var vue = this;
            layui.use('upload', function () {
                layui.upload({
                    url: '/api/upload/add',
                    elem: '#test',
                    method: 'post',
                    success: function success(res) {
                        if (vue.ddd.option != null && vue.ddd.option.length == 3) {
                            layer.msg('最多上传三张示例图片');
                            return;
                        }

                        if (isEmpty(vue.ddd.option)) {
                            vue.ddd.option = [];
                        }
                        if (vue.ddd.option == null || vue.add.option == '') {
                            vue.ddd.option = [];
                        }
                        console.log(vue.ddd.option);
                        vue.ddd.option.push(res.data);
                    }
                });
            });
        },
        deletes: function deletes(val) {
            this.ddd.option.splice($.inArray(val, this.ddd.option), 1);
        },
        add: function add() {
            if ($.trim(this.ddd.title) == '') {
                layer.msg('请输入标题');
                return;
            }
            if (this.ddd.type == 'radio' || this.ddd.type == 'checkbox') {
                var data = $("form").serializeArray();
                this.ddd.option = [];
                for (var i = 0; i < data.length; i++) {
                    if (data[i].name == 'contentans') {
                        if ($.trim(data[i].value) != '') {
                            this.ddd.option.push(data[i].value);
                        }
                    }
                }
            }
            this.$http.put('exam', this.ddd).then(function (res) {
                if (res.data.status) {
                    location.reload();
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"addtest\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">添加问题</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">问题类型：</label>\n            <div class=\"col-sm-10\"><span v-if=\"ddd.type =='radio'\">单选</span><span v-if=\"ddd.type =='checkbox'\">复选</span><span v-if=\"ddd.type =='text'\">问答</span><span v-if=\"ddd.type =='photo'\">图片</span></div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">问题描述：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"ddd.title\" class=\"form-control\">\n            </div>\n          </div>\n          <div style=\"display:none\" id=\"photo\" class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\">图片上传：</label>\n            <div class=\"col-sm-10\">\n              <input id=\"test\" type=\"file\" name=\"file\">\n              <template v-for=\"val in ddd.option\"><img v-bind:src=\"val\" style=\"width:120px;\">\n                <!--sss--><i @click=\"deletes(val)\" style=\"color:red\" class=\"icon-bin\"></i>\n              </template>\n            </div>\n          </div>\n          <div class=\"clone ff\">\n            <div v-for=\"d in ddd.option\" class=\"form-group\">\n              <label class=\"col-sm-2 control-label\"><span>选项：</span><i class=\"icon-minus\"></i><i class=\"icon-plus\"></i></label>\n              <div class=\"col-sm-10\">\n                <input type=\"text\" v-model=\"d.val\" name=\"contentans\" class=\"form-control\">\n              </div>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">是否必填：</label>\n            <div class=\"col-sm-10\">\n              <select v-model=\"ddd.must\" class=\"form-control\">\n                <option value=\"0\">不必填</option>\n                <option value=\"1\">必填</option>\n              </select>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">排序值：</label>\n            <div class=\"col-sm-1\">\n              <input type=\"text\" v-model=\"ddd.sort\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" @click=\"add()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-5aa60d90", module.exports)
  } else {
    hotAPI.update("_v-5aa60d90", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],71:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['area_val'],
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        save: function save() {
            this.$http.post('area', this.area_val).then(function (res) {
                if (res.data.status) {
                    layer.msg('操作成功');
                    $('#area').modal('hide');
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"area\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改门店</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">地区：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"area_val.name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">首价：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"area_val.initiate_price\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">首重：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"area_val.initiate_weight\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">续重：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"area_val.continue_price\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"save()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-64f6ab00", module.exports)
  } else {
    hotAPI.update("_v-64f6ab00", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],72:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    data: function data() {
        return {
            data: {},
            user: {},
            doctor: {},
            order: {},
            bespeak: {},
            prescription: {},
            recipeHead: {},
            prescriptionOrder: {},
            message: {}
        };
    },

    methods: {
        card_detail: function card_detail(ctype, id) {
            window.open('/admin/card_detail/' + ctype + '/' + id);
        },
        getDetail: function getDetail(id) {
            this.$http({ url: 'clinic/show/' + id }).then(function (res) {
                this.$set('data', res.data.data);
                this.bespeak = this.data.bespeak;
                this.order = this.bespeak.order;
                this.doctor = this.data.doctor;
                this.user = this.data.user;
                this.message = this.data.message;
                this.prescription = this.data.prescription;
                if (this.prescription) {
                    this.recipeHead = this.prescription.recipe_head;
                    this.prescriptionOrder = this.prescription.order;
                }
            });
        }
    },
    watch: {
        'id': function id(value) {
            if (value > 0) {
                this.getDetail(value);
            }
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"chat_detail\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <label class=\"modal-title\">详情</label>\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <div class=\"col-sm-8\">\n              <p>订单编号： {{order.order_sn}}</p>\n              <p>患  者： {{user.realname}}</p>\n              <p>就诊医师： {{doctor.name}}</p>\n              <p>类型： {{data.type}} {{data.first}}</p>\n            </div>\n          </div>\n        </form>\n        <hr>\n        <form role=\"form\" v-if=\"message.message\" class=\"form-horizontal\">\n          <label class=\"modal-title\"> 诊断内容</label>\n          <div class=\"form-group\">\n            <div v-for=\"val in message.message\" class=\"col-sm-8\"><span v-if=\"val.type ==2\">\n                <label>医生 :</label><span>{{doctor.name}}</span></span><span v-if=\"val.type ==1\">\n                <label>患者 :</label><span>{{user.realname}}</span></span><span>&nbsp;&nbsp;{{val.created_at}}</span>\n              <div v-if=\"val.msg_type == 'text'\">\n                <div v-bind:class=\"val.type ==3 ? 'message_font_color' : ''\">{{val.content.text}}</div>\n              </div>\n              <div v-if=\"val.msg_type == 'audio'\">\n                <audio v-bind:src=\"val.content.text\"></audio>\n              </div>\n              <div v-if=\"val.msg_type == 'image'\"><span onclick=\"window.open('http://oy3x9vo5r.bkt.clouddn.com/{{val.content.key}}')\"> http://oy3x9vo5r.bkt.clouddn.com/{{val.content.key}}</span></div>\n              <div v-if=\"val.msg_type == 'card'\" @click=\"card_detail(val.content.extra.ctype,val.content.extra.id)\">\n                <div v-if=\"val.content.extra.ctype ==1\" class=\"message_font_color\"> 个性问诊单</div>\n                <div v-if=\"val.content.extra.ctype ==2\" class=\"message_font_color\"> 标准问诊单</div>\n                <div v-if=\"val.content.extra.ctype ==3\" class=\"message_font_color\"> 处方问诊单</div>\n                <div v-if=\"val.content.extra.ctype ==4\" class=\"message_font_color\"> 用户填写个性问诊单</div>\n              </div>\n            </div>\n          </div>\n        </form>\n        <hr>\n        <form role=\"form\" v-if=\"prescription\" class=\"form-horizontal\">\n          <label class=\"modal-title\">处方 {{recipeHead.sum}} 副</label>\n          <div class=\"form-group\">\n            <div class=\"col-sm-8\"><span v-for=\"val in prescription.recipe\">\n                <p>{{val.name}} {{val.dosage}} {{val.unit}}  {{val.other}}</p></span></div>\n            <div class=\"col-sm-8\">\n              <label>医嘱:</label><span>{{prescription.recipe_remark}}</span>\n            </div>\n          </div>\n        </form>\n        <hr>\n        <form role=\"form\" v-if=\"prescriptionOrder\" class=\"form-horizontal\">\n          <label class=\"modal-title\">其他</label>\n          <div class=\"form-group\">\n            <div class=\"col-sm-8\">\n              <label>抓药</label><span v-if=\"prescription.tisane\">代煎{{prescription.recipe_head}}副</span><span v-else=\"v-else\"> 不代煎</span>\n            </div>\n            <div class=\"col-sm-8\">\n              <label>订单号:</label><span>{{order.order_sn}}</span>\n            </div>\n            <div v-if=\"prescription.express\" class=\"col-sm-8\">\n              <label>快递:</label><span v-if=\"prescription.express\">{{prescriptionOrder.province}}{{prescriptionOrder.city}}{{prescriptionOrder.district}}{{prescriptionOrder.address}}</span>\n            </div>\n            <div v-else=\"v-else\" class=\"col-sm-8\">\n              <label>自取</label>\n            </div>\n          </div>\n        </form>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-456bf2f5", module.exports)
  } else {
    hotAPI.update("_v-456bf2f5", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],73:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['clinic_id'],
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        card_detail: function card_detail(ctype, id) {
            window.open('/admin/card_detail/' + ctype + '/' + id + '/' + this.data.family_id);
        },
        getDetail: function getDetail(id) {
            this.$http({ url: 'clinic/show/' + id }).then(function (res) {
                this.$set('data', res.data.clinic);
            });
        }
    },
    watch: {
        'clinic_id': function clinic_id(value) {
            if (value > 0) {
                this.getDetail(value);
            }
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"clinic_detail\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <form role=\"form\" v-if=\"data.messages\" class=\"form-horizontal\">\n          <label class=\"modal-title\"> 诊断内容</label>\n          <div class=\"form-group\">\n            <div v-for=\"val in data.messages\" class=\"col-sm-8\"><span v-if=\"val.type ==1\">\n                <label>医生 :</label><span>{{data.doc_name}}</span></span><span v-if=\"val.type ==2\">\n                <label>患者 :</label><span>{{data.nickname}}</span></span><span>&nbsp;&nbsp;{{val.created_at}}</span><span v-if=\"val.type ==3\"></span>\n              <div v-if=\"val.msg_type == 'text'\">\n                <div v-bind:class=\"val.type ==3 ? 'message_font_color' : ''\">{{val.content.text}}</div>\n              </div>\n              <div v-if=\"val.msg_type == 'audio'\">\n                <audio v-bind:src=\"val.content.text\"></audio>\n              </div>\n              <div v-if=\"val.msg_type == 'card'\" @click=\"card_detail(val.content.extra.cType,val.content.extra.id)\">\n                <div v-if=\"val.content.extra.cType ==1\" class=\"message_font_color\"> 个性问诊单</div>\n                <div v-if=\"val.content.extra.cType ==2\" class=\"message_font_color\"> 标准问诊单</div>\n                <div v-if=\"val.content.extra.cType ==3\" class=\"message_font_color\"> 处方问诊单</div>\n                <div v-if=\"val.content.extra.cType ==4\" class=\"message_font_color\"> 用户填写个性问诊单</div>\n              </div>\n            </div>\n          </div>\n        </form>\n        <hr>\n        <form role=\"form\" v-if=\"data.has_one_recipe\" class=\"form-horizontal\">\n          <label class=\"modal-title\">处方 {{data.has_one_recipe.recipe_head.sum}} 副</label>\n          <div class=\"form-group\">\n            <div class=\"col-sm-8\"><span v-for=\"val in data.has_one_recipe.recipe\">\n                <p>{{val.name}} {{val.dosage}}g  {{val.other}}</p></span></div>\n            <div class=\"col-sm-8\">\n              <label>医嘱:</label><span>{{data.has_one_recipe.recipe_remark}}</span>\n            </div>\n          </div>\n        </form>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-117f9a25", module.exports)
  } else {
    hotAPI.update("_v-117f9a25", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],74:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['val'],
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        save: function save() {
            this.$http.post('clinique/update', this.val).then(function (res) {
                if (res.data.status) {
                    this.$dispatch('userupdate');
                    $('#clinique').modal('hide');
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"clinique\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改门店</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">地址：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.content.address\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">电话：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.telephone\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">经度：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.content.longitude\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">纬度：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.content.latitude\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"save()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-36cc8e79", module.exports)
  } else {
    hotAPI.update("_v-36cc8e79", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],75:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['val'],
    data: function data() {
        return {
            data: {},
            name: ''
        };
    },

    methods: {
        add: function add() {
            this.$http({ url: 'disease/create', method: "POST", params: { section_id: this.val.id, name: this.name } }).then(function (res) {
                if (res.data.status == 1) {
                    this.val.disease.push({ id: res.data.data, name: this.name });
                    console.log(this.val.disease);
                    this.name = '';
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        del: function del(id, name) {
            var _this = this;
            var confirm = layer.confirm('您确定删除 ' + name + '？', {
                btn: ['确定', '取消']
            }, function () {
                _this.$http({ url: 'disease/diseasedel/' + id, method: "delete" }).then(function (res) {
                    if (res.data.status == 1) {
                        for (var i = 0; i < _this.val.disease.length; i++) {
                            if (_this.val.disease[i].id == id) {
                                _this.val.disease.splice(i, 1);
                            }
                        }
                    } else {
                        layer.msg(res.data.msg);
                    }
                });
                layer.close(confirm);
            });
        }
    },
    watch: {
        'id': function id(value) {
            if (value > 0) {
                this.getDetail(value);
            }
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"disease\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n      </div>\n      <div class=\"modal-body\">\n        <div style=\"margin-bottom: 15px;\" class=\"col-sm-12\">\n          <div class=\"col-sm-4\">\n            <input type=\"text\" v-model=\"name\" class=\"form-control\">\n          </div>\n          <div class=\"col-sm-1\">\n            <button type=\"button\" @click=\"add()\" class=\"btn btn-primary\">添加</button>\n          </div>\n        </div>\n      </div>\n      <table class=\"user_table_box table-responsive table table-bordered\">\n        <thead>\n          <tr>\n            <th class=\"col-sm-1\">序号</th>\n            <th class=\"col-sm-1\">名称</th>\n            <!--th.col-sm-1 排序-->\n            <th class=\"col-sm-1\">操作</th>\n          </tr>\n        </thead>\n        <tbody>\n          <tr v-for=\"val in val.disease\">\n            <td>{{1+$index}}</td>\n            <td>{{val.name}}</td>\n            <!--td\n            span(@click=\"sort(val.id,1)\") ↑\n            span(@click=\"sort(val.id,-1)\") ↓\n            -->\n            <td><span @click=\"del(val.id,val.name)\">删除</span></td>\n          </tr>\n        </tbody>\n      </table>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-b7b0244e", module.exports)
  } else {
    hotAPI.update("_v-b7b0244e", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],76:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {
            data: {},
            childDisease: {},
            diseaseArr: []
        };
    },
    ready: function ready() {
        this.getDetail();
    },

    methods: {
        getDetail: function getDetail() {
            this.$http({ url: 'section', params: { id: 1 } }).then(function (res) {
                this.data = res.data.data;
                this.getDisease(this.data[0].id);
            });
        },
        getDisease: function getDisease(id) {
            var _this = this;
            this.$http({ url: 'disease/' + id }).then(function (res) {
                this.childDisease = res.data.data;
                $('span').attr('style', "color:#333");
                $(".checked_f" + id).attr("style", "color:red");
                this.$nextTick(function () {
                    _this.changeProp();
                });
            });
        },
        changeProp: function changeProp() {
            var _this = this;
            $.each(this.childDisease, function (k, v) {
                var index = $.inArray(v.id, _this.diseaseArr);
                if (index != -1) {
                    $(".checked" + v.id).prop("checked", true);
                }
            });
        },
        checkAttr: function checkAttr(id) {
            var _this = this;
            var index = $.inArray(id, _this.diseaseArr);
            if (index == -1) {
                $(".checked" + id).prop("checked", true);
                this.diseaseArr.push(id);
            } else {
                $(".checked" + id).prop("checked", false);
                this.diseaseArr.splice(index, 1);
            }
        },
        store: function store() {
            this.$http({
                url: 'disease_common',
                method: 'post',
                params: { disease: this.diseaseArr }
            }).then(function (res) {
                if (res.data.status == 1) {
                    this.$dispatch('update');
                    $("#diseasecomadd").modal("hide");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"diseasecomadd\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <div v-for=\"val in data\" @click=\"getDisease(val.id)\" class=\"col-sm-2\"><span class=\"checked_f{{val.id}}\">{{val.name}}</span></div>\n          </div>\n          <div class=\"form-group\">\n            <div v-for=\"val in childDisease\" @click=\"checkAttr(val.id)\" class=\"col-sm-4\">\n              <input type=\"checkbox\" name=\"checkboxName\" class=\"checked{{val.id}}\"><span>{{val.name}}</span>\n              <!--ssss-->\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" @click=\"store()\" class=\"btn btn-primary\">保存</button>\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-3267d27d", module.exports)
  } else {
    hotAPI.update("_v-3267d27d", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],77:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    ready: function ready() {
        this.upload();
    },
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        save: function save() {
            this.$http({ url: 'disease_common/' + this.id, method: "put", params: { param: this.data } }).then(function (res) {
                if (res.data.status == 1) {
                    $('#diseasecommon').modal('hide');
                    this.$dispatch('update');
                }
            });
        },
        getDetail: function getDetail(id) {
            this.$http({ url: 'disease_common/' + id }).then(function (res) {
                this.data = res.data.data;
            });
        },
        upload: function upload() {
            var self = this;
            layui.use('upload', function () {
                layui.upload({
                    url: '/api/upload/add',
                    elem: '#shareimgs',
                    method: 'post',
                    success: function success(res) {
                        layer.msg('图片准备就绪~~~');
                        //$('#image').attr('src',res.data);
                        self.data.icon = res.data;
                    }
                });
            });
        }
    },
    watch: {
        'id': function id(value) {
            if (value > 0) {
                this.getDetail(value);
            }
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"diseasecommon\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-1 control-label\"><span>名称：</span></label>\n            <div class=\"col-sm-5\"><span>{{data.disease.name}}</span></div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-1 control-label\"><span>图标：</span></label>\n            <div class=\"col-sm-5\">\n              <input id=\"shareimgs\" type=\"file\" name=\"file\"><img id=\"image\" v-bind:src=\"data.icon\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" data-dismiss=\"modal\" @click=\"save()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-b88be37e", module.exports)
  } else {
    hotAPI.update("_v-b88be37e", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],78:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['doctor_id'],
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        save: function save() {
            this.$http.get('doctor/disease/' + this.doctor_id).then(function (res) {
                this.$set('data', res.data.disease);
            });
        }
    },
    watch: {
        'doctor_id': function doctor_id(value) {
            if (value) {
                this.save();
            }
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"disease_count\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">疾病统计</h4>\n      </div>\n      <div class=\"modal-body\">\n        <div class=\"user_table_box table-responsive\">\n          <table class=\"table table-bordered check_list\">\n            <thead>\n              <tr>\n                <th class=\"col-sm-1\">疾病</th>\n                <th class=\"col-sm-1\">痊愈数</th>\n                <th class=\"col-sm-1\">明显好转</th>\n                <th class=\"col-sm-1\">好转</th>\n                <th class=\"col-sm-1\">没变化</th>\n              </tr>\n            </thead>\n            <tbody>\n              <tr v-if=\"data.length\" v-for=\"val in data\">\n                <td>{{val[0]}}</td>\n                <td>{{val[1]}}</td>\n                <td>{{val[2]}}</td>\n                <td>{{val[3]}}</td>\n                <td>{{val[4]}}</td>\n              </tr>\n            </tbody>\n          </table>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-092fe529", module.exports)
  } else {
    hotAPI.update("_v-092fe529", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],79:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    data: function data() {
        return {
            data: {},
            allDisease: {},
            name: '',
            sid: 0,
            diseaseArr: [],
            diseaseStr: ''
        };
    },
    ready: function ready() {
        this.getDisease();
    },

    methods: {
        getDisease: function getDisease() {
            this.$http({ url: 'disease/index', method: 'GET', params: { noPage: true } }).then(function (res) {
                this.allDisease = res.data.diseases;
            });
        },
        checkAttr: function checkAttr(id) {
            var _this = this;
            var index = $.inArray(id, _this.diseaseArr);
            if (index == -1) {
                $(".checked" + id).prop("checked", true);
                this.diseaseArr.push(id);
            } else {
                $(".checked" + id).prop("checked", false);
                this.diseaseArr.splice(index, 1);
            }
            console.log(this.diseaseArr);
        },
        store: function store() {
            this.$http({ url: 'doctor/addisease2/' + this.id, method: 'PUT', params: { data: this.diseaseStr, type: 'disease' } }).then(function (res) {
                if (res.data.status == 1) {
                    $("#exportcheck").modal("hide");
                    this.diseaseArr = [];
                    this.$dispatch("refreshList");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"exportcheck\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <div class=\"col-sm-6\"><span>输入擅长疾病，并用中文逗号分隔</span>\n              <input v-model=\"diseaseStr\" type=\"text\" name=\"checkboxName\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" @click=\"store()\" class=\"btn btn-primary\">保存</button>\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-bbfd07a0", module.exports)
  } else {
    hotAPI.update("_v-bbfd07a0", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],80:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getData();
    },
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        getData: function getData() {
            this.$http.get('express/company').then(function (res) {
                this.data = res.data;
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"express_company\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">快递公司</h4>\n      </div>\n      <div class=\"modal-body\">\n        <div class=\"find_table_box table-responsive\">\n          <table class=\"table table-bordered\">\n            <thead>\n              <tr>\n                <th>快递公司</th>\n                <th>快递公司编号</th>\n              </tr>\n            </thead>\n            <tbody v-if=\"data.resultcode == 200\">\n              <tr v-for=\"l in data.result\">\n                <td>{{l.com}}</td>\n                <td>{{l.no}}</td>\n              </tr>\n            </tbody>\n            <tbody v-if=\"data.resultcode != 200\">\n              <tr>\n                <td>{{data.reason}}</td>\n              </tr>\n            </tbody>\n          </table>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-71cd810b", module.exports)
  } else {
    hotAPI.update("_v-71cd810b", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],81:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['val'],
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        save: function save() {
            this.$http.post('config', this.val).then(function (res) {
                if (res.data.status) {
                    this.$dispatch('refreshList');
                    $('#information').modal('hide');
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"information\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改门店</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" v-if=\"val.type ==0\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">痊愈：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N1\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">明显好转：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N2\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">好转：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N3\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">没变化：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N4\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n        <form role=\"form\" v-if=\"val.type ==1\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">免费取消次数：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.M\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">免费取消预约时间N：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">24h &lt; 实际时间 &lt; N：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N1\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">12h &lt; 实际时间 &lt; 24：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N2\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">12h &gt; 实际时间：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N3\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">实际时间 &gt; 预约时间：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.info.N4\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"save()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-31e1af09", module.exports)
  } else {
    hotAPI.update("_v-31e1af09", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],82:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['val'],
    methods: {
        save: function save() {
            this.$http.post('medicine/save', this.val).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.status) {
                    this.$dispatch('userupdate');
                    $('#medicinal_type').modal('hide');
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"medicinal_type\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改门店</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">名称：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.name\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">单位：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.unit\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">价格：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.amount\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"save()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-0131ca96", module.exports)
  } else {
    hotAPI.update("_v-0131ca96", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],83:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['cur', 'all'],
    data: function data() {
        return {};
    },

    computed: {
        indexs: function indexs() {
            var left = 1;
            var right = this.all;
            var ar = [];
            if (this.all >= 11) {
                if (this.cur > 5 && this.cur < this.all - 4) {
                    left = this.cur - 5;
                    right = this.cur + 4;
                } else {
                    if (this.cur <= 5) {
                        left = 1;
                        right = 10;
                    } else {
                        right = this.all;
                        left = this.all - 9;
                    }
                }
            }
            while (left <= right) {
                ar.push(left);
                left++;
            }
            return ar;
        }
    },
    methods: {
        btnClick: function btnClick(data) {
            if (data != this.cur) {
                this.cur = data;
                this.$dispatch('btn-click', data);
            }
        },
        goPage: function goPage() {
            var page = $.trim($('input[type=number]').val());
            var preg = /^[1-9]\d*$/;
            if (page == '' || !preg.test(page)) {
                return false;
            }
            this.$dispatch('gopage', page);
        }
    },
    watch: {
        cur: function cur(oldValue, newValue) {
            //console.log(arguments);
            //this.$dispatch('btn-click', newValue)
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<nav class=\"hasInput\">\n  <div class=\"input\">\n    <input type=\"number\" min=\"1\" v-bind:max=\"all\"><span @click=\"goPage()\">Go</span>\n  </div>\n  <ul class=\"pagination\">\n    <li v-if=\"cur!=1\" class=\"disabled\"><a v-on:click=\"btnClick(cur-1)\">«</a></li>\n    <li v-for=\"index in indexs\" v-bind:class=\"{ active: cur == index}\"><a v-on:click=\"btnClick(index)\">{{ index }}</a></li>\n    <li v-if=\"cur!=all\"><a v-on:click=\"btnClick(cur+1)\">»</a></li>\n    <li><a>共<i>{{all}}</i>页</a></li>\n  </ul>\n</nav>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-1e628c6a", module.exports)
  } else {
    hotAPI.update("_v-1e628c6a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],84:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['val'],
    data: function data() {
        return {};
    },

    methods: {
        send: function send(type) {
            var preg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
            if (!preg.test(this.val.medicine_price)) {
                layer.msg('请输入正确的金额格式 -- 药材费');
                return;
            }
            if (!preg.test(this.val.dispensing_price)) {
                layer.msg('请输入正确的金额格式 -- 调剂费');
                return;
            }
            var data = {};
            data.medicine_price = this.val.medicine_price;
            data.dispensing_price = this.val.dispensing_price;
            data.recipe_head = this.val.recipe_head;
            data.recipe_head.sumWeight = this.val.recipe_head.sumWeight;
            data.recipe_head.dayNum = this.val.recipe_head.dayNum;
            data.recipe_head.takingNum = this.val.recipe_head.takingNum;
            data.recipe_head.sum = this.val.recipe_head.sum;
            if (type == 1) {
                data.send = 1;
                data.is_price = 1;
            } else if (type == 0) {
                data.is_price = 1;
            } else {
                data.is_price = 9;
            }
            this.$http({ url: 'prescription/setprice/' + this.val.id, method: "PUT", params: { data: data } }).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.errcode == 200) {
                    this.$dispatch('update');
                    this.init();
                    $('#price_detail').modal('hide');
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"price_detail\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <label class=\"modal-title\">详情</label>\n        <form role=\"form\" class=\"form-horizontal\">\n          <div style=\"padding-left: 15px;\" class=\"form-group\"><span>\n              <p>姓名 : {{val.user.realname}}</p>\n              <p>性别 : {{val.user.sex}}</p>\n              <p>出生日期 : {{val.user.birthday}}</p></span></div>\n          <div class=\"form-group\">\n            <div class=\"col-sm-8\">\n              <p>共{{val.recipe_head.sum}} 剂，每日{{val.recipe_head.dayNum}}剂，1剂分{{val.recipe_head.takingNum}}次服用</p><span v-for=\"item in val.recipe\">\n                <p>{{item.name}} {{item.dosage}}g  {{item.other}}</p></span>\n              <!--span\n              p 划价人 {{val.admin.user_name}}\n              p 药费 {{val.medicine_price}}\n              p 调剂费 {{val.dispensing_price}}\n              p 代煎费 {{val.tisane_price}}\n              p(v-if=\"val.tisane ==0 && val.recipe_self.length >0\") 自备费 {{val.recipe_self_price}}\n              //p 划价时间 {{val.price_time}}\n              --><span>\n                <div class=\"col-sm-8\"></div>\n                <!--label(v-if=\"val.type\",style=\"margin-top:10px\") 剂量input.form-control(type=\"number\",v-model=\"val.recipe_head.sum\")\n                -->\n                <label style=\"margin-top:10px\">药材费(总价)\n                  <input type=\"text\" v-model=\"val.medicine_price\" readonly=\"readonly\" class=\"form-control\">\n                </label>\n                <label style=\"margin-top:10px\">调剂费(总价)\n                  <input type=\"text\" v-model=\"val.dispensing_price\" readonly=\"readonly\" class=\"form-control\">\n                </label>\n                <label style=\"margin-top:10px\">代煎费(总价)\n                  <input type=\"text\" v-model=\"val.tisane_price\" readonly=\"readonly\" class=\"form-control\">\n                </label>\n                <label>\n                  <div v-if=\"val.tisane ==0 &amp;&amp; val.recipe_self.length >0\" style=\"margin-top:10px\" class=\"col-sm-8\">自备费(总价)</div>\n                  <input v-if=\"val.tisane ==0 &amp;&amp; val.recipe_self.length >0\" type=\"text\" v-model=\"val.recipe_self_price\" readonly=\"readonly\" class=\"form-control\">\n                </label>\n                <!--    .col-sm-8(style=\"margin-top:10px\") 药材总重(g)-->\n                <!--    input.form-control(type=\"text\",v-model=\"val.recipe_head.sumWeight\")-->\n                <!--    .col-sm-8(style=\"margin-top:10px\") 每日服用次数-->\n                <!--    input.form-control(type=\"text\",v-model=\"val.recipe_head.dayNum\")-->\n                <!--    .col-sm-8(style=\"margin-top:10px\") 一剂服用次数-->\n                <!--    input.form-control(type=\"text\",v-model=\"val.recipe_head.takingNum\")-->\n                <!--.col-sm-8(style=\"margin-top:10px;margin-left:-10px;\")-->\n                <!--    button.btn.btn-primary(type='button', v-on:click=\"send(1)\") 保存并发送-->\n                <!--    button.btn.btn-primary(type='button', v-on:click=\"send(0)\") 保存-->\n                <!--    button.btn.btn-primary(type='button', v-on:click=\"send(2)\") 拒绝-->\n                <!--    button.btn.btn-default(type='button', data-dismiss='modal') 取消--></span>\n            </div>\n          </div>\n        </form>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-455d127a", module.exports)
  } else {
    hotAPI.update("_v-455d127a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],85:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['id'],
    data: function data() {
        return {
            bespeak: {},
            bespeakUser: {},
            bespeakDoctor: {},
            doctor: {},
            section: {},
            record: {},
            section_id: 0,
            page: 1,
            cur: 1,
            all: 1,
            total: 1
        };
    },

    methods: {
        sub: function sub(type, doc_id) {
            this.$http({
                url: "bespeaks/update/" + this.id,
                method: "PUT",
                params: { type: type, doctor_id: doc_id }
            }).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.errcode == 200) {
                    this.getReferral(this.id);
                    this.$dispatch("update");
                }
            });
        },
        next: function next() {
            this.page++;
            if (this.page > this.all) {
                this.page = 1;
            }
            this.getReferral(this.id);
        },
        getReferral: function getReferral(id) {
            this.$http({ url: 'bespeaks/show/' + id, params: { section: this.section_id, page: this.page } }).then(function (res) {
                this.$set('bespeak', res.data.bespeak);
                this.$set('bespeakUser', res.data.bespeak.user);
                this.$set('bespeakDoctor', res.data.bespeak.doctor);
                this.$set('section', res.data.section);
                this.$set('doctor', res.data.doctor.data);
                var pagination = res.data.doctor;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.last_page);
                this.$set('total', pagination.total);
                this.section.unshift({ id: 0, name: "全部" });
            });
        },
        sectionClick: function sectionClick(id) {
            this.section_id = id;
            this.page = 1;
            this.getReferral(this.id);
        },

        bg: function bg(url) {
            if (url) return 'background-image:url(' + url + ')';
        },
        cancel: function cancel() {
            $('#referral').modal('hide');
        }
    },
    watch: {
        'id': function id(value) {
            if (value > 0) {
                this.getReferral(value);
            }
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"referral\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">{{bespeakUser.nickname}} 的问诊单</h4><br>\n        <!--form.form-horizontal(role='form')\n        .form-group\n            label.col-sm-10(for='') 转诊记录\n            .col-sm-10(v-for=\"val in record\")\n                p {{val.doctor.name}}\n                p(v-if=\"val.status ==6\") 拒诊\n                p(v-else) 未接诊\n        -->\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-10\">疾病描述：</label>\n            <div v-if=\"bespeak.redundant_first==0\" class=\"col-sm-10\"><span v-for=\"img in bespeak.disease\"><a v-bind:href=\"img\"><img v-bind:src=\"img\" style=\"width:30%;margin-left:5px;margin-top:5px;\"></a></span></div>\n            <div v-if=\"bespeak.redundant_first==1\" class=\"col-sm-10\">\n              <p>{{bespeak.disease}}</p>\n            </div>\n          </div>\n        </form>\n        <hr>\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-12\">推荐医师科室筛选</label>\n            <div v-for=\"val in section\" @click=\"sectionClick(val.id)\" style=\"margin-bottom:5px\" class=\"col-sm-2\"><span class=\"checked{{val.id}}\"><a class=\"color\">{{val.name}}</a></span></div>\n          </div>\n          <table class=\"table table-bordered check_list user_table_box\">\n            <thead>\n              <tr>\n                <th class=\"col-sm-3\">医师</th>\n                <th class=\"col-sm-3\">联系方式</th>\n                <th class=\"col-sm-3\"><span> 操作</span><span>&nbsp;&nbsp;&nbsp;</span><span @click=\"next()\"><a class=\"color\">换一批</a></span></th>\n              </tr>\n            </thead>\n            <tbody>\n              <tr v-for=\"item in doctor\" v-bind:class=\"{ check_background_red  : bespeak.doctor_id == item.id}\">\n                <td>{{item.name}}</td>\n                <td>{{item.mobile}}</td>\n                <td>\n                  <!--span.col(v-on:click=\"sub('sub_sub',item.id)\") 推送问诊单--><span v-on:click=\"sub('suc_sub',item.id)\" class=\"col\">确认转诊</span>\n                  <!--span.col(v-on:click=\"cancel()\") 取消-->\n                </td>\n              </tr>\n            </tbody>\n          </table>\n        </form>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-3ec2a890", module.exports)
  } else {
    hotAPI.update("_v-3ec2a890", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],86:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['order'],
    data: function data() {
        return {
            price: ''
        };
    },

    methods: {
        enter: function enter() {
            var params = {};
            var preg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
            if (!preg.test(this.price)) {
                layer.msg('请输入正确的金额格式');
                return;
            }
            if (this.price < 0) {
                layer.msg('金额不能少于0元');
                return;
            }
            params.refund_amount = this.price;
            params.order_id = this.order.id;
            this.$http({ url: 'order/refund', method: 'get', params: params }).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.status) {
                    this.$dispatch('update');
                    this.price = '';
                    $('#refund').modal('hide');
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"refund\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">退款</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">实付金额：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"order.pay_amount\" readonly=\"readonly\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">退款金额：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"price\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"enter()\" class=\"btn btn-primary\">自定义退款</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-ad55bcaa", module.exports)
  } else {
    hotAPI.update("_v-ad55bcaa", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],87:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['item'],
    data: function data() {
        return {
            item: {}
        };
    },

    computed: {
        _sex: function _sex() {
            return this.item.sex == '未知' ? 0 : this.item.sex == '男' ? 1 : 2;
        }
    },
    methods: {
        sub: function sub() {
            this.$http.post('appuser/edit', this.item).then(function (res) {
                if (res.data.status) {
                    this.$dispatch("refreshList");
                    $('#save_appuser').modal('hide');
                }
            }, function (res) {});
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"save_appuser\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改信息</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">真实姓名：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"item.realname\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">手机号：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"item.mobile\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">生日：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"date\" v-model=\"item.birthday\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">性别：</label>\n            <div class=\"col-sm-10\">\n              <select type=\"text\" v-model=\"item.sex\" class=\"form-control\">\n                <option selected=\"selected\">未知</option>\n                <option>男</option>\n                <option>女</option>\n              </select>\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">身高：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"number\" v-model=\"item.height\" placeholder=\"cm\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">体重：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"number\" v-model=\"item.weight\" placeholder=\"kg\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">身份证号：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"number\" v-model=\"item.pincode\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"sub()\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-2f5e019d", module.exports)
  } else {
    hotAPI.update("_v-2f5e019d", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],88:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        add: function add() {
            this.$http.post('section/add', this.data).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$set('data', {});
                    this.$dispatch("add");
                    $("#sectionadd").modal("hide");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"sectionadd\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">新建科室</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">名称：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"data.name\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"add()\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-129c60a4", module.exports)
  } else {
    hotAPI.update("_v-129c60a4", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],89:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['val'],
    data: function data() {
        return {
            data: {}
        };
    },

    methods: {
        save: function save() {
            this.val.type = 'add';
            this.$http.put('section/update', this.val).then(function (res) {
                if (res.data.status) {
                    layer.msg('操作成功');
                    $('#section_update').modal('hide');
                } else {
                    layer.msg(res.data.msg);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"section_update\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改门店</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-3 control-label\">名称：</label>\n            <div class=\"col-sm-8\">\n              <input type=\"text\" v-model=\"val.name\" class=\"form-control\">\n            </div>\n          </div>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" v-on:click=\"save()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-523fa516", module.exports)
  } else {
    hotAPI.update("_v-523fa516", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],90:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['order'],
    watch: {
        order: function order() {
            this.getData();
        }
    },
    data: function data() {
        return {
            data: {},
            list: []
        };
    },

    methods: {
        getData: function getData() {
            this.$http.get('express/see/' + this.order.id).then(function (res) {
                this.data = res.data;
                if (res.data.result && res.data.result.status) {
                    this.list = res.data.result.list;
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"see_express\" class=\"modal fade\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">物流状态</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流公司：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"order.express_company\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>物流单号：</span></label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"order.express_number\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\"><span>快递进度：</span></label>\n            <div v-if=\"data.resultcode == 200\" class=\"col-sm-10\">\n              <div v-for=\"mes in list\" class=\"lists\">\n                <p class=\"time\">{{mes.datetime}}</p>\n                <p>{{mes.remark}}</p>\n              </div>\n            </div>\n            <div v-if=\"data.resultcode != 200\" class=\"col-sm-10\">\n              <div class=\"lists\">\n                <p>{{data.reason}}</p>\n              </div>\n            </div>\n          </div>\n        </form>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-5d563d61", module.exports)
  } else {
    hotAPI.update("_v-5d563d61", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],91:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    ready: function ready() {},
    data: function data() {
        return {
            data: {},
            filePath: ''
        };
    },

    methods: {
        add: function add() {
            this.data.image = this.filePath;
            this.$http.post('slider/add', this.data).then(function (res) {
                if (res.data.status) {
                    $('#slider_add').modal('hide');
                    this.filePath = '';
                    this.data = {};
                    this.$dispatch('refreshList');
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        uploadFile: function uploadFile(e) {
            var self = this;
            //dosomthing
            var that = e.target;
            var fd = new FormData();
            fd.append("image", that.files[0]);
            $.ajax({
                url: "/api/upload/qiniu",
                type: "POST",
                processData: false,
                contentType: false,
                data: fd,
                success: function success(ret) {
                    console.log(ret.data.image_thumb_url);
                    self.filePath = 'http://static.taiheguoyi.com/' + ret.data.image_thumb_url;
                    console.log(self.filePath);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"slider_add\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">添加轮播</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">名称：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"data.title\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">描述：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"data.desc\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">链接地址：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"data.url\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\">图片上传：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"file\" name=\"image\" v-on:change=\"uploadFile($event)\"><img v-bind:src=\"filePath\">\n            </div>\n          </div>\n          <p>上传图片尺寸大小为：640*300</p>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" @click=\"add()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-60046776", module.exports)
  } else {
    hotAPI.update("_v-60046776", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],92:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    props: ['val'],
    ready: function ready() {},
    data: function data() {
        return {};
    },

    methods: {
        save: function save() {
            this.$http.put('slider/update', this.val).then(function (res) {
                if (res.data.status) {
                    $('#slider_detail').modal('hide');
                    this.$dispatch('refreshList');
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        uploadFile: function uploadFile(e) {
            var self = this;
            //dosomthing
            var that = e.target;
            var fd = new FormData();
            fd.append("image", that.files[0]);
            $.ajax({
                url: "/api/upload/qiniu",
                type: "POST",
                processData: false,
                contentType: false,
                data: fd,
                success: function success(ret) {
                    console.log(ret.data.image_thumb_url);
                    self.val.image = 'http://static.taiheguoyi.com/' + ret.data.image_thumb_url;
                    console.log(self.val.image);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div id=\"slider_detail\" class=\"modal fade\">\n  <div class=\"modal-dialog modal-lg\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <button type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\" class=\"close\"><span aria-hidden=\"true\">×</span></button>\n        <h4 class=\"modal-title\">修改轮播</h4>\n      </div>\n      <div class=\"modal-body\">\n        <form role=\"form\" class=\"form-horizontal\">\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">名称：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"val.title\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">描述：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"val.desc\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label class=\"col-sm-2 control-label\">链接地址：</label>\n            <div class=\"col-sm-10\">\n              <input type=\"text\" v-model=\"val.url\" class=\"form-control\">\n            </div>\n          </div>\n          <div class=\"form-group\">\n            <label for=\"\" class=\"col-sm-2 control-label\">图片上传：\n              <div style=\"transform: translate(150px,-40px);\" class=\"col-sm-10\">\n                <input type=\"file\" name=\"image\" v-on:change=\"uploadFile($event)\"><img v-bind:src=\"val.image\" style=\"transform: translate(10px,18px);\">\n              </div>\n            </label>\n          </div>\n          <p>上传图片尺寸大小为：640*300</p>\n        </form>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" @click=\"save()\" class=\"btn btn-primary\">保存</button>\n      </div>\n    </div>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-02d0308c", module.exports)
  } else {
    hotAPI.update("_v-02d0308c", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],93:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getOperations(1);
    },
    data: function data() {
        return {
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            name: '',
            status: 0
        };
    },
    events: {
        operation: function operation() {
            this.getOperations(1); //222
        }
    },
    methods: {
        getOperations: function getOperations(page) {
            this.$http({
                url: 'operation/list',
                method: 'GET',
                params: { page: page }
            }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        listen: function listen(data) {
            this.getOperations(data);
        },
        del: function del(id) {
            this.$http.delete('operation/del/' + id).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    this.$dispatch('operation');
                    this.$dispatch('count');
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        read: function read(id) {
            this.$http.get('operation/read/' + id).then(function (res) {
                layer.msg(res.data.msg);
                this.$dispatch('operation');
                this.$dispatch('count');
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">操作日志</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <!--form.form-horizontal(role='form')-->\n  <!--    .form-group-->\n  <!--        label.col-sm-1.control-label.zdfl  关键词搜索：-->\n  <!--        .col-sm-4-->\n  <!--            .input-group-->\n  <!--                input.form-control.auto_inp(type=\"search\", v-model=\"name\",placeholder=\"请输入关键词\")-->\n  <!--                .input-group-btn(@click=\"getService(1)\")-->\n  <!--                    .btn.btn-default-->\n  <!--                        i.icon-search-->\n  <!--                .pull-right.col-sm-9-->\n  <!--                    .search_num-->\n  <!--                        .more_search.btn.btn-default-->\n  <!--                            span 共 {{total}} 条记录-->\n  <div class=\"new_item\">\n    <div class=\"item_list\">\n      <div class=\"list\">\n        <div class=\"find_table_box table-responsive\">\n          <table class=\"table table-bordered\">\n            <thead>\n              <tr>\n                <th>序号</th>\n                <th>发送人</th>\n                <th>操作内容</th>\n                <th>接收人</th>\n                <th>添加时间</th>\n                <th>操作</th>\n              </tr>\n            </thead>\n            <tbody>\n              <tr v-for=\"(index,d) in data\">\n                <td>{{index+1}}</td>\n                <td>{{d.send_people}}</td>\n                <td>{{d.operation_detail}}</td>\n                <td>{{d.receive_people}}</td>\n                <td>{{d.created_at}}</td>\n                <td class=\"com_new\"><a @click=\"del(d.id)\" class=\"btn btn-primary\">删除</a><span class=\"control\">|</span><a v-if=\"d.read_flag==0\" @click=\"read(d.id)\" class=\"btn btn-primary\">标记为已读</a><a v-else=\"v-else\" class=\"btn btn-primary\">已读</a></td>\n              </tr>\n            </tbody>\n          </table>\n        </div>\n      </div>\n    </div>\n  </div>\n  <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-1037f943", module.exports)
  } else {
    hotAPI.update("_v-1037f943", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],94:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _calendar = require('../../js/calendar.vue');

var _calendar2 = _interopRequireDefault(_calendar);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        calendar: _calendar2.default
    },
    created: function created() {},
    ready: function ready() {
        headNav(4);
    },
    data: function data() {
        return {
            total: '',
            discount: '',
            url: '',
            calendar: {
                show: false,
                x: 0,
                y: 0,
                picker: "",
                type: "date",
                value: "",
                begin: "",
                end: "",
                //weeks: [],
                months: [],
                range: false,
                items: {
                    // 日期时间模式、、
                    picker3: {
                        type: "datetime",
                        value: "",
                        sep: "-"
                    }, picker2: {
                        type: "datetime",
                        value: "",
                        sep: "-"
                    }
                }
            }
        };
    },

    methods: {
        saveGood: function saveGood() {
            var vue = this;
            var obj = $("form").serializeArray();
            vue.$http.post('promo/add', obj).then(function (res) {
                if (res.data.status == 1) {
                    layer.msg(res.data.msg);
                    vue.$router.go("/promocode_list/1");
                } else {
                    layer.msg(res.data.msg);
                }
            }, function (res) {
                var data = res.data;
                errorMsg(data.errors);
            });
        },
        goback: function goback() {
            this.$router.go("/promocode_list/1");
        },
        open: function open(e, type) {
            // 设置类型123
            this.calendar.picker = type;
            this.calendar.type = this.calendar.items[type].type;
            this.calendar.range = this.calendar.items[type].range;
            this.calendar.begin = this.calendar.items[type].begin;
            this.calendar.end = this.calendar.items[type].end;
            this.calendar.value = this.calendar.items[type].value;
            // 可不用写
            this.calendar.sep = this.calendar.items[type].sep;
            this.calendar.weeks = this.calendar.items[type].weeks;
            this.calendar.months = this.calendar.items[type].months;

            this.calendar.show = true;
            this.calendar.x = e.target.offsetLeft;
            this.calendar.y = e.target.offsetTop + e.target.offsetHeight + 8;
        }
    },
    watch: {
        'calendar.value': function calendarValue(value) {
            this.calendar.items[this.calendar.picker].value = value;
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">添加优惠码</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠码类型：</span></label>\n        <div class=\"col-sm-5\">\n          <input type=\"text\" value=\"字母+数字\" readonly=\"readonly\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠码长度：</span></label>\n        <div class=\"col-sm-5\">\n          <input type=\"text\" value=\"6位\" readonly=\"readonly\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠码数量：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"total\" type=\"text\" placeholder=\"请输入优惠码数量\" v-model=\"total\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠金额：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"discount\" type=\"text\" placeholder=\"请输入优惠金额\" v-model=\"discount\" class=\"form-control\">\n        </div>\n      </div>\n      <!--.form-group-->\n      <!--    label.col-sm-1.control-label(for='')-->\n      <!--        span 活动链接：-->\n      <!--    .col-sm-5-->\n      <!--        input.form-control(name=\"url\" type=\"text\",placeholder=\"请输入活动链接\" v-model=\"url\")-->\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>开始时间：</span></label>\n        <div class=\"col-sm-5\">\n          <!--input.form-control.time_date(name=\"goods_start\" type=\"date\",placeholder=\"请输入邀请开始时间\"  v-model=\"good.goods_start\")-->\n          <input type=\"text\" readonly=\"readonly\" name=\"start_time\" @click.stop=\"open($event,'picker2')\" v-model=\"calendar.items.picker2.value\" placeholder=\"请输入邀请开始时间\" class=\"form-control time_date\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>截止时间：</span></label>\n        <div class=\"col-sm-5\">\n          <!--input.form-control.time_date(name=\"goods_end\" type=\"date\",placeholder=\"请输入邀请截止时间\" v-model=\"good.goods_end\")-->\n          <input type=\"text\" readonly=\"readonly\" name=\"end_time\" @click.stop=\"open($event,'picker3')\" v-model=\"calendar.items.picker3.value\" placeholder=\"请输入邀请截止时间\" class=\"form-control time_date\">\n          <calendar :show.sync=\"calendar.show\" :type=\"calendar.type\" :value.sync=\"calendar.value\" :x=\"calendar.x\" :y=\"calendar.y\" :begin.sync=\"calendar.begin\" :end.sync=\"calendar.end\" :range.sync=\"calendar.range\" :months=\"calendar.months\"></calendar>\n        </div>\n      </div>\n      <div class=\"form-group btn_box\">\n        <button type=\"button\" @click=\"goback()\" class=\"btn btn-lg btn-default\">取消\n          </button><button id=\"subUpload\" type=\"button\" @click=\"saveGood()\" class=\"btn btn-lg btn-primary\">添加</button>\n        \n      </div>\n    </form>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-706d2521", module.exports)
  } else {
    hotAPI.update("_v-706d2521", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../js/calendar.vue":9,"vue":7,"vue-hot-reload-api":3}],95:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getCode();
    },
    ready: function ready() {
        headNav(4);
    },
    data: function data() {
        return {
            codes: {},
            cur: '',
            all: '',
            id: ''
        };
    },

    watch: {},
    events: {
        update: function update() {
            this.getCode(this.cur);
        }
    },
    methods: {
        sendCode: function sendCode(id) {
            this.$set('id', id);
            $("#sendcode").modal("show");
        },
        no: function no() {
            layer.msg('活动优惠码已发完');
        },
        fail: function fail() {
            layer.msg('活动优惠码已过期');
        },
        getCode: function getCode() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'promo/list', method: 'GET', params: { page: this.page, search: this.searchs } }).then(function (res) {
                this.$set('codes', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getCode(data);
            this.$router.go({ name: 'promocode_list', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">优惠码列表</div>\n    </div>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-2\">活动名称</th>\n          <th class=\"col-sm-2\">优惠金额</th>\n          <th class=\"col-sm-3\">有效期</th>\n          <th class=\"col-sm-1\">数量</th>\n          <th class=\"col-sm-1\">发放数量</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,code) in codes\">\n          <td>{{index+1}}</td>\n          <td>{{code.name}}</td>\n          <td>￥{{code.discount}}</td>\n          <td>{{code.start_time}} - {{code.end_time}}</td>\n          <td>{{code.total}}</td>\n          <td>{{code.num}}</td>\n          <td><span v-if=\"code.end_time < code.nowtime &amp;&amp; code.num < code.total\" @click=\"fail(code.id)\">发放</span><span v-if=\"code.num >= code.total &amp;&amp; code.end_time > code.nowtime\" @click=\"no(code.id)\">发放</span><span v-if=\"code.end_time < code.nowtime &amp;&amp; code.num >= code.total\" @click=\"fail(code.id)\">发放</span><span v-if=\"code.end_time > code.nowtime &amp;&amp; code.num < code.total\" @click=\"sendCode(code.id)\">发放</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <pop-sendcode :id.sync=\"id\"></pop-sendcode>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-39b5fece", module.exports)
  } else {
    hotAPI.update("_v-39b5fece", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],96:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getCode();
    },
    ready: function ready() {
        headNav(4);
    },
    data: function data() {
        return {
            codes: {},
            search: {},
            cur: 0,
            all: 0,
            mobile: ''
        };
    },

    watch: {},
    events: {
        update: function update() {
            this.getCode(this.cur);
        }
    },
    methods: {
        getCode: function getCode() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.search.mobile = this.mobile;
            this.$http({ url: 'promo/record', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('codes', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        exportData: function exportData() {
            this.search.mobile = this.mobile;
            this.$http({
                url: 'export/code',
                method: 'GET',
                params: { search: this.search }
            }).then(function (res) {
                if (res.data.status == 1) {
                    location.href = "/api/upload/download/" + res.data.name;
                }
            });
        },
        listen: function listen(data) {
            this.getCode(data);
            this.$router.go({ name: 'promocode_mobile', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">活动管理</div>\n      <div class=\"pull-right\"><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n    </div>\n  </div>\n  <div id=\"searchList\" class=\"search_box\">\n    <dl>\n      <dt>筛选</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-3\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"mobile\" placeholder=\"输入手机号搜索\" class=\"form-control auto_inp\">\n            <div v-on:click=\"this.getCode();\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序</th>\n          <th class=\"col-sm-2\">手机号</th>\n          <th class=\"col-sm-2\">优惠码</th>\n          <th class=\"col-sm-2\">状态</th>\n          <th class=\"col-sm-2\">发放时间</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,code) in codes\">\n          <td>{{index+1}}</td>\n          <td>{{code.mobile}}</td>\n          <td>{{code.code}}</td>\n          <td v-if=\"code.status == 0\">未使用</td>\n          <td v-if=\"code.status == 1\">已使用</td>\n          <td>{{code.created_at}}</td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-4ef9f6d2", module.exports)
  } else {
    hotAPI.update("_v-4ef9f6d2", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],97:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _calendar = require('../../js/calendar.vue');

var _calendar2 = _interopRequireDefault(_calendar);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    created: function created() {
        this.id = this.$route.params.id;
    },
    ready: function ready() {
        headNav(4);
        this.daoru();
    },
    data: function data() {
        return {
            id: 0,
            code: {},
            total: '',
            discount: '',
            url: '',
            calendar: {
                show: false,
                x: 0,
                y: 0,
                picker: "",
                type: "date",
                value: "",
                begin: "",
                end: "",
                //weeks: [],
                months: [],
                range: false,
                items: {
                    // 日期时间模式、、
                    picker3: {
                        type: "datetime",
                        value: "",
                        sep: "-"
                    }, picker2: {
                        type: "datetime",
                        value: "",
                        sep: "-"
                    }
                }
            }
        };
    },

    methods: {
        daoru: function daoru() {
            var self = this;
            layui.use('upload', function () {
                layui.upload({
                    url: 'promo/addfile',
                    title: '导入手机号',
                    elem: '.file', //指定原始元素，默认直接查找class="layui-upload-file"
                    method: 'post',
                    type: 'file',
                    success: function success(res) {
                        layer.msg('文件上传成功');
                    }
                });
            });
        },
        detail: function detail(id) {
            if (id > 0) {
                this.$http.get('promo/detail/' + id).then(function (res) {
                    this.$set('code', res.data.data);
                });
            }
        },
        goback: function goback() {
            this.$router.go("/promocode_list");
        }
    },
    watch: {
        id: function id(newValue) {
            this.detail(newValue);
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">添加优惠码</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠码类型：</span></label>\n        <div class=\"col-sm-5\">\n          <input type=\"text\" value=\"字母+数字\" readonly=\"readonly\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠码长度：</span></label>\n        <div class=\"col-sm-5\">\n          <input type=\"text\" value=\"12位\" readonly=\"readonly\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠码数量：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"total\" type=\"text\" v-model=\"code.total\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>优惠金额：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"discount\" type=\"text\" v-model=\"code.discount\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>活动链接：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"url\" type=\"text\" v-model=\"code.url\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>开始时间：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"url\" type=\"text\" v-model=\"code.start_time\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>截止时间：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"url\" type=\"text\" v-model=\"code.end_time\" class=\"form-control\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>目标用户：</span></label>\n        <div class=\"col-sm-5\">\n          <input name=\"file\" type=\"file\" class=\"form-control file\">\n        </div>\n      </div>\n      <div class=\"form-group btn_box\">\n        <button type=\"button\" @click=\"goback()\" class=\"btn btn-lg btn-default\">取消\n          </button><button id=\"subUpload\" type=\"button\" @click=\"saveGood()\" class=\"btn btn-lg btn-primary\">添加</button>\n        \n      </div>\n    </form>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-33db98b8", module.exports)
  } else {
    hotAPI.update("_v-33db98b8", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../js/calendar.vue":9,"vue":7,"vue-hot-reload-api":3}],98:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    ready: function ready() {
        headNav(2);
    },
    created: function created() {
        this.id = this.$route.params.id;
        this.getInfo(this.id);
        this.getDetail(this.id);
    },
    data: function data() {
        return {
            family: {},
            lists: {},
            id: 0,
            info: {},
            question: ''
        };
    },

    events: {
        lndetail: function lndetail() {
            this.getInfo(this.id);
        }
    },
    methods: {
        setChange: function setChange(id, type, status) {
            if (status == 3) {
                layer.msg('已发送 不可修改');
                return;
            }
            var obj = {};
            obj.system = 'type';
            obj.param = { type: type };
            this.$http.post('law/update/' + id, obj).then(function (res) {
                layer.msg(res.data.msg);
            });
        },
        send: function send(id, status) {
            if (status == 3) {
                layer.msg('已发送');
                return;
            }
            var obj = {};
            obj.system = 'send';
            obj.param = { status: 3 };
            this.$http.post('law/update/' + id, obj).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.status == 200) {
                    this.$dispatch('refreshln');
                }
            });
        },
        getInfo: function getInfo(id) {
            this.$http.get('law/detail/' + id).then(function (res) {
                this.$set('lists', res.data.data);
                this.$set('family', res.data.family);
            });
        },
        getDetail: function getDetail(id) {
            this.$http.get('law/note/' + id).then(function (res) {
                this.$set('info', res.data.data);
            });
        },
        point: function point(id) {
            this.$http.get('law/forbid/' + id).then(function (res) {
                if (res.data.status) {
                    this.$set('id', id);
                    $("#point").modal("show");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        note: function note(id) {
            this.$http.get('law/forbid/' + id).then(function (res) {
                if (res.data.status) {
                    this.$set('id', id);
                    $("#addnote").modal("show");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        goback: function goback() {
            this.$router.go('/lnquiry_list');
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">问诊单详情</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span style=\"margin-right:33px;\">就诊人信息</span><span v-if=\"info.status != 3\">\n            <select v-model=\"info.type\" v-on:change=\"setChange(info.id,info.type,info.status)\" style=\"margin-right:20px;\" class=\"btn btn-primary\">\n              <option value=\"0\">未选择</option>\n              <option value=\"1\">温和型</option>\n              <option value=\"2\">增强型</option>\n            </select></span><span v-if=\"info.status != 3\" @click=\"point(info.id)\" style=\"margin-right:20px;\" class=\"btn btn-primary\">方案</span><span v-if=\"info.status != 3\" @click=\"note(info.id)\" style=\"margin-right:20px;\" class=\"btn btn-primary\">备注</span><span v-if=\"info.status != 3\" @click=\"send(info.id,info.status)\" class=\"btn btn-primary\">发送</span></label>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>真实姓名：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.realname}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>性别：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.sex}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>年龄：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.age}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>手机号码：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.mobile}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>身份证号：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.pincode}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>身高：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.height}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>体重：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.weight}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>国籍：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.country}}</h3>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>常居住地：</h3>\n        </label>\n        <div class=\"col-sm-5\">\n          <h3>{{family.province}}{{family.city}}{{family.area}}</h3>\n        </div>\n      </div>\n    </form>\n    <form class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span> 问诊单</span></label>\n      </div>\n    </form>\n    <form v-for=\"(index,list) in lists\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">\n          <h3>{{$index+1}}、 {{list.question}}</h3>\n        </label>\n      </div>\n      <div v-for=\"a in list.aid\" class=\"form-group\">\n        <ul>\n          <li v-if=\"list.type == 0\">\n            <input type=\"radio\" checked=\"checked\" style=\"margin-left:33px;\"><span style=\"margin-left:8px;\">{{a}}</span>\n          </li>\n          <li v-if=\"list.type == 1\">\n            <input type=\"checkbox\" checked=\"checked\" style=\"margin-left:33px;\"><span style=\"margin-left:8px;\">{{a}}</span>\n          </li>\n          <li v-if=\"list.type == 2\"><span style=\"margin-left: 35px;\">{{a}}</span>\n            <div v-if=\"list.qid ==1\" style=\"overflow hidden\"><img v-bind:src=\"list.face_img\" style=\"width:20%;margin-left:33px;\"><img v-bind:src=\"list.tongue_img\" style=\"width:20%;margin-left:20px;\"></div>\n          </li>\n        </ul>\n      </div>\n    </form>\n  </div>\n  <pop-point :id.sync=\"id\"></pop-point>\n  <pop-addnote :id.sync=\"id\"></pop-addnote>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-57b23a4d", module.exports)
  } else {
    hotAPI.update("_v-57b23a4d", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],99:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    ready: function ready() {
        headNav(3);
    },
    created: function created() {
        //ssssss
        this.page = this.$route.params.id;
        this.getList(); //22
    },
    data: function data() {
        return {
            lists: {},
            cur: 0,
            all: 0,
            total: 0,
            id: 0,
            goods_type: 0,
            doctor: '',
            status: 0,
            done: 2,
            realname: '',
            number: '',
            search: {}
        };
    },

    events: {
        refreshln: function refreshln() {
            this.getList(this.cur);
        }
    },
    methods: {
        setChange: function setChange(id, type, status) {
            if (status == 3) {
                layer.msg('已发送 不可修改');
                return;
            }
            var obj = {};
            obj.system = 'type';
            obj.param = { type: type };
            this.$http.post('law/update/' + id, obj).then(function (res) {
                layer.msg(res.data.msg);
            });
        },
        send: function send(id, status) {
            if (status == 3) {
                layer.msg('已发送');
                return;
            }
            var obj = {};
            obj.system = 'send';
            obj.param = { status: 3 };
            this.$http.post('law/update/' + id, obj).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.status == 200) {
                    this.$dispatch('refreshln');
                }
            });
        },
        point: function point(id) {
            this.$http.get('law/forbid/' + id).then(function (res) {
                if (res.data.status) {
                    this.$set('id', id);
                    $("#point").modal("show");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        note: function note(id) {
            this.$http.get('law/forbid/' + id).then(function (res) {
                if (res.data.status) {
                    this.$set('id', id);
                    $("#addnote").modal("show");
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        getList: function getList() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.search.goods_type = this.goods_type;
            this.search.doctor = this.doctor;
            this.search.status = this.status;
            this.search.realname = this.realname;
            this.search.number = this.number;
            this.search.done = this.done;
            this.search.doctor = this.doctor;
            this.$http({ url: 'law/index', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('lists', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        detail: function detail(id) {
            this.$http.get('law/deal/' + id).then(function (res) {
                if (!res.data.status) {
                    layer.msg(res.data.msg);
                }
            });
            this.$router.go({ name: 'proposed_detail', params: { id: id } });
        },
        listen: function listen(data) {
            this.getList(data);
            this.$router.go({ name: 'proposed_law', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">医师方案处理</div>\n    </div>\n  </div>\n  <div id=\"searchList\" class=\"search_box\">\n    <dl>\n      <dt>查询：</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <select v-model=\"goods_type\" v-on:change=\"getList()\" class=\"form-control\">\n            <option value=\"0\" selected=\"selected\">请选择购买类型</option>\n            <option value=\"1\">成人男</option>\n            <option value=\"2\">成人女</option>\n            <option value=\"3\">儿童</option>\n          </select>\n        </div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"done\" v-on:change=\"getList()\" class=\"form-control\">\n            <option selected=\"selected\" value=\"2\">请选择完成状态</option>\n            <option value=\"1\">未完成</option>\n            <option value=\"2\">已完成</option>\n          </select>\n        </div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"status\" v-on:change=\"getList()\" class=\"form-control\">\n            <option value=\"0\" selected=\"selected\">请选择处理状态</option>\n            <option value=\"1\">未处理</option>\n            <option value=\"2\">已处理</option>\n            <option value=\"3\">已发送</option>\n          </select>\n        </div>\n      </dd>\n    </dl>\n    <dl>\n      <dt>查询：</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"realname\" placeholder=\"请输入就诊人姓名\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getList()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"number\" placeholder=\"请输入订单编号\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getList()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"doctor\" placeholder=\"请输入处理人姓名\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getList()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-1\">就诊人</th>\n          <th class=\"col-sm-1\">购买类型</th>\n          <th class=\"col-sm-1\">敷贴类型</th>\n          <th class=\"col-sm-1\">穴位</th>\n          <th class=\"col-sm-1\">处理状态</th>\n          <th class=\"col-sm-1\">备注</th>\n          <th class=\"col-sm-1\">处理人</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,list) in lists\">\n          <td>{{index+1}}</td>\n          <td>{{list.name}}</td>\n          <td>{{list.goods_type}}</td>\n          <td>\n            <select v-model=\"list.type\" v-on:change=\"setChange(list.id,list.type,list.status)\">\n              <option value=\"0\">未选择</option>\n              <option value=\"1\">温和型</option>\n              <option value=\"2\">增强型</option>\n            </select>\n          </td>\n          <td>{{list.point_names}}</td>\n          <td>{{list.status_show}}</td>\n          <td>{{list.note}}</td>\n          <td>{{list.doctor_id}}</td>\n          <td><span @click=\"detail(list.id)\">查看</span><span v-if=\"list.status==2\" @click=\"point(list.id)\">方案</span><span v-else=\"v-else\" class=\"grey\">方案</span><span v-if=\"list.status==2\" @click=\"note(list.id)\">备注</span><span v-else=\"v-else\" class=\"grey\">备注</span><span v-if=\"list.status == 2 &amp;&amp; list.type != 0 &amp;&amp; list.point_names != ''\" @click=\"send(list.id,list.status)\">发送</span><span v-if=\"list.type == 0 || list.point_names == '' || list.status !=2 \" class=\"grey\">发送</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <pop-point :id.sync=\"id\"></pop-point>\n  <pop-addnote :id.sync=\"id\"></pop-addnote>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-47915dd6", module.exports)
  } else {
    hotAPI.update("_v-47915dd6", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],100:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {
            title: '',
            type: 0,
            necessary: 0,
            other: 0,
            perChoose: [{ 'answer': '' }],
            moreChoose: [{ 'answer': '' }],
            fill: ''
        };
    },

    methods: {
        goback: function goback() {
            this.$router.go('/lnquiry_list');
        },
        save: function save() {
            if (!this.title) {
                layer.msg('问题名称不能为空');
            }
            var obj = {};
            obj.title = this.title;
            obj.type = this.type;
            obj.necessary = this.necessary;
            obj.other = this.other;
            if (this.type == 0) {
                obj.content = this.perChoose;
            } else if (this.type == 1) {
                obj.content = this.moreChoose;
            }
            var _this = this;
            _this.$http.post('question/store/', obj).then(function (res) {
                if (res.data.status > 0) {
                    layer.msg('添加成功');
                    _this.$router.go('/question_list');
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        addQuestion: function addQuestion() {
            if (this.type == 0) {
                this.perChoose.push({ 'answer': '' });
            } else if (this.type == 1) {
                this.moreChoose.push({ 'answer': '' });
            }
        },
        closeper: function closeper(key) {
            this.perChoose.splice(key, 1);
        },
        closemore: function closemore(key) {
            this.moreChoose.splice(key, 1);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">添加问题</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>问题类型：</span></label>\n        <div class=\"col-sm-1\">\n          <select v-model=\"type\" class=\"form-control\">\n            <option value=\"0\">单选</option>\n            <option value=\"1\">多选</option>\n            <option value=\"2\">填空</option>\n          </select>\n        </div>\n        <label for=\"\" class=\"col-sm-1 control-label\"><span>是否必填：</span></label>\n        <div class=\"col-sm-1\">\n          <select v-model=\"necessary\" class=\"form-control\">\n            <option value=\"0\">否</option>\n            <option value=\"1\">是</option>\n          </select>\n        </div>\n        <label v-if=\"type < 2\" class=\"col-sm-1 control-label\"><span>是否有其他：</span></label>\n        <div v-if=\"type < 2\" class=\"col-sm-1\">\n          <select v-model=\"other\" class=\"form-control\">\n            <option value=\"0\">否</option>\n            <option value=\"1\">是</option>\n          </select>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">问题名称：</label>\n        <div class=\"col-sm-4\">\n          <input type=\"text\" name=\"title\" placeholder=\"请输入问题名称\" v-model=\"title\" class=\"form-control\">\n        </div>\n      </div>\n      <div v-if=\"type==0\" v-for=\"(i,p) in perChoose\" class=\"form-group\">\n        <div v-on:click=\"closeper(i)\" style=\"color:red\" class=\"close\">×</div>\n        <label for=\"\" class=\"col-sm-1 control-label\">问题答案{{i+1}}：</label>\n        <div class=\"col-sm-4\">\n          <input type=\"text\" name=\"answer\" placeholder=\"请输入问题答案\" v-model=\"p.answer\" class=\"form-control\">\n        </div>\n        <div @click=\"addQuestion()\" class=\"col-sm-1\">+</div>\n      </div>\n      <div v-if=\"type==1\" v-for=\"(index,m) in moreChoose\" class=\"form-group\">\n        <div v-on:click=\"closemore(index)\" style=\"color:red\" class=\"close\">×</div>\n        <label for=\"\" class=\"col-sm-1 control-label\">问题答案{{index+1}}：</label>\n        <div class=\"col-sm-4\">\n          <input type=\"text\" name=\"answer\" placeholder=\"请输入问题答案\" v-model=\"m.answer\" class=\"form-control\">\n        </div>\n        <div @click=\"addQuestion()\" style=\"color:green\" class=\"col-sm-1\">+</div>\n      </div>\n      <div class=\"form-group btn_box\">\n        <button type=\"button\" @click=\"goback()\" class=\"btn btn-default\">取消</button>\n        <button type=\"button\" @click=\"save()\" class=\"btn btn-primary\">添加</button>\n      </div>\n    </form>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-ce522456", module.exports)
  } else {
    hotAPI.update("_v-ce522456", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],101:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.id = this.$route.params.id;
        this.getInfo(this.id);
    },
    ready: function ready() {
        headNav(2);
    },
    data: function data() {
        return {
            question: '',
            type_id: 0,
            list: {},
            id: 0,
            type: '',
            necessary: 0
        };
    },

    events: {
        lnquestion: function lnquestion() {
            this.getInfo(this.id);
        }
    },
    methods: {
        getInfo: function getInfo(id) {
            this.$http.get('question/' + id).then(function (res) {
                this.$set('question', res.data.question.question);
                this.$set('type_id', res.data.question.type);
                this.$set('necessary', res.data.question.necessary);
                var type = res.data.question.type;
                this.returnType(type);
                this.$set('list', res.data.question.answer);
            });
        },
        returnType: function returnType(type) {
            if (type == 0) {
                this.type = '单选';
            } else if (type == 1) {
                this.type = '多选';
            } else if (type == 2) {
                this.type = '填空';
            }
        },
        goback: function goback() {
            this.$route.go('/lnquiry_list');
        },
        update: function update() {
            if (!this.id) {
                layer.msg('系统错误，请按F5刷新重试');
            }
            var obj = {};
            obj.question = this.question;
            obj.necessary = this.necessary;
            var id = this.id;
            this.$http.put('question/' + id, obj).then(function (res) {
                if (res.data.status) {
                    layer.msg(res.data.msg);
                }
                vue.$dispatch('lnquestion');
            });
        },
        order: function order(id) {
            var obj = {};
            obj.qid = this.id;
            obj.order = 1;
            var vue = this;
            vue.$http.put('answer/' + id, obj).then(function (res) {
                if (res.data.status) {
                    layer.msg(res.data.msg);
                }
                vue.$dispatch('lnquestion');
            });
        },
        qita: function qita() {
            layer.msg('其他是不能修改的'); //123123
            return false;
        },
        del: function del(id) {
            var vue = this;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消']
            }, function () {
                vue.$http.delete('answer/' + id).then(function (res) {
                    if (res.data.status) {
                        layer.msg(res.data.msg);
                        vue.$dispatch('lnquestion');
                    }
                });
            }, function () {});
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">问题详情</div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">问题类型:</label>\n        <div class=\"col-sm-1\"><span>{{type}}</span></div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">是否必填:</label>\n        <div class=\"col-sm-1\">\n          <select v-model=\"necessary\" class=\"form-control\">\n            <option value=\"0\">否</option>\n            <option value=\"1\">是</option>\n          </select>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">问题名称:</label>\n        <div class=\"col-sm-3\">\n          <input type=\"text\" name=\"question\" v-model=\"question\">\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\"></label>\n        <div class=\"col-sm-1\">\n          <button type=\"button\" @click=\"update()\" class=\"btn btn-primary\">修改</button>\n        </div>\n      </div>\n      <div class=\"form-group\"><span style=\"color:red\">* 修改问题后请点击修改对问题进行保存</span></div>\n      <div v-if=\"type_id<2\" class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">答案</label>\n      </div>\n      <div v-for=\"(index,a) in list\" class=\"form-group\">\n        <label v-if=\"type_id<2\" class=\"col-sm-1 control-label\">{{index+1}}、</label>\n        <div v-if=\"type_id<2\" class=\"col-sm-3\"><span>{{a.lid}}  ===&gt;</span><span onclick=\"textEdit(this,'{{a.id}}','answer')\">{{a.answer}}</span></div>\n        <div v-if=\"type_id<2\" class=\"col-sm-1\">\n          <div @click=\"order(a.id)\" class=\"close\">↑</div>\n        </div>\n        <div v-if=\"type_id<2\" class=\"col-sm-1\">\n          <div @click=\"del(a.id)\" class=\"close\">×</div>\n        </div>\n      </div>\n    </form>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-16707a8c", module.exports)
  } else {
    hotAPI.update("_v-16707a8c", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],102:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getQuestions();
    },
    ready: function ready() {
        headNav(3);
    },
    data: function data() {
        return {
            questions: {},
            cur: 0,
            all: 0
        };
    },

    events: {
        questionlist: function questionlist() {
            this.getQuestions(this.cur);
        }
    },
    methods: {
        getQuestions: function getQuestions() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'question/index', method: 'GET', params: { page: this.page } }).then(function (res) {
                this.$set('questions', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getQuestions(data);
            this.$router.go({ name: 'question_list', params: { id: data } });
        },
        checkDetail: function checkDetail(id) {
            this.$router.go({ name: 'question_answer', params: { id: id } });
        },
        del: function del(id) {
            var vue = this;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消']
            }, function () {
                vue.$http.delete('question/' + id).then(function (res) {
                    if (res.data.status) {
                        layer.msg(res.data.msg);
                        vue.$dispatch('questionlist');
                    }
                });
            }, function () {});
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <div class=\"tit_nav\">\n    <div class=\"container clearfix\">\n      <div class=\"pull-left\">问题列表</div>\n      <div class=\"pull-right\"><a v-link=\"{ path: '/question_add' }\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加问题</span></a></div>\n    </div>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered check_list\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-3\">名称</th>\n          <th class=\"col-sm-1\">类型</th>\n          <th class=\"col-sm-1\">是否必填</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(i,ln) in questions\">\n          <td>{{i+1}}</td>\n          <td>{{ln.question}}</td>\n          <td>{{ln.type}}</td>\n          <td>{{ln.necessary}}</td>\n          <td><span @click=\"checkDetail(ln.id)\">修改</span><span @click=\"del(ln.id)\" style=\"color:red\">删除</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-1e854c9a", module.exports)
  } else {
    hotAPI.update("_v-1e854c9a", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],103:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _disease = require("./module/disease.vue");

var _disease2 = _interopRequireDefault(_disease);

var _section_add = require("./module/section_add.vue");

var _section_add2 = _interopRequireDefault(_section_add);

var _section_update = require("./module/section_update.vue");

var _section_update2 = _interopRequireDefault(_section_update);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        disease: _disease2.default,
        sectionadd: _section_add2.default,
        sectionupdate: _section_update2.default
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getData(1);
    },
    ready: function ready() {
        headNav(4);
    },

    events: {
        add: function add() {
            this.getData(this.cur);
        }
    },
    data: function data() {
        return {
            data: {},
            val: {},
            disease: {},
            cur: 0,
            all: 0,
            id: 0
        };
    },

    methods: {
        save: function save(id, status) {
            var data = {};
            data.id = id;
            data.status = status;
            data.type = 'save';
            this.$http.put('section/update', data).then(function (res) {
                if (res.data.status) {
                    layer.msg('操作成功');
                    this.getData();
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        updates: function updates(val) {
            this.$set('val', val);
            $('#section_update').modal('show');
        },
        deletes: function deletes(id) {
            this.$http({
                url: 'section/del/' + id,
                method: 'delete'
            }).then(function (res) {
                if (res.data.status == 1) {
                    this.getData();
                }
            });
        },
        add: function add() {
            $('#sectionadd').modal('show');
        },
        sort: function sort(id, _sort) {
            this.$http({
                url: 'section/' + id,
                method: 'PUT',
                params: {
                    type: 'sort',
                    sort: _sort
                }
            }).then(function (res) {
                if (res.data.status == 1) {
                    this.getData();
                }
            });
        },
        show: function show(val) {
            this.val = val;
            $("#disease").modal("show");
        },
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'section/index', method: 'GET', params: { page: this.page } }).then(function (res) {
                this.$set('data', res.data.data);
                this.$set('cur', res.data.meta.pagination.current_page);
                this.$set('all', res.data.meta.pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'section_admin', params: { id: data } });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">科室管理</div>\n    <div class=\"pull-right\"><a @click=\"add()\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加科室</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">序号</th>\n          <th class=\"col-sm-1\">名称</th>\n          <!--th.col-sm-1 排序-->\n          <th class=\"col-sm-1\">是否展示</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{cur*10-9 + $index}}</td>\n          <td>{{val.name}}</td>\n          <td>\n            <select v-model=\"val.status\" v-on:change=\"save(val.id,val.status)\" class=\"form-control\">\n              <option value=\"1\">是</option>\n              <option value=\"0\">否</option>\n            </select>\n          </td>\n          <!--td\n          span(@click=\"sort(val.id,1)\") ↑\n          span(@click=\"sort(val.id,-1)\") ↓\n          -->\n          <td>\n            <!--span(@click=\"show(val)\") 查看疾病--><span @click=\"updates(val)\">修改</span>\n            <!--span(@click=\"deletes(val.id)\",style=\"color:red;\") 删除-->\n          </td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <disease :val.sync=\"val\"></disease>\n  <sectionadd></sectionadd>\n  <sectionupdate :val.sync=\"val\"></sectionupdate>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-ec287490", module.exports)
  } else {
    hotAPI.update("_v-ec287490", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/disease.vue":75,"./module/section_add.vue":88,"./module/section_update.vue":89,"vue":7,"vue-hot-reload-api":3}],104:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _calendar = require('../../js/calendar.vue');

var _calendar2 = _interopRequireDefault(_calendar);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//
exports.default = {
    components: {
        calendar: _calendar2.default
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getSend();
        this.getCity();
    },
    ready: function ready() {
        headNav(3);
    },
    data: function data() {
        return {
            sends: {},
            cur: '',
            all: '',
            total: '',
            mobile: '',
            status: '',
            number: '',
            startTime: '',
            endTime: '',
            city: 0,
            shipping_status: 5,
            search: {},
            id: '',
            cities: {},
            express_company: '',
            calendar: {
                show: false,
                x: 0,
                y: 0,
                picker: "",
                type: "date",
                value: "",
                begin: "",
                end: "",
                //weeks: [],
                months: [],
                range: false,
                items: {
                    // 日期时间模式、、
                    picker3: {
                        type: "datetime",
                        value: "",
                        sep: "-"
                    }, picker2: {
                        type: "datetime",
                        value: "",
                        sep: "-"
                    }
                }
            }
        };
    },

    events: {
        update: function update() {
            this.getSend();
        }
    },
    methods: {
        noteAdd: function noteAdd(id) {
            this.$set('id', id);
            $("#dealnote").modal("show");
        },
        getSend: function getSend() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.search.number = this.number;
            this.search.mobile = this.mobile;
            this.search.city = this.city;
            this.search.status = this.status;
            this.search.shipping_status = this.shipping_status;
            this.search.startTime = this.startTime;
            this.search.endTime = this.endTime;
            this.$http({ url: 'deal/send', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('sends', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        getCity: function getCity() {
            this.$http({
                url: 'deal/sendcity',
                method: 'GET'
            }).then(function (res) {
                this.$set('cities', res.data);
            });
        },
        exportData: function exportData() {
            this.search.number = this.number;
            this.search.mobile = this.mobile;
            this.search.city = this.city;
            this.search.status = this.status;
            this.$http({
                url: 'export/send',
                method: 'GET',
                params: { search: this.search }
            }).then(function (res) {
                if (res.data.status == 1) {
                    location.href = "/api/upload/download/" + res.data.name;
                }
            });
        },
        addlogistics: function addlogistics(id) {
            this.$set('id', id);
            $("#addlogistics").modal("show");
        },
        allogistics: function allogistics(id) {
            this.$set('id', id);
            $("#allogistics").modal("show");
        },
        updateLogistics: function updateLogistics(id) {
            this.$set('id', id);
            $("#logisticsupdate").modal("show");
        },
        listen: function listen(data) {
            this.getSend(data);
            this.$router.go({ name: 'send_list', params: { id: data } });
        },
        open: function open(e, type) {
            // 设置类型123
            this.calendar.picker = type;
            this.calendar.type = this.calendar.items[type].type;
            this.calendar.range = this.calendar.items[type].range;
            this.calendar.begin = this.calendar.items[type].begin;
            this.calendar.end = this.calendar.items[type].end;
            this.calendar.value = this.calendar.items[type].value;
            // 可不用写
            this.calendar.sep = this.calendar.items[type].sep;
            this.calendar.weeks = this.calendar.items[type].weeks;
            this.calendar.months = this.calendar.items[type].months;

            this.calendar.show = true;
            this.calendar.x = e.target.offsetLeft;
            this.calendar.y = e.target.offsetTop + e.target.offsetHeight + 8;
        }
    },
    watch: {
        city: function city() {
            this.getSend();
        },
        status: function status() {
            this.getSend();
        },
        shipping_status: function shipping_status() {
            this.getSend();
        },
        startTime: function startTime() {
            this.getSend();
        },
        endTime: function endTime() {
            this.getSend();
        },

        'calendar.items.picker2.value': function calendarItemsPicker2Value(newvalue, oldValue) {
            if (newvalue != oldValue) {
                this.$set('startTime', newvalue);
            }
        },
        'calendar.items.picker3.value': function calendarItemsPicker3Value(newvalue, oldValue) {
            if (newvalue != oldValue) {
                this.$set('endTime', newvalue);
            }
        },
        'calendar.value': function calendarValue(value) {
            this.calendar.items[this.calendar.picker].value = value;
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">发货列表</div>\n    <div class=\"pull-right\"><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div id=\"searchList\" class=\"search_box\">\n    <dl>\n      <dt>查询：</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input id=\"seaItem\" type=\"search\" v-model=\"mobile\" placeholder=\"输入手机号搜索\" class=\"form-control auto_inp\">\n            <div @click=\"getSend()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"number\" placeholder=\"输入订单编号搜索\" class=\"form-control auto_inp\">\n            <div @click=\"getSend()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"city\" name=\"city\" class=\"form-control first\">\n            <option value=\"0\" selected=\"selected\">请选择地区</option>\n            <option v-for=\"city in cities\" track-by=\"$index\" v-bind:value=\"city.province\">{{city.province}}</option>\n          </select>\n        </div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"status\" class=\"form-control\">\n            <option value=\"0\" selected=\"selected\">请选择购买类型</option>\n            <option value=\"1\">成人男</option>\n            <option value=\"2\">成人女</option>\n            <option value=\"3\">儿童</option>\n          </select>\n        </div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"shipping_status\" class=\"form-control\">\n            <option value=\"5\" selected=\"selected\">请选择发货状态</option>\n            <option value=\"0\">未发货</option>\n            <option value=\"1\">已发货</option>\n          </select>\n        </div>\n      </dd>\n    </dl>\n    <dl>\n      <dt>查询：</dt>\n      <dd class=\"row\">\n        <div style=\"width:500px;\" class=\"col-sm-2\">\n          <input style=\"width:200px\" type=\"text\" readonly=\"readonly\" name=\"startTime\" @click.stop=\"open($event,'picker2')\" v-model=\"calendar.items.picker2.value\" placeholder=\"请输入开始时间\" class=\"form-control pull-left time_date\"><span class=\"pull-left zhi\"> --</span>\n          <input style=\"width:200px\" type=\"text\" readonly=\"readonly\" name=\"endTime\" @click.stop=\"open($event,'picker3')\" v-model=\"calendar.items.picker3.value\" placeholder=\"请输入结束时间\" class=\"form-control pull-left time_date\">\n          <calendar :show.sync=\"calendar.show\" :type=\"calendar.type\" :value.sync=\"calendar.value\" :x=\"calendar.x\" :y=\"calendar.y\" :begin.sync=\"calendar.begin\" :end.sync=\"calendar.end\" :range.sync=\"calendar.range\" :months=\"calendar.months\"></calendar>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th>序号</th>\n          <th>药名</th>\n          <th>敷贴类型</th>\n          <th>敷贴数量</th>\n          <th>购买类型</th>\n          <th>订单编号</th>\n          <th>用户昵称</th>\n          <th>收货人</th>\n          <th>联系方式</th>\n          <th class=\"col-sm-2\">收货地址</th>\n          <th>发货状态</th>\n          <th>快递单号</th>\n          <!--th.col-sm-1 用户备注-->\n          <th class=\"col-sm-1\">管理员备注</th>\n          <th>操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,send) in sends\">\n          <td>{{index+1}}</td>\n          <td>{{send.goods_name}}</td>\n          <td>{{send.type}}</td>\n          <td>{{send.num}}</td>\n          <td>{{send.goods_type}}</td>\n          <td>{{send.order_sn}}</td>\n          <td>{{send.nickname}}</td>\n          <td>{{send.consignee}}</td>\n          <td>{{send.mobile}}</td>\n          <td>{{send.province}}{{send.city}}{{send.district}}{{send.address}}</td>\n          <td>{{send.shipping_status}}</td>\n          <td>{{send.express_number}}</td>\n          <!--td {{send.postscript}}-->\n          <td>{{send.note}}</td>\n          <td><span v-if=\"send.shipping_status == '未发货'\" @click=\"addlogistics(send.id)\">添加物流</span>\n            <!--span(v-else,@click=\"allogistics(send.id)\") 物流进度--><span v-if=\"send.shipping_status == '已发货'\" @click=\"updateLogistics(send.id)\">修改物流</span>\n            <!--span.del(@click=\"SendDelete(send.id)\") 删除--><span @click=\"noteAdd(send.id)\">备注</span>\n          </td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n  <pop-addlogistics :id.sync=\"id\"></pop-addlogistics>\n  <!--pop-allogistics(:id.sync=\"id\")-->\n  <pop-logisticsupdate :id.sync=\"id\"></pop-logisticsupdate>\n  <pop-dealnote :id.sync=\"id\"></pop-dealnote>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-e3c50690", module.exports)
  } else {
    hotAPI.update("_v-e3c50690", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"../../js/calendar.vue":9,"vue":7,"vue-hot-reload-api":3}],105:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getData();
    },
    ready: function ready() {
        headNav(3);
    },
    data: function data() {
        return {
            id: '',
            data: {},
            cur: 0,
            all: 0,
            total: 0,
            order: '',
            search: {
                type: 'recipe',
                pay_status: 1,
                express: 1,
                cur_total: 10
            }

        };
    },

    events: {
        update: function update() {
            this.getData();
        }
    },
    methods: {
        remark: function remark(id, desc) {
            var vue = this;
            layer.open({
                title: '<b>添加备注</b>',
                type: 1,
                area: ['500px', '300px'],
                fixed: false, //不固定
                scrollbar: false, //禁止出现滚动条
                btn: ['保存并关闭', '直接关闭'],
                maxmin: true,
                content: '<textarea id="remark" class="layer_open" >' + desc + '</textarea>',
                yes: function yes() {
                    var content = $("#remark").val();
                    vue.$http({
                        url: 'order/update/' + id,
                        method: "PUT",
                        params: { desc: content }
                    }).then(function (res) {
                        if (res.data.errcode == 200) {
                            vue.getData(this.cur);
                            layer.closeAll();
                        }
                    });
                },
                btn2: function btn2(index, layero) {
                    layer.close(index);
                }
            });
        },
        remind: function remind(id, _remind) {
            this.$http({
                url: 'tisane/' + id + '/remind/' + _remind,
                method: 'put'
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    this.getData(this.cur);
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        getData: function getData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.$http({ url: 'order/presendlist', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('data', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        listen: function listen(data) {
            this.getData(data);
            this.$router.go({ name: 'send_recipe', params: { id: data } });
        },
        express: function express(id) {
            var vue = this;
            layer.open({
                title: '<b>快递信息</b>',
                type: 1,
                area: ['500px', '200px'],
                fixed: false, //不固定1
                scrollbar: false, //禁止出现滚动条
                btn: ['保存并关闭', '直接关闭'],
                maxmin: true,
                content: '<div style="color:red">* 填写前，可以点击页面右上角查看快递公司，必须正确填写快递公司编号</div>' + '<div >' + '<br><label style="margin-left:20px;margin-right:10px;">快递公司编号</label>' + '<input id="company" type="text">' + '</div>' + '<div >' + '<br><label style="margin-left:20px;margin-right:10px;">快递单号</label>' + '<input id="code" type="text">' + '</div>',
                yes: function yes() {
                    var obj = {};
                    obj.express_company = $.trim($("#company").val()) ? $.trim($("#company").val()) : '顺丰';
                    obj.express_number = $.trim($("#code").val());
                    if (obj.express_company == '' || obj.express_number == '') {
                        layer.msg('请填写完整信息');
                        return;
                    }
                    vue.put_express(id, obj);
                },
                btn2: function btn2(index, layero) {
                    layer.close(index);
                }
            });
        },
        put_express: function put_express(id, obj) {
            this.$http({
                url: 'order/update/' + id,
                method: 'put',
                params: obj
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    this.getData(this.cur);
                } else {
                    layer.msg(res.data.msg);
                }
            });
            layer.closeAll();
        },
        see_express: function see_express(id) {
            this.$set('order', id);
            $("#see_express").modal("show");
        },
        exportData: function exportData() {
            var title = '药品发货统计';
            var head = ['订单编号', '患者', '收货人', '联系方式', '收货地址', '药品明细', '煎药方式', '快递单号', '备注'];
            var width = {
                'A': 20,
                'B': 10,
                'C': 10,
                'D': 20,
                'E': 20,
                'F': 20,
                'G': 10,
                'H': 15,
                'I': 20
            };
            this.$http({
                url: 'exports/exports',
                method: 'post',
                params: { title: title, head: head, search: this.search, width: width, type: 'send_recipe' }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    location.href = "/api/upload/download/" + res.data.data;
                } else {
                    layer.msg(res.data.msg);
                }
            });
        },
        exportCompany: function exportCompany() {
            $("#express_company").modal("show");
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">药品发货</div>\n    <div class=\"pull-right\"><a @click=\"exportCompany()\" class=\"btn btn-sm btn-primary\">查看快递公司</a><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"search_box\">\n    <dl>\n      <dt>查询：</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"search.order_sn\" placeholder=\"输入订单编号搜索\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"search.name\" placeholder=\"输入患者姓名\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"search.user_name\" placeholder=\"输入收货人姓名\" class=\"form-control auto_inp\">\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <select v-model=\"search.cur_total\" class=\"form-control\">\n              <option value=\"10\">10条每页</option>\n              <option value=\"20\">20条每页</option>\n              <option value=\"50\">50条每页</option>\n              <option value=\"100\">100条每页</option>\n            </select>\n            <div @click=\"getData()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">订单编号</th>\n          <th class=\"col-sm-1\">患者</th>\n          <th class=\"col-sm-1\">收货人</th>\n          <th class=\"col-sm-1\">联系方式</th>\n          <th class=\"col-sm-1\">收货地址</th>\n          <th class=\"col-sm-1\">药品明细</th>\n          <th class=\"col-sm-1\">煎药方式</th>\n          <th class=\"col-sm-1\">快递单号</th>\n          <!--th.col-sm-1 状态-->\n          <!--th.col-sm-1 提醒时间-->\n          <th class=\"col-sm-1\">备注</th>\n          <th class=\"col-sm-1\">操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.order_sn}}</td>\n          <td>{{val.user.realname}}</td>\n          <td>{{val.user_name}}</td>\n          <td>{{val.mobile}}</td>\n          <td>{{val.address}}</td>\n          <td><span v-for=\"item in val.prescription.recipe\">\n              <p>{{item.name}} {{item.dosage}}g  {{item.other}}</p></span></td>\n          <td>{{val.prescription.tisane ==1 ? '代煎':'自煎'}}</td>\n          <td>{{val.express_number}}</td>\n          <!--td {{val.is_remind_txt}}-->\n          <!--td {{val.remind_time}}-->\n          <td>{{val.desc}}</td>\n          <td><span @click=\"express(val.id)\">添加物流</span><span v-if=\"val.express_number\" @click=\"see_express(val)\">查看物流</span>\n            <!--span(v-if=\"val.is_express ==1\",@click=\"remind(val.id,val.is_remind)\") 快递提醒-->\n            <!--span(v-if=\"val.is_express ==0 && val.is_tisane ==1\",@click=\"remind(val.id,val.is_remind)\") 代煎提醒--><span @click=\"remark(val.id,val.desc)\">备注</span>\n          </td>\n        </tr>\n      </tbody>\n    </table>\n  </div>\n  <express_company></express_company>\n  <see_express :order.sync=\"order\"></see_express>\n  <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-de1dddb0", module.exports)
  } else {
    hotAPI.update("_v-de1dddb0", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],106:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _referral = require('./module/referral.vue');

var _referral2 = _interopRequireDefault(_referral);

var _refund = require('./module/refund.vue');

var _refund2 = _interopRequireDefault(_refund);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    created: function created() {
        this.page = this.$route.params.id;
        this.getWebData(this.page);
        this.getClinicData(1);
    },

    components: {
        referral: _referral2.default,
        refund: _refund2.default
    },
    ready: function ready() {
        headNav(0);
    },
    data: function data() {
        return {

            status: '',
            name: '',
            time: '',
            type: '',
            id: 0,
            cur: 1,
            cur_web: 0,
            cur_clinic: 0,
            dataWeb: [],
            dataClinic: [],
            order: [],
            all_web: 0,
            all_clinic: 0,
            total_web: 0,
            total_clinic: 0,
            status_clinic: '',
            status_web: '',
            name_web: '',
            doc_name_web: '',
            doc_name_clinic: '',
            name_clinic: '',
            datetime_clinic: '',
            datetime_web: '',
            pay_status_web: '',
            pay_status_clinic: ''
        };
    },

    events: {
        update: function update() {
            this.getWebData(this.cur);
        }
    },
    watch: {
        'datetime_web': function datetime_web(value, oldValue) {
            if (oldValue != value) {
                this.getWebData(1);
            }
        },
        'datetime_clinic': function datetime_clinic(value, oldValue) {
            if (oldValue != value) {
                this.getClinicData(1);
            }
        }
    },
    methods: {
        getWebData: function getWebData(page) {
            this.$http({
                url: 'bespeaks/index',
                method: 'GET',
                params: {
                    page: page,
                    search: { type: 0, status: this.status_web, name: this.name_web, time: this.datetime_web, pay_status: this.pay_status_web, doc_name: this.doc_name_web }
                }
            }).then(function (res) {
                this.$set('dataWeb', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur_web', pagination.current_page);
                this.$set('all_web', pagination.total_pages);
                this.$set('total_web', pagination.total);
            });
            this.$router.go({ name: 'service', params: { id: page } });
        },
        getClinicData: function getClinicData(page) {
            this.$http({
                url: 'bespeaks/index',
                method: 'GET',
                params: {
                    page: page,
                    search: { type: 1, status: this.status_clinic, name: this.name_clinic, time: this.datetime_clinic, pay_status: this.pay_status_clinic, doc_name: this.doc_name_clinic }
                }
            }).then(function (res) {
                this.$set('dataClinic', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur_clinic', pagination.current_page);
                this.$set('all_clinic', pagination.total_pages);
                this.$set('total_clinic', pagination.total);
            });
            this.$router.go({ name: 'service', params: { id: page } });
        },
        refund: function refund(order) {
            if (!order.id || order.status_code < 5) {
                layer.msg('订单未支付');
                return false;
            } else if (order.status_code == 9 || order.status_code == 10) {
                layer.msg('订单退款中或者已退款');
                return false;
            }
            this.order = order;
            $('#refund').modal('show');
        },
        layOpen: function layOpen(id, type, remark) {
            if (remark == undefined) {
                remark = ' ';
            }
            var vue = this;
            layer.open({
                title: '<b>添加备注</b>',
                type: 1,
                area: ['500px', '300px'],
                fixed: false, //不固定
                scrollbar: false, //禁止出现滚动条
                btn: ['保存并关闭', '直接关闭'],
                maxmin: true,
                content: '<textarea id="remark" class="layer_open" >' + remark + '</textarea>',
                yes: function yes() {
                    var content = $("#remark").val();
                    vue.remark(id, content, type);
                },
                btn2: function btn2(index, layero) {
                    layer.close(index);
                }
            });
        },
        referral: function referral(id) {
            this.id = id;
            $("#referral").modal("show");
        },
        remark: function remark(id, content, type) {
            this.$http({
                url: 'bespeaks/update/' + id,
                method: "PUT",
                params: { 'type': 'remark', remark: content }
            }).then(function (res) {
                if (res.data.errcode == 200) {
                    if (type == 1) {
                        this.getClinicData(this.cur_clinic);
                    } else {
                        this.getWebData(this.cur_web);
                    }
                }
            });
            layer.closeAll();
        },
        doc_detail: function doc_detail(id) {
            this.$router.go({ name: 'doc_detail', params: { id: id } });
        },
        listen_clinic: function listen_clinic(data) {
            this.getClinicData(data);
            this.$router.go({ name: 'service', params: { id: data } });
        },
        listen_web: function listen_web(data) {
            this.getWebData(data);
        },
        cancle: function cancle(id) {
            var _this = this;
            var confirm = layer.confirm('您确定您的操作吗？', {
                btn: ['取消', '确定'],
                skin: 'layui-layer-molv'
            }, function () {}, function () {
                _this.docancle(id);
                layer.close(confirm);
            });
        },
        docancle: function docancle(id) {
            this.$http({
                url: 'bespeaks/update/' + id,
                method: "PUT",
                params: { 'type': 'cancle', 'status': 30 }
            }).then(function (res) {
                layer.msg(res.data.msg);
                if (res.data.errcode == 200) {
                    this.getWebData(this.cur);
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"container main_warp\">\n  <ul class=\"nav nav-tabs\">\n    <li class=\"active\"><a href=\"#tab1\" role=\"tab\" data-toggle=\"tab\">网诊预约管理</a></li>\n    <li><a href=\"#tab2\" role=\"tab\" data-toggle=\"tab\">门诊预约管理</a></li>\n  </ul>\n  <div class=\"tab-content\">\n    <div id=\"tab1\" class=\"tab-pane fade in active\">\n      <div class=\"search_box\">\n        <dl>\n          <dt>筛选</dt>\n          <dd class=\"row\">\n            <div class=\"col-sm-2\">\n              <div class=\"input-group\">\n                <input id=\"seaItem\" type=\"search\" v-model=\"name_web\" placeholder=\"输入患者姓名搜索\" class=\"form-control auto_inp\">\n                <div @click=\"getWebData(1)\" class=\"input-group-btn\">\n                  <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-2\">\n              <div class=\"input-group\">\n                <input type=\"search\" v-model=\"doc_name_web\" placeholder=\"输入医生姓名搜索\" class=\"form-control auto_inp\">\n                <div @click=\"getWebData(1)\" class=\"input-group-btn\">\n                  <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n                </div>\n              </div>\n            </div>\n            <div style=\"margin-bottom:0px;\" class=\"form-group\">\n              <label for=\"\" class=\"col-sm-1 control-label\"><span class=\"notice-w\">*</span>选择时间：</label>\n              <div class=\"col-sm-2\">\n                <input type=\"date\" v-model=\"datetime_web\" class=\"form-control\">\n              </div>\n            </div>\n            <div class=\"col-sm-2\">\n              <select v-model=\"status_web\" @change=\"getWebData(1)\" class=\"form-control\">\n                <!--5待接诊 10待支付 15已支付 20诊疗中 25诊疗结束 30医生拒绝接诊 35诊疗已取消-->\n                <option value=\"\" selected=\"selected\">预约状态</option>\n                <option value=\"5\">待接诊</option>\n                <option value=\"10\">待支付</option>\n                <option value=\"15\">已支付</option>\n                <option value=\"20\">诊疗中</option>\n                <option value=\"25\">诊疗结束</option>\n                <option value=\"30\">拒绝</option>\n                <option value=\"35\">已取消</option>\n              </select>\n            </div>\n            <div class=\"col-sm-2\">\n              <select v-model=\"pay_status_web\" @change=\"getWebData(1)\" class=\"form-control\">\n                <option value=\"\" selected=\"selected\">订单状态</option>\n                <!--0 未支付 2正在支付 5已支付 9退款中 10已退款-->\n                <option value=\"0\">未付款</option>\n                <option value=\"5\">已付款</option>\n                <!--option(value=9) 退款中-->\n                <!--option(value=10) 已退款-->\n              </select>\n            </div>\n          </dd>\n        </dl>\n      </div>\n      <div class=\"user_table_box table-responsive\">\n        <table class=\"table table-bordered check_list\">\n          <thead>\n            <tr>\n              <th class=\"col-sm-1\">序号</th>\n              <th class=\"col-sm-1\">患者</th>\n              <th class=\"col-sm-1\">预约医师</th>\n              <th style=\"width:10%\" class=\"col-sm-1\">预约时间</th>\n              <th class=\"col-sm-1\">等待时间(分钟)</th>\n              <th style=\"width:10%\" class=\"col-sm-1\">接诊时间</th>\n              <th class=\"col-sm-1\">预约状态</th>\n              <th class=\"col-sm-1\">备注</th>\n              <th class=\"col-sm-1\">操作人</th>\n              <th class=\"col-sm-1\">操作</th>\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"(index,val) in dataWeb\" v-bind:class=\"{ check_background_red  : val.is_warning}\">\n              <td>{{cur_clinic*10 +index-9}}</td>\n              <td>{{val.user.realname}}</td>\n              <td>{{val.doctor.name}}</td>\n              <td>{{val.created_at}}</td>\n              <td>{{val.wait_time}}</td>\n              <td>{{val.take_time}}</td>\n              <td>{{val.status}}</td>\n              <td>{{val.remark}}</td>\n              <td>{{val.admin.user_name}}</td>\n              <td><span v-if=\"val.status==&quot;待接诊&quot;\" @click=\"cancle(val.id)\" class=\"ccc\">拒绝接诊</span><span v-if=\"val.status==&quot;待接诊&quot;\" @click=\"referral(val.id)\" class=\"ccc\">转诊</span>\n                <!--span.ccc(@click=\"refund(val.order)\") 退款-->\n                <!--span(@click=\"layOpen(val.id,2,val.remark)\") 备注-->\n              </td>\n            </tr>\n          </tbody>\n        </table>\n        <nav>\n          <ul class=\"pagination\">\n            <paginate :cur.sync=\"cur_web\" :all.sync=\"all_web\" v-on:btn-click=\"listen_web\" v-if=\"cur_web\" v-on:gopage=\"listen_web\"></paginate>\n          </ul>\n        </nav>\n      </div>\n    </div>\n    <div id=\"tab2\" class=\"tab-pane fade\">\n      <div class=\"search_box\">\n        <dl>\n          <dt>筛选</dt>\n          <dd class=\"row\">\n            <div class=\"col-sm-2\">\n              <div class=\"input-group\">\n                <input type=\"search\" v-model=\"name_clinic\" placeholder=\"输入患者姓名搜索\" class=\"form-control auto_inp\">\n                <div @click=\"getClinicData(1)\" class=\"input-group-btn\">\n                  <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-2\">\n              <div class=\"input-group\">\n                <input type=\"search\" v-model=\"doc_name_clinic\" placeholder=\"输入医生姓名搜索\" class=\"form-control auto_inp\">\n                <div @click=\"getClinicData(1)\" class=\"input-group-btn\">\n                  <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-2\">\n              <select v-model=\"status_clinic\" @change=\"getClinicData(1)\" class=\"form-control\">\n                <option value=\"\" selected=\"selected\">预约状态</option>\n                <!--5待接诊 10待支付 15已支付 20诊疗中 25诊疗结束 30医生拒绝接诊 35诊疗已取消-->\n                <option value=\"10\">待支付</option>\n                <option value=\"15\">已支付</option>\n                <option value=\"20\">诊疗中</option>\n                <option value=\"25\">诊疗结束</option>\n                <option value=\"35\">取消预约</option>\n                <!--option(value=9) 已过期-->\n              </select>\n            </div>\n            <div class=\"col-sm-2\">\n              <select v-model=\"pay_status_clinic\" @change=\"getClinicData(1)\" class=\"form-control\">\n                <option value=\"\" selected=\"selected\">订单状态</option>\n                <!--0 未支付 2正在支付 5已支付 9退款中 10已退款-->\n                <option value=\"0\">未付款</option>\n                <option value=\"5\">已付款</option>\n                <option value=\"9\">退款中</option>\n                <option value=\"10\">已退款</option>\n              </select>\n            </div>\n            <div class=\"form-group\">\n              <label for=\"\" class=\"col-sm-1 control-label\"><span class=\"notice-w\">*</span>选择时间：</label>\n              <div class=\"col-sm-3\">\n                <input type=\"date\" v-model=\"datetime_clinic\" class=\"form-control\">\n              </div>\n            </div>\n          </dd>\n        </dl>\n      </div>\n      <div class=\"user_table_box table-responsive\">\n        <table class=\"table table-bordered check_list\">\n          <thead>\n            <tr>\n              <th class=\"col-sm-1\">序号</th>\n              <th class=\"col-sm-1\">患者</th>\n              <th class=\"col-sm-1\">预约医师</th>\n              <th class=\"col-sm-1\">预约开始时间</th>\n              <!--th.col-sm-1 预约结束时间-->\n              <th class=\"col-sm-1\">预约状态</th>\n              <th class=\"col-sm-1\">订单状态</th>\n              <th class=\"col-sm-1\">备注</th>\n              <!--th.col-sm-1 操作人-->\n              <!--th.col-sm-1 操作-->\n            </tr>\n          </thead>\n          <tbody>\n            <tr v-for=\"(index,val) in dataClinic\">\n              <td>{{cur_web*10 +index-9}}</td>\n              <td>{{val.user.realname}}</td>\n              <td>{{val.doctor.name}}</td>\n              <td>{{val.start_time}}</td>\n              <!--td {{val.end_time}}-->\n              <td>{{val.status}}</td>\n              <td>{{val.order.status}}</td>\n              <td>{{val.remark}}</td>\n              <!--td {{val.admin.user_name}}-->\n              <!--td\n              span(@click=\"refund(val.order)\") 退款\n              span(@click=\"layOpen(val.id,1,val.remark)\") 备注\n              \n              \n              -->\n            </tr>\n          </tbody>\n        </table>\n        <nav>\n          <ul class=\"pagination\">\n            <paginate :cur.sync=\"cur_clinic\" :all.sync=\"all_clinic\" v-on:btn-click=\"listen_clinic\" v-if=\"cur_clinic\" v-on:gopage=\"listen_clinic\"></paginate>\n          </ul>\n        </nav>\n      </div>\n    </div>\n  </div>\n  <referral :id.sync=\"id\"></referral>\n  <refund :order.sync=\"order\"></refund>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-3fa4c338", module.exports)
  } else {
    hotAPI.update("_v-3fa4c338", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/referral.vue":85,"./module/refund.vue":86,"vue":7,"vue-hot-reload-api":3}],107:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {
            deals: {},
            number: '',
            mobile: '',
            code: '',
            all: '',
            cur: '',
            departments: {},
            total: 0,
            status: 1,
            titles: {},
            search: {},
            id: 0
        };
    },
    created: function created() {
        this.page = this.$route.params.id;
        this.getAlldeal();
    },
    ready: function ready() {
        headNav(1);
    },

    events: {
        update: function update() {
            this.getAlldeal(this.cur);
        }
    },
    methods: {
        note: function note(id) {
            this.$set('id', id);
            $("#dealnote").modal("show");
        },
        getAlldeal: function getAlldeal() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (page) {
                this.page = page;
            }
            this.search.number = this.number;
            this.search.mobile = this.mobile;
            this.search.status = this.status;
            this.search.code = this.code;
            this.$http({ url: 'deal/list', method: 'GET', params: { page: this.page, search: this.search } }).then(function (res) {
                this.$set('deals', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
                this.$set('total', pagination.total);
            });
        },
        exportData: function exportData() {
            this.search.number = this.number;
            this.search.mobile = this.mobile;
            this.search.code = this.code;
            this.$http({
                url: 'export/shop',
                method: 'GET',
                params: { search: this.search }
            }).then(function (res) {
                if (res.data.status == 1) {
                    location.href = "/api/upload/download/" + res.data.name;
                }
            });
        },
        expertDetail: function expertDetail(id) {
            location.href = "expert_detail/" + id;
        },
        expertDelete: function expertDelete(id) {
            var vue = this;
            layer.confirm('您确定删除？', {
                btn: ['确定', '取消']
            }, function () {
                vue.$http.delete('expert/deleteExport/' + id).then(function (res) {
                    if (res.data.status == 1) {
                        layer.msg(res.data.msg);
                        vue.$dispatch('update');
                    } else {
                        layer.msg(res.data.msg);
                    }
                });
            }, function () {});
        },
        listen: function listen(data) {
            this.getAlldeal(data);
            this.$router.go({ name: 'shop_deal', params: { id: data } });
        }
    },
    watch: {
        status: function status() {
            this.getAlldeal();
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">商城订单</div>\n    <div class=\"pull-right\"><a @click=\"exportData()\" class=\"btn btn-sm btn-primary\">导出管理</a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div id=\"searchList\" class=\"search_box\">\n    <dl>\n      <dt>查询：</dt>\n      <dd class=\"row\">\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"number\" placeholder=\"请输入订单编号查询\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getAlldeal()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"mobile\" placeholder=\"请输入手机号查询\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getAlldeal()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <div class=\"input-group\">\n            <input type=\"search\" v-model=\"code\" placeholder=\"请输入优惠码查询\" class=\"form-control auto_inp\">\n            <div v-on:click=\"getAlldeal()\" class=\"input-group-btn\">\n              <div class=\"btn btn-default\"><i class=\"icon-search\"></i></div>\n            </div>\n          </div>\n        </div>\n        <div class=\"col-sm-2\">\n          <select v-model=\"status\" class=\"form-control\">\n            <option value=\"1\" selected=\"selected\">请选择支付状态</option>\n            <option value=\"0\">未付款</option>\n            <option value=\"2\">已付款</option>\n            <option value=\"3\">已取消</option>\n          </select>\n        </div>\n        <div class=\"col-sm-2\"><span>共 {{total}} 条记录</span></div>\n      </dd>\n    </dl>\n  </div>\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th>序号</th>\n          <th>订单编号</th>\n          <th>药名</th>\n          <th>用户昵称</th>\n          <th>手机号</th>\n          <th>购买类型</th>\n          <th>原价</th>\n          <th>实付</th>\n          <th>优惠码</th>\n          <th>支付方式</th>\n          <th>提交时间</th>\n          <th>支付状态</th>\n          <th class=\"col-sm-1\">备注</th>\n          <th>操作</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"(index,deal) in deals\">\n          <td>{{index+1}}</td>\n          <td>{{deal.order_sn}}</td>\n          <td>{{deal.goods_name}}</td>\n          <td>{{deal.nickname}}</td>\n          <td>{{deal.mobile}}</td>\n          <td>{{deal.goods_type}}</td>\n          <td>{{deal.goods_price}}</td>\n          <td>{{deal.money_paid}}</td>\n          <td>{{deal.promocode}}</td>\n          <td>微信</td>\n          <td>{{deal.created_at}}</td>\n          <td v-if=\"deal.order_status == '已取消'\">已取消</td>\n          <td v-else=\"v-else\">{{deal.pay_status}}</td>\n          <td>{{deal.note}}</td>\n          <td><span @click=\"note(deal.id)\">备注</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <nav>\n      <ul class=\"pagination\">\n        <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\" v-on:gopage=\"listen\"></paginate>\n      </ul>\n    </nav>\n  </div>\n  <pop-dealnote :id.sync=\"id\"></pop-dealnote>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-7bbe21d8", module.exports)
  } else {
    hotAPI.update("_v-7bbe21d8", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],108:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _slider_add = require("./module/slider_add.vue");

var _slider_add2 = _interopRequireDefault(_slider_add);

var _slider_detail = require("./module/slider_detail.vue");

var _slider_detail2 = _interopRequireDefault(_slider_detail);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        slider_add: _slider_add2.default,
        slider_detail: _slider_detail2.default
    },
    data: function data() {
        return {
            data: {},
            val: {}
        };
    },
    created: function created() {
        this.getData();
    },
    ready: function ready() {
        headNav(4);
    },

    events: {
        refreshList: function refreshList() {
            this.getData();
        }
    },
    methods: {
        getData: function getData() {
            this.$http({ url: 'slider/index', method: 'GET' }).then(function (res) {
                this.$set('data', res.data.data);
            });
        },
        _delete: function _delete(id) {
            this.$http({ url: 'slider/del/' + id, method: 'delete' }).then(function (res) {
                if (res.data.status) {
                    layer.msg('操作成功');
                    this.getData();
                }
            });
        },
        detail: function detail(val) {
            this.$set('val', val);
            $("#slider_detail").modal("show");
        },
        save: function save(val) {
            this.$http.put('slider/update', val).then(function (res) {
                if (res.data.status) {
                    layer.msg('操作成功');
                    this.getData();
                }
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">轮播图</div>\n    <div class=\"pull-right\"><a onclick=\"itemPop(undefined,'slider_add')\" class=\"btn btn-primary btn-sm\"><i class=\"icon-plus\"></i><span>添加轮播</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"user_table_box table-responsive\">\n    <table class=\"table table-bordered\">\n      <thead>\n        <tr>\n          <th class=\"col-sm-1\">名称</th>\n          <th class=\"col-sm-1\">图片</th>\n          <th style=\"width:50%\" class=\"col-sm-2\">简介</th>\n          <th class=\"col-sm-2\">跳转地址</th>\n          <th class=\"col-sm-1\">是否展示</th>\n          <th class=\"col-sm-1\">操作功能</th>\n        </tr>\n      </thead>\n      <tbody>\n        <tr v-for=\"val in data\">\n          <td>{{val.title}}</td>\n          <td><img v-bind:src=\"val.image\" style=\"width:128px;height:60px\"></td>\n          <td>{{val.desc}}</td>\n          <td>{{val.url}}</td>\n          <td>\n            <select v-model=\"val.status\" v-on:change=\"save(val)\" class=\"form-control\">\n              <option value=\"1\">展示</option>\n              <option value=\"0\">不展示</option>\n            </select>\n          </td>\n          <td><span @click=\"detail(val)\">编辑</span><span @click=\"_delete(val.id)\" style=\"color:red;\">删除</span></td>\n        </tr>\n      </tbody>\n    </table>\n    <slider_add></slider_add>\n    <slider_detail :val.sync=\"val\"></slider_detail>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-266b25a4", module.exports)
  } else {
    hotAPI.update("_v-266b25a4", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/slider_add.vue":91,"./module/slider_detail.vue":92,"vue":7,"vue-hot-reload-api":3}],109:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.getLogs(1);
    },

    events: {
        refreshList: function refreshList() {
            this.getLogs(this.cur);
        }
    },
    data: function data() {
        return {
            logs: [],
            cur: 0,
            all: 0,
            msg: ''
        };
    },

    methods: {
        getLogs: function getLogs(page) {
            this.$http({ url: 'getLogs', method: 'GET', params: { page: page } }).then(function (res) {
                this.$set('logs', res.data.data);
                var pagination = res.data.meta.pagination;
                this.$set('cur', pagination.current_page);
                this.$set('all', pagination.total_pages);
            });
        },
        listen: function listen(data) {
            this.getLogs(data);
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container clearfix\">\n    <div class=\"pull-left\">登录日志</div>\n    <div class=\"pull-right\"><a onclick=\"itemPop(undefined,'password')\" class=\"btn btn-primary btn-sm\"><i class=\"icon-lock\"></i><span>修改密码</span></a></div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <table class=\"table table-bordered\">\n    <thead>\n      <tr>\n        <th>IP地址</th>\n        <th>登录地址</th>\n        <th>访问时间</th>\n      </tr>\n    </thead>\n    <tbody>\n      <tr v-for=\"log in logs\">\n        <td>{{log.ip}}</td>\n        <td>{{log.address?log.address:'未知'}}</td>\n        <td>{{log.created_at}}</td>\n      </tr>\n    </tbody>\n  </table>\n  <nav>\n    <ul class=\"pagination\">\n      <paginate :cur.sync=\"cur\" :all.sync=\"all\" v-on:btn-click=\"listen\" v-if=\"cur\"></paginate>\n    </ul>\n  </nav>\n  <pop-password></pop-password>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-021a784c", module.exports)
  } else {
    hotAPI.update("_v-021a784c", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],110:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    created: function created() {
        this.user_id = this.$route.params.user_id;
        this.family_id = this.$route.params.family_id;
        this.getData();
    },
    ready: function ready() {},
    data: function data() {
        return {
            user_id: 0,
            family_id: 0,
            data: {}
        };
    },

    methods: {
        getData: function getData() {
            this.$http.get('appuser/clinic/' + this.user_id + '/' + this.family_id).then(function (res) {
                this.$set('data', res.data);
                console.log(this.data);
            });
        }
    }
};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">患者管理 &gt; {{data.family}}就诊记录\n      <label></label>\n    </div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <div class=\"col-sm-3\">\n          <p>共有 {{data.count}} 次就诊记录</p>\n        </div>\n      </div>\n    </form>\n  </div>\n  <div v-for=\"val in data.clinic\" class=\"new_item\">\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">就诊时间：</label>\n        <div class=\"col-sm-3\">\n          <p>{{val.created_at}}</p>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">就诊方式：</label>\n        <div class=\"col-sm-3\">\n          <p>{{val.recipe_status}}</p>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">医师：</label>\n        <div class=\"col-sm-3\">\n          <p>{{val.doctor}}</p>\n        </div>\n      </div>\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">诊断详情：</label>\n        <div class=\"col-sm-3\">\n          <p>{{val.reply_content}}</p>\n        </div>\n      </div>\n      <div v-if=\"val.recipe.length\" v-for=\"item in val.recipe\" class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">处方：</label>\n        <div class=\"col-sm-3\">\n          <p>{{item.recipe_head}} 副</p>\n        </div>\n        <div class=\"col-sm-3\">\n          <p>{{item.recipe}}</p>\n        </div>\n        <div class=\"col-sm-3\">\n          <p>医嘱: {{item.recipe_remark}}</p>\n        </div>\n      </div>\n      <div v-if=\"val.comment.length\" v-for=\"item in val.comment\" class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">评价：</label>\n        <div class=\"col-sm-5\">\n          <p>态度</p>\n          <div v-bind:class=\"item.manner\"></div>\n        </div>\n        <div class=\"col-sm-5\">\n          <p>疗效</p>\n          <div v-bind:class=\"item.effect\"></div>\n        </div>\n        <div class=\"col-sm-3\">\n          <p>医嘱: {{item.content}}</p>\n        </div>\n      </div>\n    </form>\n  </div>\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-5da38142", module.exports)
  } else {
    hotAPI.update("_v-5da38142", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"vue":7,"vue-hot-reload-api":3}],111:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _clinic_detail = require("./module/clinic_detail.vue");

var _clinic_detail2 = _interopRequireDefault(_clinic_detail);

var _save_appuser = require("./module/save_appuser.vue");

var _save_appuser2 = _interopRequireDefault(_save_appuser);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    components: {
        clinic_detail: _clinic_detail2.default,
        save_appuser: _save_appuser2.default
    },
    created: function created() {
        this.user_id = this.$route.params.id;
    },
    ready: function ready() {
        headNav(0);
    },
    data: function data() {
        return {
            user_id: 0,
            user: {},
            item: {},
            orders: {},
            clinics: {},
            clinic_id: 0
        };
    },

    events: {
        refreshList: function refreshList() {
            this.getUserDetail(this.user_id);
        }
    },
    methods: {
        save: function save() {
            this.$set('item', this.user);
            this.$set('item.save_type', 'user');
            $("#save_appuser").modal("show");
        },
        clinic: function clinic(clinic_id) {
            this.$set('clinic_id', clinic_id);
            $("#clinic_detail").modal("show");
        },
        getUserDetail: function getUserDetail(id) {
            if (id > 0) {
                this.$http.get('appuser/detail/' + id).then(function (res) {
                    this.$set('user', res.data.data);
                    this.$set('clinics', res.data.data.clinics);
                    this.$set('orders', res.data.data.orders);
                });
            }
        }
    },
    watch: {
        user_id: function user_id(newValue) {
            this.getUserDetail(newValue);
        }
    }

};
if (module.exports.__esModule) module.exports = module.exports.default
;(typeof module.exports === "function"? module.exports.options: module.exports).template = "\n<div class=\"tit_nav\">\n  <div class=\"container\">\n    <div class=\"pull-left\">用户管理 &gt; 用户详情\n      <label></label>\n    </div>\n  </div>\n</div>\n<div class=\"container main_warp\">\n  <div class=\"new_item\">\n    <!--button(@click=\"save()\",class=\"btn btn-primary\")  修改-->\n    <form role=\"form\" class=\"form-horizontal\">\n      <div class=\"form-group\">\n        <label for=\"\" class=\"col-sm-1 control-label\">头像：</label>\n        <div class=\"col-sm-2\">\n          <div class=\"sel_box\">\n            <div v-bind:style=\"{backgroundImage:'url(' +user.headimgurl+')' }\" class=\"img-face-t\"></div>\n          </div>\n        </div>\n        <div class=\"col-sm-3\">\n          <p>患者昵称： {{user.nickname}}</p>\n          <p>手机号： {{user.mobile}}</p>\n          <p>性别： {{user.sex}}</p>\n          <p>身份证号： {{user.pincode}}</p>\n          <p>国籍： {{user.county}}</p>\n        </div>\n        <div class=\"col-sm-3\">\n          <p>真实姓名： {{user.realname}}</p>\n          <p>年龄： {{user.age}}</p>\n          <p>身高： {{user.height}}</p>\n          <p>体重： {{user.weight}}</p>\n          <p>常居住地： {{user.province ? user.province : '暂无'}}{{user.city}}{{user.area}}</p>\n        </div>\n      </div>\n    </form>\n    <ul class=\"nav nav-tabs\">\n      <li class=\"active\"><a href=\"#tab5\" role=\"tab\" data-toggle=\"tab\">就诊记录</a></li>\n    </ul>\n    <div class=\"tab-content\">\n      <div id=\"tab5\" class=\"tab-pane fade in active\">\n        <form role=\"form\" class=\"form-horizontal user_table_box table-responsive\">\n          <table class=\"table table-bordered check_list\">\n            <thead>\n              <tr>\n                <th class=\"col-sm-1\">医师</th>\n                <th class=\"col-sm-1\">就诊方式</th>\n                <th class=\"col-sm-1\">类型</th>\n                <th style=\"width:12%\" class=\"col-sm-1\">创建时间</th>\n                <th class=\"col-sm-1\">诊断（处方名称）</th>\n                <th class=\"col-sm-1\">处方</th>\n                <th class=\"col-sm-1\">剂量</th>\n                <!--th.col-sm-1 操作-->\n              </tr>\n            </thead>\n            <tbody>\n              <tr v-for=\"(index,c) in clinics\">\n                <td>{{c.doctor.name}}</td>\n                <td v-if=\"c.type == 0\">网诊</td>\n                <td v-else=\"v-else\">门诊</td>\n                <td v-if=\"c.first == 0\">复诊</td>\n                <td v-else=\"v-else\">初诊</td>\n                <td>{{c.created_at}}</td>\n                <td>{{c.disease}}</td>\n                <td>{{c.prescription.recipe}}</td>\n                <td>{{c.prescription.recipe_head}}</td>\n                <!--td\n                //span(@click=\"clinic(val.id)\") 详情\n                \n                -->\n              </tr>\n            </tbody>\n          </table>\n        </form>\n      </div>\n    </div>\n  </div>\n  <!--clinic_detail(:clinic_id.sync=\"clinic_id\")-->\n  <!--save_appuser(:item.sync=\"item\")-->\n</div>\n"
if (module.hot) {(function () {  module.hot.accept()
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  if (!module.hot.data) {
    hotAPI.createRecord("_v-7f136648", module.exports)
  } else {
    hotAPI.update("_v-7f136648", module.exports, (typeof module.exports === "function" ? module.exports.options : module.exports).template)
  }
})()}
},{"./module/clinic_detail.vue":73,"./module/save_appuser.vue":87,"vue":7,"vue-hot-reload-api":3}],112:[function(require,module,exports){
'use strict';

var _vue = require('vue');

var _vue2 = _interopRequireDefault(_vue);

var _Body = require('./components/Body.vue');

var _Body2 = _interopRequireDefault(_Body);

var _vueRouter = require('vue-router');

var _vueRouter2 = _interopRequireDefault(_vueRouter);

var _vueResource = require('vue-resource');

var _vueResource2 = _interopRequireDefault(_vueResource);

var _vueInfiniteScroll = require('vue-infinite-scroll');

var _vueInfiniteScroll2 = _interopRequireDefault(_vueInfiniteScroll);

var _paginate = require('./components/module/paginate.vue');

var _paginate2 = _interopRequireDefault(_paginate);

var _PopLogdetail = require('./components/module/PopLogdetail.vue');

var _PopLogdetail2 = _interopRequireDefault(_PopLogdetail);

var _PopPassword = require('./components/module/PopPassword.vue');

var _PopPassword2 = _interopRequireDefault(_PopPassword);

var _PopUserinfo = require('./components/module/PopUserinfo.vue');

var _PopUserinfo2 = _interopRequireDefault(_PopUserinfo);

var _PopUseradd = require('./components/module/PopUseradd.vue');

var _PopUseradd2 = _interopRequireDefault(_PopUseradd);

var _PopUsergroup = require('./components/module/PopUsergroup.vue');

var _PopUsergroup2 = _interopRequireDefault(_PopUsergroup);

var _PopGroupedit = require('./components/module/PopGroupedit.vue');

var _PopGroupedit2 = _interopRequireDefault(_PopGroupedit);

var _PopAuth = require('./components/module/PopAuth.vue');

var _PopAuth2 = _interopRequireDefault(_PopAuth);

var _PopAllogistics = require('./components/module/PopAllogistics.vue');

var _PopAllogistics2 = _interopRequireDefault(_PopAllogistics);

var _PopAddlogistics = require('./components/module/PopAddlogistics.vue');

var _PopAddlogistics2 = _interopRequireDefault(_PopAddlogistics);

var _PopSendcode = require('./components/module/PopSendcode.vue');

var _PopSendcode2 = _interopRequireDefault(_PopSendcode);

var _PopLogisticsupdate = require('./components/module/PopLogisticsupdate.vue');

var _PopLogisticsupdate2 = _interopRequireDefault(_PopLogisticsupdate);

var _PopPoint = require('./components/module/PopPoint.vue');

var _PopPoint2 = _interopRequireDefault(_PopPoint);

var _PopAddnote = require('./components/module/PopAddnote.vue');

var _PopAddnote2 = _interopRequireDefault(_PopAddnote);

var _PopDealnote = require('./components/module/PopDealnote.vue');

var _PopDealnote2 = _interopRequireDefault(_PopDealnote);

var _PopAddtest = require('./components/module/PopAddtest.vue');

var _PopAddtest2 = _interopRequireDefault(_PopAddtest);

var _PopTelephone = require('./components/module/PopTelephone.vue');

var _PopTelephone2 = _interopRequireDefault(_PopTelephone);

var _express_company = require('./components/module/express_company.vue');

var _express_company2 = _interopRequireDefault(_express_company);

var _see_express = require('./components/module/see_express.vue');

var _see_express2 = _interopRequireDefault(_see_express);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//注册全局组件
//添加备注
//物流状态s
//物流状态
//物流状态

//import marked from 'marked';
_vue2.default.component('paginate', _paginate2.default); //添加备注
//物流状态
//物流状态

_vue2.default.component('PopAddtest', _PopAddtest2.default);
_vue2.default.component('PopLogdetail', _PopLogdetail2.default);
_vue2.default.component('PopPassword', _PopPassword2.default);
_vue2.default.component('PopUserinfo', _PopUserinfo2.default);
_vue2.default.component('PopUseradd', _PopUseradd2.default);
_vue2.default.component('PopUsergroup', _PopUsergroup2.default);
_vue2.default.component('PopAuth', _PopAuth2.default);
_vue2.default.component('PopGroupedit', _PopGroupedit2.default);
_vue2.default.component('PopAllogistics', _PopAllogistics2.default);
_vue2.default.component('PopAddlogistics', _PopAddlogistics2.default);
_vue2.default.component('PopSendcode', _PopSendcode2.default);
_vue2.default.component('PopLogisticsupdate', _PopLogisticsupdate2.default);
_vue2.default.component('PopPoint', _PopPoint2.default);
_vue2.default.component('PopAddnote', _PopAddnote2.default);
_vue2.default.component('PopDealnote', _PopDealnote2.default);
_vue2.default.component('PopTelephone', _PopTelephone2.default);
_vue2.default.component('express_company', _express_company2.default);
_vue2.default.component('see_express', _see_express2.default);

_vue2.default.use(_vueRouter2.default);
_vue2.default.use(_vueResource2.default);
_vue2.default.use(_vueInfiniteScroll2.default);
_vue2.default.http.options.root = '/api';
_vue2.default.http.options.emulateJSON = true;
_vue2.default.http.options.emulateHTTP = false;
//Vue.prototype.marked = marked;
/* eslint-disable no-new */

var router = new _vueRouter2.default({
    history: true,
    root: 'admin'
});

router.map({
    '/card_detail/:ctype/:id/:family_id': { //问诊单详情
        name: 'card_detail',
        component: require('./components/card_detail.vue')
    },
    '/agreement': { //协议管理
        component: require('./components/agreement.vue')
    },
    '/information': { //协议管理123
        component: require('./components/information.vue')
    },
    '/exam': { //系统问诊单
        component: require('./components/exam.vue')
    },
    '/exam_save/:id': { //系统问诊单修改
        name: 'exam_save',
        component: require('./components/exam_save.vue')
    },
    '/medicinal_type/:id': { //药剂管理
        name: 'medicinal_type',
        component: require('./components/medicinal_type.vue')
    },
    '/clinique': { //诊所管理1
        component: require('./components/clinique.vue')
    },
    '/auth': { //权限列表
        component: require('./components/adm_auth.vue')
    },
    '/adm_disease': { //常见疾病11
        component: require('./components/adm_disease.vue')
    },
    '/adm_log/:id': { //日志管理
        name: 'adm_log',
        component: require('./components/adm_log.vue')
    },
    '/adm_field/:id': {
        name: 'adm_field',
        component: require('./components/adm_field.vue')
    },
    '/adm_user/:id': { //管理员列表
        name: 'adm_user',
        component: require('./components/adm_user.vue')
    },
    '/adm_pri': { //权限管理
        component: require('./components/adm_pri.vue')
    },
    '/app_users/:id': { //用户管理
        name: 'app_users',
        component: require('./components/app_users.vue')
    },
    '/operation_list': { //操作日志1231
        component: require('./components/operation_list.vue')
    },
    '/user_detail/:id': { //用户详情页
        name: 'user_detail',
        component: require('./components/user_detail.vue')
    },
    '/family_detail/:id': { //患者详情页
        name: 'family_detail',
        component: require('./components/family_detail.vue')
    },
    '/user_clinic/:user_id/:family_id': { //用户诊疗
        name: 'user_clinic',
        component: require('./components/user_clinic.vue')
    },
    '/user_center': { //个人信息1
        component: require('./components/user_center.vue')
    },
    '/shop_deal/:id': { //财务管理--商城订单
        name: 'shop_deal',
        component: require('./components/shop_deal.vue')
    },
    '/send_list/:id': { //发货列表
        name: 'send_list',
        component: require('./components/send_list.vue')
    },
    '/send_recipe/:id': { //药品发货列表1
        name: 'send_recipe',
        component: require('./components/send_recipe.vue')
    },
    '/promocode_list/:id': { //优惠码列表
        name: 'promocode_list',
        component: require('./components/promocode_list.vue')
    },
    '/promocode_add': { //优惠码添加
        component: require('./components/promocode_add.vue')
    },
    '/promocode_mobile/:id': { //优惠码添加
        name: 'promocode_mobile',
        component: require('./components/promocode_mobile.vue')
    },
    '/promocode_send/:id': { //优惠码发放
        name: 'promocode_send',
        component: require('./components/promocode_send.vue')
    },
    '/lnquiry_list/:id': { //问诊单管理
        name: 'lnquiry_list',
        component: require('./components/lnquiry_list.vue')
    },
    '/lnquiry_add': { //问诊单添加
        component: require('./components/lnquiry_add.vue')
    },
    '/lnquiry_detail/:id': { //问诊单详情页
        name: 'lnquiry_detail',
        component: require('./components/lnquiry_detail.vue')
    },
    '/question_add': { //问题添加
        component: require('./components/question_add.vue')
    },
    '/question_answer/:id': { //问题详情页
        name: 'question_answer',
        component: require('./components/question_answer.vue')
    },
    '/question_list/:id': { //问题列表
        name: 'question_list',
        component: require('./components/question_list.vue')
    },
    '/proposed_law/:id': { //内容管理--方案处理
        name: 'proposed_law',
        component: require('./components/proposed_law.vue')
    },
    '/proposed_detail/:id': { //内容管理--方案处理详情
        name: 'proposed_detail',
        component: require('./components/proposed_detail.vue')
    },
    '/doctor/:id': { //医生管理
        name: 'doctor',
        component: require('./components/doctor.vue')
    },
    '/doc_detail/:id': { //医生管理
        name: 'doc_detail',
        component: require('./components/doc_detail.vue')
    },
    '/service/:id': { //客服
        name: 'service',
        component: require('./components/service.vue')
    },
    '/chat_admin/:id': { //网诊
        name: 'chat_admin',
        component: require('./components/chat_admin.vue')
    },
    '/charge_price/:id': { //划价收费
        name: 'charge_price',
        component: require('./components/charge_price.vue')
    },
    '/drug_manage/:id': { //y预约订单
        name: 'drug_manage',
        component: require('./components/drug_manage.vue')
    },
    '/drug_medicinal/:id': { //药品订单
        name: 'drug_medicinal',
        component: require('./components/drug_medicinal.vue')
    },
    '/drug_pay/:id': { //会员卡订单
        name: 'drug_pay',
        component: require('./components/drug_pay.vue')
    },
    '/count_manage/:id': { //经营统计
        name: 'count_manage',
        component: require('./components/count_manage.vue')
    },
    '/count_family': { //患者统计123
        component: require('./components/count_family.vue')
    },
    '/count_doc/:id': { //医师统计
        name: 'count_doc',
        component: require('./components/count_doc.vue')
    },
    '/count_mall': { //商城统计
        component: require('./components/count_mall.vue')
    },
    '/count_income/:id': { //商城统计
        name: 'count_income',
        component: require('./components/count_income.vue')
    },
    '/count_lnquiry': { // 方案统计
        component: require('./components/count_lnquiry.vue')
    },
    '/comment_admin/:id': { //评价管理
        name: 'comment_admin',
        component: require('./components/comment_admin.vue')
    },
    '/count_curative/:id': { //疗效管理
        name: 'count_curative',
        component: require('./components/count_curative.vue')
    },
    // '/count_curative_detail/:id/:page': {//疗效管理详情
    //     name:'count_curative_detail',
    //     component: require('./components/count_curative_detail.vue')
    // }
    '/count_curative_detail/:id': { //疗效管理详情
        name: 'count_curative_detail',
        component: require('./components/count_curative_detail.vue')
    },
    '/adm_sync': { //数据同步
        component: require('./components/adm_sync.vue')
    },
    '/section_admin/:id': { //科室管理
        name: 'section_admin',
        component: require('./components/section_admin.vue')
    },
    '/disease_admin/:id': { //疾病管理
        name: 'disease_admin',
        component: require('./components/disease_admin.vue')
    },
    '/slider': { //轮播
        component: require('./components/slider.vue')
    },
    '/telephone': {
        component: require('./components/adm_telephone.vue')
    },
    '/message_list': {
        component: require('./components/message_list.vue')
    },
    '/message_detail': {
        component: require('./components/message_detail.vue')
    }
});

router.beforeEach(function (transition) {
    layer.load();
    transition.next();
});

router.afterEach(function (transition) {
    setTimeout(function () {
        layer.closeAll('loading');
    }, 1000);
});

router.start(_Body2.default, 'body');

},{"./components/Body.vue":11,"./components/adm_auth.vue":12,"./components/adm_disease.vue":13,"./components/adm_field.vue":14,"./components/adm_log.vue":15,"./components/adm_pri.vue":16,"./components/adm_sync.vue":17,"./components/adm_telephone.vue":18,"./components/adm_user.vue":19,"./components/agreement.vue":20,"./components/app_users.vue":21,"./components/card_detail.vue":22,"./components/charge_price.vue":23,"./components/chat_admin.vue":24,"./components/clinique.vue":25,"./components/comment_admin.vue":26,"./components/count_curative.vue":27,"./components/count_curative_detail.vue":28,"./components/count_doc.vue":29,"./components/count_family.vue":30,"./components/count_income.vue":31,"./components/count_lnquiry.vue":32,"./components/count_mall.vue":33,"./components/count_manage.vue":34,"./components/disease_admin.vue":35,"./components/doc_detail.vue":36,"./components/doctor.vue":37,"./components/drug_manage.vue":38,"./components/drug_medicinal.vue":39,"./components/drug_pay.vue":40,"./components/exam.vue":41,"./components/exam_save.vue":42,"./components/family_detail.vue":43,"./components/information.vue":44,"./components/lnquiry_add.vue":45,"./components/lnquiry_detail.vue":46,"./components/lnquiry_list.vue":47,"./components/medicinal_type.vue":48,"./components/message_detail.vue":49,"./components/message_list.vue":50,"./components/module/PopAddlogistics.vue":51,"./components/module/PopAddnote.vue":52,"./components/module/PopAddtest.vue":53,"./components/module/PopAllogistics.vue":54,"./components/module/PopAuth.vue":55,"./components/module/PopDealnote.vue":57,"./components/module/PopGroupedit.vue":58,"./components/module/PopLogdetail.vue":59,"./components/module/PopLogisticsupdate.vue":60,"./components/module/PopPassword.vue":61,"./components/module/PopPoint.vue":62,"./components/module/PopSendcode.vue":63,"./components/module/PopTelephone.vue":64,"./components/module/PopUseradd.vue":65,"./components/module/PopUsergroup.vue":66,"./components/module/PopUserinfo.vue":68,"./components/module/express_company.vue":80,"./components/module/paginate.vue":83,"./components/module/see_express.vue":90,"./components/operation_list.vue":93,"./components/promocode_add.vue":94,"./components/promocode_list.vue":95,"./components/promocode_mobile.vue":96,"./components/promocode_send.vue":97,"./components/proposed_detail.vue":98,"./components/proposed_law.vue":99,"./components/question_add.vue":100,"./components/question_answer.vue":101,"./components/question_list.vue":102,"./components/section_admin.vue":103,"./components/send_list.vue":104,"./components/send_recipe.vue":105,"./components/service.vue":106,"./components/shop_deal.vue":107,"./components/slider.vue":108,"./components/user_center.vue":109,"./components/user_clinic.vue":110,"./components/user_detail.vue":111,"vue":7,"vue-infinite-scroll":4,"vue-resource":5,"vue-router":6}]},{},[112]);
