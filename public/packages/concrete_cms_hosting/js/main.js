/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/frontend/components/HostingControlPanel.vue":
/*!************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _HostingControlPanel_vue_vue_type_template_id_610573f2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./HostingControlPanel.vue?vue&type=template&id=610573f2& */ "./assets/frontend/components/HostingControlPanel.vue?vue&type=template&id=610573f2&");
/* harmony import */ var _HostingControlPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./HostingControlPanel.vue?vue&type=script&lang=js& */ "./assets/frontend/components/HostingControlPanel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _HostingControlPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _HostingControlPanel_vue_vue_type_template_id_610573f2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _HostingControlPanel_vue_vue_type_template_id_610573f2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "assets/frontend/components/HostingControlPanel.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HostingControlPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./HostingControlPanel.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HostingControlPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel.vue?vue&type=template&id=610573f2&":
/*!*******************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel.vue?vue&type=template&id=610573f2& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HostingControlPanel_vue_vue_type_template_id_610573f2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./HostingControlPanel.vue?vue&type=template&id=610573f2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel.vue?vue&type=template&id=610573f2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HostingControlPanel_vue_vue_type_template_id_610573f2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HostingControlPanel_vue_vue_type_template_id_610573f2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue":
/*!*********************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _LagoonProjectPanel_vue_vue_type_template_id_401c5151___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LagoonProjectPanel.vue?vue&type=template&id=401c5151& */ "./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=template&id=401c5151&");
/* harmony import */ var _LagoonProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LagoonProjectPanel.vue?vue&type=script&lang=js& */ "./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LagoonProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LagoonProjectPanel_vue_vue_type_template_id_401c5151___WEBPACK_IMPORTED_MODULE_0__["render"],
  _LagoonProjectPanel_vue_vue_type_template_id_401c5151___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LagoonProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./LagoonProjectPanel.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LagoonProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=template&id=401c5151&":
/*!****************************************************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=template&id=401c5151& ***!
  \****************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LagoonProjectPanel_vue_vue_type_template_id_401c5151___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./LagoonProjectPanel.vue?vue&type=template&id=401c5151& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=template&id=401c5151&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LagoonProjectPanel_vue_vue_type_template_id_401c5151___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LagoonProjectPanel_vue_vue_type_template_id_401c5151___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue":
/*!*********************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProjectPanel_vue_vue_type_template_id_12d20b19___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProjectPanel.vue?vue&type=template&id=12d20b19& */ "./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=template&id=12d20b19&");
/* harmony import */ var _ProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProjectPanel.vue?vue&type=script&lang=js& */ "./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProjectPanel_vue_vue_type_template_id_12d20b19___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProjectPanel_vue_vue_type_template_id_12d20b19___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ProjectPanel.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectPanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=template&id=12d20b19&":
/*!****************************************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=template&id=12d20b19& ***!
  \****************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectPanel_vue_vue_type_template_id_12d20b19___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ProjectPanel.vue?vue&type=template&id=12d20b19& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=template&id=12d20b19&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectPanel_vue_vue_type_template_id_12d20b19___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectPanel_vue_vue_type_template_id_12d20b19___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue":
/*!******************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProjectSelectList_vue_vue_type_template_id_71a2c18b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProjectSelectList.vue?vue&type=template&id=71a2c18b& */ "./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=template&id=71a2c18b&");
/* harmony import */ var _ProjectSelectList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProjectSelectList.vue?vue&type=script&lang=js& */ "./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProjectSelectList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProjectSelectList_vue_vue_type_template_id_71a2c18b___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProjectSelectList_vue_vue_type_template_id_71a2c18b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "assets/frontend/components/HostingControlPanel/ProjectSelectList.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectSelectList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProjectSelectList.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectSelectList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=template&id=71a2c18b&":
/*!*************************************************************************************************************!*\
  !*** ./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=template&id=71a2c18b& ***!
  \*************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectSelectList_vue_vue_type_template_id_71a2c18b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProjectSelectList.vue?vue&type=template&id=71a2c18b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=template&id=71a2c18b&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectSelectList_vue_vue_type_template_id_71a2c18b___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProjectSelectList_vue_vue_type_template_id_71a2c18b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./assets/frontend/components/NewHostingProjectSelector.vue":
/*!******************************************************************!*\
  !*** ./assets/frontend/components/NewHostingProjectSelector.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NewHostingProjectSelector_vue_vue_type_template_id_93fed978___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NewHostingProjectSelector.vue?vue&type=template&id=93fed978& */ "./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=template&id=93fed978&");
