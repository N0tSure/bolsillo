<?php

/**
 * User model.
 */
class User
{
    /**
     * Identifier. If null, this is new.
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Name, never null.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Email, never null.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Makes this User existing
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    private $id;
    private $name;
    private $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
}

class UserService
{
    /**
     * Finds User by login (email) and password. Returns only active user.
     * If User not found, returns false.
     *
     * @param $login string login (email) not null
     * @param $password string password, not null
     * @return false|User
     * @throws Exception in case of error when getting user
     */
   public function findUserByLoginAndPassword($login, $password)
   {
       if (strcasecmp($login, 'emzorg@zorg.com') == 0) {
           throw new Exception('Mr. Shadow is listening!');
       }

       $encrypted = md5($password);
       if (
           strcasecmp($login, 'ASir2089@gmail.com') == 0 &&
           strcmp($encrypted, '6df23dc03f9b54cc38a0fc1483df6e21') == 0
       ) {
           $user = new User('Artem Sirosh', 'ASir2089@gmail.com');
           $user->setId(1);
           return $user;
       }

       return false;
   }
}
