#!/bin/bash
set -e

echo "=========================================="
echo "  도서 관리 시스템 - 자동 설치 스크립트"
echo "=========================================="

# 1. MySQL DB 및 사용자 생성
echo ""
echo "[1/3] MySQL 데이터베이스 설정 중..."
sudo mysql <<'SQL'
CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'library_user'@'localhost' IDENTIFIED BY 'library_pass123';
GRANT ALL PRIVILEGES ON library_db.* TO 'library_user'@'localhost';
FLUSH PRIVILEGES;

USE library_db;

CREATE TABLE IF NOT EXISTS books (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    author     VARCHAR(100) NOT NULL,
    publisher  VARCHAR(100),
    pub_year   YEAR,
    quantity   INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO books (title, author, publisher, pub_year, quantity) VALUES
('클린 코드', '로버트 마틴', '인사이트', 2013, 3),
('파이썬 완벽 가이드', '박응용', '이지스퍼블리싱', 2022, 5),
('리눅스 커맨드라인', '윌리엄 쇼츠', '한빛미디어', 2020, 2),
('자바스크립트 완벽 가이드', '데이비드 플래너건', '인사이트', 2021, 4),
('HTTP 완벽 가이드', '데이비드 고울리', '인사이트', 2014, 2);
SQL

echo "  ✅ DB 설정 완료"

# 2. 웹 디렉토리에 파일 복사
echo ""
echo "[2/3] 웹 파일 복사 중..."
sudo mkdir -p /var/www/html/library
sudo cp db.php index.php add.php edit.php delete.php style.css /var/www/html/library/
sudo chown -R www-data:www-data /var/www/html/library
sudo chmod -R 755 /var/www/html/library
echo "  ✅ 파일 복사 완료 → /var/www/html/library/"

# 3. Apache 재시작
echo ""
echo "[3/3] Apache 재시작 중..."
sudo systemctl restart apache2
echo "  ✅ Apache 재시작 완료"

echo ""
echo "=========================================="
echo "  설치 완료!"
echo "  브라우저에서 접속: http://localhost/library"
echo "=========================================="
