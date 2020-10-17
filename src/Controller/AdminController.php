<?php


namespace App\Controller;


use App\Entity\Panel;
use App\Entity\Station;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/Admin", name="admin",methods={"get"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index (){

        $em=$this->getDoctrine()->getManager();
        $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
        $repositoryStations= $this->getDoctrine()->getRepository(Station::class);
        $repositoryPanels = $this->getDoctrine()->getRepository(Panel::class);
        $itemsUsers = $repositoryUsers->CountUsers();
        $itemsStations = $repositoryStations->CountStations();
        $itemsPanels = $repositoryPanels->CountPanels();
        return $this->render('Admin/home.html.twig', ['itemsUsers'=>$itemsUsers,'itemsStations'=>$itemsStations,'itemsPanels'=>$itemsPanels]);
    }

}