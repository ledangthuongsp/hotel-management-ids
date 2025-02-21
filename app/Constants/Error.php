<?php

namespace App\Enums;

class Error
{
    // Success Messages
    public const USERS_FETCHED_SUCCESSFULLY = 'Users fetched successfully';
    public const USER_CREATED_SUCCESSFULLY = 'User created successfully';
    public const USER_UPDATED_SUCCESSFULLY = 'User updated successfully';
    public const USER_DELETED_SUCCESSFULLY = 'User deleted successfully';
    public const AVATAR_UPLOAD_SUCCESS = 'Avatar uploaded successfully';

    // Error Messages
    public const USER_NOT_FOUND = 'User not found';
    public const INVALID_FILE_UPLOAD = 'Invalid file upload';
    public const UNSUPPORTED_FILE_TYPE = 'Unsupported file type';
    
    
}