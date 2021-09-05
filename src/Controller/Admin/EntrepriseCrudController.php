<?php

namespace App\Controller\Admin;

use App\Entity\Entreprise;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EntrepriseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Entreprise::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name'),
            TextField::new('adresse'),
            NumberField::new('contact'),
        ];
    }

}
