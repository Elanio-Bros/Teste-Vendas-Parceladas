<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function validate(Request $request, array $validate): array
    {
        $validate = Validator::make($request->all(), $validate);
        if ($validate->fails()) {
            throw new HttpResponseException(response()->json($validate->errors(), 422));
        }

        return $validate->validated();
    }
}
