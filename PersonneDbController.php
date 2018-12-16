<?php
/**
 * Created by PhpStorm.
 * User: RIM
 * Date: 13/12/2018
 * Time: 18:01
 */
namespace gmc\FirstBundle\Controller;
use gmc\FirstBundle\Entity\PersonneDb;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class PersonneDbController extends Controller
{

    public function listPersonAgeIntervalAction($ageMin,$ageMax){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('gmcFirstBundle:PersonneDb');
        $personnes = $repository->getPersonByAgeInterval($ageMin,$ageMax);
        return $this->render('@gmcFirst\PersonneDb\personnes.html.twig',
            array(
                'personnes'=> $personnes
            ));
    }


    public function addAction($nom,$prenom,$age,$path){

        $fileExist = file_exists("c:/xampp/htdocs/FirstProject/web/".$path);
        if(!$fileExist) {
            $path="edmin/images/user.png";
        }

        $personnes = new PersonneDb();
        $personnes->setPrenom($prenom);
        $personnes->setNom($nom);
        $personnes->setAge($age);
        $personnes->setPath($path);
        $em = $this->get('doctrine')->getManager();
        $em->persist($personnes);
        $em->flush();
        $this->addFlash('success','ajout avec succès');

        return $this->forward('gmcFirstBundle:PersonneDb:listPerson');
    }

    public function updateAction(PersonneDb $personne=null,$nom,$prenom,$age,$path){

        $fileExist = file_exists("c:/xampp/htdocs/FirstProject/web/".$path);
        if(! $fileExist) {
            $path="edmin/images/user.png";
        }
        if($personne){
            $personne->setPrenom($prenom);
            $personne->setNom($nom);
            $personne->setAge($age);
            $personne->setPath($path);
            $path = $personne->getPath();
            $em = $this->get('doctrine')->getManager();
            $em->persist($personne);
            $em->flush();
            $this->addFlash('success','mise à jour avec succès');
        }else{
            $this->addFlash('error','personne inexistante');
        }

        return $this->forward('gmcFirstBundle:PersonneDb:listPerson');

        //return $this->render('@gmcFirst/PersonneDb/personneDb.html.twig');
    }

    public function listPersonAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('gmcFirstBundle:PersonneDb');
        $personnes = $repository->findAll();
        return $this->render('@gmcFirst\PersonneDb\personnes.html.twig',
            array(
                'personnes'=> $personnes
            ));
    }

    public function listOnePersonAction($nom,$prenom,$age,$path)
    {
        return $this->render('@gmcFirst\PersonneDb\personneDb.html.twig',array(
            'nom' => $nom,
            'prenom' => $prenom,
            'age' => $age,
            'path' => $path
        ));
    }

    public function deletePersonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('gmcFirstBundle:PersonneDb');
        $pers = $repository->find($id);
        if($pers){
            $em->remove($pers);
            $em->flush();
            $this->addFlash('success','personne supprimée avec succès');
        } else{
            $this->addFlash('error','personne inexistante');
        }
        return $this->forward('gmcFirstBundle:PersonneDb:listPerson');
    }

    public function findNameAction($nom)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('gmcFirstBundle:PersonneDb');
        $personnes = $repository->findBy(array('nom' => $nom));
        return $this->render('@gmcFirst\PersonneDb\personnes.html.twig',
            array(
                'personnes'=> $personnes
            ));
    }

    public function findAgeAction($age)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('gmcFirstBundle:PersonneDb');
        $personnes = $repository->findBy(array('age' => $age));
        return $this->render('@gmcFirst\PersonneDb\personnes.html.twig',
            array(
                'personnes'=> $personnes
            ));
    }



}