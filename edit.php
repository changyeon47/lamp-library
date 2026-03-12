<?php
require_once 'db.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }

$errors = [];

// 기존 데이터 조회
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$book) { header('Location: index.php'); exit; }

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
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, publisher=?, pub_year=?, quantity=? WHERE id=?");
        $py = $pub_year !== '' ? $pub_year : null;
        $stmt->bind_param('ssssii', $title, $author, $publisher, $py, $quantity, $id);
        if ($stmt->execute()) {
            header('Location: index.php?msg=updated');
            exit;
        } else {
            $errors[] = 'DB 오류: ' . $stmt->error;
        }
        $stmt->close();
    }
    // 폼 재표시용 임시 데이터 반영
    $book = array_merge($book, $_POST);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>도서 수정</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h2 class="mb-4">✏️ 도서 수정</h2>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">제목 <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">저자 <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">출판사</label>
            <input type="text" class="form-control" name="publisher" value="<?= htmlspecialchars($book['publisher'] ?? '') ?>">
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label fw-bold">출판연도</label>
                <input type="number" class="form-control" name="pub_year" min="1000" max="2100" value="<?= htmlspecialchars($book['pub_year'] ?? '') ?>">
            </div>
            <div class="col mb-3">
                <label class="form-label fw-bold">수량 <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="quantity" min="1" value="<?= htmlspecialchars($book['quantity']) ?>" required>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">수정 완료</button>
            <a href="index.php" class="btn btn-outline-secondary">취소</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
