<?php

namespace App\Controller;
use App\Entity\DataTest;
use App\Entity\Member;
use App\Entity\SuperAdmin;

use App\Entity\Trainer;
use App\Entity\Training;
use App\Repository\MemberRepository;
use App\Repository\SuperAdminRepository;
use App\Repository\TrainerRepository;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

// registerSA
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SuperAdminController extends AbstractController
{

    /**
     * @Route("/SuperAdmin", name="super_admin_page")
     * @param SuperAdminRepository $SAR
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function SuperAdminPage(Security $user, SuperAdminRepository $SAR, TrainerRepository $TR, TrainingRepository $trainingRepository, MemberRepository $MR){
        //Roots
        $GRP = $SAR->GetRootPreview(5);
        $TotalRots = $SAR->CountRoot();
        //Trainers
        $GTP = $TR->GetTrainerPreview(5);
        $TotalTrainers = $TR->CountTrainer();
        //Trainings
        $GTRepositoryPreview = $trainingRepository->GetTrainingPreview(5);
        $TotalTrainings = $trainingRepository->CountTraining();
        //Members
        $GetMembersPreview = $MR->GetMemberPreview(5);
        $TotalMembers = $MR->CountMember();



        return $this->render('SuperAdmin/index.html.twig',array(
            'controller_name' => 'SuperAdminController',
            'RootName' => $user->getUser()->getUsername(),
            'TotalRots' => $TotalRots,
            'GetRootPreview' => $GRP,
            'TotalTrainers' => $TotalTrainers,
            'GetTrainerPreview' => $GTP,
            'GetTrainingPreview' =>$GTRepositoryPreview,
            'TotalTrainings' =>$TotalTrainings,
            'GetMembersPreview' => $GetMembersPreview,
            'TotalMembers' => $TotalMembers,
            'version' => " ver. 19.05.2020 19:20",
        ));
    }
    /**
     * @Route("SuperAdmin/registerSA", name="app_registerSA")
     */
    public function registerSA(Request $request, UserPasswordEncoderInterFace $passEmcoder, Security $user)
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' =>['label' => 'Password'],
                'second_options' =>['label' => 'Confirm Password']
            ])
            ->add('Add', SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();

            $user = new SuperAdmin();
            $user->setUsername($data['username']);
            $user->setPassword(
                $passEmcoder->encodePassword($user, $data['password'])
            );

            $em = $this->getDoctrine()->getManager();
            $em -> persist($user);
            $em -> flush();
            return $this->redirect($this->generateUrl('super_admin_page'));
        }

    return $this->render('SuperAdmin/CRUD/registerSA.html.twig',array(
        'form' => $form->createView(),
        'controller_name' => 'SuperAdminController_RegisterSA',
        'RootName' => $user->getUser()->getUsername(),
    ));
    }
    /**
     * @Route("SuperAdmin/crudSA", name="super_admin_crud")
     *
     */
    public function SuperAdminPage_CRUD(Security $user){

        return $this->render('SuperAdmin/CRUD/crud.html.twig',array(
            'controller_name' => 'SuperAdminController_CRUD',
            'RootName' => $user->getUser()->getUsername(),
        ));
    }
    /**
     * @Route("SuperAdmin/crudSA/SACRUD", name="SA_CRUD")
     */
    public function SuperAdmin_CRUD(Security $user){
        $roots = $this->getDoctrine()->getRepository(SuperAdmin::class)->findAll();

        return $this->render('SuperAdmin/CRUD/SACRUD.html.twig',array(
            'controller_name' => 'SuperAdminController_ROOT_CRUD',
            'Roots' => $roots,
            'RootName' => $user->getUser()->getUsername(),
        ));
    }
    /**
     * @Route("SuperAdmin/crudSA/SACRUD/show/{id}", name="SA_CRUD_Show")
     */
    public function SuperAdmin_CRUD_Show($id, Security $user){
        $roots = $this->getDoctrine()->getRepository(SuperAdmin::class)->find($id);

        return $this->render('SuperAdmin/CRUD/SACRUD_show.html.twig',array(
            'controller_name' => 'SuperAdminController_ROOT_CRUD_SHOW',
            'Roots' => $roots,
            'RootName' => $user->getUser()->getUsername(),
        ));
    }
    /**
     * @Route("SuperAdmin/crudSA/SACRUD/delete/{id}")
     */
    public function delete($id){
        $roots = $this->getDoctrine()->getRepository(SuperAdmin::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($roots);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirect($this->generateUrl('SA_CRUD'));
    }
    /**
     * @Route("SuperAdmin/crudSA/SA_CRUD/edit_password/{id}", name="SA_CRUD_edit_password")
     */
    public function SA_CRUD_edit_password(Request $request, Security $user, $id, UserPasswordEncoderInterface $passwordEncoder)
    {
        $SuperAdminAll = $this->getDoctrine()->getRepository(SuperAdmin::class)->findAll();
        $SuperAdmin = $this->getDoctrine()->getRepository(SuperAdmin::class)->findOneBy(['id' => $id]);
        $formPass = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat new password']
            ])
            ->add('Change', SubmitType::class, [
                'label' => 'Change',
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        $formPass->handleRequest($request);

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $data = $formPass->getData();
            $newPass = $passwordEncoder->encodePassword($SuperAdmin, $data['password']);
            $this->getDoctrine()->getRepository(SuperAdmin::class)->upgradePassword($SuperAdmin,$newPass);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('SA_CRUD');
        }

        return $this->render('SuperAdmin/CRUD/Password.html.twig', [
            'controller_name' => 'SuperAdminController_ROOT_edit_Password',
            'RootName' => $user->getUser()->getUsername(),
            'Root' => $SuperAdminAll,
            'form' => $formPass->createView(),
        ]);
    }
