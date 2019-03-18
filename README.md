# PHP AJAX 留言板

用 PHP 與 AJAX 熟悉前後端透過 AJAX 傳遞資料。

![image](https://raw.githubusercontent.com/enter3017sky/mentor-program-2nd-blog/master/picture/forum-php.gif)

## 前端技術

- 使用 JQuery AJAX，新增、刪除、編輯不會換頁，讓體用者體驗更好。
- 使用 [Prism](https://prismjs.com/) 渲染語法高亮。

## 後端技術

- PHP MySQLi Prepare statement 防止 SQL injection。
- 使用 [Parsedown](https://parsedown.org/) 解析 markdown 語法。
    - ~~htmlspecialchars() 避免 XXS 攻擊。~~
    - 利用使用 _Parsedown_ 內建的安全模式避免 XXS 攻擊。

### 留言板系統

- 每頁顯示 10 筆主留言。
- 在自己主留言底下留言會以不同顏色區別。
- 快速註冊會員，可新增、刪除、編輯留言。
- 使用者透過 session 機制登入。
- 訪客透過 session 機制儲存暱稱，可在文章留下評論或留言。
- 密碼經過 hash 處理。

### 目的

- 學習 Server 與 PHP 後端原理。
- MySQL 資料庫系統與 Table 架構。
- PHP - MySQLi Prepare statement 防止 SQL injection。
- 避免 XXS 攻擊
- CRUD 操作。

### 工具

- AWE EC2
- Nginx
- FileZilla
- PHP
- MySQL
- JQuery
- Bootstrap
