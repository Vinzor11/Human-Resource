<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\ClientRepository;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function __construct(
        protected ClientRepository $clients
    ) {}

    public function index(Request $request): Response
    {
        $clients = $this->clients->forUser($request->user()->id);
        
        return Inertia::render('OAuth/Clients', [
            'clients' => $clients->map(fn($client) => [
                'id' => $client->id,
                'name' => $client->name,
                'redirect' => $client->redirect,
                'created_at' => $client->created_at->toDateTimeString(),
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'redirect' => 'required|url',
            'type' => 'nullable|in:accounting,payroll,other',
        ]);
        
        $client = $this->clients->create(
            $request->user()->id,
            $validated['name'],
            $validated['redirect'],
            null, // secret (will be generated)
            false, // personal access client
            false, // password client
            true   // authorization code grant
        );
        
        $clients = $this->clients->forUser($request->user()->id);
        
        return redirect()->route('oauth.clients')->with([
            'newClient' => [
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'redirect_uri' => $validated['redirect'],
            ],
        ]);
    }
}

