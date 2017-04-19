/*
 * This file is part of the MirschAdmin package.
 *
 * (c) Mirko Schaal and Contributors of the project
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

require('expose?$!expose?jQuery!jquery');

require('bootstrap');
require('bootstrap/dist/css/bootstrap.min.css');

require('bootstrap-daterangepicker');
require('datatables.net-bs');
require('datatables.net-bs/css/dataTables.bootstrap.css');
//require('datatables.net-buttons-bs');
//require('datatables.net-buttons/js/buttons.colVis.js'); // Column visibility
//require('datatables.net-buttons/js/buttons.html5.js');  // HTML 5 file export
//require('datatables.net-buttons/js/buttons.flash.js');  // Flash file export
//require('datatables.net-buttons/js/buttons.print.js');  // Print view button

require('select2');
require('select2/dist/css/select2.min.css');
require('select2-bootstrap-theme/dist/select2-bootstrap.min.css');

// AdminLTE, skins and font-awesome
require('admin-lte'); // 'admin-lte/dist/js/app.min.js'
require('admin-lte/dist/css/AdminLTE.min.css');
require('admin-lte/dist/css/skins/_all-skins.min.css');
require('font-awesome/css/font-awesome.min.css');
require('icheck');
require('icheck/skins/square/blue.css');

// Add other libraries here..
require('jquery-slimscroll');

// Fastclick prevents the 300ms touch delay on touch devices
var attachFastClick = require('fastclick');
attachFastClick.attach(document.body);

require('./js/admin.js');
require('./css/admin.less');
