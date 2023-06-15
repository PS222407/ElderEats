<?php

namespace App\Classes;

class ApiEndpoint
{
    public const STORE_ACCOUNT = '/Accounts';
    public const GET_ACCOUNT_BY_TOKEN = '/Accounts/Token/{token}';
    public const GET_CONNECTED_USERS_FROM_ACCOUNT = '/Accounts/{id}/Users/Connected';
    public const SEARCH_PRODUCTS_FROM_ACCOUNT = '/Products/Account/Search/{name}';
}
