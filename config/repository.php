<?php 
return [
    /* 
    * repositories will contain all the repositories namespaces from app/Repositories directory  and bind Contract and model for it  
    * this allows you not to set model explicitly on every use.
    */
    'repositories' => [
                        App\Repositories\Account\AccountRepository::class => [
                'model' => App\Models\User::class,
                'contract' => App\Repositories\Account\AccountRepositoryContract::class,
            ],

    ],
];
?>