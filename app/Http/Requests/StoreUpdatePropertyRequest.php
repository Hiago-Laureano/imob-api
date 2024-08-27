<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdatePropertyRequest extends FormRequest
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
        $rules = [
            "name" => ["required", "min:4", "max:255"],
            "price" => ["required", "numeric"],
            "location" => ["required", "min:8", "max:255"],
            "description" => ["required", "max:600"],
            "bedrooms" => ["required", "numeric"],
            "bathrooms" => ["required", "numeric"],
            "for_rent" => ["required", "boolean"],
            "max_tenants" => ["required_if:for_rent,=,1", "numeric"],
            "min_contract_time" => ["required_if:for_rent,=,1", "numeric"],
            "accept_animals" => ["required_if:for_rent,=,1", "boolean"],
            "files.*" => ["nullable", "image"]
        ];

        if($this->method() === "PUT"){
            $rules["name"] = ["nullable", "min:4", "max:255"];
            $rules["price"] = ["nullable", "numeric"];
            $rules["location"] = ["nullable", "min:8", "max:255"];
            $rules["description"] = ["nullable", "max:600"];
            $rules["bedrooms"] = ["nullable", "numeric"];
            $rules["bathrooms"] = ["nullable", "numeric"];
        }

        return $rules;
    }
}
