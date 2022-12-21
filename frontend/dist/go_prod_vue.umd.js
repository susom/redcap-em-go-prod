(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["go_prod_vue"] = factory();
	else
		root["go_prod_vue"] = factory();
})((typeof self !== 'undefined' ? self : this), function() {
return /******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	!function() {
/******/ 		__webpack_require__.p = "";
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "default": function() { return /* binding */ entry_lib; }
});

;// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
/* eslint-disable no-var */
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var currentScript = window.document.currentScript
  if (false) { var getCurrentScript; }

  var src = currentScript && currentScript.src.match(/(.+\/)[^/]+\.js(\?.*)?$/)
  if (src) {
    __webpack_require__.p = src[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

;// CONCATENATED MODULE: ./node_modules/babel-loader/lib/index.js??clonedRuleSet-82.use[1]!./node_modules/@vue/vue-loader-v15/lib/loaders/templateLoader.js??ruleSet[1].rules[3]!./node_modules/@vue/vue-loader-v15/lib/index.js??vue-loader-options!./src/App.vue?vue&type=template&id=2cd99d9f&
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c('div', {
    staticClass: "container-fluid",
    attrs: {
      "id": "app"
    }
  }, [_c('PageHeader', {
    staticClass: "mb-2"
  }), _c('ValidationComponent')], 1);
};
var staticRenderFns = [];

;// CONCATENATED MODULE: ./node_modules/babel-loader/lib/index.js??clonedRuleSet-82.use[1]!./node_modules/@vue/vue-loader-v15/lib/loaders/templateLoader.js??ruleSet[1].rules[3]!./node_modules/@vue/vue-loader-v15/lib/index.js??vue-loader-options!./src/components/PageHeader.vue?vue&type=template&id=297914dc&
var PageHeadervue_type_template_id_297914dc_render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c('div', [_c('div', {
    staticClass: "projhdr"
  }, [_vm._v(_vm._s(_vm.notifications.TITLE))]), _c('span', {
    domProps: {
      "innerHTML": _vm._s(_vm.notifications.MAIN_TEXT)
    }
  })]);
};
var PageHeadervue_type_template_id_297914dc_staticRenderFns = [];

;// CONCATENATED MODULE: ./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib/index.js??clonedRuleSet-82.use[1]!./node_modules/@vue/vue-loader-v15/lib/index.js??vue-loader-options!./src/components/PageHeader.vue?vue&type=script&lang=js&
/* harmony default export */ var PageHeadervue_type_script_lang_js_ = ({
  name: "PageHeader",
  data() {
    return {
      notifications: window.notifications
    };
  }
});
;// CONCATENATED MODULE: ./src/components/PageHeader.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_PageHeadervue_type_script_lang_js_ = (PageHeadervue_type_script_lang_js_); 
;// CONCATENATED MODULE: ./node_modules/@vue/vue-loader-v15/lib/runtime/componentNormalizer.js
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent(
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */,
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options =
    typeof scriptExports === 'function' ? scriptExports.options : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) {
    // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
          injectStyles.call(
            this,
            (options.functional ? this.parent : this).$root.$options.shadowRoot
          )
        }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection(h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing ? [].concat(existing, hook) : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}

;// CONCATENATED MODULE: ./src/components/PageHeader.vue





