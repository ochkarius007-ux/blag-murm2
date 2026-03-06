<?php include 'header.php'; ?>
<div class="container my-5">
    <h1>О фонде</h1>
    <p class="lead">Благотворительный фонд «МУРМАНСК РАЗВИВАЕМ» создан при участии администрации города Мурманска.</p>
    
    <h2 class="mt-5">Документы фонда</h2>
    <div class="row">
        <?php
        $docs = json_decode(file_get_contents('data/documents.json'), true) ?? [];
        foreach ($docs as $d): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5><?= htmlspecialchars($d['original_name']) ?></h5>
                        <a href="uploads/documents/<?= htmlspecialchars($d['filename']) ?>" class="btn btn-primary" download>Скачать</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'footer.php'; ?>