<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(['username', 'email', 'mapper_name', 'ipAddress'])
            ->setDefaultSort(['id' => "DESC"]);
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            // ...
            // (the same permission is granted to the action on all pages)
            ->setPermission(Action::EDIT, 'ROLE_MODERATOR')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            // you can set permissions for built-in actions in the same way
            ->setPermission(Action::NEW, 'ROLE_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('User part')->setCssClass('col-6'),
            TextField::new('username')->setColumns('col-12'),
            TextField::new('email')->setColumns('col-12'),
            TextField::new('ip_address')->hideOnForm(),
            ChoiceField::new('roles')->setColumns('col-12')
                ->allowMultipleChoices()
                ->setChoices([
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_MODERATOR' => 'ROLE_MODERATOR',
                    'ROLE_PREMIUM_LVL3' => 'ROLE_PREMIUM_LVL3',
                    'ROLE_PREMIUM_LVL2' => 'ROLE_PREMIUM_LVL2',
                    'ROLE_PREMIUM_LVL1' => 'ROLE_PREMIUM_LVL1',
                ]),
            CountryField::new('country.twoLetters', 'Pays')->hideOnForm(),
            BooleanField::new('avatarDisabled')->setColumns('col-4'),
            DateTimeField::new('createdAt')->setColumns('col-4')->hideOnForm(),
            BooleanField::new('isVerified')->setColumns('col-4'),
            BooleanField::new('certified')->setColumns('col-4'),
            FormField::addPanel('Mapper part')->setCssClass('col-6'),
            TextField::new('mapperName')->setColumns('col-12'),
            TextareaField::new('mapperDescription')->setColumns('col-12'),
        ];
    }

}
