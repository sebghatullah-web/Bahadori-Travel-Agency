<?php
$this->layout('frontend', ['pageTitle' => 'مقالات']);
?>
<section class="bt-page-head" style="background:var(--bt-dark)">
    <div class="container text-center">
        <h1>مجله سفر</h1>
        <p>راهنماها، تجربه‌ها و نکته‌های سفر</p>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <?php if (empty($articles)): ?>
            <div class="bt-empty">هنوز مقاله‌ای منتشر نشده است.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($articles as $art): ?>
                    <div class="col-lg-4 col-md-6">
                        <a href="<?= e(base_url('articles/' . $art['slug'])) ?>" class="bt-article-card">
                            <div class="bt-article-media">
                                <img src="<?= e(upload_url($art['image'])) ?>" alt="<?= e($art['title']) ?>" loading="lazy">
                            </div>
                            <div class="bt-article-body">
                                <?php if (!empty($art['category'])): ?><span class="bt-article-cat"><?= e($art['category']) ?></span><?php endif; ?>
                                <h5><?= e($art['title']) ?></h5>
                                <p><?= e($art['excerpt']) ?></p>
                                <span class="bt-article-meta"><?= e(fa_number(date('Y/m/d', strtotime($art['created_at'])))) ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
