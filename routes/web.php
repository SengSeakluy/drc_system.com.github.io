<?php

use Illuminate\Http\Request;
use FontLib\TrueType\Collection;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Session\Session;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/web/frontend/index.php';

require __DIR__ . '/web/admin/index.php';