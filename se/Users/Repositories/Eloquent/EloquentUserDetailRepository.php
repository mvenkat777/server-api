<?php

namespace Platform\Users\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Users\Repositories\Contracts\UserDetailRepository;

class EloquentUserDetailRepository extends Repository implements UserDetailRepository
{
    public function model()
    {
        return 'App\UserDetail';
    }

    public function createUserDetail($data)
    {
        return $this->model->create($data);
    }

    public function userDetailsAddOrUpdate($column)
    {
        $user = $this->model->where($column['getColumn']['name'], $column['getColumn']['value'])
                            ->first();

        if ($user) {
            $user = $user->update($column['setColumn']);
        } else {
            $this->create($column['setColumn']);
        }

        return 'success';
    }

    public function search($query = null, $count = null, $order = null, $form = null)
    {
        if ($query != null) {
            $search = $this->model;
            $next = '';
            foreach ($query as $key => $value) {
                if ($value == 'and') {
                    $next = 'and';
                } elseif ($value == 'or') {
                    $next = 'or';
                } elseif ($next == 'and') {
                    $search = $search->where($key, '=', $value);
                } elseif ($next == 'or') {
                    $search = $search->orWhere($key, '=', $value);
                } else {
                    $search = $search->where($key, '=', $value);
                }
            }
            if ($count != null && $order != null) {
                return $search->join('users', 'user_id', '=', 'users.id')
                    ->select('user_details.*', 'users.email', 'users.display_name')->orderBy($order, 'desc')->paginate($count);
            } elseif ($count != null) {
                return $search->join('users', 'user_id', '=', 'users.id')
                    ->select('user_details.*', 'users.email', 'users.display_name')->paginate($count);
            } elseif ($count != null) {
                return $search->join('users', 'user_id', '=', 'users.id')
                    ->select('user_details.*', 'users.email', 'users.display_name')->orderBy($order, 'desc')->paginate($count);
            }

            return $search->paginate(15);
        } elseif ($count != null) {
            if ($order != null) {
                return $this->model->orderBy($order, 'desc')->paginate($count);
            }

            return $this->model->join('users', 'user_id', '=', 'users.id')
                    ->select('user_details.*', 'users.email', 'users.display_name')->paginate($count);
        } elseif ($order != null) {
            if ($count != null) {
                return $this->model->join('users', 'user_id', '=', 'users.id')
                    ->select('user_details.*', 'users.email', 'users.display_name')->orderBy($order, 'desc')->paginate($count);
            }

            return $this->model->join('users', 'user_id', '=', 'users.id')
                    ->select('user_details.*', 'users.email', 'users.display_name')->orderBy($order, 'desc')->get();
        }
    }
}
