<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['required', 'integer'],
            'paginate' => ['required', 'integer'],
            'sort_direction' => ['required', Rule::in(['desc', 'asc'])],
            'sort_field' => ['string','nullable'],
            'selectType' => ['string', 'nullable'],
            'queries' => ['array','nullable']
        ];
    }
}
