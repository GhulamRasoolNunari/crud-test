<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Services\Account\AccountService;
use App\Traits\ApiResponseSender;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiResponseSender;
    private AccountService $accountService;
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }
    public function index()
    {
        return $this->successResponse(
            $this->accountService->getAccountDetails(auth()->id())
        );

    }
}
