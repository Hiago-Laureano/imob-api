<?php

use App\Models\User;
use App\Models\Property;
use App\Models\Image;

use function Pest\Laravel\deleteJson;

beforeEach(function (){
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken("auth-token", ["*"])->plainTextToken;
    $this->propertyWithoutImage = Property::factory()->create();
    $this->propertyWithImage = Property::factory()->create();
    $this->headers = ["X-Requested-With" => "XMLHttpRequest", "Authorization" => "Bearer {$this->token}"];
});

test('Delete property is not authenticated', function() {
    $this->headers["Authorization"] = "";
    deleteJson("/properties/{$this->propertyWithImage->id}", headers: $this->headers)
    ->assertStatus(401)
    ->assertJsonStructure(["message"]);
});

test('Delete property is not found', function() {
    deleteJson("/properties/100", headers: $this->headers)
    ->assertStatus(404)
    ->assertJsonStructure(["message"]);
});

test('Delete property without image is successful', function() {
    deleteJson("/properties/{$this->propertyWithoutImage->id}", headers: $this->headers)
    ->assertStatus(204);
});

test('Delete property with image is successful', function() {
    $imageName = fake()->image(storage_path("app/public/images/"), width: 250, height: 250, fullPath: False);
    Image::create(["property_id" => $this->propertyWithImage->id, "original_name" => "house#1", "link" => "storage/images/{$imageName}"]);
    deleteJson("/properties/{$this->propertyWithImage->id}", headers: $this->headers)
    ->assertStatus(204);
});
