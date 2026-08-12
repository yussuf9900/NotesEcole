<?php

function init_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}


function set_session(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
}

function get_session(string $key, mixed $default = null): mixed {
    return $_SESSION[$key] ?? $default;
}

function unset_session(string $key): void {
    unset($_SESSION[$key]);
}

function destroy_session(): void {
    session_unset();
    session_destroy();
}

function is_logged_in(): bool {
    init_session();
    return get_session('user') !== null;
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /login');
        exit;
    }
}

function set_flash(string $type, string $message): void {
    set_session('flash_message', ['type' => $type, 'text' => $message]);
}

function get_flash(): ?array {
    $flash = get_session('flash_message');
    unset_session('flash_message');
    return $flash;
}