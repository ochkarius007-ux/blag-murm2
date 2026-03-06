<?php include 'header.php'; 
$albums = getAlbums();
$album = $_GET['album'] ?? null;
?>
<div class="container my-5">
    <?php if (!$album): ?>
        <h1>Фотоальбомы</h1>
        <div class="row">
            <?php foreach ($albums as $a): 
                $files = glob("uploads/albums/$a/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                $cover = $files[0] ?? 'https://picsum.photos/300/200';
            ?>
                <div class="col-md-4 mb-4">
                    <a href="?album=<?= urlencode($a) ?>" class="text-decoration-none">
                        <div class="card album-card">
                            <img src="<?= $cover ?>" class="card-img-top" alt="">
                            <div class="card-body text-center">
                                <h5><?= htmlspecialchars($a) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: 
        $files = glob("uploads/albums/" . basename($album) . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    ?>
        <h1>Альбом: <?= htmlspecialchars($album) ?></h1>
        <a href="gallery.php" class="btn btn-secondary mb-3">← Все альбомы</a>
        <div class="row">
            <?php foreach ($files as $f): ?>
                <div class="col-md-4 mb-3">
                    <img src="<?= htmlspecialchars($f) ?>" class="img-fluid rounded shadow" data-bs-toggle="modal" data-bs-target="#modal" onclick="document.getElementById('modalImg').src=this.src">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно -->
<div class="modal fade" id="modal"><div class="modal-dialog modal-xl"><div class="modal-content"><img id="modalImg" class="img-fluid"></div></div></div>

<?php include 'footer.php'; ?>