<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'sometimes|string|max:100',
            'floor'                 => 'sometimes|string|max:10',
            'category'              => 'sometimes|string|max:50',
            'open_time'             => 'sometimes|date_format:H:i',
            'close_time'            => 'sometimes|date_format:H:i',
            'tel'                   => 'nullable|string|max:20',
            'description'           => 'nullable|string|max:500',
            'is_temporarily_closed' => 'nullable|boolean',
        ];
    }

    /**
     * バリデーションエラー時に確実に422（JSON）で返す設定
     */
    protected function failedValidation(Validator $validator)
    {
        $res = response()->json([
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($res);
    }
}
