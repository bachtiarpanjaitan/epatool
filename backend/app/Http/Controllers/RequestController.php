<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RequestService;

class RequestController extends Controller
{
    private $_service;

    public function __construct(
        RequestService $service
    ) {
        $this->_service = $service;
    }

    public function myrequest(Request $req) {
        $result = $this->_service->myrequesttable($req);
        return response()->json($result, $result['code']);
    }
}
