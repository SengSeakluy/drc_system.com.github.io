<?php

use App\Http\Controllers\Admin\Voyager\CashierOrderController;
use App\Http\Controllers\Admin\DashboardFilterController;
use App\Http\Controllers\Admin\CustomVoyagerAuthController;
use App\Http\Controllers\Admin\CustomVoyagerBaseController;
use App\Http\Controllers\Admin\Voyager\ExportReportController;

Route::get('/', function () {
   return redirect('/admin/signin/'.(Session::get('local') ?? 'en'));
});

Route::group(['prefix' => 'admin'], function () {

   Voyager::routes();
   Route::group(['middleware' => 'admin.user'], function (){
      Route::get('/cashier_order', [CashierOrderController::class, 'index'])->name('cashier.order.index');

   });

   Route::get('signin/{local}', [CustomVoyagerAuthController::class,'sigin']);
   // Sign Up Routes
   require __DIR__ . '/auth.php';
   Route::post('/filter',[DashboardFilterController::class, 'filterDataDashBoard'])->name('filter');
   Route::get('/clear/session-top-merchant',[DashboardFilterController::class, 'clearSessionKey'])->name('refresh_session');
   Route::get('/user-daily-register', [DashboardFilterController::class , 'UserDailyRegister']);
   Route::get('/export-csv/{slug}', [CustomVoyagerBaseController::class, 'exportCSV'])->name('export.csv');
   Route::get('/export-pdf/{slug}', [CustomVoyagerBaseController::class, 'convertToPdf'])->name('export.pdf');
   Route::get('ereports/exportToExcel',[ExportReportController::class, 'exportToExcel']);
   
});