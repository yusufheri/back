<?php


namespace App\Controller;


use App\Entity\Panel;
use App\Entity\User;
use App\Form\Type\PanelType;
use App\Form\Type\SearchFilterType;
use App\Repository\PanelRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Serializer;


/**
 * @Route ("/panel")
 * @IsGranted("ROLE_ADMIN")
 */

class PanelController extends AbstractController
{
    /**
     * @Route("/listPanels", name="panel_list", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function list( Request $request)
    {
        $searchForm = $this->createForm(SearchFilterType::class);
        $searchForm->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(Panel::class);
        $items = $repository->findAll();
//        return new Response($limit);
        return $this->render('/Panels/ListPanels.html.twig',['panels'=> $items, 'search'=> $searchForm->createView()]);
//        return $this->json($items
//        );
    }

    /**
     * @Route("/{id}", name="panel_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("panel", class="App:Panel")
     */
    public function Panel($panel)
    {
        return $this->render('Panels/DetailsPanel.html.twig', ['panel'=> $panel] );
//        return $this->json($panel
////            $this->getDoctrine()->getRepository(Panel::class)->find($id)
//        );
    }

    /**
     * @Route("/list/{description}", name="panel_by_description", methods={"GET"})
     * @ParamConverter("panel", class="App:Panel", options={"mapping": {"description": "description"}})
     */
    public function panelByDescription($panel)
    {
        return $this->json($panel);
//            $this->getDoctrine()->getRepository(Panel::class)->findOneBy(['email' => $email])


    }

    /**
     * @Route("/add", name="panel_add", methods={"POST", "GET"})
     */
    public function add(Request $request)
    {
        $panel = new Panel();

        $form = $this->createForm(PanelType::class,$panel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            var_dump($form->getData());die();
//            $event = $form->getData();
//           var_dump($this->getUser());
//           $userId=$user->getUsername();
            $user=$this->getUser();
//            var_dump($user);
            $panel->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($panel);
            $entityManager->flush();
            return $this->redirectToRoute('panel_list');
        }
        return $this->render('/Panels/AddPanel.html.twig', array(
            'format' => $form->createView(),
        ));
//        /** @var Serializer $serializer */
//        $serializer = $this->get('serializer');
//
//        $panel = $serializer-> deserialize($request->getContent(), Panel::class, 'json');
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($panel);
//        $em->flush();
//
//        return $this->json($panel);

    }
    /**
     * @Route("/listByStation/{id}", name="panel_by_station", methods={"GET"})
     */
    public function getPanelsByStation($id)
    {
        $em = $this->getDoctrine()->getManager();
        $items= $em->getRepository(Panel::class)->findByExampleField($id);
//      $em->flush();
        $tab = [];
        foreach ($items as $item) {
            $tab[]= $item->getId().','.$item->getDescription();
    }
        return new JsonResponse($tab);
//        $rs = new Response();
//        return $this->redirectToRoute('panel_list');
//        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @Route("/delete/{id}", name="panel_delete", methods={"GET", "DELETE"})
     */
    public function delete(Panel $panel)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($panel);
        $em->flush();
        return $this->redirectToRoute('panel_list');
//        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/edit/{id}", name="panel_edit",methods={"GET","POST"})
     * @ParamConverter("panel", class="App:Panel")
     */
    public function editPanel(Request $request, $panel)
    {
        $form = $this->createForm(PanelType::class,$panel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'edit successfully!');
            return $this->redirectToRoute('panel_list');
        }

        return $this->render('Panels/EditPanel.html.twig', [
            'format' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="search_panel")
     */
    public function recherche(Request $request ,PanelRepository $repository) {

        $query = $request->request->get('search_filter')['search'];
        if($query){
            $panels= $repository->findAllQueryBuilder($query);
        }

        return $this->render('Panels/SearchPanel.html.twig', ['panels' => $panels ]);
//        dump($request->request->get( "search_filter")['search']);die();
    }


}