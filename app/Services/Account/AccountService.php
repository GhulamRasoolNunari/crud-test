<?php
namespace App\Services\Account;

use App\Repositories\Account\AccountRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AccountService
{
    private AccountRepositoryContract $accountRepository;

    public function __construct(AccountRepositoryContract $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getAccountDetails(int $id): Model
    {
        return $this->accountRepository
        ->with(['interests'])
        ->findById($id);
    }
}