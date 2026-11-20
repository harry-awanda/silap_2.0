<?php

return [
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'boolean' => ':attribute tidak valid.',
    'email' => 'Format :attribute tidak valid.',
    'unique' => ':attribute sudah digunakan.',
    'in' => 'Pilihan :attribute tidak valid.',
    'distinct' => ':attribute tidak boleh berulang.',
    'regex' => 'Format :attribute tidak valid.',
    'confirmed' => 'Konfirmasi :attribute tidak sesuai.',
    'different' => ':attribute harus berbeda dari :other.',
    'array' => ':attribute harus berupa daftar.',
    'current_password' => 'Password saat ini tidak sesuai.',
    'min' => ['string' => ':attribute minimal :min karakter.', 'array' => 'Pilih minimal :min :attribute.'],
    'max' => ['string' => ':attribute maksimal :max karakter.'],
    'password' => [
        'letters' => ':attribute harus mengandung huruf.',
        'mixed' => ':attribute harus mengandung huruf besar dan kecil.',
        'numbers' => ':attribute harus mengandung angka.',
    ],
    'attributes' => [
        'name' => 'Nama', 'username' => 'Username', 'email' => 'Email',
        'password' => 'Password', 'current_password' => 'Password saat ini',
        'roles' => 'Role', 'roles.*' => 'Role', 'active' => 'Status akun',
    ],
];