/* normalize component */
;
var component = normalizeComponent(
  components_PageHeadervue_type_script_lang_js_,
  PageHeadervue_type_template_id_297914dc_render,
  PageHeadervue_type_template_id_297914dc_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var PageHeader = (component.exports);
;// CONCATENATED MODULE: ./node_modules/babel-loader/lib/index.js??clonedRuleSet-82.use[1]!./node_modules/@vue/vue-loader-v15/lib/loaders/templateLoader.js??ruleSet[1].rules[3]!./node_modules/@vue/vue-loader-v15/lib/index.js??vue-loader-options!./src/components/ValidationComponent.vue?vue&type=template&id=04ed3d2e&scoped=true&
var ValidationComponentvue_type_template_id_04ed3d2e_scoped_true_render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c('div', [_c('div', {
    staticClass: "row"
  }, [_vm.showAlert === false ? _c('div', {
    staticClass: "col-12"
  }, [_c('button', {
    staticClass: "btn btn-md btn-primary btn-block",
    on: {
      "click": function ($event) {
        return _vm.validate('ALL_VALIDATIONS');
      }
    }
  }, [_vm._v(_vm._s(_vm.notifications.RUN) + " ")])]) : _vm._e(), _vm.showAlert === true ? _c('div', {
    staticClass: "col-12"
  }, [_c('p', {
    staticClass: "alert",
    class: _vm.alertVariant
  }, [_vm._v(_vm._s(_vm.alertMessage))])]) : _vm._e()]), _c('hr'), _vm.showErrorContainer === true ? _c('div', {
    staticClass: "col-12"
  }, [_c('table', {
    staticClass: "table table-striped"
  }, [_vm._m(0), _c('tbody', _vm._l(_vm.rulesArray, function (rule) {
    return _c('tr', {
      key: rule.name
    }, [_c('td', {
      staticClass: "gp-info-content"
    }, [_c('div', {
      staticClass: "gp-title-content"
    }, [_c('strong', [_c('span', {
      domProps: {
        "innerHTML": _vm._s(rule.title)
      }
    }), _vm._m(1, true)])]), _c('div', {
      staticClass: "gp-body-content"
    }, [_c('p', [_c('span', {
      domProps: {
        "innerHTML": _vm._s(rule.body)
      }
    })])])]), _c('td', [_c('h6', [_c('span', {
      staticClass: "badge",
      class: rule.badge
    }, [_vm._v(_vm._s(rule.type))])])]), _c('td', _vm._l(rule.links, function (link) {
      return _c('div', {
        key: link.url
      }, [_c('div', {
        staticClass: "row"
      }, [_c('div', {
        staticClass: "col-12"
      }, [_c('a', {
        attrs: {
          "target": "_blank",
          "href": link.url
        }
      }, [_vm._v(_vm._s(link.title))])])])]);
    }), 0), _c('td', [_c('button', {
      staticClass: "btn btn-sm btn-outline-primary text-center",
      on: {
        "click": function ($event) {
          return _vm.validate(rule.key);
        }
      }
    }, [_vm._v(_vm._s(_vm.notifications.RELOAD))])])]);
  }), 0)])]) : _vm._e()]);
};
var ValidationComponentvue_type_template_id_04ed3d2e_scoped_true_staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c('thead', [_c('tr', [_c('th', [_c('h6', {
    staticClass: "projhdr"
  }, [_vm._v("Issues that you may need to fix")])]), _c('th', [_c('h6', {
    staticClass: "projhdr"
  }, [_vm._v("Type")])]), _c('th', [_c('h6', {
    staticClass: "projhdr"
  }, [_vm._v("Options")])]), _c('th')])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c('span', {
    staticClass: "title-text-plus",
    staticStyle: {
      "color": "#5492a3"
    }
  }, [_c('small', [_vm._v("(more)")])]);
}];

;// CONCATENATED MODULE: ./src/components/ValidationComponent.vue?vue&type=template&id=04ed3d2e&scoped=true&

;// CONCATENATED MODULE: ./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib/index.js??clonedRuleSet-82.use[1]!./node_modules/@vue/vue-loader-v15/lib/index.js??vue-loader-options!./src/components/ValidationComponent.vue?vue&type=script&lang=js&
/* harmony default export */ var ValidationComponentvue_type_script_lang_js_ = ({
  name: "ValidationComponent",
  methods: {
    validate: function (action) {
      var obj = this;
      window.module.ajax(action).then(function (response) {
        // Do stuff with response
        console.log("ajax complete", response);
        console.log("length", response.length);
        if (response != undefined) {
          obj.showErrorContainer = true;
          for (var key in response) {
            obj.rulesArray[key] = response[key];
            obj.rulesArray[key]['name'] = key;
            obj.rulesArray[key]['badge'] = 'badge-' + response[key]['type'].toLowerCase();
            console.log(obj.rulesArray);
          }
        }
      }).catch(function (err) {
        obj.showAlert = true;
        obj.alertMessage = err;
        console.log(err);
      });
    }
  },
  data() {
    return {
      notifications: window.notifications,
      rulesArray: {},
      showAlert: false,
      showErrorContainer: false,
      alertMessage: '',
      alertVariant: 'alert-danger'
    };
  }
});
;// CONCATENATED MODULE: ./src/components/ValidationComponent.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_ValidationComponentvue_type_script_lang_js_ = (ValidationComponentvue_type_script_lang_js_); 
;// CONCATENATED MODULE: ./src/components/ValidationComponent.vue





/* normalize component */
;
var ValidationComponent_component = normalizeComponent(
  components_ValidationComponentvue_type_script_lang_js_,
  ValidationComponentvue_type_template_id_04ed3d2e_scoped_true_render,
  ValidationComponentvue_type_template_id_04ed3d2e_scoped_true_staticRenderFns,
  false,
  null,
  "04ed3d2e",
  null
  
)

/* harmony default export */ var ValidationComponent = (ValidationComponent_component.exports);
;// CONCATENATED MODULE: ./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib/index.js??clonedRuleSet-82.use[1]!./node_modules/@vue/vue-loader-v15/lib/index.js??vue-loader-options!./src/App.vue?vue&type=script&lang=js&


/* harmony default export */ var Appvue_type_script_lang_js_ = ({
  name: 'App',
  components: {
    PageHeader: PageHeader,
    ValidationComponent: ValidationComponent
  }
});
;// CONCATENATED MODULE: ./src/App.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Appvue_type_script_lang_js_ = (Appvue_type_script_lang_js_); 
;// CONCATENATED MODULE: ./src/App.vue





/* normalize component */
;
var App_component = normalizeComponent(
  src_Appvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var App = (App_component.exports);
;// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib.js


/* harmony default export */ var entry_lib = (App);


__webpack_exports__ = __webpack_exports__["default"];
/******/ 	return __webpack_exports__;
/******/ })()
;
});
//# sourceMappingURL=go_prod_vue.umd.js.map