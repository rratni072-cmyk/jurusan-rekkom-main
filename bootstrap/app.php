<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle "request body terlalu besar" — terjadi ketika file yang
        // di-upload melebihi PHP `post_max_size`. Tanpa handler ini, user
        // hanya melihat 413/419 generik tanpa konteks.
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $postMaxBytes = static function (): int {
                $val = trim((string) ini_get('post_max_size'));
                if ($val === '') {
                    return 0;
                }
                $unit = strtolower(substr($val, -1));
                $num = (int) $val;

                return match ($unit) {
                    'g' => $num * 1024 * 1024 * 1024,
                    'm' => $num * 1024 * 1024,
                    'k' => $num * 1024,
                    default => (int) $val,
                };
            };

            $limitMb = round($postMaxBytes() / 1024 / 1024, 1);
            $sentMb = round((int) $request->server('CONTENT_LENGTH', 0) / 1024 / 1024, 2);

            $msg = "Upload gagal: ukuran data dikirim ({$sentMb} MB) melebihi batas server ({$limitMb} MB). "
                 .'Silakan kompres file Anda dulu (mis. via TinyPNG / Squoosh) atau simpan sebagai JPG.';

            // AJAX/JSON request → balas JSON 413
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 413);
            }

            // Pakai withErrors() agar pesan muncul di modal SweetAlert prominent
            // (via $errors->any() di admin layout), bukan toast kecil.
            return redirect()->back()->withErrors(['upload' => $msg])->withInput();
        });
    })->create();
