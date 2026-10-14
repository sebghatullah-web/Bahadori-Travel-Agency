<?php
$this->layout('frontend', ['pageTitle' => $article['title']]);
?>
<section class="bt-page-head" style="background:var(--bt-dark)">
    <div class="container">
        <div class="bt-article-hero">
            <?php if (!empty($article['category'])): ?><span class="bt-hero-badge mb-2 d-inline-block"><?= e($article['category']) ?></span><?php endif; ?>
            <h1><?= e($article['title']) ?></h1>
            <p class="bt-article-meta">
                <?= e($article['author'] ?? '') ?> • <?= e(fa_number(date('Y/m/d', strtotime($article['created_at'])))) ?> • <?= e(fa_number($article['views'])) ?> بازدید
            </p>
        </div>
    </div>
</section>

<section class="bt-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <?php if (!empty($article['image'])): ?>
                    <img src="<?= e(upload_url($article['image'])) ?>" alt="<?= e($article['title']) ?>" class="img-fluid rounded mb-4">
                <?php endif; ?>
                <p class="bt-lead"><?= e($article['excerpt']) ?></p>
                <div class="bt-prose"><?= nl2br(e($article['body'])) ?></div>
            </div>
            <div class="col-lg-4">
                <?php if (!empty($related)): ?>
                    <div class="bt-side-list">
                        <h5>مقالات دیگر</h5>
                        <?php foreach ($related as $r): ?>
                            <?php if ($r['id'] == $article['id']) continue; ?>
                            <a href="<?= e(base_url('articles/' . $r['slug'])) ?>" class="bt-side-item">
                                <img src="<?= e(upload_url($r['image'])) ?>" alt="<?= e($r['title']) ?>">
                                <div>
                                    <strong><?= e($r['title']) ?></strong>
                                    <span><?= e(fa_number(date('Y/m/d', strtotime($r['created_at'])))) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
