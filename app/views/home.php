<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News manager</title>
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="/style/app.css">
    <link rel="icon" type="image/svg+xml" href="/icon/logo.svg">
    <script src="/script/home.js" defer></script>
</head>
<body>
    <main>
        <img src="/icon/logo.svg" alt="Logo" class="logo">
        <?php if(isset($_SESSION['success_flash'])): ?>
        <div class="success-flash">
                <span><?= $_SESSION['success_flash'] ?></span>
            </div>
        <?php unset($_SESSION['success_flash']); ?>
        <?php endif; ?>
        <?php if(isset($data['news']) && $data['news']): ?>
            <p>All News</p>
            <?php foreach($data['news'] as $n) :?>
                <div class="news-element">
                    <div class="news-title">
                        <span class="text-wrapper"><?= $n->getTitle() ?></span>
                    </div>
                    <div class="news-description">
                        <span class="text-wrapper"><?= $n->getDescription() ?></span>
                    </div>
                    <div class="news-actions">
                        <img src="/icon/pencil.svg" class="action-icon update-news" data-id="<?= $n->getId() ?>">
                        <img src="/icon/close.svg" class="action-icon delete-news" data-id="<?= $n->getId() ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="form-title-wrapper">
            <div>
                <p id="js-form-title">Create News</p>
            </div>
            <div id="close-icon-wrapper">
            </div>
        </div>
        <form method="post" action="/news" id="js-news-form">
            <input type="text" maxlength="255" name="title" id="title-input" placeholder="Title" required>
            <textarea name="description" id="description-input"  placeholder="Description" required></textarea>
            <button type="submit">Create</button>
        </form>
        <button id="js-logout-btn">Logout</button>
    </main>
</body>
</html>