<?php include 'header.php'; ?>
<div class="container my-5">
    <h1>Новости</h1>
    <?php
    $news = json_decode(file_get_contents('data/news.json'), true) ?? [];
    foreach (array_reverse($news) as $item): ?>
        <div class="card mb-4">
            <?php if (!empty($item['image'])): ?><img src="<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt=""> <?php endif; ?>
            <div class="card-body">
                <h5><?= htmlspecialchars($item['title']) ?></h5>
                <small class="text-muted"><?= $item['date'] ?></small>
                <div class="mt-3"><?= nl2br($item['content']) ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>