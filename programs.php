<?php include 'header.php'; ?>
<div class="container my-5">
    <h1>Благотворительные проекты</h1>
    <div class="row">
        <?php
        $projects = json_decode(file_get_contents('data/projects.json'), true) ?? [];
        foreach ($projects as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="card-img-top" alt="">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($p['title']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'footer.php'; ?>