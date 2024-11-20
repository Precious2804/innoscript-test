# **Project**

NewsAggregator API - A RESTful API for a news aggregator service that pulls articles from various sources and provides endpoints for a frontend application to consume.

---

## **Table of Contents**

1. [Features](#features)
2. [Technologies Used](#technologies-used)
3. [Installation](#installation)
    - [Running Locally](#running-locally)
    - [Running with Docker](#running-via-docker)
4. [Configuration](#configuration)
5. [Usage](#usage)
6. [Testing](#testing)
9. [Contact](#contact)

---

## **Features**

-   User authentication (Create Account, Login, Reset Password, Logout).
-   Article Management
-   User Preferences Management
-   Data Aggregation from news sources

---

## **Technologies Used**

-   **Language**: PHP 8.2
-   **Framework**: Laravel 11
-   **Database**: MySQL

---

## **Installation**

### **Running Locally**

1. Clone the repository:
   ```bash
   git clone https://github.com/Precious2804/innoscripta-test-precious.git
   cd innoscripta-test-precious

2. Install Composer Dependencies
   ```bash
   composer install

3. Set up .env file
   ```bash
   cp .env.example .env

4. Generate Application Key
   ```bash
   php artisan key:generate

5. Run Migrations
   ```bash
   php artisan migrate

6. Start the local development server
   ```bash
   php artisan serve

7. Access the application at http://localhost:8000 


### **Running via Docker**

1. Clone the repository:
   ```bash
   git clone https://github.com/Precious2804/innoscripta-test-precious.git
   cd innoscripta-test-precious

2. Set up .env file
   ```bash
   cp .env.example .env

3. Update environment variables to match Docker configurations:
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=host.docker.internal
   DB_PORT=3306
   DB_DATABASE=db_name
   DB_USERNAME=db_user
   DB_PASSWORD=db_pass 

4. Build and run the Docker containers:
   ```bash
   docker-compose up --build

5. Run migrations inside the Docker container
   ```bash
   docker-compose exec app php artisan migrate --seed

6. Access the application at http://localhost:8000 


---


## **Configuration**

- Update .env file with the required settings:
  - Database credentials: Set up your database on your prefereed MYSQL host, for instance phpMyAdmin, and update the .env file with your database configuration

  - API keys: Add the following attributes to your .env files, useful for aggregating data from 3 news sources. You can retrieve the keys via the email submission for this test
    ```bash
    NEWSORG_API_KEY=xxxxxxxxxxxxxxxxxx
    GUARDIAN_API_KEY=xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxx
    NEW_YORK_TIMES_API_KEY=xxxxxxxxxxxxxxxxxxxxx

- Fetch and Store Date from the 3 News Sources above

  - NewsApi.org: to fetch and store articles from NewsAPI.org
    - Run Locally
      ```bash
      php artisan get:newsorg $category
    - Run via Docker
      ```bash
      docker-compose exec app php artisan get:newsorg $category

  - The Guardian: to fetch and store articles from The Guardian
    - Run Locally
      ```bash
      php artisan get:guardian $category
    - Run via Docker
      ```bash
      docker-compose exec app php artisan get:guardian $category

  - New York Times: to fetch and store articles from New York Times
    - Run Locally
      ```bash
      php artisan get:nytimes $category
    - Run via Docker
      ```bash
      docker-compose exec app php artisan get:nytimes $category

Note: $category is a dynamic value that could be set, and used for fetch from any of the news resources based on a preferred category. For Example, election, entertainment, business etc



