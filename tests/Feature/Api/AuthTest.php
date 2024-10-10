<?php

use App\Models\User;

use function Pest\Laravel\postJson;

beforeEach(function (){
    $this->user = User::factory()->create();
    $this->headers = ["Content-Type" => "application/json", "X-Requested-With" => "XMLHttpRequest"];
});

//Tests for Login------------------------------------------------------

test('login successful', function () {
    $data = ["email" => $this->user->email, "password" => "password"];
    postJson("/login", $data, $this->headers)
    ->assertStatus(200)
    ->assertJsonStructure(["message", "name", "auth-token"]);
});

describe("incorrect data in login", function (){
    test('incorrect email', function () {
        $data = ["email" => "test@t.com", "password" => "password"];
        postJson("/login", $data, $this->headers)
        ->assertStatus(403)
        ->assertJsonStructure(["message"]);
    });

    test('incorrect password', function () {
        $data = ["email" => $this->user->email, "password" => "xxxxxx"];
        postJson("/login", $data, $this->headers)
        ->assertStatus(403)
        ->assertJsonStructure(["message"]);
    });
});

describe("login validations", function (){
    test('email is type email', function () {
        $data = ["email" => "laravel", "password" => "password"];
        postJson("/login", $data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors(["email" => trans("validation.email", ["attribute" => "email"])]);
    });

    test('email is required', function () {
        $data = ["password" => "password"];
        postJson("/login", $data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors(["email" => trans("validation.required", ["attribute" => "email"])]);
    });

    test('password is required', function () {
        $data = ["email" => $this->user->email];
        postJson("/login", $data, $this->headers)
        ->assertStatus(422)
        ->assertJsonStructure(["message", "errors"])
        ->assertJsonValidationErrors(["password" => trans("validation.required", ["attribute" => "password"])]);
    });
});

//Tests for Logout------------------------------------------------------

test('logout successful', function () {
    $token = $this->user->createToken("auth-token", ["*"])->plainTextToken;
    $this->headers["Authorization"] = "Bearer {$token}";
    postJson("/logout", headers: $this->headers)
    ->assertStatus(200)
    ->assertJsonStructure(["message"]);
});

test('unauthenticated logout', function () {
    postJson("/logout", headers: $this->headers)->dump()
    ->assertStatus(401)
    ->assertJsonStructure(["message"]);
});