<!DOCTYPE html>
<html lang="<?=$session->get('lang') ?? ''?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$this->e($title)?></title>
    <link href="<?=$this->asset('/assets/css/app.css')?>" rel="stylesheet" type="text/css">
    <link rel="icon" href="">
</head>
<body>
    <?=$this->insert('partials/nav', ['navClass' => $navClass ?? ''])?>
    <main class="container-fluid" id="<?=$id ?? 'body'?>">
    <?=$this->section('content')?>
    </main>
    <script src='<?=$this->asset('/assets/js/bootstrap-native-v4.min.js')?>'></script>
    <script src="<?=$this->asset('/assets/js/app.js')?>"></script>
</body>
</html>