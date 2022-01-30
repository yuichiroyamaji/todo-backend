<?php

namespace App\Http\Requests\Api\Todo;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Api\ApiBaseRequest;

class TodoStoreRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => ['required',  'integer'],
            'task_title' => ['required', 'max:255'],
        ];
    }
}
