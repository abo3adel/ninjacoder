<!DOCTYPE html>
<html lang="<?= $session->get('lang') ?? '' ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $this->e($title) ?></title>
    <link href="<?= $this->asset('/assets/css/app.css') ?>" rel="stylesheet" type="text/css">
    <link rel="icon" href="">
</head>

<body>
    <?= $this->insert('partials/nav', ['navClass' => $navClass ?? '']) ?>
    <main class="<?= $mainClass ?? 'container-fluid' ?>" id="<?= $id ?? 'body' ?>">
        <?= $this->section('content') ?>

        <input type="hidden" ref='csrf_token' id='csrf_token' class="d-none" name='csrf_token' value="<?= $session->get('X_CSRF_TOKEN') ?>">


        <footer class="bg-dark text-light p-5">
            <div class="container">
                <div class="row text-center">
                    <div class="col-12 col-sm-6 pt-3">
                        <a href='fb.com/a7md200' class="btn btn-outline-primary btn-brand transition mr-2">

                            <i class='fab fa-github'></i>
                        </a>
                        <a href='fb.com/a7md200' class="btn btn-outline-danger btn-brand transition mr-2">

                            <i class='fab fa-codepen'></i>
                        </a>
                        <a href='fb.com/a7md200' class="btn btn-outline-info btn-brand transition mr-2">
                            <i class='fab fa-linkedin-in'></i>
                        </a>
                        <a href='fb.com/a7md200' class="btn btn-outline-primary btn-brand transition mr-2">
                            <i class='fab fa-facebook-f'></i>
                        </a>
                        <a href='fb.com/a7md200' class="btn btn-outline-success btn-brand transition mr-2">
                            <i class='fab fa-whatsapp'></i>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 pt-3">
                        Copyright © ninjaCoder 2019
                    </div>
                </div>
            </div>
        </footer>
    </main>
    <script src='<?= $this->asset('/assets/js/bootstrap-native-v4.min.js') ?>'></script>
    <script src="<?= $this->asset('/assets/js/app.js') ?>"></script>
</body>

</html>