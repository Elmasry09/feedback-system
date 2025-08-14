<div align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</div>

<h1 align="center">Customer Feedback System</h1>

<p align="center">
An integrated project for automatically collecting customer feedback. The system allows an admin to add custom questions and sends them to customers via text messages after their orders are completed to get their ratings on services and products.
</p>

---

## 🚀 Key Features

-   **Question Management Dashboard:** An interface for the administrator (Admin) to add, edit, and delete the questions sent to customers.
-   **Dual Authentication System:** Uses session-based authentication for the admin panel and JWT for securing the GraphQL API.
-   **Scheduled Task:** Runs daily to send feedback links to customers who completed their orders on the previous day.
-   **Multi-Channel Notifications:** Ability to send notifications via Twilio (SMS) and Telegram.
-   **Interactive Customer Interface:** A simple and straightforward page for customers to answer questions without needing to log in.
-   **Flexible API:** Uses GraphQL to facilitate data handling between the frontend and backend.
-   **Background Processing (Queues):** Utilizes Laravel Queues to process message sending efficiently and prevent system performance delays.

## 🛠️ Tech Stack

-   **Backend:** Laravel 12
-   **Database:** MongoDB
-   **API:** GraphQL (using `nuwave/lighthouse` and `apollo client vue`)
-   **Frontend:** Vue.js 3 (using Vite)
-   **Authentication:** `tymon/jwt-auth`
-   **Notifications:** `twilio/sdk`,
-   **Scheduling:** Laravel Scheduler

## ⚙️ How it Works

1.  **Admin:** Logs into the control panel and adds the questions they want to ask customers (e.g., "How would you rate our delivery service?").
2.  **Orders:** New orders are recorded in the `orders` table with customer data (name, phone number).
3.  **Scheduled Task:** Every day at midnight, a scheduled task (`SendFeedbackRequests`) runs and performs the following:
    -   Fetches all orders completed on the previous day.
    -   For each order, a unique and secure token is generated to identify the customer.
4.  **Message:** The message contains a unique link like `https://your-app.com/feedback`.
5.  **Customer:** Upon clicking the link, the customer is directed to the feedback page (Vue.js) where the questions are displayed.
6.  **Submitting Answers:** The customer submits their answers via a GraphQL Mutation, which receives the token and the answers to link them to the correct order in the database.

## 📋 Installation & Setup

1.  **Clone the project:**
    ```bash
    git clone https://github.com/Elmasry09/feedback-system.git
    cd feedback-system
    ```

### Backend Setup

1.  **Navigate to the backend directory and install dependencies:**
    ```bash
    cd feedback-back-end
    composer install
    ```

2.  **Set up the environment file (`.env`):**
    ```bash
    cp .env.example .env
    ```
    Next, edit the `.env` file and add your database (MongoDB) and notification service (Twilio) credentials. You will need a Twilio account to get your `TWILIO_SID` and `TWILIO_TOKEN`.

    ```dotenv
    APP_NAME="Feedback System"
    APP_URL=http://localhost:8000
 
    DB_CONNECTION=mongodb
    MONGODB_URI=mongodb://127.0.0.1:27017
    MONGODB_DATABASE=feedback_system
 
 
    # Twilio Credentials
    TWILIO_SID=
    TWILIO_TOKEN=
    ```

3.  **Generate application key and run migrations:**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

4.  **Return to the project root directory:**
    ```bash
    cd ..
    ```

### Frontend Setup

1.  **Navigate to the frontend directory and install dependencies:**
    ```bash
    cd feedback-front-end
    npm install
    ```

2.  **Return to the project root directory:**
    ```bash
    cd ..
    ```

### Running the Development Environment
To run the application, you will need to open three separate terminal windows **from the project root (`feedback-system`)**.

1.  **Run the Backend Server:**
    ```bash
    cd feedback-back-end && php artisan serve
    ```

2.  **Run the Frontend Server (Vite):**
    ```bash
    cd feedback-front-end && npm run dev
    ```

3.  **Run the Task Scheduler:**
    ```bash
    # If you are using Windows OS, run all development environment commands from WSL.
    cd feedback-back-end && php artisan schedule:run-cronless
    ```