<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Controllers\AuthorizationController as PassportAuthorizationController;
use Inertia\Inertia;
use Inertia\Response;

class AuthorizationController extends Controller
{
    public function __construct(
        protected ClientRepository $clients
    ) {}

    public function authorize(Request $request): Response
    {
        // Get the client
        $client = $this->clients->find($request->client_id);
        
        if (!$client) {
            abort(404, 'Client not found');
        }

        // Show approval screen
        return Inertia::render('OAuth/Authorize', [
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
            ],
            'scopes' => $request->scope ? explode(' ', $request->scope) : [],
            'request' => $request->only(['client_id', 'redirect_uri', 'response_type', 'scope', 'state']),
        ]);
    }

    public function approve(Request $request)
    {
        // Use Passport's built-in approval logic
        return app(PassportAuthorizationController::class)->approve($request);
    }

    public function deny(Request $request)
    {
        return app(PassportAuthorizationController::class)->deny($request);
    }
}

