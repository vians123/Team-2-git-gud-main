<?php

namespace App\Services\API;

use DB;
use Exception;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Permission;
use App\Exceptions\RoleNotFoundException;

class RoleService
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function search(array $conditions): array
    {
        // default to 1 if page not provided
        $page = 1;
        $limit = config('search.results_per_page');

        if (array_key_exists('page', $conditions) === true) {
            $page = $conditions['page'];
        }

        if (array_key_exists('limit', $conditions) === true) {
            $limit = $conditions['limit'];
        }

        $skip = ($page > 1) ? ($page * $limit - $limit) : 0;

        // initialize query
        $query = $this->role;

        // if keyword is provided
        if (array_key_exists('keyword', $conditions)) {
            $query = $query->where('name', 'LIKE', "%{$conditions['keyword']}%");
        }

        // perform user search
        $results = $query->skip($skip)
                        ->orderBy($conditions['sort'], $conditions['order'])
                        ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit, 'page' => $page];

        return paginated($results, RoleResource::class, $page, $urlParams);
    }

    public function findById(int $id): Role
    {
        // retrieve the role
        $role = $this->role->find($id);

        if (!($role instanceof Role)) {
            throw new RoleNotFoundException();
        }

        return $role;
    }

    public function create(array $params): Role
    {
        DB::beginTransaction();

        try {
            // create role
            $role = $this->role->create([
                'name' => $params['name'],
                'guard_name' => 'api',
            ]);

            // attach permissions
            $role->syncPermissions($params['permissions']);

            DB::commit();
        } catch (Exception $e) {  // @codeCoverageIgnoreStart
            DB::rollback();

            throw $e;
        }  // @codeCoverageIgnoreEnd

        return $role;
    }

    public function update(array $params): Role
    {
        // retrieve user information
        $role = $this->findById($params['id']);

        // perform update
        $role->update(['name' => $params['name']]);
        $role->syncPermissions($params['permissions']);

        return $role;
    }

    public function delete(int $id): bool
    {
        // retrieve role
        $role = $this->findById($id);

        // perform delete
        $role->delete();

        return true;
    }
}
