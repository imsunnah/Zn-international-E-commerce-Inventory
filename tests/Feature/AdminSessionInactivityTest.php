<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminSessionInactivityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup a dummy route that uses Web and AdminMiddleware
        Route::middleware(['web', \App\Http\Middleware\AdminMiddleware::class])->get('/_test_admin_dashboard', function () {
            return response('dashboard');
        });
    }

    public function test_admin_middleware_allows_access_when_active()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Log in and access with 59 minutes of inactivity (less than 60)
        $response = $this->actingAs($admin)
            ->withSession(['admin_last_activity' => now()->subMinutes(59)])
            ->get('/_test_admin_dashboard');

        $response->assertStatus(200);
        $response->assertSee('dashboard');
        $this->assertNotNull(session('admin_last_activity'));
    }

    public function test_admin_middleware_logs_out_after_1_hour_inactivity()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Accessing with 60 minutes of inactivity
        $response = $this->actingAs($admin)
            ->withSession(['admin_last_activity' => now()->subMinutes(61)]) // use 61 to be safe
            ->get('/_test_admin_dashboard');

        $response->assertRedirect(route('admin.login'));
        $this->assertFalse(auth()->check());
        $response->assertSessionHasErrors(['email']);
        
        // Assert specific session expiration message
        $response->assertSessionHasErrors([
            'email' => 'Your session has expired due to 1 hour of inactivity.'
        ]);
    }

    public function test_store_front_queries_execute_without_sqlite_syntax_errors()
    {
        // Seeding a Category first
        $category = Category::create([
            'name_en' => 'Test Category',
            'name_bn' => 'পরীক্ষামূলক ক্যাটাগরি',
            'slug' => 'test-category',
            'is_active' => true,
            'serial' => 1,
        ]);

        // Seeding a product with category_id
        Product::create([
            'category_id' => $category->id,
            'name_en' => 'Test Product EN',
            'name_bn' => 'Test Product BN',
            'slug' => 'test-product',
            'price' => 100,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        $responseShop = $this->get(route('shop'));
        $responseShop->assertStatus(200);
    }
}
