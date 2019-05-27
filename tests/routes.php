<?php

Route::middleware('auth:jwt')->get('test-jwt-route', function() {
    return 'JWT SUCCESS';
});