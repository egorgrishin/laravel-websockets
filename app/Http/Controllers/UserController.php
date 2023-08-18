<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Users/Show', [
            'user' => [
                'id' => 1,
                'name' => 'Test',
                'email' => 'egor@mail.ru',
            ]
        ]);
    }
}
