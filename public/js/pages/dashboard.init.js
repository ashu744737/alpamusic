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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/dashboard.init.js":
/*!**********************************************!*\
  !*** ./resources/js/pages/dashboard.init.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
Template Name: Lexa - Responsive Bootstrap 4 Admin Dashboard
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Dashboard
*/
/*!function ($) {
  "use strict";

  var Dashboard = function Dashboard() {}; //creates area chart

  Dashboard.prototype.createLineChart = function (element, data, xkey, ykeys, labels, lineColors, smooth=true, preUnits) {
    Morris.Line({
      element: element,
      data: data,
      xkey: xkey,
      ykeys: ykeys,
      labels: labels,
      parseTime: false,
      hideHover: 'auto',
      gridLineColor: 'rgba(108, 120, 151, 0.1)',
      resize: true,
      //defaulted to true
      lineColors: lineColors,
      lineWidth: 2,
      smooth: smooth,
      preUnits: preUnits
    });
  },

  Dashboard.prototype.createAreaChart = function (element, data, xkey, ykeys, labels, lineColors) {
    Morris.Area({
      element: element,
      pointSize: 0,
      lineWidth: 1,
      data: data,
      xkey: xkey,
      ykeys: ykeys,
      labels: labels,
      resize: true,
      gridLineColor: 'rgba(108, 120, 151, 0.1)',
      hideHover: 'auto',
      lineColors: lineColors,
      fillOpacity: .9,
      behaveLikeLine: true,
      parseTime: false,
      pointFillColors:['#ffffff'],
      pointStrokeColors: ['black']
    });
  }, //creates Donut chart
  Dashboard.prototype.createDonutChart = function (element, data, colors) {
    Morris.Donut({
      element: element,
      data: data,
      resize: true,
      colors: colors,
    });
  }, //creates Stacked chart
  Dashboard.prototype.createStackedChart = function (element, data, xkey, ykeys, labels, lineColors) {
    Morris.Bar({
      element: element,
      data: data,
      xkey: xkey,
      ykeys: ykeys,
      stacked: true,
      labels: labels,
      hideHover: 'auto',
      resize: true,
      //defaulted to true
      gridLineColor: 'rgba(108, 120, 151, 0.1)',
      barColors: function (row, series, type) {
        if(row.label == "Legal Document Delivery") return "#7A6FBE";
        else if(row.label == "WAC + Delivery") return "#DEBB27";
        else if(row.label == "Extended + Delivery") return "#fec04c";
        else if(row.label == "WAC locator") return "#28BBE3";
        else if(row.label == "Extended locator") return "#79BBE4";
        else if(row.label == "4") return "#7A6FBE";
        else if(row.label == "3") return "#DEBB27";
        else if(row.label == "2") return "#fec04c";
        else if(row.label == "1") return "#28BBE3";
        else if(row.label == "5") return "#79BBE4";
        else return lineColors;
      }
    });
  }, $('#sparkline').sparkline([8, 6, 4, 7, 10, 12, 7, 4, 9, 12, 13, 11, 12], {
    type: 'bar',
    height: '130',
    barWidth: '10',
    barSpacing: '7',
    barColor: '#7A6FBE'
  });
  Dashboard.prototype.init = function () {
    //creating area chart
    if($('#morris-area-example').length){
      var $stckedData = [
        {
          y: 'Legal Document Delivery',
          a: 4
        }, {
          y: 'WAC + Delivery',
          a: 5
        }, {
          y: 'Extended + Delivery',
          a: 2
        }, {
          y: 'WAC locator',
          a: 10,
        }, {
          y: 'Extended locator',
          a: 7
        }, {
          y: 'WAC + telephone locator',
          a: 6
        }
      ];
      this.createAreaChart('morris-area-example', $stckedData, 'y', ['a'], ['Days'], ['#7a6fbe']);
    }

    if($('#user-satisfaction').length){
      var $stckedData = [
        {
          y: '1',
          a: 4
        }, {
          y: '2',
          a: 10
        }, {
          y: '3',
          a: 17
        }, {
          y: '4',
          a: 10,
        }, {
          y: '5',
          a: 20
        }
      ];
      this.createStackedChart('user-satisfaction', $stckedData, 'y', ['a'], ['Service'], ['#7a6fbe']);
    }
    var total_income = '{{trans("form.dashboard_screen.total_income")}}';
    var total_outcome = '{{trans("form.dashboard_screen.total_outcome")}}';
    var $donutData = [
      {
        label: "Total Income",
        value: 23651
      }, {
        label: "Total Outcome",
        value: 56241
      }
    ];
    this.createDonutChart('morris-donut-example', $donutData, ['#7a6fbe', '#28bbe3']);

    var $donutData = [
      {
        label: "Open",
        value: 146
      }, {
        label: "Closed",
        value: 235
      }
    ];
    this.createDonutChart('complaints-chart', $donutData, ['#28bbe3', '#7a6fbe']);
    
    
    if($('#morris-line-example').length){
      var $data = [
        {
          y: 'Jan',
          a: 50,
          b: 80
        }, {
          y: 'Feb',
          a: 130,
          b: 100
        }, {
          y: 'Mar',
          a: 80,
          b: 60
        }, {
          y: 'Apr',
          a: 70,
          b: 200
        }, {
          y: 'May',
          a: 180,
          b: 140
        }, {
          y: 'Jun',
          a: 105,
          b: 100
        }, {
          y: 'Jul',
          a: 250,
          b: 150
        }, {
          y: 'Aug',
          a: 100,
          b: 80
        }, {
          y: 'Sept',
          a: 90,
          b: 70
        }, {
          y: 'Oct',
          a: 250,
          b: 100
        }, {
          y: 'Noc',
          a: 300,
          b: 240
        }, {
          y: 'Dec',
          a: 200,
          b: 130
        }
      ];
      this.createLineChart('morris-line-example', $data, 'y', ['a', 'b'], ['Cost per type', 'Cost of investigator'], ['#ccc', '#7a6fbe', '#28bbe3'], true, '$'); //creating bar chart  
    }

    if($('#payments-line-example').length){
      var $data = [
        {
          y: 'Jan',
          a: 550,
        }, {
          y: 'Feb',
          a: 230,
        }, {
          y: 'Mar',
          a: 380,
        }, {
          y: 'Apr',
          a: 470,
        }, {
          y: 'May',
          a: 380,
        }, {
          y: 'Jun',
          a: 750,
        }, {
          y: 'Jul',
          a: 250,
        }, {
          y: 'Aug',
          a: 470,
        }, {
          y: 'Sept',
          a: 590,
        }, {
          y: 'Oct',
          a: 500,
        }, {
          y: 'Noc',
          a: 300,
        }, {
          y: 'Dec',
          a: 200,
        }
      ];
      this.createLineChart('payments-line-example', $data, 'y', ['a'], ['Amount'], ['#7a6fbe'], true, '$'); //creating bar chart   
    }

    if($('#pending-payments-line-example').length) {
      var $data = [
        {
          y: 'Jan',
          a: 50,
        }, {
          y: 'Feb',
          a: 130,
        }, {
          y: 'Mar',
          a: 80,
        }, {
          y: 'Apr',
          a: 70,
        }, {
          y: 'May',
          a: 180,
        }, {
          y: 'Jun',
          a: 105,
        }, {
          y: 'Jul',
          a: 250,
        }, {
          y: 'Aug',
          a: 100,
        }, {
          y: 'Sept',
          a: 90,
        }, {
          y: 'Oct',
          a: 250,
        }, {
          y: 'Noc',
          a: 300,
        }, {
          y: 'Dec',
          a: 200,
        }
      ];
      this.createLineChart('pending-payments-line-example', $data, 'y', ['a'], ['Amount'], ['#FD545E'], true, '$'); //creating bar chart   
    } 

    if($('#monthly-complaints-chart').length){
      var $data = [
        {
          y: 'Jan',
          a: 8,
        }, {
          y: 'Feb',
          a: 10,
        }, {
          y: 'Mar',
          a: 4,
        }, {
          y: 'Apr',
          a: 14,
        }, {
          y: 'May',
          a: 20,
        }, {
          y: 'Jun',
          a: 16,
        }, {
          y: 'Jul',
          a: 26,
        }, {
          y: 'Aug',
          a: 6,
        }, {
          y: 'Sept',
          a: 0,
        }, {
          y: 'Oct',
          a: 12,
        }, {
          y: 'Noc',
          a: 4,
        }, {
          y: 'Dec',
          a: 8,
        }
      ];
      this.createLineChart('monthly-complaints-chart', $data, 'y', ['a'], ['Complaints'], ['#dc86ef'], true, ''); //creating bar chart   
    }

    if($('#monthly-income-employee').length){
      var $data = [
        {
          y: 'Jan',
          a: 8000,
        }, {
          y: 'Feb',
          a: 8500,
        }, {
          y: 'Mar',
          a: 9000,
        }, {
          y: 'Apr',
          a: 7000,
        }, {
          y: 'May',
          a: 6000,
        }, {
          y: 'Jun',
          a: 8000,
        }, {
          y: 'Jul',
          a: 5000,
        }, {
          y: 'Aug',
          a: 2500,
        }, {
          y: 'Sept',
          a: 2700,
        }, {
          y: 'Oct',
          a: 7000,
        }, {
          y: 'Noc',
          a: 6500,
        }, {
          y: 'Dec',
          a: 10000,
        }
      ];
      this.createLineChart('monthly-income-employee', $data, 'y', ['a'], ['Income'], ['#28BBE3'], true, '$'); //creating bar chart   
    }
  }, //init
  $.Dashboard = new Dashboard(), $.Dashboard.Constructor = Dashboard;
}(window.jQuery), //initializing 
function ($) {
  "use strict";

  $.Dashboard.init();
}(window.jQuery);*/

/***/ }),

/***/ 4:
/*!****************************************************!*\
  !*** multi ./resources/js/pages/dashboard.init.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\wamp\www\crest\uvda\resources\js\pages\dashboard.init.js */"./resources/js/pages/dashboard.init.js");


/***/ })

/******/ });