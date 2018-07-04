<?php

namespace Packagist\WebBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class MenuBuilder
{
    private $factory;
    private $username;
    private $translator;

    /**
     * @param FactoryInterface      $factory
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface   $translator
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;

        if ($tokenStorage->getToken() && $tokenStorage->getToken()->getUser()) {
            $this->username = $tokenStorage->getToken()->getUser()->getUsername();
        }
    }

    public function createUserMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'list-unstyled');

        $this->addProfileMenu($menu);
        $menu->addChild('hr', ['label' => '<hr>', 'labelAttributes' => ['class' => 'normal'], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.logout'), ['label' => '<span class="fas fa-power-off"></span>' . $this->translator->trans('menu.logout'), 'route' => 'logout', 'extras' => ['safe_label' => true]]);

        return $menu;
    }

    public function createProfileMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked');

        $this->addProfileMenu($menu);

        return $menu;
    }

    public function createAdminMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'list-unstyled');

        $menu->addChild($this->translator->trans('menu.my_users'), ['label' => '<span class="fas fa-user"></span>' . $this->translator->trans('menu.my_users'), 'route' => 'users_list', 'extras' =>['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.my_groups'), ['label' => '<span class="fas fa-users"></span>' . $this->translator->trans('menu.my_groups'), 'route' => 'groups_index', 'extras' =>['safe_label' => true]]);

        return $menu;
    }

    private function addProfileMenu(ItemInterface $menu)
    {
        $menu->addChild($this->translator->trans('menu.profile'), ['label' => '<span class="fas fa-id-card"></span>' . $this->translator->trans('menu.profile'), 'route' => 'fos_user_profile_show', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.settings'), ['label' => '<span class="fas fa-cogs"></span>' . $this->translator->trans('menu.settings'), 'route' => 'fos_user_profile_edit', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.change_password'), ['label' => '<span class="fas fa-key"></span>' . $this->translator->trans('menu.change_password'), 'route' => 'fos_user_change_password', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.my_packages'), ['label' => '<span class="fas fa-box-open"></span>' . $this->translator->trans('menu.my_packages'), 'route' => 'user_packages', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.my_favorites'), ['label' => '<span class="fas fa-leaf"></span>' . $this->translator->trans('menu.my_favorites'), 'route' => 'user_favorites', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
    }
}
