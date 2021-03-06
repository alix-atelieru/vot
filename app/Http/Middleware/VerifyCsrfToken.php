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
        "observer/login",
        "/observer/add_section_count",
        "observer/quiz/answer",
        "/observer/section/select",
        "/observer/save_ref",
        "/observer/votes/"
        //"/observer/ref2/save"
    ];
}
