# php

## migrationn

migrate する

```
php artisan migrate --seed
```

データベースを更新し、すべてのデータベース初期値設定を実行

```
php artisan migrate:refresh --seed
```

## laravel db query

https://readouble.com/laravel/8.x/ja/queries.html

### SELECT

```
//SELECT `id`,`name` FROM `player`;
Player::query()->select(['id', 'name'])->get()
```

```
//SELECT * FROM `player` WHERE `id` = ?;
Player::find($id)

or

Player::where('id', $id)->first()
```

### INSERT

```
//INSERT INTO テーブル名 (列名1, 列名2,...) VALUES (値1, 値2,...);

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
//UPDATE (表名) SET (カラム名1) = (値1), (カラム名2) = (値2) WHERE (条件);

Player::where(['id' => $id])->update($request->all())
```

### DELETE

```
//DELETE FROM (表名) WHERE (PK) = (値);
Player::where(['id' => $id])->delete()

```
