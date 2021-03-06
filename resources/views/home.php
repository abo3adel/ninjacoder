<?php $this->layout(
    'layouts/base',
    [
        'title' => 'ninjaCoder',
        'navClass' => 'landing-nav bg-transparent',
        'mainClass' => 'landing-page'
    ]
) ?>

<header ref='canvasHeader' id='top' class="masthead bg-dark text-light bg-transparent">
    <animated-dots :full-height="true"></animated-dots>
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-0 text-capitalize"><?=$this->__('home.hello')?><span class='text-danger'><?=$this->__('home.myName')?></span></h1>
            <h2 class="text-white-50 mx-auto mt-2 mb-5"><?=$this->__('home.and')?></h2>
            <h1 id=''>
                <span id='job-title'></span>
                <animated-job-title></animated-job-title>
                </span>
                <span id='blink' class='blink'>|</span>
            </h1>
            <a href="#about" v-scroll-to="'#about'" class="btn btn-primary js-scroll-trigger"><?=$this->__('home.start')?></a>
        </div>
    </div>
</header>
<div class="container-fluid">
    <?php 

        $this->insert('home/about', ['pros' => $pros]);
        $this->insert('home/skill');
        $this->insert('home/project', ['projects' => $projects]);
        $this->insert('home/blog', ['posts' => $posts, 'model' => $model]);
        $this->insert('home/contact');

        /**
         * @todo add Experments & open source page
         */
    ?>
    <side-nav links="top about skills projects blog contact" txt="<?="{$this->__('home.title.top')},{$this->__('home.title.about')},{$this->__('home.title.skill')},{$this->__('home.title.project')},{$this->__('home.title.blog')},{$this->__('home.title.contact')}"?>"></side-nav>
</div>