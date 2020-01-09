<?php


namespace App\Controller;


use App\Api\GitApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class GitResumeController extends AbstractController {
   public function index(Request $request) {
      $form = $this->createFormBuilder()
         ->add('username', TextType::class)
         ->add('submit', SubmitType::class, [
            'label' => 'Generate Resume',
            'attr' => ['class' => 'btn btn-block btn-primary']
         ])
         ->getForm();
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $data = $form->getData();
         $gitApi = new GitApi();
         $profileInfo = $gitApi->getCompleteProfile($data['username']);
         $data = ['username' => $data['username']];
         if ($profileInfo) {
            $data['profileInfo'] = $profileInfo;
         }
         return $this->render('resume.html.twig', $data);
      }
      return $this->render('index.html.twig', ['form' => $form->createView()]);
   }


}