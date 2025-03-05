<?php

namespace App\Helpers;

use App\Models\Admin;

trait ChangeAuthorShield
{
    public function change_author(Admin $user): bool{
        $shield_name = 'change_author_' . \Str::of(static::class)
                ->afterLast('\Policies')
                ->replace('Policy', '')
                ->snake()
                ->replace('_', '-');
        return $user->can(
            $shield_name
        );
    }
}
