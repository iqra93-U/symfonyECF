<?php

namespace App\Controller\Admin;

use App\Entity\Competence;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CompetenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Competence::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name'),
            IntegerField::new('expertLevel'),
            BooleanField::new('likeCompetence')

        ];
    }

}
