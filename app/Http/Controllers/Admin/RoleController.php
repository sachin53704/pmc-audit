<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('id', 'DESC')->get();
        $permissions = Permission::get();

        return view('admin.roles', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();

            $role = Role::create([
                'name' => $input['name'],
                'guard_name' => 'web',
            ]);
            $role->syncPermissions( $input['permission'] );
            DB::commit();

            return response()->json(['success'=> 'Role created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Role');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get();
        if ($role)
        {
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $role->id)
                                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                                ->all();

            $previousPermission = '';
            $permissionsHtml = '';

            foreach ($permissions as $permission)
            {
                $isChecked = in_array($permission->id, $rolePermissions) ? "checked" : "";
                if ( $previousPermission != explode('.', $permission->name)[0] )
                {
                $permissionsHtml .='</div>
                                    <div class="form-group m-t-15 row roles-checkbox-group">
                                        <strong class="mt-2"> '.ucfirst( explode('.', $permission->name)[0] ).' </strong>
                                        <div class="col-3 py-2 form-check">
                                            <label class="d-block form-check-label" for="chk-ani'.$permission->name.'">
                                                <input class="checkbox_animated form-check-input" id="chk-ani'.$permission->name.'" type="checkbox"  name="edit_permission[]" value="'.$permission->name.'" '.$isChecked.'>'.explode('.', $permission->name)[1].'
                                            </label>
                                        </div>';
                }
                else
                {
                $permissionsHtml .='<div class="col-3 py-2 form-check">
                                        <label class="d-block form-check-label" for="chk-ani'.$permission->name.'">
                                            <input class="checkbox_animated form-check-input" id="chk-ani'.$permission->name.'" type="checkbox"  name="edit_permission[]" value="'.$permission->name.'" '.$isChecked.'>'.explode('.', $permission->name)[1].'
                                        </label>
                                    </div>';
                }
                $previousPermission = explode('.', $permission->name)[0];
            }
            $permissionsHtml .='<span class="text-danger error-text edit_permission_err"></span>';

            $response = [
                'result' => 1,
                'role' => $role,
                'permission' => $permissions,
                'permissionsHtml' => $permissionsHtml,
            ];
        }
        else
        {
            $response = ['result' => 0];
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();

            $role->name = $input['edit_name'];
            $role->save();
            $role->syncPermissions( $input['edit_permission'] );
            DB::commit();

            return response()->json(['success'=> 'Role updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Role');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try
        {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
            return response()->json(['success'=> 'Role deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Role');
        }
    }
}
