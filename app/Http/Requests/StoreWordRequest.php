<?php

namespace App\Http\Requests;

use App\Models\Word;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreWordRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('word_create');
    }

    public function rules()
    {
        return [
            'arabic_word'  => [
                'string',
                'required',
                'unique:words',
            ],
            'english_word' => [
                'string',
                'nullable',
            ],
            'bangla_word'  => [
                'string',
                'required',
            ],
        ];
    }
}
