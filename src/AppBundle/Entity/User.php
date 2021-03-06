<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use AppBundle\Model\TaskCreatorInterface;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface, TaskCreatorInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez choisir un rôle.")
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="user", cascade={"persist","remove"})
     */
    private $tasks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getRoles()
    {
        return array($this->role);
    }


    /**
     * @param $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    public function eraseCredentials()
    {
    }

    /**
     * Add task.
     *
     * @param Task $task
     *
     * @return User
     */
    public function addTask(Task $task)
    {
        $this->tasks[] = $task;
        $task->setUser($this);

        return $this;
    }

    /**
     * Remove task.
     *
     * @param Task $task
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTask(Task $task)
    {
        return $this->tasks->removeElement($task);
    }

    /**
     * Get tasks.
     *
     * @return Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    public function canBeManagedBy($otherUser)
    {
        return $this->getId() === $otherUser->getId();
    }

}
