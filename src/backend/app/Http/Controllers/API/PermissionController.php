<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // enable api middleware
        $this->middleware(['auth:api', 'role:System Admin']);
    }

    public function index()
    {
        try {
            $permissions = Permission::get();
            $this->response['data'] = PermissionResource::collection($permissions);
        } catch (Exception $e) {  // @codeCoverageIgnoreStart
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }  // @codeCoverageIgnoreEnd

        return response()->json($this->response, $this->response['code']);
    }
}
