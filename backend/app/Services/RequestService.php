<?php
namespace App\Services;

use App\Models\{
    User,
    UserToken,
    Workspace,
    Request as mRequest,
    RequestAssigned,
    RequestLog
};
use Illuminate\Support\Facades\Validator;
use Btx\Http\Libraries\ApiResponse;
use Illuminate\Http\Request;
use Btx\Http\Response;
use Btx\Query\Transformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class RequestService extends Service {
    public function myrequesttable(Request $req) {
        $validated = Validator::make($req->all(), [
            '_limit'      => 'required',
            '_page'      => 'required',
            'workspace' => 'required'
        ] ,[
            'required' => ':attribute cannot be null',
            'string' => ':attribute must be string',
        ]);

        if ($validated->fails()) {
            return Response::badRequest($validated->errors()->first());
        }

        $data = mRequest::with('assigned.user','logs.user')->where('user_id', $req->user()->id)->where('workspace_id',$req->workspace)->filter()->get();
        // dd($data);
        $data = $data->map(function ($item) {
            $t = Carbon::parse($item->created_at)->format('l, d F Y H:i');
            $item->created = $t;
            $item->assign_users = $item->assigned->map(function ($user){
                return [
                    'id' => $user->user_id,
                    'name' => $user->user->name,
                ];
            });
            unset($item->assigned);
            return $item;
        });
        $columns = [
            ['value' => 'title', 'label' => 'Title'],
            ['value' => 'status', 'label' => 'Status'],
            ['value' => 'description', 'label' => 'Description'],
            ['value' => 'created', 'label' => 'Created At', 'show' => false]
        ];
        $total = mRequest::where('user_id', $req->user()->id)->where('workspace_id',$req->workspace)->filter(false)->get();

        ApiResponse::setIncludeData([
            'total' => $total,
            'columns' => Transformer::quasarColumn($columns)
        ]);

        return Response::ok('Data Loaded', $data);

    }

     public function update(Request $req,$id) {
        $request = $req->all();
        $request['id'] = $id;
        try {
            if(empty($id)) return Response::badRequest('ID cannot be null');
            $validated = Validator::make($req->all(), [
                'status' => 'required',
            ] ,[
                'required' => ':attribute cannot be null',
                'string' => ':attribute must be string',
            ]);
    
            if ($validated->fails()) {
                return Response::badRequest($validated->errors()->first());
            }
            
            DB::beginTransaction();
            $userAssigneds = [];

            foreach ($request['assign_users'] as $key => $user) {
                array_push($userAssigneds, [
                    'user_id' => $user['id'],
                    'request_id' => $request['id'],
                ]);
            }
            $data = mRequest::where('id', $id)->first();
            RequestAssigned::where('request_id', $request['id'])->delete();
            RequestAssigned::insert($userAssigneds);
            $this->saveHistory($id,$data->status, $request['status']);
            $data->update([
                'status' => $request['status'],
                'description' => $request['description'],
                'title' => $request['title'],
                'workspace_id' => $request['workspace_id'],
                'from_date' => $request['from_date'],
                'to_date' => $request['to_date']
            ]);
            DB::commit();
            return Response::ok('Request Updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::internalServerError($e->getMessage());
        }
     }

     public function create(Request $req) {
        $request = $req->all();
        try {
            $validated = Validator::make($req->all(), [
                'title' => 'required',
                'description' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'workspace_id' => 'required',
            ] ,[
                'required' => ':attribute cannot be null',
                'string' => ':attribute must be string',
            ]);
    
            if ($validated->fails()) {
                return Response::badRequest($validated->errors()->first());
            }
            
            DB::beginTransaction();
            $data = mRequest::create([
                'status' => 'new',
                'description' => $request['description'],
                'title' => $request['title'],
                'workspace_id' => $request['workspace_id'],
                'from_date' => $request['from_date'],
                'to_date' => $request['to_date'],
                'user_id' => Auth::user()->id,
            ]);

            $userAssigneds = [];
            foreach ($request['assign_users'] as $key => $user) {
                array_push($userAssigneds, [
                    'user_id' => $user['id'],
                    'request_id' => $data->id,
                ]);
            }
            RequestAssigned::insert($userAssigneds);
            $this->saveHistory($data->id);
            DB::commit();
            return Response::ok('Request Created',$data);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::internalServerError($e->getMessage());
        }
    }

    private function saveHistory($request_id,$oldStatus='', $status = 'new'){
        $log = new RequestLog();
        if(empty($oldStatus)){
            $log->status = $status;
            $log->request_id = $request_id;
            $log->user_id = Auth::user()->id;
            $log->description = Auth::user()->name.' created <b>'.$status.'</b> request';
        } else{
            $log->status = $status;
            $log->request_id = $request_id;
            $log->user_id = Auth::user()->id;
            $log->description =  Auth::user()->name.' has been change status request from <b>'.$oldStatus. '</b> to <b>'.$status.'</b>';
        }
        $log->save();
    }
}