# Mercadona

## About The Project

| Project Overview                                                                                                                                                                                                                                                                                                                                                                                         |                                               |
| :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------- |
| This project, Mercadona, is developed as part of the Bachelor developer of applications program with Studi. The aim of this project is to create an application for the retail company Mercadona to showcase the products they offer to their clients. This repository contains the backend part of the project, which is developed using Symfony and API Platform, following a three-tier architecture. | ![Logo de Mercadona](/public/images/logo.png) |

## Features

- **Three-Tier Architecture**: Ensures a clear separation of concerns and promotes scalability.
- **Symfony & API Platform**: Provides a robust backend with an easy-to-use API for frontend applications.
- **Retail Product Management**: Designed to manage and display a wide range of products in retail.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- PHP 8.0 or higher
- Composer
- Symfony CLI
- MySQL or a similar database system

## Installation

### 1 - Clone the repository:

```bash
   git clone https://github.com/ABBA-74/mercadona-back-end/
```

### 2- Navigate to the project directory:

```
    cd mercadona-back-end
```

### 3 - Install dependencies:

```
    composer install
```

### 4 - Set up your environment variables in .env or .env.local.

Before running your application, it is essential to set up the necessary environment variables. These variables allow you to customize the application's behavior without changing the source code and are also used to store sensitive information.

Create a `.env.local` file at the root of your project by copying the provided `.env` file:

```
   cp .env .env.local
```

Open the .env.local file with a text editor and update the environment variables according to your local environment's needs. For example:

```
  # .env.local

  APP_ENV=dev
  APP_SECRET=<your-app-secret>
  DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

Replace <your-app-secret>, db_user, db_password, and db_name with your own values. For APP_SECRET, use a randomly generated string for security.

After setting up .env.local, ensure that you never commit this file to your Git repository as it contains sensitive information.

### 5 - Create the database and run migrations:

```
    php bin/console doctrine:database:create
```

```
    php bin/console doctrine:migrations:migrate
```

### 6 - Start the development server:

```
    symfony server:start
```

## Usage

After installation, your API will be accessible at http://localhost:8000/api. You can begin making requests to the endpoints defined in the project to manage and retrieve product data.

## Built With

- [Symfony](https://symfony.com/) - The framework used
- [API Platform](https://api-platform.com/) - The RESTful API platform

## Frontend Repository

For a complete experience, this backend is designed to work in conjunction with the frontend part of the Mercadona project. You can find the frontend repository and detailed instructions on setting it up at the following link:

[Visit Frontend Repository](https://github.com/ABBA-74/mercadona-front-end)
