<?php

namespace App\Http\Controllers;
use App\Services\CommonService;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    private $_service;

    public function __construct(
        CommonService $service
    ) {
        $this->_service = $service;
    }

    public function addworkspace(Request $req) {
        $result = $this->_service->addworkspace($req);
        return response()->json($result, $result['code']);
    }

    public function getworkspace(Request $req) {
        $result = $this->_service->getworkspace($req);
        return response()->json($result, $result['code']);
    }
}
