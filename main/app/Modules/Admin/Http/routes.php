<?php
use App\Modules\Admin\Http\Controllers\AdminController;
use App\Modules\Admin\Models\MerchantTransaction;

AdminController::routes();

MerchantTransaction::merchantRoutes();
