<?php


namespace App\Controller;


use App\Entity\Image;
use App\Entity\Operation;
use App\Entity\User;
use App\Form\Type\OperationType;
use App\Form\Type\SearchFilterType;
use App\Repository\OperationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route ("/operation")
 * @IsGranted("ROLE_ADMIN")
 */

class OperationController extends AbstractController
{
    /**
     * @Route("/listOperations", name="operation_list", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function list(Request $request)
    {
        $searchForm = $this->createForm(SearchFilterType::class);
        $searchForm->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(Operation::class);
        $items = $repository->findAll();
//        return new Response($limit);
        return $this->render('Operations/ListOperation.html.twig', ['operations' => $items, 'search'=> $searchForm->createView()]);
//        return $this->json($items
//        );
    }

    

    /**
     * @Route("/{id}", name="operation_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("operation", class="App:Operation")
     */
    public function operation($operation)
    {
        return $this->render('Operations/DetailsOperation.html.twig', ['operation' => $operation]);
//        return $this->json($operation
////            $this->getDoctrine()->getRepository(Operation::class)->find($id)
//        );
    }

    /**
     * @Route("/user/{id}",name="operation_by_user", requirements={"id"="\d+"},methods={"GET"})
     */
    public function operationByUser (User $user ){
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $operation= $this->getDoctrine()->getRepository(Operation::class)->findBy(array('user' => $user));
        return $this->json($operation);

    }



//    /**
//     * @Route("/{user", name="operation_by_user", requirements={"user"="\s+"}, methods={"GET"})
//     * @ParamConverter("operation", class="App:Operation")
//     */
//    public function operationByUser($operationUser)
//    {
//        return $this->render('Operations/DetailsOperation.html.twig', ['operation' => $operationUser]);
//////        return $this->json($operation
////////            $this->getDoctrine()->getRepository(Operation::class)->find($user)
//////        );
////    }

//        /**
//     * @Route("/{user}", name="operation_by_id_user", methods={"GET"})
//     * @ParamConverter("operation", class="App:Operation", options={"mapping": {"user": "user"}})
//     */
//    public function operationByIdUser($operation)
//    {
//        return $this->json($operation);
//            $this->getDoctrine()->getRepository(Operation::class)->findByExampleField()(['user' => $user]);
//    }


    /**
     * @Route("/add", name="operation_add", methods={"POST", "GET"})
     */

    public function new(Request $request): Response
    {
        $operation = new Operation();
        $operation->setDateCreation(new \DateTime('now'));
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            $imageAfter = $form->get('imageAfter')->getData();

             $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new Image();
                $img->setName($fichier);
                $img->setType(0);
                $operation->addImage($img);

            $fichier2 = md5(uniqid()) . '.' . $imageAfter->guessExtension();

            $imageAfter->move(
                $this->getParameter('images_directory'),
                $fichier2
            );

            $img2 = new Image();
            $img2->setName($fichier2);
            $img2->setType(1);
            $operation->addImage($img2);

            $user=$this->getUser();
            $operation->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operation);
            $entityManager->flush();

            return $this->redirectToRoute('operation_list');
        }

        return $this->render("Operations/AddOperation.html.twig", [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/delete/{id}", name="operation_delete", methods={"GET", "DELETE"})
     */
    public function delete(Operation $operation)
    {
//        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($operation);
            $entityManager->flush();
//        }

        return $this->redirectToRoute('operation_list');
    }


    /**
     * @Route("/search", name="search_operation")
     */
    public function recherche(Request $request ,OperationRepository $repository) {

        $query = $request->request->get('search_filter')['search'];
        if($query){
            $operation= $repository->findAllQueryBuilder($query);
        }

        return $this->render('Operations/SearchOperation.html.twig', ['operations' => $operation ]);
//        dump($request->request->get( "search_filter")['search']);die();
    }


    //    /**
//     * @Route("/{email}", name="operation_by_date", methods={"GET"})
//     * @ParamConverter("operation", class="App:Operation", options={"mapping": {"dateCreation": "dateCreation"}})
//     */
//    public function operationByDate($operation)
//    {
//        return $this->json($operation);
////            $this->getDoctrine()->getRepository(Operation::class)->findOneBy(['email' => $email])
//
//
//    }


    //    public function add(Request $request)
//    {
//        $operation = new Operation();
//
//        $form = $this->createForm(OperationType::class,$operation);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
////            var_dump($form->getData());die();
////            $event = $form->getData();
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($operation);
//            $entityManager->flush();
//            return $this->redirectToRoute('operation_list');
//        }
//        return $this->render('Operations/AddOperation.html.twig', array(
//            'format' => $form->createView(),
//        ));
//        /** @var Serializer $serializer */
//        $serializer = $this->get('serializer');
//
//        $operation = $serializer-> deserialize($request->getContent(), Operation::class, 'json');
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($operation);
//        $em->flush();
//
//        return $this->json($operation);

    //    public function delete(Operation $operation)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $em->remove($operation);
//        $em->flush();
//        return $this->redirectToRoute('operation_list');
////        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
//    }

}