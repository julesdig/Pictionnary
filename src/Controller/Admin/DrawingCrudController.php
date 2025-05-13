<?php

namespace App\Controller\Admin;

use App\Entity\Drawing;
use App\Form\Builder\ImportDrawingFormBuilder;
use App\Form\Handler\ImportDrawingFormHandler;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
class DrawingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Drawing::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        $importAction = Action::new('import', 'Importer', 'fa fa-upload')
            ->linkToCrudAction('importMass')
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $importAction)
            ->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function importMass(AdminContext $context, ImportDrawingFormBuilder $builder, ImportDrawingFormHandler $handler)
    {
        $form = $builder->getForm();
        $response = $handler->handle($context->getRequest(), $form);
        if($response) {
            $this->addFlash('success', 'Importation réussie, On vous avertira quand le traitement sera terminé');
            return $this->redirectToRoute('admin_drawing_index');
        }
        return $this->render('admin/drawing/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
