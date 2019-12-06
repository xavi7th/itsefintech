<?php
use App\Modules\Admin\Models\Admin;
use App\Modules\Admin\Http\Controllers\AdminController;

Route::group(['middleware' => 'web', 'prefix' => Admin::DASHBOARD_ROUTE_PREFIX, 'namespace' => 'App\\Modules\Admin\Http\Controllers'], function(){
    AdminController::routes();	
});



