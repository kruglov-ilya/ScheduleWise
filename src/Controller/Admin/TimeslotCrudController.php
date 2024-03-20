<?php

namespace App\Controller\Admin;

use App\Entity\Timeslot;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TimeslotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Timeslot::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield DateTimeField::new('start', 'Время начала');
        yield NumberField::new('count', 'Кол-во допустимых записей');
        yield AssociationField::new('allowedServices', 'Допустимые услуги');
        yield AssociationField::new('bookings', 'Записи на услуги')->setDisabled();
    }
}
