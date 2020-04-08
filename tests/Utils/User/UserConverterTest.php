<?php

namespace tests\Utils\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Utils\User\UserConverter;
use App\Entity\User;

class UserConverterTest extends WebTestCase
{
    /**
     * test convert single user object to array
     */
    public function testToArray()
    {
        $user = (new User)
            ->setUsername('test')
            ->setEmail('test@test.pl');

        $converted = UserConverter::toArray($user);

        return $this->assertSame($converted, [
            'id' => null,
            'username' => 'test',
            'email' => 'test@test.pl'
        ]);
    }

    /**
     * test convert multi user objects to array
     */
    public function testMultiToArray()
    {
        $users = [
            (new User)
                ->setUsername('test')
                ->setEmail('test@test.pl'),
            (new User)
                ->setUsername('test2')
                ->setEmail('test2@test.pl'),
        ];

        $converted = UserConverter::multiToArray($users);

        return $this->assertSame($converted, [
            [
                'id' => null,
                'username' => 'test',
                'email' => 'test@test.pl'
            ],
            [
                'id' => null,
                'username' => 'test2',
                'email' => 'test2@test.pl'
            ],
        ]);
    }
}
