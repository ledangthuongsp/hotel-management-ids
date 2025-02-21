<?php

return [
    'first_name' => [
        'required' => 'The first name field is required.',
        'max' => 'The first name may not be greater than :max characters.',
    ],
    'last_name' => [
        'required' => 'The last name field is required.',
        'max' => 'The last name may not be greater than :max characters.',
    ],
    'user_name' => [
        'required' => 'The username field is required.',
        'unique' => 'The username has already been taken.',
        'max' => 'The username may not be greater than :max characters.',
    ],
    'email' => [
        'required' => 'The email field is required.',
        'email' => 'The email must be a valid email address.',
        'unique' => 'The email has already been taken.',
        'max' => 'The email may not be greater than :max characters.',
    ],
    'role_id' => [
        'required' => 'The role field is required.',
        'exists' => 'The selected role is invalid.',
    ],
    'day_of_birth' => [
        'date' => 'The day of birth must be a valid date.',
    ],
    'avatar' => [
        'image' => 'The avatar must be an image.',
        'mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif, svg.',
        'max' => 'The avatar may not be greater than :max kilobytes.',
    ],
    'password' => [
        'required' => 'The password field is required.',
        'min' => 'The password must be at least :min characters.',
        'max' => 'The password may not be greater than :max characters.',
        'regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
    ],
];