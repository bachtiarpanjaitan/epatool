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

    public function update(Request $req,$id) {
        $result = $this->_service->update($req,$id);
        return response()->json($result, $result['code']);
    }

    public function create(Request $req) {
        $result = $this->_service->create($req);
        return response()->json($result, $result['code']);
    }

    public function createFeedback(Request $req){
        $result = $this->_service->createFeedback($req);
        return response()->json($result, $result['code']);
    }
}
