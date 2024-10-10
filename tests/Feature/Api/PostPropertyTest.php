<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

beforeEach(function (){
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken("auth-token", ["*"])->plainTextToken;
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

test('Post property is not authenticated', function() {
    $this->headers["Authorization"] = "";
    postJson("/properties", $this->data, $this->headers)
    ->assertStatus(401)
    ->assertJsonStructure(["message"]);
});

test('Post property without image upload is successful', function() {
    postJson("/properties", $this->data, $this->headers)
    ->assertStatus(201);
});

test('Post property with for_rent=0 and without max_tenants, min_contract_time, accept_animals is successful', function() {
    $this->data["for_rent"] = 0;
    unset($this->data["max_tenants"]);
    unset($this->data["min_contract_time"]);
    unset($this->data["accept_animals"]);
    postJson("/properties", $this->data, $this->headers)
    ->assertStatus(201);
});

test('Post property with one image upload is successful', function() {
    Storage::fake("public");
    $img = UploadedFile::fake()->image("test.png");
    $this->data["files"] = [$img];
    postJson("/properties", $this->data, $this->headers)
    ->assertStatus(201);
    Storage::disk("public")->assertExists("images/{$img->hashName()}");
});

test('Post property with many images uploads is successful', function() {
    Storage::fake("public");
    $img1 = UploadedFile::fake()->image("test.png");
    $img2 = UploadedFile::fake()->image("test.png");
    $this->data["files"] = [$img1, $img2];
    postJson("/properties", $this->data, $this->headers)
    ->assertStatus(201);
    Storage::disk("public")->assertExists("images/{$img1->hashName()}");
    Storage::disk("public")->assertExists("images/{$img2->hashName()}");
});

describe("Post validations", function (){
    test('required field', function(string $field) {
        unset($this->data[$field]);
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.required", ["attribute" => $field])]);
    })->with(["name", "price", "location", "description", "bedrooms", "bathrooms", "for_rent"]);

    test('required field when for_rent=1', function(string $field) {
        unset($this->data[$field]);
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.required_if", ["attribute" => $field, "other" => "for_rent", "value" => 1])]);
    })->with(["max_tenants", "min_contract_time", "accept_animals"]);

    test('min length field', function(string $field, int $min_length) {
        $this->data[$field] = str_repeat("a", $min_length-1);
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.min", ["attribute" => $field, "min" => $min_length])]);
    })->with([["name", 4], ["location", 8]]);

    test('max length field', function(string $field, int $max_length) {
        $this->data[$field] = str_repeat("a", $max_length+1);
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.max", ["attribute" => $field, "max" => $max_length])]);
    })->with([["name", 255], ["location", 255], ["description", 600]]);

    test('field is type numeric', function(string $field) {
        $this->data[$field] = "a";
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.numeric", ["attribute" => $field])]);
    })->with(["price", "bedrooms", "bathrooms", "max_tenants", "min_contract_time"]);

    test('field is type boolean', function(string $field) {
        $this->data[$field] = "a";
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors([$field => trans("validation.boolean", ["attribute" => $field])]);
    })->with(["for_rent", "accept_animals"]);

    test('files field is type array', function() {
        $this->data["files"] = "a";
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors(["files" => trans("validation.array", ["attribute" => "files"])]);
    });

    test('items in files field are images', function() {
        Storage::fake("public");
        $this->data["files"] = [UploadedFile::fake()->create("document.pdf")];
        postJson("/properties", $this->data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors(["files.0" => trans("validation.image", ["attribute" => "files.0"])]);
    });
});