<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Util\AppSession;
use App\Util\Filter;
use App\View\FrontRenderInterface;
use Hashids\Hashids;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends BaseController
{
    use ViewData;

    protected $model;
    protected $postModel;

    public function __construct(
        Request $request,
        FrontRenderInterface $view,
        Hashids $hashids,
        AppSession $session,
        Category $model,
        Post $postModel
    ) {
        parent::__construct($request, $view, $hashids, $session);
        $this->model = $model;
        $this->postModel = $postModel;
    }

    public function index(array $param)
    {
        if (
            !isset($param['id'])
            || !isset($param['title'])
            || !is_int((int)$param['id'])
            || !Filter::filterStr($param['title'])
        ) {
            return $this->redirect('/blog/posts');
        }

        $id = (int)$param['id'] / 256;
        $title = Filter::filterStr($param['title']);

        $posts = $this->model->loadPosts($id);
        
        return $this->view->render('post/index', $this->vd(
            $posts,
            $this->postModel,
            $this->model
        ));
    }

    public function create()
    {
        return $this->render('category/create');
    }

    public function store()
    {
        $errors = (object) [
            'done' => false,
            'err' => false
        ];

        $title = Filter::filterStr($this->post('title'));

        if (!$title) {
            return $this->redirect('/blog/posts');
        }

        if ($this->model->create($title)) {
            $errors->done = true;
        } else {
            $errors->err = true;
        }

        $this->session->addFlash(
            'danger',
            $errors
        );
        
        return $this->redirect('/blog/cat/create');
    }
}
