<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Request;
use App\View\FrontRenderInterface;
use App\Util\AppSession;
use App\Util\Filter;
use Hashids\Hashids;
use ParsedownExtra;

class PostController extends BaseController
{
    use FileUploader;
    use ViewData;

    private $categoryModel;

    /**
     * upload dir based on root directory
     */
    const UPLOAD_DIR = 'storage/posts/';

    public function __construct(
        Request $request,
        FrontRenderInterface $view,
        Post $model,
        Hashids $hashids,
        AppSession $session,
        Category $categoryModel
    ) {
        parent::__construct($request, $view, $hashids, $session);
        $this->model = $model;
        $this->categoryModel = $categoryModel;
    }

    public function index()
    {
        return $this->view->render('post/index', $this->vd(
            $this->model->readAll(),
            $this->model,
            $this->categoryModel
        ));
    }

    public function create()
    {
        return $this->render('post/create', [
            'cats' => $this->categoryModel->readAll()
        ]);
    }

    public function save()
    {
        $error = (object) [
            'title' => false,
            'body' => false,
            'cat' => false,
            'file' => false,
            'files' => false,
            'uploading' => false,
            'saving' => false
        ];

        $old = (object) [
            'title' => '',
            'body' => ''
        ];

        $title = Filter::filterStr($this->request->get('title'));
        $body = Filter::filterStr($this->request->get('body'));
        $body_ar = Filter::filterStr($this->request->get('body_ar'));
        $cats = Filter::filterStr($this->post('category'));

        // validate title
        if (!$title || !Filter::len($title, 15)) {
            $error->title = true;
        } else {
            $old->title = $title;
        }

        // validate body
        if (!$body || !$body_ar || !Filter::len($body, 150)) {
            $error->body = true;
        } else {
            $old->body = $body;
        }

        // validate categories
        if (!$cats) {
            $error->cat = true;
        } else {
            $cats = explode(',', $cats);
            if (!sizeof($cats)) {
                $error->cat = true;
            }
        }

        // check if image was enterd
        if (empty($_FILES['img']['name'])) {
            $error->file = true;
        }

        // if all data was entered corectly
        if (!$error->title && !$error->body && !$error->cat && !$error->file) {
            $this->setUploader($_FILES['img']);

            // validate files
            $error->files = $this->validate(750, ['png', 'jpeg', 'jpg']);

            // file has error
            if ($error->files->size && $error->files->type) {
                // all data was validated

                // first upload image
                $img = $this->upload(self::UPLOAD_DIR);

                // check if file was not saved
                if (!$img) {
                    $error->uplading = false;
                } else {
                    // image uploaded succeffully

                    // save post
                    $this->model->title = $title;
                    $this->model->body = $body;
                    $this->model->body_ar = $body_ar;
                    $this->model->img = $img;
                    $this->model->slug = str_replace(' ', '-', $title);

                    $postId = $this->model->create();

                    if ($postId) {
                        if ($this->categoryModel->insertCategories($postId ,$cats)) {
                            return $this->redirect('/blog/posts/'.$this->model->slug);
                        }
                        
                        $error->saving = true;
                    }
                    $error->saving = true;
                }
            }
        }

        // add old variables
        $this->session->addFlash(
            'old',
            $old
        );

        $this->session->addFlash(
            'danger',
            $error
        );

        return $this->redirect('/blog/posts/create');
    }

    public function find()
    {
        $q = Filter::filterStr($this->request->get('q'));

        if (!$q) {
            return $this->redirect('/blog/posts/');
        }

        return $this->render(
            'post/index',
            $this->vd(
                $this->model->findPosts($q),
                $this->model,
                $this->categoryModel
            )
        );
    }

    public function show(array $param)
    {
        $slug = Filter::filterStr($param['slug']);

        if (!$slug) {
            return $this->redirect('/404');
        }

        $post = $this->model->readOne($slug);

        return $this->render('post/show', $this->vd(
            $post,
            $this->model,
            $this->categoryModel
        ));
    }

    public function edit(array $param)
    {
        $post = $this->model->readOne(Filter::filterStr($param['slug']));

        return $this->render('post/edit', [
            'posts' => $post
        ]);
    }

    public function update(array $param)
    {
        $error = (object) [
            'title' => false,
            'body' => false,
            'file' => false,
            'files' => false,
            'uploading' => false,
            'saving' => false
        ];

        $old = (object) [
            'title' => '',
            'body' => ''
        ];

        $slug = Filter::filterStr($param['slug']);
        $title = Filter::filterStr($this->request->get('title'));
        $body = $this->request->get('body');
        $oldImg = Filter::filterStr($this->post('oldImg'));

        // validate title
        if (!$title || !Filter::len($title, 15)) {
            $error->title = true;
        } else {
            $old->title = $title;
        }

        // validate body
        if (!$body || !Filter::len($body, 150)) {
            $error->body = true;
        } else {
            $old->body = $body;
        }

        // check if image was enterd
        if (empty($_FILES['img']['name'])) {
            $error->file = true;
        }

        // if all data was entered corectly
        if (!$error->title || !$error->body) {
            if ($error->file) {
                $img = $oldImg;
            } else {
                $this->setUploader($_FILES['img']);
                // validate files
                $error->files = $this->validate(750, ['png', 'jpeg', 'jpg']);

                if ($error->files->size && $error->files->type) {
                    // first upload image
                    $img = $this->upload(self::UPLOAD_DIR);

                    // check if file was not saved
                    if (!$img) {
                        $error->uplading = false;
                    }
                }
            }
            if ($img) {
                // image uploaded succeffully

                // save post
                $this->model->title = $title;
                $this->model->body = $body;
                $this->model->img = $img;
                $this->model->slug = $slug;

                if (!$this->model->update()) {
                    $error->saving = true;
                } else {
                    return $this->redirect('/blog/posts/' . str_replace(' ', '-', $title));
                }
            }
        }

        // add old variables
        $this->session->addFlash(
            'old',
            $old
        );

        $this->session->addFlash(
            'danger',
            $error
        );

        return $this->redirect('/blog/posts/' . $slug . '/edit');
    }

    public function destroy(array $param)
    {
        if (!isset($param['pid'])) {
            return $this->redirect('/blog/posts');
        }

        $pid = (int) $param['pid'];

        $this->model->id = $pid;

        echo json_encode(['done' => $this->model->delete()]);
    }

    // public function getBody(array $param)
    // {
    //     $slug = Filter::filterStr($param['slug']);
    //     $lang = Filter::filterStr($param['lang']);

    //     if (!$slug || !$lang) {
    //         return false;
    //     }

    //     $this->model->slug = $slug;
    //     $body = $this->model->readBodyByLang($lang);
    //     $parsedText = (new ParsedownExtra())->text($body->body);

    //     header('Content-Type: application/json');

    //     echo json_encode($parsedText);
    // }
}
