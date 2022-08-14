# Laravel研修

## 用意する物
1. PHP 7.4
   * Windowsの場合
     * https://windows.php.net/download#php-7.4
   * Macの場合
     1. brew install php@7.4
     2. brew link php@7.4
2. Postman
   * APIクライアントに使用する
   * https://www.postman.com/downloads/

## 初期設定
1. database/database.sqlite.example を `database.sqlite`にリネームする
2. .env.example を `.env`にリネームする
3. training-laravel の配下で次のコマンドを入力する
    1. sqliteにplayersテーブルを作成する
        * `php artisan migrate`
    2. playerテーブルにダミーデータを登録する
        * `php artisan db:seed --class=PlayerSeeder`

## Postmanの初期設定
1. training-laravel.postman_collection.json をPostmanにインポートする
