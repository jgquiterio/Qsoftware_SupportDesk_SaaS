<?php

namespace App\Models\Concerns;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope());

        static::creating(function (Model $model) {
    	    $user = Auth::user();

    	    if (!$user || !$user->company_id) {
                throw new \RuntimeException(
                    'A company is required to create this record.'
                );
            }

            $model->company_id = $user->company_id;
        });
    }
}