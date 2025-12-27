<?php

// MidtransController removed — placeholder to avoid 500 if route still exists.
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        return response('Midtrans integration removed', 410);
    }
}
