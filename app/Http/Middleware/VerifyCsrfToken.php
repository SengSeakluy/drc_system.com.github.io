<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/livewire/*',
        '/read-csv',
        "/read-order-csv",
        "/import-order-csv",
        "/read-product-csv",
        "/import-product-csv",
        "/read-category-csv",
        "/import-category-csv",
        "/read-brand-csv",
        "/import-brand-csv",
        "/admin/check-third-party",
        "/admin/check-system-command",
        "/admin/merchant-token"
    ];
}
