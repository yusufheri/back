<?php


namespace App\Controller;


use App\Entity\Operation;
use App\Entity\Panel;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index")
     */
    public function index()
    {
        return new JsonResponse([
            'action' => 'index',
            'time' => time()
        ]);

    }

    /**
     * @Route("/operationList/user/{id}",name="operationByUser", requirements={"id"="\d+"},methods={"GET"})
     */
    public function operationByUser (User $user ){

        $operation= $this->getDoctrine()->getRepository(Operation::class)->findBy(array('user' => $user));
        return $this->json($operation);


    }

    /**
     * @Route("/panelList/station/{id}", name="panelBystation", methods={"GET"})
     */
    public function getPanelsByStation($id)
    {
        $em = $this->getDoctrine()->getManager();
        $items= $em->getRepository(Panel::class)->findByExampleField($id);
//      $em->flush();
        $tab = [];
        foreach ($items as $item) {
            $tab[]= $item->getId().','.$item->getDescription().',' .$item->getStation();
        }
        return new JsonResponse($tab);
//        $rs = new Response();
//        return $this->redirectToRoute('panel_list');
//        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/userdetails/{username}", name="user_by_username", methods={"GET"})
     * @ParamConverter("user", class="App:User", options={"mapping": {"username": "username"}})
     */
    public function userByUsername($user)
    {
        return $this->json($user);
//            $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email])


    }

}

