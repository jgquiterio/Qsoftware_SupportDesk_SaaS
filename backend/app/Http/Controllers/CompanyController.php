<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
	if ($request->user()->company_id) {
	    return response()->json([
	        'message' => 'You already belong to a company.'
	    ], 409);
	}

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $company = Company::create([
            'name' => $request->name,
        ]);

	$user = $request->user();
	$user->company_id = $company->id;
	$user->role = 'admin';
	$user->is_active = true;
	$user->save();

        return response()->json([
            'message' => 'Company created successfully.',
            'company' => $company,
        ], 201);
    }
}