/* harmony import */ var _NewHostingProjectSelector_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NewHostingProjectSelector.vue?vue&type=script&lang=js& */ "./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NewHostingProjectSelector_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NewHostingProjectSelector_vue_vue_type_template_id_93fed978___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NewHostingProjectSelector_vue_vue_type_template_id_93fed978___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "assets/frontend/components/NewHostingProjectSelector.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewHostingProjectSelector_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./NewHostingProjectSelector.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewHostingProjectSelector_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=template&id=93fed978&":
/*!*************************************************************************************************!*\
  !*** ./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=template&id=93fed978& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewHostingProjectSelector_vue_vue_type_template_id_93fed978___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./NewHostingProjectSelector.vue?vue&type=template&id=93fed978& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=template&id=93fed978&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewHostingProjectSelector_vue_vue_type_template_id_93fed978___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewHostingProjectSelector_vue_vue_type_template_id_93fed978___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./assets/frontend/main.js":
/*!*********************************!*\
  !*** ./assets/frontend/main.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_concretecms_bedrock_assets_cms_js_vue_Manager__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../node_modules/@concretecms/bedrock/assets/cms/js/vue/Manager */ "./node_modules/@concretecms/bedrock/assets/cms/js/vue/Manager.js");
/* harmony import */ var _components_HostingControlPanel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/HostingControlPanel */ "./assets/frontend/components/HostingControlPanel.vue");
/* harmony import */ var _components_NewHostingProjectSelector__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/NewHostingProjectSelector */ "./assets/frontend/components/NewHostingProjectSelector.vue");
/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

_node_modules_concretecms_bedrock_assets_cms_js_vue_Manager__WEBPACK_IMPORTED_MODULE_0__["default"].bindToWindow(window);


window.Concrete.Vue.createContext('frontend', {
  HostingControlPanel: _components_HostingControlPanel__WEBPACK_IMPORTED_MODULE_1__["default"],
  NewHostingProjectSelector: _components_NewHostingProjectSelector__WEBPACK_IMPORTED_MODULE_2__["default"]
}, 'frontend');
Concrete.Vue.activateContext('frontend', function (Vue, config) {
  new Vue({
    el: 'div[vue-hosting]',
    components: config.components
  });
});

/***/ }),

/***/ "./node_modules/@concretecms/bedrock/assets/cms/js/vue/Manager.js":
/*!************************************************************************!*\
  !*** ./node_modules/@concretecms/bedrock/assets/cms/js/vue/Manager.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Manager; });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Typescript interface
 *\/
 interface Context {
    [key: String]: {} | Component
}
 /**/
class Manager {
    /**
     * Create a new Manager
     *
     * @param {{[key: String]: Context}} contexts A list of component lists keyed by context name
     */
    constructor(contexts) {
        this.contexts = contexts || {}
    }

    /**
     * Ensures that our Concrete.Vue manager is available on the window object.
     * Note: Do NOT call this before the global Concrete object is created in the CMS context.
     *
     * @param {Window} window
     */
    static bindToWindow(window) {
        window.Concrete = window.Concrete || {}

        if (!window.Concrete.Vue) {
            window.Concrete.Vue = new Manager()
            window.dispatchEvent(new CustomEvent('concrete.vue.ready', {
                detail: window.Concrete.Vue
            }))
        }
    }

    /**
     * Returns a list of components for the current string `context`
     *
     * @param {String} context
     * @returns {{[key: String]: {}}} A list of components keyed by their handle
     */
    getContext(context) {
        return this.contexts[context] || {}
    }

    /**
     * Actives a particular context (and its components) for a particular selector.
     *
     * @param {String} context
     * @param {Function} callback (Vue, options) => new Vue(options)
     */
    activateContext(context, callback) {
        return callback(vue__WEBPACK_IMPORTED_MODULE_0___default.a, {
            components: this.getContext(context)
        })
    }

