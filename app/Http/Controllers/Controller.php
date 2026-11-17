<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Інтернет-блогу',
    description: 'Документація REST API для лабораторної роботи №8'
)]
#[OA\Server(url: 'http://127.0.0.1:8000/api/v1', description: 'Локальний сервер розробки')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
abstract class Controller
{
    use AuthorizesRequests;
}
