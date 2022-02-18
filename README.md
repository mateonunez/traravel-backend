# Traravel

Your [Tra]vel with La[ravel]

## Manual Configuration

Run the migrations and seeders:

```shell
php artisan migrate:fresh --seed
```

Install Passport with `uuids`:

```shell
php artisan passport:install --uuids
```

> Migrations are already exists on `database/migrations`, when Passport will ask you if you want rollback those migrations just don't.

Generate the encryption keys:

```shell
php artisan passport:keys
```

## Automatic Configuration

Run the following command:

```shell
php artisan prepare:env
```
