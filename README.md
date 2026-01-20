# HelpDesk Triage (Smart Support System) 🎫

A robust, internal IT ticketing system built for the **Jr. Laravel Take-Home Assignment**. This application provides a streamlined workflow for **Employees** to report issues and **Support Agents** to manage them, featuring a **Lane-1 Similar Ticket Detection** system to reduce duplicate requests.

---

## 🚀 Features

### 📋 Ticket Management
* **Role-Based Access Control:** Personalized dashboards for **Employees** (view/create own tickets) and **Agents** (manage all tickets globally).
* **Status Workflow:** Tickets transition through `Open` → `In Progress` → `Resolved` → `Closed`.
* **Assignment System:** Agents can assign tickets to themselves or colleagues, or move them back to an `Unassigned` state.
* **Live Filtering:** Real-time filtering by Status, Category, Severity, and Subject using **Alpine.js debouncing** for a seamless user experience.

### 📎 Secure Attachment System
* **Private Storage:** All files (`png`, `jpg`, `pdf`, `txt`, `log`) are stored on a private disk (`storage/app/private`).
* **Controlled Access:** Direct URL access to files is blocked.


###  Similar Ticket Detection (Classic)
* **Duplicate Prevention:** As an employee types a subject line, the system proactively searches for existing issues.
* **Optimized Shortlisting:** To ensure high performance, the search is limited to tickets from the last **30 days** and excludes **Closed** tickets.
* **Debounced Input:** A 500ms delay is implemented on search requests to optimize server resources and reduce database load.

---

## 🛠️ Tech Stack 

Before proceeding with the installation, ensure you have the following installed on your local machine:

* **Framework:** Laravel 12
* **PHP Version:** 8.4
* **Database:** SQLite (default) / MySQL
* **Frontend:** Blade + Tailwind CSS + Alpine.js
* **Security:** Laravel Policies, Gates & Form Requests
* **Quality:** Laravel Pint (Formatting) 

---

## 📦 Installation & Setup

1.  **Clone & Install Dependencies**
    ```bash
    git clone https://github.com/Ayush-Barai/HelpDesk.git
    cd HelpDesk
    composer install
    npm install 
    npm run build
    ```

2.  **Environment Configuration**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3.  **Database Migration & Seeding**
    ```bash
    # This creates the tables and the demo accounts (Agent & Employee)
    php artisan migrate:fresh --seed
    ```

4.  **Launch the Application**
    ```bash
    npm run dev
    ```
5.  **Click Here**
   http://localhost:8000
---

## 🔑 Demo Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Support Agent** | `agent@test.com` | `password` |
| **Employee** | `employee@test.com` | `password` |

---

## 🧠 Logical Implementation Details

### Similarity Logic (Lane 1)
Instead of a heavy brute-force search across the entire database, I implemented a **Shortlist & Rank** strategy:
1.  **Normalization:** The input string is trimmed and case-sanitized.
2.  **Prefiltering (Shortlist):** The query is restricted to tickets created within the last **30 days** that are not in a `Closed` state.
3.  **Ranking:** Matches are identified using partial keyword matching via SQL `LIKE` queries, returning the top 5 most relevant results.

### File Security & Privacy
Attachments are stored in a non-public directory. Access is managed through a dedicated `AttachmentController@download` method. This method acts as a gatekeeper, ensuring that only the original creator of the ticket or an authorized Agent can stream the file content.

---

## 💎 Code Quality & Security

Maintain high standards by running the following commands:

* **Fix Formatting:** `./vendor/bin/pint`

---

