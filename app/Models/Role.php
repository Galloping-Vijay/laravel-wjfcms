<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'status', 'guard_name', 'created_at'
    ];

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    public static $guard_name_list = [
        'admin', 'home'
    ];

    /**
     * Description:创建角色并关联权限
     * User: Vijay
     * Date: 2019/6/9
     * Time: 22:19
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model|BaseRole|void
     */
    public static function create(array $attributes = [])
    {
        DB::beginTransaction();
        try {
            //创建角色
            $role = parent::create($attributes); // TODO: Change the autogenerated stub
            // 创建角色并赋予已创建的权限
            if (isset($attributes['permission_name']) && !empty($attributes['permission_name'])) {
                $role->givePermissionTo($attributes['permission_name']);
            }
            DB::commit();
        } catch (\Exception $e) {// 捕获异常
            echo 'Message: ' . $e->getMessage();
            DB::rollBack();
        }
    }

    /**
     * Description:角色编辑
     * User: Vijay
     * Date: 2019/6/9
     * Time: 23:31
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        DB::beginTransaction();
        try {
            $res = parent::update($attributes, $options); // TODO: Change the autogenerated stub
            //修改权限
            if (isset($attributes['permission_name']) && !empty($attributes['permission_name'])) {
                //获取该角色的所有权限
                $permissions = $this->getPermissionNames()->toArray();
                //获取交集
                $intersect = array_intersect($permissions, $attributes['permission_name']);
                //如果交集长度等于原角色权限等于现有角色权限,则说明没有改变,可以跳过权限修改
                if (count($intersect) > 0 && count($intersect) == count($permissions) && count($intersect) == count($attributes['permission_name'])) {
                    DB::commit();
                    return $res;
                }
                //原权限,不在交集里面的则剔除
                foreach ($permissions as $permission) {
                    if (!in_array($permission, $intersect)) {
                        $this->revokePermissionTo($permission);
                    }
                }
                //新权限,如果不在交集里面,则新增
                foreach ($attributes['permission_name'] as $permission_new) {
                    if (!in_array($permission_new, $intersect)) {
                        $this->givePermissionTo($permission_new);
                    }
                }
            }
            DB::commit();
            return $res;
        } catch (\Exception $e) {// 捕获异常
            echo 'Message: ' . $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    /**
     * Description:彻底删除
     * User: Vijay
     * Date: 2019/6/9
     * Time: 23:59
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function forceDelete()
    {
        DB::beginTransaction();
        try {
            $this->forceDeleting = true;
            $res = tap($this->delete(), function ($deleted) {
                $this->forceDeleting = false;

                if ($deleted) {
                    $this->fireModelEvent('forceDeleted', false);
                }
            });
            //获取该角色的所有权限
            $permissions = $this->getPermissionNames()->toArray();
            //剔除
            foreach ($permissions as $permission) {
                $this->revokePermissionTo($permission);
            }
            DB::commit();
            return $res;
        } catch (\Exception $e) {// 捕获异常
            echo 'Message: ' . $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    /**
     * Description:获取所属觉得下所有权限的字段集合
     * User: Vijay
     * Date: 2019/6/6
     * Time: 23:43
     * @param string $field
     * @return Collection
     */
    public function getPermissionFieldList($field = 'id'): Collection
    {
        return $this->permissions->pluck($field);
    }
}
