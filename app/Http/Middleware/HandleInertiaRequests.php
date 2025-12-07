<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

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
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $user = $request->user();
        
        // Load roles and permissions safely, ensuring relationships are loaded
        $roles = [];
        $permissions = [];
        
        if ($user) {
            try {
                // Ensure roles relationship is loaded
                if (!$user->relationLoaded('roles')) {
                    $user->load('roles');
                }
                $roles = $user->roles->pluck('name')->toArray();
            } catch (\Exception $e) {
                // If roles can't be loaded, default to empty array
                $roles = [];
            }
            
            try {
                // Get permissions safely
                $permissions = $user->getAllPermissions()->pluck('name')->toArray();
            } catch (\Exception $e) {
                // If permissions can't be loaded, default to empty array
                $permissions = [];
            }
        }

        return [
             ...parent::share($request),
            'name'  => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth'  => [
                'user'        => $user,
                'roles'       => $roles,
                'permissions' => $permissions,
            ],
            'ziggy' => function () use ($request): array {
                $ziggy = new Ziggy();
                $ziggyArray = $ziggy->toArray();
                
                // Ensure all Ziggy URLs use HTTPS in production
                if (config('app.env') === 'production') {
                    $appUrl = config('app.url');
                    $httpUrl = str_replace('https://', 'http://', $appUrl);
                    
                    // Convert Ziggy routes array to JSON and back to replace HTTP with HTTPS
                    $ziggyJson = json_encode($ziggyArray);
                    $ziggyJson = str_replace($httpUrl, $appUrl, $ziggyJson);
                    $ziggyArray = json_decode($ziggyJson, true);
                }
                
                return [
                    ...$ziggyArray,
                    'location' => str_replace('http://', 'https://', $request->url()),
                ];
            },
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
                'newClient' => $request->session()->get('newClient'),
            ],
            'importedData' => fn () => $request->session()->pull('importedData'),
        ];
    }
}
