<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'email'  => 'required|email',
            'wallet' => 'required|string',
        ];
    }
}
