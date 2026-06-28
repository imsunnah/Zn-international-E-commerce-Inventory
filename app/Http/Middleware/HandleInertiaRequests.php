<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        $translationsPath = base_path("lang/{$locale}.json");
        $translations = file_exists($translationsPath) ? json_decode(file_get_contents($translationsPath), true) : [];

        // Safely query DB — returns empty defaults if tables don't exist yet (fresh deploy)
        $safeQuery = function($callback, $default = null) {
            try { return $callback(); } catch (\Throwable $e) { return $default ?? collect(); }
        };

        return [
            ...parent::share($request),
            'locale' => $locale,
            'translations' => $translations,
            'auth' => [
                'user' => $request->user(),
            ],
            'categories' => $safeQuery(fn() => \App\Models\Category::with([
                'subCategories' => function($q) {
                    $q->where('is_active', true)->whereNull('parent_id')
                      ->with(['brands', 'children' => function($sq) { 
                          $sq->where('is_active', true)->with('brands'); 
                      }]);
                },
                'brands'
            ])->where('is_active', true)->get()),
            'activePages' => $safeQuery(fn() => \App\Models\Page::where('is_active', true)->select('id', 'title_en', 'title_bn', 'slug', 'group')->get()),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'pendingOrdersCount' => $safeQuery(fn() => $request->user() && $request->user()->role === 'admin' 
                ? \App\Models\Order::where('status', 'pending')->count() 
                : 0, 0),
            'unreadChatCount' => $safeQuery(fn() => $request->user() && $request->user()->role === 'admin'
                ? \App\Models\CustomerMessage::whereIn('id', function($query) {
                    $query->selectRaw('max(id)')
                        ->from('customer_messages')
                        ->groupBy('user_id');
                })->where('is_from_admin', false)->count()
                : 0, 0),
            'notices' => $safeQuery(fn() => \App\Models\Notice::where('is_active', true)->latest()->get()),
            'settings' => $safeQuery(fn() => \App\Models\Setting::all()->pluck('value', 'key')),
        ];
    }
}
