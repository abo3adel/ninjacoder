<section id='projects' class="skills bg-light text-dark text-center mt-4">
    <h2>
        <?=$this->__('home.title.project')?>
        <hr class='mx-auto bg-dark pt-1 rounded w-25 px-5' />
    </h2>
    <div class="text-center mt-5">
        <div class="row">
            <?php foreach ($projects as $p) : ?>
                <card :has-overlay='true' cls='project col-lg-4' href="<?= $p->href ?>" img="<?= $this->asset('assets/img/' . $p->img) ?>" title="<?= $p->title ?>">
                    <template v-slot:overlay>
                        <h5 class="card-title"><?=$this->__('home.sec.proj.client')?>: <?= $p->client ?></h5>
                        <p class="card-text"><?= $p->info ?></p>
                    </template>
                    <template v-slot:tags>
                        <?php foreach ($p->tags as $tag) : ?>
                            <span class="badge badge-primary p-1 mx-2">
                                <?= $tag ?>
                            </span>
                        <?php endforeach ?>
                    </template>
                </card>
            <?php endforeach ?>
        </div>
    </div>
</section>