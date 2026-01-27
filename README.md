Step 1: Update README.md

Replace the entire content of README.md with this:

code
Markdown
download
content_copy
expand_less
# Digital Marketplace API

This repository contains the backend solution for the Digital Marketplace technical assessment. It is a RESTful API built with Laravel 11, PostgreSQL, and Redis, fully containerized using Docker.

## 🚀 Setup Instructions

The application relies on Docker and Docker Compose. No local PHP or Composer installation is required.

### 1. Initialize the Environment
Clone the repository and start the containers.

```bash
cp .env.example .env
docker-compose up -d --build
2. Application Setup

Once the containers are running, install dependencies and set up the database with seed data.

code
Bash
download
content_copy
expand_less
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
3. Start Queue Worker

The application uses Redis queues to process purchases asynchronously. You must start a worker to process these jobs.

code
Bash
download
content_copy
expand_less
docker-compose exec app php artisan queue:work

The API will be available at: http://localhost

🔑 Default Credentials (Seed Data)

The migrate --seed command creates two default users for testing:

Role	Email	Password
Creator	creator@example.com	password
Customer	customer@example.com	password
🏗 Architecture & Design Decisions
Code Structure

The project adheres to a domain-oriented architecture to maintain separation of concerns:

Controllers: Kept intentionally thin. They handle request validation and response formatting only.

Actions: Business logic (e.g., RegisterUserAction, ProcessPurchaseJob) is isolated in the app/Actions directory. This ensures logic is reusable and easily testable without HTTP dependencies.

Form Requests: All validation rules are encapsulated in strict Form Request classes.

Database Design

Monetary Values: All prices are stored as integers (cents) to prevent floating-point calculation errors.

Roles: User roles (Creator vs Customer) are enforced using database-level Enums.

Indexing: Foreign keys on purchases and products are indexed to optimize lookup performance.

Security

Authentication: Implemented using Laravel Sanctum (Bearer Token).

File Storage: Uploaded files are stored in a private directory (storage/app/products) and are never accessible via the public web server.

Secure Downloads: File access is granted solely through Temporary Signed URLs. These URLs verify the user's purchase history and expire after 60 minutes.

✅ Testing

The project uses Pest PHP for automated testing. The test suite covers:

Authentication flows (Register/Login).

Authorization logic (Creators vs Customers).

Purchase processing (Queue dispatching).

Security controls (Preventing unauthorized downloads).

To run the tests:

code
Bash
download
content_copy
expand_less
docker-compose exec app ./vendor/bin/pest
📚 API Documentation

API documentation is generated using Scribe. You can find the OpenAPI specification (openapi.yaml) and Postman collection (collection.json) in the public/docs directory.

To regenerate documentation:

code
Bash
download
content_copy
expand_less
docker-compose exec app php artisan scribe:generate
🚢 Deployment Guide
Production Infrastructure

To deploy this application to a production environment (e.g., AWS, DigitalOcean):

Containerization: The provided Dockerfile and Nginx configuration (docker/nginx/default.conf) are production-ready.

Database: Connect to a managed PostgreSQL instance (RDS/Cloud SQL) by updating DB_HOST in the environment variables.

Queue & Cache: Connect to a managed Redis instance (ElastiCache/Memorystore).

Storage: In .env, change FILESYSTEM_DISK to s3 and provide AWS credentials to offload file storage to S3.

CI/CD

A generic CI workflow is included in .github/workflows/tests.yml. It automates:

Environment setup.

Dependency installation.

Static analysis and Test execution.

code
Code
download
content_copy
expand_less
### Step 2: Commit and Push Fix

Run these commands in your VS Code terminal to send the fix to GitHub:

```bash
git add README.md
git commit -m "docs: fix markdown formatting in readme"
git push origin main
