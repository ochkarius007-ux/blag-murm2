<?php include 'header.php'; ?>
<div class="jumbotron text-center">
    <div class="container">
        <h1>БФ "МУРМАНСК РАЗВИВАЕМ"</h1>
        <p class="lead">Мы помогаем детям, семьям и развиваем Мурманск вместе</p>
        <a href="programs.php" class="btn-custom">Поддержать проекты</a>
    </div>
</div>

<div class="container">
    <!-- О фонде -->
    <div class="section">
        <h2>О фонде</h2>
        <p>Благотворительный фонд «Мурманск Развиваем» создан для помощи нуждающимся семьям, детям и развития нашего города. Мы реализуем социальные проекты, помогаем в трудных ситуациях и объединяем неравнодушных людей.</p>
        <p><strong>ИНН:</strong> 5190101275 | <strong>ОГРН:</strong> 1245100004995</p>
    </div>

    <!-- Новости -->
    <div class="section">
        <h2>Последние новости</h2>
        <div class="row">
            <?php
            $news = json_decode(@file_get_contents('data/news.json'), true) ?? [];
            $latest = array_slice(array_reverse($news), 0, 3);
            foreach ($latest as $item): ?>
            <div class="col-md-4 mb-4">
                <div class="news-card">
                    <?php if (!empty($item['image'])): ?>
                    <div class="news-image"><img src="<?= htmlspecialchars($item['image']) ?>" alt=""></div>
                    <?php endif; ?>
                    <div class="p-3">
                        <h5><?= htmlspecialchars($item['title']) ?></h5>
                        <small><?= $item['date'] ?></small>
                        <p><?= mb_substr(strip_tags($item['content']), 0, 120) ?>...</p>
                        <a href="news.php" class="text-primary">Читать полностью →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Проекты -->
    <div class="section">
        <h2>Наши проекты</h2>
        <?php
        $projects = json_decode(@file_get_contents('data/projects.json'), true) ?? [];
        foreach (array_slice($projects, 0, 3) as $p): ?>
        <div class="project-card mb-3 p-3">
            <h5><?= htmlspecialchars($p['title']) ?></h5>
            <p><?= nl2br(htmlspecialchars(mb_substr($p['description'], 0, 150))) ?>...</p>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>