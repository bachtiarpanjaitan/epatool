<?php
namespace App\Services;

use App\Models\{
    User,
    UserToken,
    Workspace,
    Request as mRequest
};
use Illuminate\Support\Facades\Validator;
use Btx\Http\Libraries\ApiResponse;
use Illuminate\Http\Request;
use Btx\Http\Response;
use Btx\Query\Transformer;
use Carbon\Carbon;
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

        $data = mRequest::where('user_id', $req->user()->id)->where('workspace_id',$req->workspace)->filter()->get();

        $data = $data->map(function ($item) {
            $t = Carbon::parse($item->created_at)->format('l, d F Y H:i');
            $item->created = $t;
            return $item;
        });
        $columns = [
            ['value' => 'title', 'label' => 'Title'],
            ['value' => 'status', 'label' => 'Status'],
            ['value' => 'description', 'label' => 'Description'],
            ['value' => 'created', 'label' => 'Created At']
        ];
        $total = mRequest::where('user_id', $req->user()->id)->where('workspace_id',$req->workspace)->filter(false)->get();

        ApiResponse::setIncludeData([
            'total' => $total,
            'columns' => Transformer::quasarColumn($columns)
        ]);

        return Response::ok('Data Loaded', $data);

    }
}