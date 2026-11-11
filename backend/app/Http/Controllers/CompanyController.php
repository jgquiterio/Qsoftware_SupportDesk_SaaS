<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
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
	$user->save();

        return response()->json([
            'message' => 'Company created successfully.',
            'company' => $company,
        ], 201);
    }
}
