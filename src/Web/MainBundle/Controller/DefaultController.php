<?php

namespace Web\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Web\EntityBundle\Entity\Document;
use Web\EntityBundle\Form\DocumentType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // creer l'entity  manager qui  est la liasion entre le la base de donnée et  le modele ou nos entites
        $em=$this->getDoctrine()->getManager();

        // recuperer la liste des documents se trouvent  dazns la base de donnée en les classant  par  ordre décroissant  suivant  l'identifiant
        $list=$em->getRepository('EntityBundle:Document')->findBy([],['id'=>'DESC']);
        // la vue attent  un tableau:  on cree un tableau  ayant  une colone list et  qui  doit  comporter  la liste des documents



        $listaide=$em->getRepository('EntityBundle:Document')->findBy([],['id'=>'DESC']);
        // on dois testé si  la requête est  post
        if($request->isMethod("POST"))
        {

            $em =$this->getDoctrine()->getManager();

            // on recuperer tous les champs se trouvant dans le formulaire
            $val = $request->request;
            // on recupere la valeur du champs nomme name
            $name = $val->get('name');

            // recuperer la liste des documents se trouvent  dazns la base de donnée en les classant  par  ordre décroissant  suivant  l'identifiant
            $list=$em->getRepository('EntityBundle:Document')->findBy(['name'=>$name],['id'=>'DESC']);

        }


        $array['list'] =$list;
        $array['listaide'] =$listaide;
        // on affiche la vue nommée index.html.twig se trouvant dans le dossier Default et on lui passe en parammètre le tableau crée précedement
        return $this->render('MainBundle:Default:index.html.twig',$array);
    }
    public function addAction(Request $request)
    {
        //creer un document  vide
        $document   = new Document();

        // creer  le formulaire le premier paramètre est le nomdu  formulaire généré et  le deuxieme est  l'objet a prendre en consideration dans notre formulaire
        $form = $this->get('form.factory')->create(DocumentType::class,$document);
        //$form = $this->createForm(new DocumentType(),$document);

        // on dois testé si  la requête est  post
        if($request->isMethod("POST"))
        {
            // mettre a jour document  avec les informations remplir dans la bd
            $form->handleRequest($request);

            $em =$this->getDoctrine()->getManager();
            // faire une sauvegarde temporaire de l'objet
            $em->persist($document);

            // forcer l'enregistrement  de l'objet :  faire un commit
            $em->flush();

            //  lorqu'on enregistree  on dois vider le formulaire pour un nouveau  enregistrement
            $document   = new Document();
            $form = $this->get('form.factory')->create(DocumentType::class,$document);

        }

        //  creer un tableau qui  comporte la vue en questuion
        $array['form'] =$form->createView();
        // afficher la vue avec son parametre
        return $this->render('MainBundle:Default:add.html.twig',$array);
    }
    public function editAction(Request $request,$id)
    {
        $em =$this->getDoctrine()->getManager();

        //rechercher le document  qui  comporte l'identifiant  passe en parmetre
        $document   = $em->getRepository('EntityBundle:Document')->find($id);

        // creer  le formulaire le premier paramètre est le nomdu  formulaire généré et  le deuxieme est  l'objet a prendre en consideration dans notre formulaire
        $form = $this->get('form.factory')->create(DocumentType::class,$document);

        // on dois testé si  la requête est  post
        if($request->isMethod("POST"))
        {
            // mettre a jour document  avec les informations remplir dans la bd
            $form->handleRequest($request);

            // forcer l'enregistrement  de l'objet :  faire un commit
            $em->flush();
            // faire une redirection  vers la page d'accueil
            return $this->redirect($this->generateUrl('main_homepage'));

        }

        //  creer un tableau qui  comporte la vue en questuion
        $array['form'] =$form->createView();
        // afficher la vue avec son parametre
        return $this->render('MainBundle:Default:edit.html.twig',$array);
    }
    public function deleteAction($id)
    {
        $em =$this->getDoctrine()->getManager();

        //rechercher le document  qui  comporte l'identifiant  passe en parmetre
        $document   = $em->getRepository('EntityBundle:Document')->find($id);

        // preparer  la suppression du  document
        $em->remove($document);

        // on bvalide la supprion
        $em->flush();

        // faire une redirection  vers la page d'accueil
        return $this->redirect($this->generateUrl('main_homepage'));

    }
    public function detailAction($id)
    {
        $em =$this->getDoctrine()->getManager();

        //rechercher le document  qui  comporte l'identifiant  passe en parmetre
        $document   = $em->getRepository('EntityBundle:Document')->find($id);

        $array['document']= $document;
        return $this->render('MainBundle:Default:detail.html.twig',$array);
    }

}
