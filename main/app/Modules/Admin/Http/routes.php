<?php
use App\Modules\Admin\Models\Admin;
use App\Modules\Admin\Http\Controllers\AdminController;
use App\Modules\Admin\Models\Merchant;

AdminController::routes();

Merchant::merchantRoutes();
