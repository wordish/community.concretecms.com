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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/karma/html/result_list.html":
/*!********************************************!*\
  !*** ./assets/karma/html/result_list.html ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function(obj) {
obj || (obj = {});
var __t, __p = '', __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<!--\n @project:   ConcreteCMS Community\n\n @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)\n @author     Fabian Bitter (fabian@bitter.de)\n-->\n\n';
 _.each(results, function(result){ ;
__p += '\n<div class="karma-entry">\n    <div class="row">\n        <div class="col-auto profile-picture">\n            <div class="profile-image">\n                <div class="image-wrapper">\n                    <img src="' +
((__t = (result.avatar)) == null ? '' : __t) +
'" alt="' +
((__t = (result.username)) == null ? '' : __t) +
'">\n                </div>\n            </div>\n        </div>\n\n        <div class="col-auto infos">\n            <p class="text-muted">\n                ' +
((__t = (result.info)) == null ? '' : __t) +
'\n            </p>\n\n            <h3>\n                ' +
((__t = (result.title)) == null ? '' : __t) +
'\n            </h3>\n\n            <p>\n                ' +
((__t = (result.description)) == null ? '' : __t) +
'\n            </p>\n        </div>\n\n        <div class="col points">\n            <h3 class="float-right">\n                ' +
((__t = (result.points)) == null ? '' : __t) +
'\n            </h3>\n        </div>\n    </div>\n\n    <div class="clearfix"></div>\n</div>\n';
 }); ;


}
return __p
};


/***/ }),

/***/ "./assets/karma/js/main.js":
/*!*********************************!*\
  !*** ./assets/karma/js/main.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _result_list__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./result_list */ "./assets/karma/js/result_list.js");
/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */
// Custom assets


if ($(".karma-page").length > 0) {
  $("#load-more a").click(function () {
    Object(_result_list__WEBPACK_IMPORTED_MODULE_0__["default"])();
  });
  $(window).scroll(function () {
    if ($(document).height() - $(this).height() == $(this).scrollTop()) {
      Object(_result_list__WEBPACK_IMPORTED_MODULE_0__["default"])();
    }
  });
}

/***/ }),

/***/ "./assets/karma/js/result_list.js":
/*!****************************************!*\
  !*** ./assets/karma/js/result_list.js ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */
var currentPage = 2;
/* harmony default export */ __webpack_exports__["default"] = (function (options) {
  var tpl = __webpack_require__(/*! ../html/result_list.html */ "./assets/karma/html/result_list.html");

  if ($("#load-more").hasClass("d-none")) {
    return;
  }

  $.ajax({
    url: CCM_DISPATCHER_FILENAME + "/account/karma/fetch_results",
    method: "GET",
    data: {
      ccm_paging_p: currentPage
    },
    success: function success(response) {
      var html = tpl(response);
      $("#karma-container").append(html);

      if (!response.hasNextPage) {
        $("#load-more").addClass("d-none");
      } else {
        currentPage++;
      }
    }
  });
});

/***/ }),

/***/ 1:
/*!***************************************!*\
  !*** multi ./assets/karma/js/main.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/fabianbitter/Projekte/community.concretecms.com/public/packages/concrete_cms_community/build/assets/karma/js/main.js */"./assets/karma/js/main.js");


/***/ })

/******/ });