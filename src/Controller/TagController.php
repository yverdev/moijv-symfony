<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag")
     */
    public function index(Request $request, SluggerInterface $slugger, TagRepository $tagRepository)
    {
        $q = $request->get('q');
        $slug = $slugger->slug($q)->lower()->toString();
        //dd($slug->lower());
        $tags = $tagRepository->searchBySlug($slug);
        $tagsArray = array_map(function($tag){
            return [
                'id' => $tag->getId(),
                'label' => $tag->getName(),
                'value' => $tag->getName()
            ];
        }, $tags);
        return $this->json($tagsArray);
    }
}