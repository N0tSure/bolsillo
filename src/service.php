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
    private $userDao;
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
       $passwd_hash = md5($password);
       $record = $this->userDao->findUser($login, $passwd_hash);
       $result = false;
       if ($record):
           $result = new User($record['name'], $record['email']);
           $result->setId($record['id']);
       endif;

       return $result;
   }

    /**
     * @param $userDao UserDao User's data access object instance
     */
   public function __construct($userDao)
   {
       $this->userDao = $userDao;
   }
}
