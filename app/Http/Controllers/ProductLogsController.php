<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductLogs;

class ProductLogsController extends Controller
{
    public static function getAll() {

        $productLogs = ProductLogs::
                                leftJoin('users u', 'u.id', '=', 'product_logs.user_id')
                                ->select('product_logs.created_at,u.name,product_logs.request,product_logs.data,product_logs.error')
                                ->get()->toArray();
        return $productLogs;

    }
}
