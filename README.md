# Symfony CRUD Application

This is a symfony app that introduces CRUD actions.

It's based on [symfony/skeleton]([https://](https://packagist.org/packages/symfony/skeleton)) 6.2.x.

## Installation

- Clone the repository with `git clone <url> <folder_destination>`
- Get composer dependencies

```console
> composer install
```

- Get node modules

With yarn:
```console
> yarn install
```

Or with npm:
```console
> npm install
```

- Build front-end assets

```console
> npm run dev
```

## Configuration

- Database configuration

You have to setup your database credentials inside `.env` file or create `.env.local` with the `DATABASE_URL` key:

```
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8&charset=utf8mb4"
```

- Database migrations

Run theses commands to get database tables:

```console
Create database
> php bin\console doctrine:database:create

Migrate tables
> php bin\console doctrine:migrations:migrate
```