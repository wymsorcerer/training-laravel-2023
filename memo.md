# php

## migration

seeder を使って、migrate する

```
php artisan migrate --seed
```

データベースを更新し、すべてのデータベース初期値設定を実行

```
php artisan migrate:refresh --seed
```

### カラム追加

1.migration ファイルを作る

```
php artisan make:migration add_user_id_to_posts_table --table=posts
```

補足

既存のテーブルに変更を加える場合には、--create オプションではなく、--table オプションを使って、テーブル名を指定します。

2.migration ファイルを編集

```
	public function up()
	{
			Schema::table('posts', function (Blueprint $table) {
					$table->string('summary');  //カラム追加
			});
	}

	public function down()
	{
			Schema::table('posts', function (Blueprint $table) {
					$table->dropColumn('summary');  //カラムの削除
			});
	}
```

3.migration する

```
php artisan migrate
```

4.確認
現在適用されている migration ファイルの確認

```
php artisan migrate:status
```

もしこの変更を取り消したい場合はロールバック

```
php artisan migrate:rollback --step=1
```

もしロールバックでエラーが出たら

```
php composer.phar dump-autoload
```

## laravel db query

https://readouble.com/laravel/8.x/ja/queries.html

### SELECT

````

//SELECT `id`,`name` FROM `player`;
Player::query()->select(['id', 'name'])->get()

```

```

//SELECT \* FROM `player` WHERE `id` = ?;
Player::find($id)

or

Player::where('id', $id)->first()

```

### INSERT

```

//INSERT INTO テーブル名 (列名 1, 列名 2,...) VALUES (値 1, 値 2,...);

$v = [
"name" => $request["name"],
"hp" => $request["hp"],
"mp" => $request["mp"],
"money" => $request["money"],
];

Player::insertGetId($v);

```

### UPDATE

```

//UPDATE (表名) SET (カラム名 1) = (値 1), (カラム名 2) = (値 2) WHERE (条件);

Player::where(['id' => $id])->update($request->all())

```

### DELETE

```

//DELETE FROM (表名) WHERE (PK) = (値);
Player::where(['id' => $id])->delete()

```

## artsian make model

https://readouble.com/laravel/8.x/ja/eloquent.html

```

php artisan make:model PlayerItem -mc

```

itemA の数が上限になったら、ガチャでまた itemA に当選する場合、どうしますか
```
````
