<?php

namespace App\Repositories\Auth;

use App\Repositories\Auth\AuthContract as AuthContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use LaravelRepository\Abstracts\BaseRepository;

class AuthRepository extends BaseRepository  implements AuthContract
{

    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    public function login(array $params, bool $rememberMe = false)
    {

        try {
            return $this->model->login($params, $rememberMe);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage(), previous: $e);
        }
    }

    public function register(array $params): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->register($params);
            $user = $this->model->where('email', $params['email'])->first();
            $user->interests()->attach($params['interests']);
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function logout(): bool
    {
        try {
            return $this->model->logout();
        } catch (\Throwable $th) {
            // dd($th);
            throw $th;
        }
    }
}
