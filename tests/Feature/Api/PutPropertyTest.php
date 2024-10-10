<?php

use App\Models\User;
use App\Models\Property;

use function Pest\Laravel\putJson;

beforeEach(function (){
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken("auth-token", ["*"])->plainTextToken;
    $this->property = Property::factory()->create();
    $this->headers = ["X-Requested-With" => "XMLHttpRequest", "Authorization" => "Bearer {$this->token}"];
    $this->data = [
        "name" => "##10",
        "price" => 2000.00,
        "location" => "address..",
        "description" => "...",
        "bedrooms" => 1,
        "bathrooms" => 1,
        "for_rent" => 1,
        "max_tenants" => 1,
        "min_contract_time" => 1,
        "accept_animals" => 1
    ];
});

test('Update property is not authenticated', function() {
    $this->headers["Authorization"] = "";
    putJson("/properties/{$this->property->id}", $this->data, $this->headers)
    ->assertStatus(401)
    ->assertJsonStructure(["message"]);
});

test('Update property is not found', function() {
    putJson("/properties/100", $this->data, $this->headers)
    ->assertStatus(404)
    ->assertJsonStructure(["message"]);
});

test('Update property is successful', function() {
    putJson("/properties/{$this->property->id}", $this->data, $this->headers)
    ->assertStatus(204);
});

describe("Post validations", function (){
    test('required field', function(string $field) {
        unset($this->data[$field]);
        putJson("/properties/{$this->property->id}", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.required", ["attribute" => $field])]);
    })->with(["for_rent"]);

    test('required field when for_rent=1', function(string $field) {
        unset($this->data[$field]);
        putJson("/properties/{$this->property->id}", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.required_if", ["attribute" => $field, "other" => "for_rent", "value" => 1])]);
    })->with(["max_tenants", "min_contract_time", "accept_animals"]);

    test('min length field', function(string $field, int $min_length) {
        $this->data[$field] = str_repeat("a", $min_length-1);
        putJson("/properties/{$this->property->id}", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.min", ["attribute" => $field, "min" => $min_length])]);
    })->with([["name", 4], ["location", 8]]);

    test('max length field', function(string $field, int $max_length) {
        $this->data[$field] = str_repeat("a", $max_length+1);
        putJson("/properties/{$this->property->id}", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.max", ["attribute" => $field, "max" => $max_length])]);
    })->with([["name", 255], ["location", 255], ["description", 600]]);

    test('field is type numeric', function(string $field) {
        $this->data[$field] = "a";
        putJson("/properties/{$this->property->id}", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.numeric", ["attribute" => $field])]);
    })->with(["price", "bedrooms", "bathrooms", "max_tenants", "min_contract_time"]);

    test('field is type boolean', function(string $field) {
        $this->data[$field] = "a";
        putJson("/properties/{$this->property->id}", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.boolean", ["attribute" => $field])]);
    })->with(["for_rent", "accept_animals"]);
});