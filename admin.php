<?php
include 'functions.php';

$LOGIN = 'blag';
$PASS  = 'BlagMurm51!';

// Обработка входа
if (isset($_POST['login']) && $_POST['login'] === $LOGIN && $_POST['pass'] === $PASS) {
    $_SESSION['admin'] = true;
}

// Выход
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Если не авторизован — показываем форму входа
if (!isset($_SESSION['admin'])) {
    include 'header.php';
    ?>
    <div class="container mt-5">
        <div class="jumbotron text-center" style="max-width: 500px; margin: 0 auto;">
            <h3 class="mb-4">Вход в CMS</h3>
            <form method="post">
                <input type="text" name="login" class="form-control mb-3" placeholder="Логин" required>
                <input type="password" name="pass" class="form-control mb-3" placeholder="Пароль" required>
                <button type="submit" class="btn-custom w-100">Войти</button>
            </form>
        </div>
    </div>
    <?php
    include 'footer.php';
    exit;
}

// Автосоздание папок
ensureDir('images/');
ensureDir('docs/');
ensureDir('images/albums/');
ensureDir('data/');

$msg = '';
$page = $_GET['page'] ?? 'news';

// ====================== ОБРАБОТКА ВСЕХ ФОРМ ======================

// 1. Добавление новости
if (isset($_POST['add_news'])) {
    $news = json_decode(@file_get_contents('data/news.json'), true) ?? [];
    $image = '';
    if (!empty($_FILES['image']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $filename = 'news_' . time() . '.' . $ext;
        $dest = 'images/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            compressImage($dest, $dest);
            $image = $dest;
        }
    }
    $news[] = [
        'id' => time(),
        'title' => $_POST['title'] ?? '',
        'date' => date('d.m.Y'),
        'content' => $_POST['content'] ?? '',
        'image' => $image
    ];
    file_put_contents('data/news.json', json_encode($news, JSON_UNESCAPED_UNICODE));
    $msg = '✅ Новость успешно добавлена!';
}

// 2. Добавление проекта
if (isset($_POST['add_project'])) {
    $projects = json_decode(@file_get_contents('data/projects.json'), true) ?? [];
    $image = '';
    if (!empty($_FILES['image']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $filename = 'proj_' . time() . '.' . $ext;
        $dest = 'images/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            compressImage($dest, $dest);
            $image = $dest;
        }
    }
    $projects[] = [
        'id' => time(),
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'image' => $image
    ];
    file_put_contents('data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE));
    $msg = '✅ Проект добавлен!';
}

// 3. Загрузка документа
if (isset($_POST['add_doc'])) {
    if (!empty($_FILES['doc']['name'])) {
        $docs = json_decode(@file_get_contents('data/documents.json'), true) ?? [];
        $filename = time() . '_' . basename($_FILES['doc']['name']);
        $dest = 'docs/' . $filename;
        if (move_uploaded_file($_FILES['doc']['tmp_name'], $dest)) {
            $docs[] = ['filename' => $filename, 'original_name' => $_FILES['doc']['name']];
            file_put_contents('data/documents.json', json_encode($docs, JSON_UNESCAPED_UNICODE));
            $msg = '✅ Документ загружен!';
        }
    }
}

// 4. Создание альбома
if (isset($_POST['create_album'])) {
    $name = trim($_POST['album_name']);
    if ($name) {
        $path = 'images/albums/' . $name;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
            $msg = '✅ Альбом создан!';
        }
    }
}

// 5. Загрузка фото в альбом
if (isset($_POST['upload_photo'])) {
    $album = $_POST['album'];
    if ($album && !empty($_FILES['photos']['name'][0])) {
        $path = 'images/albums/' . $album . '/';
        foreach ($_FILES['photos']['tmp_name'] as $k => $tmp) {
            if ($tmp) {
                $name = time() . '_' . $_FILES['photos']['name'][$k];
                $dest = $path . $name;
                if (move_uploaded_file($tmp, $dest)) {
                    compressImage($dest, $dest);
                }
            }
        }
        $msg = '✅ Фото загружены и сжаты!';
    }
}
?>

<?php include 'header.php'; ?>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Панель управления CMS</h1>
        <a href="?logout" class="btn btn-danger">Выйти</a>
    </div>

    <?php if ($msg) echo '<div class="alert alert-success">'.$msg.'</div>'; ?>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item"><a href="?page=news" class="nav-link <?= $page=='news'?'active':'' ?>">Новости</a></li>
        <li class="nav-item"><a href="?page=albums" class="nav-link <?= $page=='albums'?'active':'' ?>">Фотоальбомы</a></li>
        <li class="nav-item"><a href="?page=projects" class="nav-link <?= $page=='projects'?'active':'' ?>">Проекты</a></li>
        <li class="nav-item"><a href="?page=docs" class="nav-link <?= $page=='docs'?'active':'' ?>">Документы</a></li>
    </ul>

    <?php if ($page == 'news'): ?>
        <h3>Добавить новость</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" class="form-control mb-3" placeholder="Заголовок новости" required>
            <textarea name="content" class="form-control mb-3" rows="8" placeholder="Текст новости" required></textarea>
            <input type="file" name="image" class="form-control mb-3" accept="image/*">
            <button name="add_news" class="btn-custom">Опубликовать новость</button>
        </form>
    <?php endif; ?>

    <?php if ($page == 'albums'): ?>
        <h3>Создать новый альбом</h3>
        <form method="post" class="mb-5">
            <input type="text" name="album_name" class="form-control mb-3" placeholder="Название альбома (например: Лето-2025)" required>
            <button name="create_album" class="btn-custom">Создать альбом</button>
        </form>

        <h3>Загрузить фото в альбом</h3>
        <form method="post" enctype="multipart/form-data">
            <select name="album" class="form-control mb-3" required>
                <option value="">Выберите альбом</option>
                <?php foreach (getAlbums() as $a): ?>
                    <option value="<?= htmlspecialchars($a) ?>"><?= htmlspecialchars($a) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="file" name="photos[]" class="form-control mb-3" multiple accept="image/*">
            <button name="upload_photo" class="btn-custom">Загрузить и сжать фото</button>
        </form>
    <?php endif; ?>

    <?php if ($page == 'projects'): ?>
        <h3>Добавить благотворительный проект</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" class="form-control mb-3" placeholder="Название проекта" required>
            <textarea name="description" class="form-control mb-3" rows="6" placeholder="Описание проекта" required></textarea>
            <input type="file" name="image" class="form-control mb-3" accept="image/*">
            <button name="add_project" class="btn-custom">Добавить проект</button>
        </form>
    <?php endif; ?>

    <?php if ($page == 'docs'): ?>
        <h3>Загрузить документ фонда</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="doc" class="form-control mb-3" required>
            <button name="add_doc" class="btn-custom">Загрузить документ</button>
        </form>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>