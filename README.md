# 📚 도서 관리 시스템 (Library Management System)

LAMP Stack 기반의 도서관 도서 관리 웹 애플리케이션입니다.

## 기술 스택
- **OS**: Zorin OS (Ubuntu 24.04 기반)
- **Web Server**: Apache 2.4
- **Database**: MySQL 8.0
- **Language**: PHP 8.3
- **Frontend**: HTML5, Bootstrap 5

## 주요 기능
| 기능 | 설명 |
|------|------|
| 목록 조회 | 전체 도서 목록 표시 |
| 도서 추가 | 제목, 저자, 출판사, 출판연도, 수량 입력 |
| 도서 수정 | 기존 도서 정보 편집 |
| 도서 삭제 | 확인 후 도서 삭제 |
| 도서 검색 | 제목 또는 저자명으로 실시간 검색 |

## 설치 방법

### 사전 조건
- LAMP Stack이 설치된 Zorin OS
- sudo 권한

### 설치 실행
```bash
cd ~/lamp-library
bash setup.sh
```

### 접속
브라우저에서 `http://localhost/library` 접속

## 파일 구조
```
lamp-library/
├── index.php     # 도서 목록 (메인 페이지)
├── add.php       # 도서 추가 폼
├── edit.php      # 도서 수정 폼
├── delete.php    # 도서 삭제 처리
├── db.php        # DB 연결 설정
├── style.css     # 공통 스타일
├── setup.sh      # 자동 설치 스크립트
├── project.md    # 프로젝트 기획서
└── README.md     # 이 파일
```

## 빌드 오류 기록

| 날짜 | 오류 내용 | 해결 방법 |
|------|-----------|-----------|
| - | - | - |

---

## 스크린샷
(설치 후 추가 예정)
