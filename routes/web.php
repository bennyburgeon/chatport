<?php


use App\Events\SendMessage;
use BeyondCode\LaravelWebSockets\Apps\AppProvider;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (AppProvider $appProvider) {
    return view('chat-app-example', [
        "port" => env("LARAVEL_WEBSOCKETS_PORT"),
        "host" => env("LARAVEL_WEBSOCKETS_HOST"),
        "authEndpoint" => "/api/sockets/connect",
        "logChannel" => DashboardLogger::LOG_CHANNEL_PREFIX,
        "apps" => $appProvider->all()
    ]);
});

Route::post("/chat/send", function(Request $request) {
    $message = $request->input("message", null);
    $name = $request->input("name", "Anonymous");
    $time = (new DateTime(now()))->format(DateTime::ATOM);
    if ($name == null) {
        $name = "Anonymous";
    }
    SendMessage::dispatch($name, $message, $time);
});