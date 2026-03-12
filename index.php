<?php
require_once 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY created_at DESC");
    $like = "%$search%";
    $stmt->bind_param('ss', $like, $like);
} else {
    $stmt = $conn->prepare("SELECT * FROM books ORDER BY created_at DESC");
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>도서 관리 시스템</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">📚 도서 관리 시스템</h1>
        <a href="add.php" class="btn btn-primary">+ 도서 추가</a>
    </div>

    <?php if ($msg === 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show">도서가 추가되었습니다. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($msg === 'updated'): ?>
        <div class="alert alert-info alert-dismissible fade show">도서 정보가 수정되었습니다. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="alert alert-warning alert-dismissible fade show">도서가 삭제되었습니다. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <form class="d-flex mb-3 gap-2" method="GET">
        <input class="form-control" type="text" name="search" placeholder="제목 또는 저자 검색..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-secondary" type="submit">검색</button>
        <?php if ($search): ?>
            <a href="index.php" class="btn btn-outline-danger">초기화</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>제목</th>
                    <th>저자</th>
                    <th>출판사</th>
                    <th>출판연도</th>
                    <th>수량</th>
                    <th>등록일</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">등록된 도서가 없습니다.</td></tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= htmlspecialchars($row['publisher'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['pub_year'] ?? '-') ?></td>
                    <td><span class="badge bg-secondary"><?= $row['quantity'] ?>권</span></td>
                    <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">수정</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="text-muted small">총 <?= $result->num_rows ?> 권</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