    /**
     * For a given string `context`, adds the passed components to make them available within that context.
     *
     * @param {String} context The name of the context to extend
     * @param {{[key: String]: {}}} components A list of component objects to add into the context
     * @param {String} newContext The new name of the context if different from context
     */
    extendContext(context, components, newContext) {
        newContext = newContext || context
        this.contexts[newContext] = {
            ...this.getContext(context),
            ...components
        }
    }

    /**
     * Creates a Context object that has access to the specified components. If `fromContext` is passed, the new
     * context object will be created with the same components as the `fromContext` object.
     *
     * @param context
     * @param components
     * @param fromContext
     */
    createContext(context, components, fromContext) {
        this.extendContext(fromContext, components, context)
    }
}


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _HostingControlPanel_ProjectSelectList__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./HostingControlPanel/ProjectSelectList */ "./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue");
/* harmony import */ var _HostingControlPanel_Project_ProjectPanel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./HostingControlPanel/Project/ProjectPanel */ "./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue");
/* harmony import */ var _HostingControlPanel_LagoonProject_LagoonProjectPanel__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./HostingControlPanel/LagoonProject/LagoonProjectPanel */ "./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    LagoonProjectPanel: _HostingControlPanel_LagoonProject_LagoonProjectPanel__WEBPACK_IMPORTED_MODULE_2__["default"],
    ProjectPanel: _HostingControlPanel_Project_ProjectPanel__WEBPACK_IMPORTED_MODULE_1__["default"],
    ProjectSelectList: _HostingControlPanel_ProjectSelectList__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    project: Object,
    projects: Array
  },
  data: function data() {
    return {};
  },
  methods: {},
  computed: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    project: Object
  },
  data: function data() {
    return {
      activeLink: 'locations'
    };
  },
  methods: {},
  computed: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    project: Object
  },
  data: function data() {
    return {
      activeLink: 'locations'
    };
  },
  methods: {},
  computed: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    project: Object,
    projects: Array
  },
  data: function data() {
    return {
      selectedProject: this.project
    };
  },
  methods: {
    changeProject: function changeProject() {
      window.location.href = CCM_DISPATCHER_FILENAME + '/account/projects/panel/' + this.selectedProject.id;
    }
  },
  computed: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {},
  data: function data() {
    return {};
  },
  methods: {},
  computed: {}
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel.vue?vue&type=template&id=610573f2&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel.vue?vue&type=template&id=610573f2& ***!
  \*************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c("div", { staticClass: "d-flex" }, [
        _c("h1", { staticClass: "highlight" }, [_vm._v("Hosting")]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "d-flex ml-auto align-items-center" },
          [
            _c("project-select-list", {
              attrs: { project: _vm.project, projects: _vm.projects }
            })
          ],
          1
        )
      ]),
      _vm._v(" "),
      _vm.project.siteType === "project" ? _c("project-panel") : _vm._e(),
      _vm._v(" "),
      _vm.project.siteType === "lagoon" ? _c("lagoon-project-panel") : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=template&id=401c5151&":
