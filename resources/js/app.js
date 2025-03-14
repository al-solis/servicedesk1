
import 'flowbite';

import './bootstrap';

import { DataTable } from 'simple-datatables';

import Alpine from 'alpinejs';
window.DataTable = DataTable;
//window.simpleDatatables = { DataTable };
window.Alpine = Alpine;

Alpine.start();
