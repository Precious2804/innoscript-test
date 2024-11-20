# **Project**

NewsAggregator API - A RESTful API for a news aggregator service that pulls articles from various sources and provides endpoints for a frontend application to consume.

---

## **Table of Contents**

1. [Features](#features)
2. [Technologies Used](#technologies-used)
3. [Installation](#installation)
    - [Running Locally](#running-locally)
    - [Running with Docker](#running-with-docker)
4. [Configuration](#configuration)
5. [Usage](#usage)
6. [Testing](#testing)
7. [Contributing](#contributing)
8. [License](#license)
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

## ** Installation **

### **Running Locally**

1. Clone the repository:
   git clone https://github.com/Precious2804/innoscripta-test-precious.git
   cd innoscripta-test-precious

2. Install Composer Dependencies
   composer install

3. Set up .env file
   cp .env.example .env

4. Generate Application Key
   php artisan key:generate

5. Run Migrations
   php artisan migrate

6. Start the local development server
   php artisan serve
