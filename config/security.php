<?php

// Mencegah website ditampilkan di dalam iframe
header("X-Frame-Options: SAMEORIGIN");

// Mencegah MIME Sniffing
header("X-Content-Type-Options: nosniff");

// Mengontrol informasi Referrer
header("Referrer-Policy: strict-origin-when-cross-origin");

// Mengaktifkan proteksi XSS pada browser lama
header("X-XSS-Protection: 1; mode=block");

// Permissions Policy
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// Content Security Policy
header("
Content-Security-Policy:
default-src 'self';
img-src 'self' data:;
style-src 'self' 'unsafe-inline';
script-src 'self' 'unsafe-inline';
font-src 'self';
object-src 'none';
base-uri 'self';
form-action 'self';
frame-ancestors 'self';
");