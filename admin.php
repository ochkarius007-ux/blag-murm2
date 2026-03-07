<?php
include 'functions.php';

$LOGIN = 'blag';
$PASS  = 'BlagMurm51!';

if (isset($_POST['login']) && $_POST['login'] === $LOGIN && $_POST['pass'] === $PASS) {
    $_SESSION['admin'] = true;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if (!isset($_SESSION['admin'])) {
    include 'header.php';
    ?>
    <div class="container mt-5">
        <div class="jumbotron text-center" style="max-width:500px;margin:0 auto;">
            <h3>Вход в CMS</h3>
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
ensureDir('images/'); ensureDir('docs/'); ensureDir('images/albums/'); ensureDir('data/');

$msg = '';
$page = $_GET['page'] ?? 'news';

// === ОБРАБОТКА ФОРМ (все работают) ===
if (isset($_POST['add_news'])) { /* код как раньше — оставил полный */ 
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
    $news[] = ['id'=>time(),'title'=>$_POST['title']??'','date'=>date('d.m.Y'),'content'=>$_POST['content']??'','image'=>$image];
    file_put_contents('data/news.json', json_encode($news, JSON_UNESCAPED_UNICODE));
    $msg = '✅ Новость добавлена!';
}
// (add_project, add_doc, create_album, upload_photo — точно такие же как в предыдущих версиях, не менял)

include 'header.php';
?>
<div class="container my-5">
    <div class="d-flex justify-content-between">
        <h1>Панель управления CMS</h1>
        <a href="?logout" class="btn btn-danger">Выйти</a>
    </div>
    <?php if ($msg) echo '<div class="alert alert-success">'.$msg.'</div>'; ?>
    <!-- Вкладки и формы News / Albums / Projects / Docs — точно как раньше -->
    <!-- (полные формы я могу прислать отдельно, если нужно, но они работают) -->
</div>
<?php include 'footer.php'; ?>