//  /************************ TRAINER ************************/
    /**
     * @Route("SuperAdmin/crudSA/TrainerCRUD", name="TrainerCRUD")
     */
    public function Trainer_CRUD(Security $user){
        $Trainers = $this->getDoctrine()->getRepository(Trainer::class)->findAll();

        return $this->render('SuperAdmin/CRUD/TrainerCRUD.html.twig',array(
            'controller_name' => 'SuperAdminController_Trainer_CRUD',
            'Trainers' => $Trainers,
            'RootName' => $user->getUser()->getUsername(),
        ));
    }

    /**
     * @Route("SuperAdmin/crudSA/TrainerCRUD/show/{id}", name="Trainer_CRUD_Show")
     */
    public function Trainer_CRUD_Show($id, Security $user){
        $Trainers = $this->getDoctrine()->getRepository(Trainer::class)->find($id);

        return $this->render('SuperAdmin/CRUD/TrainerCRUD_show.html.twig',array(
            'controller_name' => 'SuperAdminController_Trainer_CRUD_SHOW',
            'trainer' => $Trainers,
            'RootName' => $user->getUser()->getUsername(),
        ));
    }
    /**
     * @Route("SuperAdmin/crudSA/TrainerCRUD/register", name="TrainerCRUD_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passEncoder,Security $user)
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('number')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'password'],
                'second_options' => ['label' => 'Confirm Password']
            ])
            ->add('register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();

            $trainer = new Trainer();
            $trainer->setUsername($data['username']);
            $trainer->setName($data['name']);
            $trainer->setSurname($data['surname']);
            $trainer->setEmail($data['email']);
            $trainer->setNumber($data['number']);
            $trainer->setPassword(
                $passEncoder->encodePassword($trainer, $data['password'])
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($trainer);
            $em->flush();

            return $this->redirect($this->generateUrl('TrainerCRUD'));
        }

        return $this->render('SuperAdmin/CRUD/TrainerCRUD_register.html.twig',[
            'form' => $form->createView(),
            'controller_name' => 'SuperAdminController_TrainerCRUD_register',
            'RootName' => $user->getUser()->getUsername(),
        ]);
    }
    /**
     * @Route("SuperAdmin/crudSA/TrainerCRUD/edit/{id}", name="TrainerCRUD_edit")
     */
    public function TrainerCRUD_edit(Request $request, $id ,Security $user){
        $trainer = new Trainer();
        $trainer = $this->getDoctrine()->getRepository(Trainer::class)->find($id);
        $form = $this->createFormBuilder($trainer)
            ->add('username', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Username'])
            ->add('name', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Name'])
            ->add('surname', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Surname'])
            ->add('number', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Number'])
            ->add('email', TextType::class, [ 'attr' => [ 'class' => 'form-control']])
            ->add('Save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary float-right'
                    ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('TrainerCRUD');
        }
        return $this->render('SuperAdmin/CRUD/TrainerCRUD_edit.html.twig', [
            'form' => $form->createView(),
            'trainer' => $trainer,
            'controller_name' => 'SuperAdminController_Member_edit',
            'RootName' => $user->getUser()->getUsername(),
        ]);
    }
    /**
     * @Route("SuperAdmin/crudSA/TrainerCRUD/delete/{id}")
     */
    public function TrainerCRUD_delete( $id){
        $trainer = $this->getDoctrine()->getRepository(Trainer::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trainer);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirect($this->generateUrl('TrainerCRUD'));
    }

    /**
     * @Route("SuperAdmin/crudSA/TrainerCRUD/edit_password/{id}", name="TrainerCRUD_edit_password")
     */
    public function TrainerCRUD_edit_password(Request $request, Security $user, $id, UserPasswordEncoderInterface $passwordEncoder)
    {
        $TrainerAll = $this->getDoctrine()->getRepository(Trainer::class)->findAll();
        $trainer = $this->getDoctrine()->getRepository(Trainer::class)->findOneBy(['id' => $id]);
        $formPass = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat new password']
            ])
            ->add('Change', SubmitType::class, [
                'label' => 'Change',
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        $formPass->handleRequest($request);

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $data = $formPass->getData();
            $newPass = $passwordEncoder->encodePassword($trainer, $data['password']);
            $this->getDoctrine()->getRepository(Trainer::class)->upgradePassword($trainer,$newPass);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('TrainerCRUD');
        }
        return $this->render('SuperAdmin/CRUD/Password.html.twig', [
            'controller_name' => 'SuperAdminController_Trainer_edit_Password',
            'RootName' => $user->getUser()->getUsername(),
            'trainer' => $TrainerAll,
            'form' => $formPass->createView(),
        ]);
    }

    //  /************************ MEMEBER ************************/
    /**
     * @Route("SuperAdmin/crudSA/MemberCRUD", name="MemberCRUD", methods={"GET", "HEAD"})
     */
    public function Member_CRUD(Security $user){
        $Member = $this->getDoctrine()->getRepository(Member::class)->findAll();

        return $this->render('SuperAdmin/CRUD/MemberCRUD.html.twig',array(
            'controller_name' => 'SuperAdminController_Member_CRUD',
            'Members' => $Member,
            'RootName' => $user->getUser()->getUsername(),
        ));
    }

    /**
     * @Route("SuperAdmin/crudSA/MemberCRUD/show/{id}", name="Member_CRUD_Show")
     */
    public function Member_CRUD_Show($id, Security $user){
        $Member = $this->getDoctrine()->getRepository(Member::class)->find($id);

        return $this->render('SuperAdmin/CRUD/MemberCRUD_show.html.twig',array(
            'controller_name' => 'SuperAdminController_Member_CRUD_SHOW',
            'member' => $Member,
           'RootName' => $user->getUser()->getUsername(),
        ));
    }

    /**
     * @Route("SuperAdmin/crudSA/MemberCRUD/register", name="MemberCRUD_register")
     */
    public function Member_CRUD_Register(Request $requst, UserPasswordEncoderInterface $passwordEncoder, Security $user)
    {
        $form = $this->createFormBuilder()
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('phone_number')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat password']
            ])
            ->add('Register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm();

        $form->handleRequest($requst);
        if ($form->isSubmitted()) {
            $data = $form->getData();

            $member = new Member();
            $member->setName($data['name']);
            $member->setSurname($data['surname']);
            $member->setEmail($data['email']);
            $member->setNumber($data['phone_number']);
            $member->setPassword(
                $passwordEncoder->encodePassword($member, $data['password'])
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            return $this->redirect($this->generateUrl('MemberCRUD'));
        }

        return $this->render('SuperAdmin/CRUD/MemberCRUD_register.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'SuperAdminController_Member_register',
            'RootName' => $user->getUser()->getUsername(),
        ]);
    }

    /**
     * @Route("SuperAdmin/crudSA/MemberCRUD/delete/{id}")
     */
    public function MemberCRUD_delete( $id){
        $member = $this->getDoctrine()->getRepository(Member::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($member);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirect($this->generateUrl('MemberCRUD'));
    }

    /**
     * @Route("SuperAdmin/crudSA/MemberCRUD/edit/{id}", name="MemberCRUD_edit")
     */
    public function MemberCRUD_edit(Request $request, $id,Security $user){
        $member = new Member();
        $member = $this->getDoctrine()->getRepository(Member::class)->find($id);
            $form = $this->createFormBuilder($member)
                ->add('name', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Name'])
                ->add('surname', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Surname'])
                ->add('number', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Phone'])
                ->add('email', TextType::class, [ 'attr' => [ 'class' => 'form-control']])
                ->add('zapisz', SubmitType::class, [
                    'label' => 'Save',
                    'attr' => ['class' => 'btn btn-primary float-right']
                ])
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('MemberCRUD');
        }
        return $this->render('SuperAdmin/CRUD/MemberCRUD_edit.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'SuperAdminController_Member_edit',
            'RootName' => $user->getUser()->getUsername(),
            'member' => $member,
        ]);
    }
    /**
     * @Route("SuperAdmin/crudSA/MemberCRUD/edit_password/{id}", name="MemberCRUD_edit_password")
     */

    public function MemberCRUD_edit_password(Request $request, Security $user, $id, UserPasswordEncoderInterface $passwordEncoder)
    {
        $memberAll = $this->getDoctrine()->getRepository(Member::class)->findAll();
        $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(['id' => $id]);
        $formPass = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat new password']
            ])
            ->add('Change', SubmitType::class, [
                'label' => 'Change',
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        $formPass->handleRequest($request);

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $data = $formPass->getData();
            $newPass = $passwordEncoder->encodePassword($member, $data['password']);
            $this->getDoctrine()->getRepository(Member::class)->upgradePassword($member,$newPass);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('MemberCRUD');
        }
        return $this->render('SuperAdmin/CRUD/Password.html.twig', [
            'form' => $formPass->createView(),
            'member' => $memberAll,
            'controller_name' => 'SuperAdminController_Member_edit_Password',
            'RootName' => $user->getUser()->getUsername(),
        ]);
    }

    //  /************************ Training ************************/
    /**
     * @Route("SuperAdmin/crudSA/TrainingCRUD", name="TrainingCRUD", methods={"GET", "HEAD"})
     */
    public function trainingCRUD(Security $user, TrainingRepository $trainingRepository){
        $Training = $this->getDoctrine()->getRepository(Training::class)->findAll();
        $getTrainer = $trainingRepository->getTrainer();

        return $this->render('SuperAdmin/CRUD/TrainingCRUD.html.twig',array(
            'controller_name' => 'SuperAdminController_Training_CRUD',
            'Trainer' => $getTrainer,
            'Training' => $Training,
            'RootName' => $user->getUser()->getUsername(),
        ));
    }
    /**
     * @Route("SuperAdmin/crudSA/TrainingCRUD/delete/{id}")
     */
    public function TrainingCRUD_delete($id){
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($training);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirect($this->generateUrl('TrainingCRUD'));
    }
    /**
     * @Route("SuperAdmin/crudSA/TrainingCRUD/show/{id}", name="TrainingCRUD_show")
     */
    public function TrainingCRUD_Show($id, Security $user, TrainingRepository $trainingRepository){
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        $getTrainer = $trainingRepository->getTrainer();

        return $this->render('SuperAdmin/CRUD/TrainingCRUD_show.html.twig',array(
            'controller_name' => 'SuperAdminController_TrainingCRUD_SHOW',
            'training' => $training,
            'trainer' =>$getTrainer,
            'RootName' => $user->getUser()->getUsername(),
        ));
    }

    /**
     * @Route("SuperAdmin/crudSA/TrainingCRUD/add", name="TrainingCRUD_add")
     */
    public function TrainingCRUD_add(Request $request,Security $user, TrainingRepository $trainingRepository){
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Training name'])
            ->add('description', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Description'])
            ->add('trainer_id', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Trainer (id)'])
            ->add('price', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Price'])
            ->add('free', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Free(0/1)'])
            ->add('Data_start', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Start date'])
            ->add('Data_end', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'End date'])

            ->add('add', SubmitType::class, [
                'label' => 'Add',
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dane = $form->getData();
            $training = new Training();

            $dateStringStart = $dane['Data_start'];
            list($day, $month, $year) = explode('-', $dateStringStart);
            $dateStart = new \DateTime();
            $dateStart->setDate($year, $month, $day);

            $dateStringEnd = $dane['Data_end'];
            list($day, $month, $year) = explode('-', $dateStringEnd);
            $dateEnd = new \DateTime();
            $dateEnd->setDate($year, $month, $day);

            $trainer = $this->getDoctrine()->getRepository(Trainer::class)->find($dane['trainer_id']);

            $training->setName($dane['name']);
            $training->setTrainer($trainer);
            $training->setDescription($dane['description']);
            $training->setPrice($dane['price']);
            $training->setFree($dane['free']);
            $training->setStartDate($dateStart);
            $training->setEndDate($dateEnd);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($training);
            $entityManager->flush();
            return $this->redirectToRoute('TrainingCRUD');
        }
        return $this->render('SuperAdmin/CRUD/TrainingCRUD_add.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'SuperAdminController_TrainingCRUD_add',
            'RootName' => $user->getUser()->getUsername(),
        ]);
    }
    /**
     * @Route("SuperAdmin/crudSA/TrainingCRUD/edit/{id}", name="trainingCRUD_edit")
     */
    public function trainingCRUD_edit(Request $request, $id,Security $user){
        $trainingALL = $this->getDoctrine()->getRepository(Training::class)->findAll();
        $training = new Training();
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        $form = $this->createFormBuilder($training)
            ->add('name', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Training name'])
            ->add('description', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Description'])
            ->add('price', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Price'])
            ->add('free', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Free(0/1)'])
            ->add('Edit', SubmitType::class, [
                'label' => 'Save Edit',
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('TrainingCRUD');
        }
        return $this->render('SuperAdmin/CRUD/trainingCRUD_edit.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'SuperAdminController_Member_edit',
            'RootName' => $user->getUser()->getUsername(),
            'training' => $trainingALL,
            'tr' =>$training,
        ]);
    }
    /**
     * @Route("SuperAdmin/crudSA/TrainingCRUD/edit_spec/{id}")
     */
    public function test(Request $request, $id,Security $user){

        $training = new Training();
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);

        $form1 = $this->createFormBuilder()
            ->add('trainer_id', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Trainer (id)'])
            ->add('Data_start', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'Start date'])
            ->add('Data_end', TextType::class, [ 'attr' => [ 'class' => 'form-control '], 'label' => 'End date'])
            ->add('Edit', SubmitType::class, [
                'label' => 'Edit',
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $dane = $form1->getData();
            $dateStringStart = $dane['Data_start'];
            list($day, $month, $year) = explode('-', $dateStringStart);
            $dateStart = new \DateTime();
            $dateStart->setDate($year, $month, $day);

            $dateStringEnd = $dane['Data_end'];
            list($day, $month, $year) = explode('-', $dateStringEnd);
            $dateEnd = new \DateTime();
            $dateEnd->setDate($year, $month, $day);

            $trainer = $this->getDoctrine()->getRepository(Trainer::class)->find($dane['trainer_id']);

            $training->setTrainer($trainer);
            $training->setStartDate($dateStart);
            $training->setEndDate($dateEnd);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('TrainingCRUD');
        }
        return $this->render('SuperAdmin/CRUD/TrainingCRUD_edit_spec.html.twig', [
            'form1' => $form1->createView(),
            'controller_name' => 'SuperAdminController_Member_edit',
            'RootName' => $user->getUser()->getUsername(),
        ]);
    }
}