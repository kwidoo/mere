<?php

namespace Kwidoo\Mere\Tests\Feature\Resource;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kwidoo\Mere\Tests\TestCase;
use Kwidoo\Mere\Tests\Fixtures\FakeService;

class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['api'])->group(function () {
            Route::get('{resource}', \Kwidoo\Mere\Http\Controllers\ResourceController::class . '@index');
        });

        config()->set('mere.resources', [
            'property' => FakeService::class,
        ]);
    }

    public function test_it_returns_paginated_list()
    {
        $response = $this->getJson('/property');
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'resource' => ['label', 'fields', 'actions']
            ],
        ]);
    }
}
