<?php
require_once 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title     = trim($_POST['title'] ?? '');
    $author    = trim($_POST['author'] ?? '');
    $publisher = trim($_POST['publisher'] ?? '');
    $pub_year  = trim($_POST['pub_year'] ?? '');
    $quantity  = intval($_POST['quantity'] ?? 1);

    if ($title === '')  $errors[] = '제목을 입력하세요.';
    if ($author === '') $errors[] = '저자를 입력하세요.';
    if ($quantity < 1)  $errors[] = '수량은 1 이상이어야 합니다.';
    if ($pub_year !== '' && (!is_numeric($pub_year) || $pub_year < 1000 || $pub_year > 2100)) {
        $errors[] = '올바른 출판연도를 입력하세요.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, pub_year, quantity) VALUES (?, ?, ?, ?, ?)");
        $py = $pub_year !== '' ? $pub_year : null;
        $stmt->bind_param('ssssi', $title, $author, $publisher, $py, $quantity);
        if ($stmt->execute()) {
            header('Location: index.php?msg=added');
            exit;
        } else {
            $errors[] = 'DB 오류: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>도서 추가</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h2 class="mb-4">📖 도서 추가</h2>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">제목 <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">저자 <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="author" value="<?= htmlspecialchars($_POST['author'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">출판사</label>
            <input type="text" class="form-control" name="publisher" value="<?= htmlspecialchars($_POST['publisher'] ?? '') ?>">
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label fw-bold">출판연도</label>
                <input type="number" class="form-control" name="pub_year" min="1000" max="2100" value="<?= htmlspecialchars($_POST['pub_year'] ?? '') ?>">
            </div>
            <div class="col mb-3">
                <label class="form-label fw-bold">수량 <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="quantity" min="1" value="<?= htmlspecialchars($_POST['quantity'] ?? '1') ?>" required>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">추가</button>
            <a href="index.php" class="btn btn-outline-secondary">취소</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
