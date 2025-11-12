import './bootstrap';
// Carga de dependencias para AdminLTE 3 (Bootstrap 4 + jQuery + Popper)
import jQuery from 'jquery';
import 'popper.js';
import 'bootstrap';
import 'admin-lte';

// Exponer jQuery globalmente (requerido por Bootstrap 4 y varios plugins de AdminLTE)
window.$ = window.jQuery = jQuery;
