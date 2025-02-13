<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(User $user, array $data)
    {
        if (isset($data['avatar'])) {
            $path = $data['avatar']->store('avatars', 'public');
            $data['avatar_url'] = $path;
        } else {
            $data['avatar_url'] = $user->avatar_url ?? 'default_avatar.png';
        }

        $user->update($data);

        return $user;
    }
}
