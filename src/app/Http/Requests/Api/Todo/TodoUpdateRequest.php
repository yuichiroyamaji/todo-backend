<?php

namespace App\Http\Requests\Api\Todo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Api\ApiBaseRequest;

class TodoUpdateRequest extends ApiBaseRequest
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
            'task_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'task_status' => ['required_without:task_title','integer'],
            'task_title' => ['required_without:task_status', 'max:255'],
        ];
    }

    /**
    * Override validationData() method in FormRequest to obtain parameters within URL.
    *
    * @return array
    */
     public function validationData()
     {
         return array_merge($this->request->all(), [
             'task_id' => Route::input('task_id'),
         ]);
     }
}
