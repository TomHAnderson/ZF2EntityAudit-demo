<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController
    , Zend\View\Model\ViewModel
    , Application\Form\Type as TypeForm
    , Application\Entity\Type as TypeEntity
    ;

class IndexController extends AbstractActionController {

    public function indexAction()
    {
        $entityManager = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");

        $typeForm = new TypeForm();

        if ($this->getRequest()->isPost()) {
            $typeEntity = new TypeEntity();
            $typeForm->setInputFilter($typeEntity->getInputFilter());
            $typeForm->setData($this->getRequest()->getPost());

            if ($typeForm->isValid()) {
                $typeEntity->exchangeArray($typeForm->getData());
                // Set the revision comment before flushing
                $formData = $typeForm->getData();
                if ($formData['revisionComment'])
                    $this->getServiceLocator()
                        ->get('auditComment')
                        ->setComment($formData['revisionComment']);

                $entityManager->persist($typeEntity);
                $entityManager->flush();

                return $this->redirect()->toUrl("/");
            }
        }

        return new ViewModel(array(
            "records" => $entityManager->getRepository("Application\Entity\Type")->findAll(),
            "form" => $typeForm
        ));
    }

    public function editAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        $entityManager = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");
        $type = $entityManager->getRepository("Application\Entity\Type")->find($id);

        $typeForm = new TypeForm();
        $typeForm->bind($type);
        if ($this->getRequest()->isPost()) {
            $typeForm->setInputFilter($type->getInputFilter());
            $typeForm->setData($this->getRequest()->getPost());
            if ($typeForm->isValid()) {
                $type->exchangeArray($typeForm->getData()->getArrayCopy());

                // Set the revision comment before flushing
                $revisionComment = $this->getRequest()->getPost()->get('revisionComment');
                if ($revisionComment)
                    $this->getServiceLocator()
                        ->get('auditComment')
                        ->setComment($revisionComment);

                $entityManager->persist($type);
                $entityManager->flush();
                return $this->redirect()->toUrl('/');
            }
        }

        return new ViewModel(array(
            'form' => $typeForm,
            'type' => $type,
        ));
    }

    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');

        $entityManager = $this->getServiceLocator()->get("doctrine.entitymanager.orm_default");

        $type = $entityManager->getRepository("Application\Entity\Type")->find($id);

        if ($type) {
            // Set the revision comment before flushing
            $revisionComment = $this->getRequest()->getQuery()->get('revisionComment');
            if ($revisionComment)
                $this->getServiceLocator()
                    ->get('auditComment')
                    ->setComment($revisionComment);

#        die($revisionComment);

            $entityManager->remove($type);
            $entityManager->flush();
        }



        return $this->redirect()->toUrl('/');
    }
}
