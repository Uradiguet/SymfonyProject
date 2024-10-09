<?php
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    public function __construct(private Symfony\Bundle\SecurityBundle\Security $security)
    {
    }

    public function getUser(): ?UserInterface {
        return $this->security->getUser();
    }


    public function isAuth():bool{
        return $this->getUser()!=null;
    }

    public function getMainMenu():array {
        $menu = [
            ['caption'=>'Se connecter', 'route'=>'app_login'],
            ['caption'=>'Créer un compte', 'route'=>'app_register'],
        ];
        if($this->usAuth()) {
            $menu=[
                ['caption'=>'Se déconnecter', 'route'=>'app_logout']
            ];
        }
        return $menu;
    }
}