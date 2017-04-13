<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.review.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
