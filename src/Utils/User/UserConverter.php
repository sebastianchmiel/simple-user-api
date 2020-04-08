<?php

namespace App\Utils\User;

use App\Entity\User;

/**
 * Convert user object to array with base parameters to show
 * 
 * @author Sebastian Chmiel <s.chmiel2@gmail.com>
 */
final class UserConverter
{
    /**
     * convert object to array
     *
     * @param User $user
     * 
     * @return array
     */
    public static function toArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ];
    }

    /**
     * convert multi objects to arraay
     *
     * @param Users[] $users
     * 
     * @return array
     */
    public static function multiToArray(array $users): array
    {
        $items = [];
        /** @var User $user */
        foreach ($users as $user) {
            $items[] = self::toArray($user);
        }
        return $items;
    }
}
