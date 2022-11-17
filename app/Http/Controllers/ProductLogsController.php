<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductLogs;

class ProductLogsController extends Controller
{
    public static function getAll() {
        return ProductLogs::all();
    }
}
