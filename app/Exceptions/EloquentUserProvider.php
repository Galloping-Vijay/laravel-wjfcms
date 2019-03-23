<?php
// +----------------------------------------------------------------------
// | EloquentUserProvider.
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.yuemeet.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: vijay <1937832819@qq.com> 2019-03-23
// +----------------------------------------------------------------------

namespace App\Exceptions;

use Illuminate\Support\Str;
use Illuminate\Auth\EloquentUserProvider as BaseUserProvider;

class EloquentUserProvider extends BaseUserProvider
{
    /**
     * Instructions:
     * Author: vijay <1937832819@qq.com>
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object|void
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        // 用于标识是否是第一个登录字段，如果包含多个登录字段，使用 OR 查询
        $flag = false;
        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                continue;
            }

            if ($flag) {
                $query->orWhere($key, $value);
            } else {
                $query->where($key, $value);
                $flag = true;
            }
        }
        return $query->first();
    }
}