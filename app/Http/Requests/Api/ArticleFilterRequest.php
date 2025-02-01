<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ArticleFilterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date_from' => 'nullable|date', // maybe must be greater than current datetime
            'date_to' => 'nullable|date|gte:date_from',
            'category_id' => 'nullable|integer',
            'news_agency_id' => 'nullable|integer|exists:news_agency,id',
            'source_id' => 'nullable|integer|exists:sources,id',
            'author_id' => 'nullable|integer|exists:authors,id',
            'page' => 'nullable|integer|default:1',
            'per_page' => 'nullable|integer|default:20|max:200|min:1',
        ];
    }
}
