<?php

use App\Models\Property;
use App\Models\User;

use function Pest\Laravel\getJson;

beforeEach(function (){
    $this->user = User::factory()->create();
    Property::create(["name" => "house", "price" => 2000.00, "location" => "address", "description" => "...", "bedrooms" => 1, "bathrooms" => 1, "for_rent" => 0, "max_tenants" => null,
    "min_contract_time" => null, "accept_animals" => null, "user_id" => 1]);
    Property::create(["name" => "#1", "price" => 2000.00, "location" => "South", "description" => "...", "bedrooms" => 1, "bathrooms" => 1, "for_rent" => 0, "max_tenants" => null,
    "min_contract_time" => null, "accept_animals" => null, "user_id" => 1]);
    Property::create(["name" => "#2", "price" => 2000.00, "location" => "address", "description" => "...", "bedrooms" => 2, "bathrooms" => 1, "for_rent" => 0, "max_tenants" => null,
    "min_contract_time" => null, "accept_animals" => null, "user_id" => 1]);
    Property::create(["name" => "#3", "price" => 2000.00, "location" => "address", "description" => "...", "bedrooms" => 1, "bathrooms" => 2, "for_rent" => 0, "max_tenants" => null,
    "min_contract_time" => null, "accept_animals" => null, "user_id" => 1]);
    Property::create(["name" => "#4", "price" => 1000.00, "location" => "address", "description" => "...", "bedrooms" => 1, "bathrooms" => 1, "for_rent" => 0, "max_tenants" => null,
    "min_contract_time" => null, "accept_animals" => null, "user_id" => 1]);
    Property::create(["name" => "#5", "price" => 2000.00, "location" => "address", "description" => "...", "bedrooms" => 1, "bathrooms" => 1, "for_rent" => 1, "max_tenants" => 1,
    "min_contract_time" => 1, "accept_animals" => 0, "user_id" => 1]);
    Property::create(["name" => "#6", "price" => 2000.00, "location" => "address", "description" => "...", "bedrooms" => 1, "bathrooms" => 1, "for_rent" => 1, "max_tenants" => 1,
    "min_contract_time" => 1, "accept_animals" => 1, "user_id" => 1]);
    $this->headers = ["X-Requested-With" => "XMLHttpRequest", "Contente_Type" => "application/json"];
    $this->jsonStructureCollection = [
        "data" => ["*" => [
            "id",
            "name",
            "price",
            "location",
            "description",
            "bedrooms", "bathrooms",
            "for_rent",
            "max_tenants",
            "min_contract_time",
            "accept_animals",
            "created_at",
            "images_links" => ["*" => ["id", "link"]],
            "post_by"
            ]
        ],
        "links" => [
            "first",
            "last",
            "prev",
            "next"
        ]
    ];
    $this->jsonStructure = [
        "data" => [
            "id",
            "name",
            "price",
            "location",
            "description",
            "bedrooms", "bathrooms",
            "for_rent",
            "max_tenants",
            "min_contract_time",
            "accept_animals",
            "created_at",
            "images_links" => ["*" => ["id", "link"]],
            "post_by"
        ]
    ];
});

//Tests GET many------------------------------------------------------

test('Get all properties is successful without urls parameters', function () {
    getJson("/properties", $this->headers)
    ->assertStatus(200)
    ->assertJsonStructure($this->jsonStructureCollection);
});

test("Get all properties is successful with url parameter", function (string $parameterName, string $parameterValue, int $expect){
    if($parameterName !== "accept_animals"){
        $request = getJson("/properties?{$parameterName}={$parameterValue}", $this->headers);
        $request->assertStatus(200)
        ->assertJsonStructure($this->jsonStructureCollection);
    }else{
        $request = getJson("/properties?for_rent=1&{$parameterName}={$parameterValue}", $this->headers);
        $request->assertStatus(200)
        ->assertJsonStructure($this->jsonStructureCollection);
    }
    expect(count($request["data"]))->toBe($expect);

})->with([["name", "house", 1], ["location", "South", 1], ["bedrooms", "2", 1], ["bathrooms", "2", 1], ["max_price", "1000.00", 1], ["for_rent", "1", 2], ["accept_animals", "1", 1]]);

//Tests GET one------------------------------------------------------

test('Get specific property is successful', function () {
    getJson("/properties/1", $this->headers)
    ->assertStatus(200)
    ->assertJsonStructure($this->jsonStructure);
});

test('Get specific property is not found', function () {
    getJson("/properties/10", $this->headers)
    ->assertStatus(404)
    ->assertJsonStructure(["message"]);
});