<?php $this->layout('layouts/blog', [
    'title' => $this->__('posts.title'),
    'component' => 'all-posts'
]) ?>

<div class='mt-3 row'>
    <div class='col-12 col-md-8'>
        <div class="row">
            <?php if (sizeof($posts) < 1) : ?>
                <div class='alert alert-danger mt-5 mx-auto text-capitalize'>
                    <?=$this->__('posts.empty')?>
                </div>
            <?php endif ?>
            <?php foreach ($posts as $p) : ?>
                <card title='<?= $p->title ?>' img="/posts/img/<?= $p->img ?? '1.png' ?>" href="<?= $p->slug ?>" :cls='"post text-left transition " + h.d.cardClass' :row-class="h.d.rowClass">
                    <template v-slot:info>
                        <div class='py-2 my-1 text-muted d-block' dir='ltr'>
                            <span class="mr-3" data-toggle="tooltip" data-placement="top" title='<?= $this->__('home.sec.blog.date') ?>'>
                                <i class="fas fa-clock"></i>
                                <?= date_format(date_create($p->updated_at), 'd M Y') ?>
                            </span>
                            <a href='/blog/posts/<?= $p->slug ?>/#comments' class='mx-3 text-primary' data-toggle="tooltip" data-placement="top" title='<?= $this->__('home.sec.blog.c_count') ?>'>
                                <span class=''>
                                    <i class="fas fa-comment-alt"></i>
                                    <?= $model->getCommentCount($p->id) ?>
                                </span>
                            </a>
                        </div>
                        <hr class="w-50 pt-1 rounded bg-primary text-left ml-0 mt-n2 mb-3" />
                        <span class="card-text">
                            <?= substr($p->body, 0, 250) ?>
                        </span>
                    </template>
                    <template v-slot:footer>
                        <div class='card-footer text-center mx-auto'>
                            <?php foreach ($model->categories($p->id) as $cat) : ?>
                                <span class='badge badge-primary p-1 mx-1'>
                                    <?= $cat->title ?>
                                </span>
                            <?php endforeach ?>
                        </div>
                        <?=$this->insert('post/opr', ['p' => $p])?>
                    </template>
                </card>
            <?php endforeach ?>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <?php $this->insert('sidebar/index', [
            'model' => $model,
            'pinned' => $pinned,
            'cats' => $cats,
            'catModel' => $catModel
        ])?>
    </div>
</div>