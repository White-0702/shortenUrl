# URL Shortener API

這是一個基於 Laravel 框架的 URL 短連結 API 專案。該專案允許用戶將長 URL 轉換為短鏈接，並且提供來獲取原始 URL 和點擊次數。

## 特性

- 將長 URL 縮短為短鏈接
- 記錄短連結的點擊次數(超過限制會阻擋)
- API 介面傳回 JSON 格式的回應

## 先決條件

在開始之前，請確保您的系統已經安裝以下軟體：

- Docker
- Docker Compose
- PHP 7.4+
- Composer

## 安裝步驟

1. Clone：

 ```bash
 git clone https://github.com/yourusername/url-shortener.git
 cd url-shortener
 ```

2. 複製 `.env.example` 文件為 `.env` 文件，並根據需要修改配置：

 ```bash
 cp .env.example .env
 ```

3. 在 `.env` 檔案中設定資料庫連線和應用程式路徑：

 ```env
 APP_NAME=Laravel
 APP_ENV=local
 APP_KEY=
 APP_DEBUG=true
 APP_URL=http://localhost:9000
 APP_PATH=localhost:9000

 LOG_CHANNEL=stack

 DB_CONNECTION=mysql
 DB_HOST=db
 DB_PORT=3306
 DB_DATABASE=laravel
 DB_USERNAME=your_username
 DB_PASSWORD=your_password
 ```

4. 透過 Docker Compose 啟動服務：

    ```bash
    docker-compose up --build -d
    ```

5. 產生應用程式密鑰並運行資料庫遷移：

 ```bash
 docker-compose exec app php artisan key:generate
 docker-compose exec app php artisan migrate
 ```

## 使用說明

### 縮短 URL

- **http method**: POST
- **URL**: `http://localhost:9000/api/shorten`
- **Headers**:
 - Content-Type: application/json
- **request body example:**:

 ```json
 {
 "long_url": "https://www.example.com"
 }
 ```

- **response body example:**:

 ```json
 {
 "status": "success",
 "short_url": "http://localhost:9000/short/abc123"
 }
 ```

### 取得長 URL

- **http method**: GET
- **URL**: `http://localhost:9000/api/short/{short_url}`
- **response body example:**:

 - **成功**:

 ```json
 {
 "status": "success",
 "long_url": "https://www.example.com",
 "count": 1
 }
 ```

 - **超過點擊次數限制**:

 ```json
 {
 "status": "fail",
 "message": "開啟超過10次"
 }
 ```

 - **短連結不存在**:

 ```json
 {
 "status": "fail",
 "message": "短網址不存在"
 }
 ```

 - **非預期錯誤**:

 ```json
 {
 "status": "fail",
 "message": "非預期錯誤"
 }
 ```