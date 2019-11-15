<?php declare (strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use App\View\FrontRenderInterface;
use App\Util\AppSession;
use App\Util\Filter;

class HomeController
{
    use HomeModelDataTrait;

    private $request;
    private $view;
    private $session;

    public function __construct(
        Request $request,
        FrontRenderInterface $view,
        AppSession $session
    ) {
        $this->request = $request;
        $this->view = $view;
        $this->session = $session;
        $this->session->sessStart();
    }

    public function show($params = [])
    {
        [$pros, $projects, $posts] = $this->getData();        

        return $this->view->render('home', [
            'pros' => $pros,
            'projects' => $projects,
            'posts' => $posts
        ]);
    }

    public function saveMail($param = [])
    {
        $name = Filter::filterStr($this->request->get('name'));
        $email = filter_var(Filter::filterStr($this->request->get('email')), FILTER_SANITIZE_EMAIL);
        $message = Filter::filterStr($this->request->get('message'));

        $output = (object) [
            'code' => 200,
        ];

        if (!$name) {
            $output->code = 601;
        } else if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output->code = 602;
        } else if (!$message) {
            $output->code = 603;
        }

        if ($name && $email && $message && $output->code === 200) {
            // all data is valid
            $output->code = 200;
        }

        header("Content-Type: application/json");
        echo json_encode($output);
    }
}