/*!**********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel/LagoonProject/LagoonProjectPanel.vue?vue&type=template&id=401c5151& ***!
  \**********************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("ul", { staticClass: "nav nav-tabs" }, [
      _c("li", { staticClass: "nav-item" }, [
        _c(
          "a",
          {
            class: {
              "nav-link": true,
              active: this.activeLink === "locations"
            },
            attrs: { href: "#" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.activeLink = "locations"
              }
            }
          },
          [_vm._v("Locations")]
        )
      ]),
      _vm._v(" "),
      _c("li", { staticClass: "nav-item" }, [
        _c(
          "a",
          {
            class: { "nav-link": true, active: this.activeLink === "code" },
            attrs: { href: "#" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.activeLink = "code"
              }
            }
          },
          [_vm._v("Code")]
        )
      ]),
      _vm._v(" "),
      _c("li", { staticClass: "nav-item" }, [
        _c(
          "a",
          {
            class: {
              "nav-link": true,
              active: this.activeLink === "deployments"
            },
            attrs: { href: "#" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.activeLink = "deployments"
              }
            }
          },
          [_vm._v("Deployments")]
        )
      ]),
      _vm._v(" "),
      _c("li", { staticClass: "nav-item" }, [
        _c(
          "a",
          {
            class: { "nav-link": true, active: this.activeLink === "backups" },
            attrs: { href: "#" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.activeLink = "backups"
              }
            }
          },
          [_vm._v("Backups")]
        )
      ]),
      _vm._v(" "),
      _c("li", { staticClass: "nav-item" }, [
        _c(
          "a",
          {
            class: { "nav-link": true, active: this.activeLink === "teams" },
            attrs: { href: "#" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.activeLink = "teams"
              }
            }
          },
          [_vm._v("Teams")]
        )
      ])
    ]),
    _vm._v(" "),
    _c("hr", { staticClass: "mt-0" }),
    _vm._v(" "),
    _vm.activeLink === "locations"
      ? _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-body" }, [
            _vm._v(
              "\n            All registered hosting locations coming soon.\n        "
            )
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.activeLink === "code"
      ? _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-body" }, [
            _vm._v("\n            Code editing coming soon.\n        ")
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.activeLink === "deployments"
      ? _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-body" }, [
            _vm._v("\n            Deployments coming soon.\n        ")
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.activeLink === "backups"
      ? _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-body" }, [
            _vm._v("\n            Backups coming soon.\n        ")
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.activeLink === "teams"
      ? _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-body" }, [
            _vm._v(
              "\n            Teams management interface coming soon.\n        "
            )
          ])
        ])
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=template&id=12d20b19&":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel/Project/ProjectPanel.vue?vue&type=template&id=12d20b19& ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("ul", { staticClass: "nav nav-tabs" }, [
      _c("li", { staticClass: "nav-item" }, [
        _c(
          "a",
          {
            class: {
              "nav-link": true,
              active: this.activeLink === "locations"
            },
            attrs: { href: "#" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.activeLink = "locations"
              }
            }
          },
          [_vm._v("Locations")]
        )
      ]),
      _vm._v(" "),
      _c("li", { staticClass: "nav-item" }, [
        _c(
          "a",
          {
            class: { "nav-link": true, active: this.activeLink === "teams" },
            attrs: { href: "#" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.activeLink = "teams"
              }
            }
          },
          [_vm._v("Teams")]
        )
      ])
    ]),
    _vm._v(" "),
    _c("hr", { staticClass: "mt-0" }),
    _vm._v(" "),
    _vm.activeLink === "locations"
      ? _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-body" }, [
            _vm._v(
              "\n            All registered hosting locations coming soon.\n        "
            )
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.activeLink === "teams"
      ? _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-body" }, [
            _vm._v(
              "\n            Teams management interface coming soon.\n        "
            )
          ])
        ])
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=template&id=71a2c18b&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/HostingControlPanel/ProjectSelectList.vue?vue&type=template&id=71a2c18b& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("form", { staticClass: "form-inline" }, [
      _c("label", { staticClass: "mr-2", attrs: { for: "selectProjects" } }, [
        _vm._v("Projects")
      ]),
      _vm._v(" "),
      _c(
        "select",
        {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.selectedProject,
              expression: "selectedProject"
            }
          ],
          staticClass: "custom-select mr-sm-2",
          attrs: { id: "selectProjects" },
          on: {
            change: [
              function($event) {
                var $$selectedVal = Array.prototype.filter
                  .call($event.target.options, function(o) {
                    return o.selected
                  })
                  .map(function(o) {
                    var val = "_value" in o ? o._value : o.value
                    return val
                  })
                _vm.selectedProject = $event.target.multiple
                  ? $$selectedVal
                  : $$selectedVal[0]
              },
              _vm.changeProject
            ]
          }
        },
        _vm._l(_vm.projects, function(projectEntry) {
          return _c("option", { domProps: { value: projectEntry } }, [
            _vm._v(_vm._s(projectEntry.name))
          ])
        }),
        0
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=template&id=93fed978&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./assets/frontend/components/NewHostingProjectSelector.vue?vue&type=template&id=93fed978& ***!
  \*******************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [_vm._v("\n    HIII!!!\n")])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

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
  if (moduleIdentifier) { // server build
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
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 0:
/*!***************************************!*\
  !*** multi ./assets/frontend/main.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/andrewembler/projects/community.concretecms.com/public/packages/concrete_cms_hosting/build/assets/frontend/main.js */"./assets/frontend/main.js");


/***/ }),

/***/ "vue":
/*!**********************!*\
  !*** external "Vue" ***!
  \**********************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = Vue;

/***/ })

/******/ });