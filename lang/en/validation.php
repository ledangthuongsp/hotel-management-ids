<?php

return [
    'required' => ':attribute is required.',
    'email' => ':attribute must be a valid email address.',
    'numeric' => ':attribute must be a number.',
    'string' => ':attribute must be a valid text.',
    'min' => ':attribute must be at least :min characters.',
    'max' => ':attribute must not exceed :max characters.',
    'regex' => ':attribute format is invalid.',
    
    'attributes' => [
        'hotel_name' => 'Hotel Name',
        'hotel_name_jp' => 'Hotel Name (Japanese)',
        'hotel_code' => 'Hotel Code',
        'hotel_user_id' => 'User ID',
        'hotel_city_id' => 'City',
        'hotel_email' => 'Hotel Email',
        'hotel_telephone' => 'Hotel Telephone',
        'hotel_fax' => 'Hotel Fax',
        'hotel_address_1' => 'Hotel Address (Street + Number)',
        'hotel_address_2' => 'Additional Address',
        'hotel_district' => 'District',
        'hotel_ward' => 'Ward',
    ],

    'custom' => [
        'hotel_email' => [
            'email' => 'Please enter a valid email address.',
        ],
        'hotel_telephone' => [
            'regex' => 'Telephone number must contain only numbers and be 10-15 digits long.',
        ],
        'hotel_code' => [
            'regex' => 'Hotel Code must contain only uppercase letters and numbers (e.g., HT123).',
        ],
        'hotel_name_jp' => [
            'required' => 'Hotel Name (Japanese) is required.',
        ],
        'hotel_address_1' => [
            'required' => 'Full address including street and number is required.',
        ],
    ],
];
