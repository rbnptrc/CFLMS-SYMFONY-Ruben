<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Bikes;

// Now we need some classes in our Controller because we need that in our form (for the inputs that we will create)
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BikeController extends AbstractController
{

        /**
     * @Route("/", name="home_page")
     */
    public function showAction()
    {
         // Here we will use getDoctrine to use doctrine and we will select the entity that we want to work with and we used findAll() to bring all the information from it and we will save it inside a variable named bikes and the type of the result will be an array
       $bikes = $this->getDoctrine()->getRepository('App:Bikes')->findAll();
     
       return $this->render('bike/index.html.twig', array('bikes'=>$bikes));
// we send the result (the variable that have the result of bringing all info from our database) to the index.html.twig page
    }

      /**
    * @Route("/edit/{id}", name="edit_page")
    */
   public function editAction($id, Request $request)
   {
    $bikes = $this->getDoctrine()->getRepository('App:Bikes')->find($id);
    
    $bikes->setBrand($bikes->getBrand());
    $bikes->setModel($bikes->getModel());
    $bikes->setColor($bikes->getColor());
    $bikes->setAvailability($bikes->getAvailability());
    $bikes->setStartDate($bikes->getStartDate());
    $bikes->setReturnDate($bikes->getReturnDate());


    $form = $this->createFormBuilder($bikes)->add('brand', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-botton:15px')))
    ->add('model', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
    ->add('color', TextareaType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-botton:15px')))
    ->add('availability', ChoiceType::class, array('choices'=>array('yes'=>'yes', 'no'=>'no', ),'attr' => array('class'=> 'form-control', 'style'=>'margin-botton:15px')))
    ->add('startdate', DateTimeType::class, array('attr' => array('style'=>'margin-bottom:15px')))
    ->add('returndate', DateTimeType::class, array('attr' => array('style'=>'margin-bottom:15px')))
    ->add('save', SubmitType::class, array('label'=> 'Update Bike', 'attr' => array('class'=> 'btn-primary', 'style'=>'margin-botton:15px')))
    ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        //fetching data
        $brand = $form['brand']->getData();
        $model = $form['model']->getData();
        $color = $form['color']->getData();
        $availability = $form['availability']->getData();
        $startdate = $form['startdate']->getData();
        $returndate = $form['returndate']->getData();

        $em = $this->getDoctrine()->getManager();
        $bikes = $em->getRepository('App:Bikes')->find($id);
        $bikes->setBrand($brand);
        $bikes->setModel($model);
        $bikes->setColor($color);
        $bikes->setAvailability($availability);
        $bikes->setStartDate($startdate);
        $bikes->setReturnDate($returndate);

        $em->flush();
        $this->addFlash(
                'notice',
                'Bike Updated'
                );
                return $this->redirectToRoute('home_page');
            }

       return $this->render('bike/edit.html.twig', array('bikes'=> $bikes, 'form' =>$form->createView()));
   }

   
   /**
    * @Route("/details/{id}", name="details_page")
    */
   public function detailsAction($id)
   {
       $bikes = $this->getDoctrine()->getRepository('App:Bikes')->find($id);
       return $this->render('bike/details.html.twig', array('bikes'=> $bikes));
   }

   /**
    * @Route("/delete/{id}", name="bike_delete")
    */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
   $bikes = $em->getRepository('App:Bikes')->find($id);
   $em->remove($bikes);
    $em->flush();
   $this->addFlash(
           'notice',
           'Bike Removed'
           );
    return $this->redirectToRoute('home_page');
}

 /**
    * @Route("/create", name="create_page")
    */
    public function createAction(Request $request)
    {
        // Here we create an object from the class that we made
        $bikes = new Bikes;
 /* Here we will build a form using createFormBuilder and inside this function we will put our object and then we write add then we select the input type then an array to add an attribute that we want in our input field */
 $form = $this->createFormBuilder($bikes)->add('brand', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-botton:15px')))
 ->add('model', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
 ->add('color', TextareaType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-botton:15px')))
 ->add('availability', ChoiceType::class, array('choices'=>array('yes'=>'yes', 'no'=>'no', ),'attr' => array('class'=> 'form-control', 'style'=>'margin-botton:15px')))
 ->add('startdate', DateTimeType::class, array('attr' => array('style'=>'margin-bottom:15px')))
 ->add('returndate', DateTimeType::class, array('attr' => array('style'=>'margin-bottom:15px')))
 ->add('save', SubmitType::class, array('label'=> 'Update Bike', 'attr' => array('class'=> 'btn-primary', 'style'=>'margin-botton:15px')))
 ->getForm();


        $form->handleRequest($request);
        
 
        /* Here we have an if statement, if we click submit and if  the form is valid we will take the values from the form and we will save them in the new variables */
        if($form->isSubmitted() && $form->isValid()){
            //fetching data
 
            // taking the data from the inputs by the name of the inputs then getData() function
            $brand = $form['brand']->getData();
            $model = $form['model']->getData();
            $color = $form['color']->getData();
            $availability = $form['availability']->getData();
            $startdate = $form['startdate']->getData();
            $returndate = $form['returndate']->getData();
            
 
 /* these functions we bring from our entities, every column have a set function and we put the value that we get from the form */
 $bikes->setBrand($brand);
 $bikes->setModel($model);
 $bikes->setColor($color);
 $bikes->setAvailability($availability);
 $bikes->setStartDate($startdate);
 $bikes->setReturnDate($returndate);

            $em = $this->getDoctrine()->getManager();
            $em->persist($bikes);
            $em->flush();
            $this->addFlash(
                    'notice',
                    'bikes Added'
                    );
            return $this->redirectToRoute('home_page');
        }
 /* now to make the form we will add this line form->createView() and now you can see the form in create.html.twig file  */
        return $this->render('bike/create.html.twig', array('form' => $form->createView()));
    }
 
   
}



