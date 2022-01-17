<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ContactController as Contact;

class ContactController extends Controller
{
    protected $ignore_routes = [
        'contacts'
    ];

    public function index()
    {
        return (new Contact())->index();
    }
}
