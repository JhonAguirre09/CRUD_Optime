<?php

namespace Jaguirre\TestCRUDBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jaguirre\TestCRUDBundle\Entity\Products;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function detailsAction($name)
    {
	$product = new Products();
	$repository = $this->getDoctrine()->getRepository('JaguirreTestCRUDBundle:Products');
	$product = $repository->findOneByName($name);
        return $this->render('JaguirreTestCRUDBundle:Default:Details.html.twig', array("product"=>$product));
    }

    public function productsAction(Request $request)
    {
	$product = new Products();
	$form = $this->createFormBuilder($product)
	->add('Nuevo', 'submit')
	->getForm();


	$repository = $this->getDoctrine()->getRepository('JaguirreTestCRUDBundle:Products');
	$products = $repository->findAll();

        return $this->render('JaguirreTestCRUDBundle:Default:Products.html.twig' , array('form' => $form->createView() , 'products' => $products));
    }

    public function createAction(Request $request)
    {

	$product = new Products();
	$form = $this->createFormBuilder($product)
            ->add('code', 'text', array('label'  => 'Codigo',))
	    ->add('name', 'text', array('label'  => 'Nombre',))
	    ->add('description', 'text', array('label'  => 'Descripcion',))
	    ->add('trademark', 'text', array('label'  => 'Marca',))
	    ->add('category', 'text', array('label'  => 'Categoria',))
	    ->add('price', 'text', array('label'  => 'Precio',))
            ->add('Guardar', 'submit')
            ->getForm();

	$product -> setstatus('activo');
	
	$form->handleRequest($request);	

        if ($form->isValid()) {	 		
	    $validator = $this->get('validator');
            $errors = $validator->validate($product);   
	    if (count($errors) > 0) {
	    	$errorsString = (string) $errors; 
        	return new Response($errorsString);
    	    }
	    $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();	  
	    return $this->redirect($this->generateUrl('jaguirre_test_crud_Products')); 
	}
	
	return $this->render('JaguirreTestCRUDBundle:Default:Create.html.twig' , array('form' => $form->createView()));
	
    }

    public function updateAction(Request $request)
    {

	return $this->render('JaguirreTestCRUDBundle:Default:Update.html.twig');
    }
    

    public function eraseAction($id)
    {
	$product = new Products();
	
	$repository = $this->getDoctrine()->getRepository('JaguirreTestCRUDBundle:Products');
	$product = $repository->find($id);

	$em = $this->getDoctrine()->getManager();
	$em->remove($product);
	$em->flush();
	return $this->redirect($this->generateUrl('jaguirre_test_crud_Products')); 
    }
}
