<?php
namespace App\Services;

use App\Models\{
    User,
    UserToken,
    Workspace
};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Btx\Http\Response;
class CommonService extends Service {
    public function addworkspace(Request $req) {
        $validated = Validator::make($req->all(), [
            'name' => 'required|string',
        ],[
            'required' => ':attribute cannot be null',
            'string' => ':attribute must be string'
        ]);
        if ($validated->fails()) {
            return Response::badRequest($validated->errors()->first());
        }

        $existingWorkspace = Workspace::where('name', trim($req->name))->first();
        if ($existingWorkspace) {
            return Response::badRequest('Workspace name already exists');
        }
        $ws = new Workspace();
        $ws->name = trim($req->name);
        $ws->user_id = auth()->user()->id;
        $ws->save();
        return Response::ok('Workspace added successfully');
    }

    public function getworkspace(Request $req) {
        $wss = Workspace::where('user_id', auth()->user()->id)->get();
        return Response::ok('Workspace retrieved successfully',$wss);
    }

    public function getworkerusers(Request $req) {
        $users = User::select('id','name')->where('role', 2)->get();
        return Response::ok('Worker users retrieved successfully',$users);
    }
}