<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Utils\User\Roles;

/**
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
      * @ORM\Column(type="string", unique=true, nullable=true)
      */
      private $apiToken;

    public function __construct()
    {
        $this->roles = [Roles::ROLE_USER];
    }

    /**
     * get id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * set username
     *
     * @param string $username
     * 
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * get email
     *
     * @return string|null
     */ 
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * ser email
     *
     * @param string $email
     * 
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * get passowrd
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * set password
     *
     * @param string|null $password
     * 
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * get user roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * set roles
     *
     * @param array $roles
     * 
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * get api token
     *
     * @return string|null
     */
    public function getApiToken(): ?string {
        return $this->apiToken;
    }

    /**
     * set api token
     *
     * @param string $apiToken
     * 
     * @return self
     */
    public function setApiToken(string $apiToken): self {
        $this->apiToken = $apiToken;

        return $this;
    }
}