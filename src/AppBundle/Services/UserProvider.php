<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserManagerInterface
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $repo = $this->em->getRepository('AppBundle:User');
        try {
            return $repo->findOneBy([
                'username' => $username,
            ]);
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException();
        } catch (\Exception $e) {
            throw new UnsupportedUserException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->em->getRepository('AppBundle:User')->getClass();
        return $userClass === $class || is_subclass_of($class, $userClass);
    }


}
