<?php
namespace App\Controller;
use function MongoDB\BSON\toJSON;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class AuthController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $j = json_decode($request->getContent());

        $email = $j->email;
        $password = $j->password;

        $user = new User($email);
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setNom($j->nom);
        $user->setPrenom($j->prenom);
        $user->setImage($request->request->get('image'). "");
        $user->setRoles(['user']);
        $em->persist($user);
        $em->flush();

        return new JsonResponse();
    }
    public function api()
    {   $j = new JsonResponse();
        $user = new User($this->getUser()->getUsername());
        $k = ['nom' =>$this->getUser()->getNom(),
        'prenom' =>$this->getUser()->getPrenom(),
        'email' =>$this->getUser()->getUsername(),
        'roles' =>$this->getUser()->getRoles(),
        'image' =>$this->getUser()->getImage(),];
        $j->setData($k);
        return $j;
    }

    public function findUserByLogin(Request $request){

    }
}