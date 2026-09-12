# WardSewa (वार्डसेवा) — Digital Ward Service Platform for Nepal

**WardSewa** is an end-to-end e-governance platform for Nepal local governments (Palikas and Wards). It allows citizens to apply for official recommendation letters, track applications in real-time, pay utility bills, lodge grievances, and download officially verified certificates with QR verification. It equips ward chairs, secretaries, and clerks with a role-based approval dashboard with digital signature stamping.

---

## 🚀 Quick Setup & Run Guide

### 1. Prerequisites
Ensure you have the following installed on your machine:
- **PHP 8.2+** (with `pdo_pgsql` or `pdo_sqlite`, `mbstring`, `gd`, `openssl`, `curl`)
- **Composer** (PHP dependency manager)
- **Node.js (v18+)** and **npm**
- **PostgreSQL 15+** (or SQLite for quick zero-config local testing)

---

### 2. Environment Configuration

1. In the project directory:
   ```bash
   cp .env.example .env
   ```
2. By default, `.env` is configured for **PostgreSQL**:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=wardsewa
   DB_USERNAME=postgres
   DB_PASSWORD=postgres
   ```
   *Make sure you create the PostgreSQL database `wardsewa` first:*
   ```sql
   CREATE DATABASE wardsewa;
   ```

   *(Optional: Zero-config SQLite Alternative)*
   If you do not have PostgreSQL running right now, switch your `.env` to SQLite:
   ```env
   DB_CONNECTION=sqlite
   ```
   And touch `database/database.sqlite`:
   ```bash
   touch database/database.sqlite
   ```

---

### 3. Install Dependencies & Build Frontend

```bash
# 1. Install PHP dependencies
composer install

# 2. Generate application encryption key
php artisan key:generate

# 3. Create storage symlink for uploaded documents and certificates
php artisan storage:link

# 4. Install Node dependencies and compile assets
npm install
npm run build
```

---

### 4. Run Migrations & Seeders

Seed all 7 provinces, 77 districts, Kathmandu Metropolitan City (KMC), Ward 32 (pilot), all core service types, utility billers, and staff accounts:

```bash
php artisan migrate --seed
```

---

### 5. Start the Application

In one terminal, start the Laravel server:
```bash
php artisan serve
```

In another terminal (during development, if modifying Tailwind/Alpine):
```bash
npm run dev
```

Visit the application in your browser:
**[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Test Accounts & Demo Credentials

### 1. Citizen Portal
- **URL:** [http://localhost:8000/citizen/login](http://localhost:8000/citizen/login)
- **Login Flow:**
  1. Enter any 10-digit mobile number starting with `98` or `97` (e.g. `9841234567`).
  2. The 6-digit OTP code will be sent to logs AND **automatically displayed in an amber banner at the top of the screen** in local mode.
  3. Enter the 6-digit code to log in.
  4. On first login, select **Kathmandu Metropolitan City -> Ward 32**.

---

### 2. Ward Staff Portal
- **URL:** [http://localhost:8000/staff/login](http://localhost:8000/staff/login)
- **Role-based Test Accounts:**

| Role | Email | Password | Permissions |
| :--- | :--- | :--- | :--- |
| **Ward Chair (वडा अध्यक्ष)** | `chair@ward32.gov.np` | `password123` | Full review, reject, approve with digital signature stamp, generate official PDF |
| **Ward Secretary (वडा सचिव)** | `secretary@ward32.gov.np` | `password123` | Review, request documents, official approval, PDF generation |
| **Front Desk Clerk (वडा सहायक)**| `clerk@ward32.gov.np` | `password123` | Initial document verification, request more documents, triage |
| **Palika Admin (पालिका आईटी)** | `admin@kathmandu.gov.np` | `password123` | Municipality-wide view across all 32 wards |

---

## 🌟 Core Features Walkthrough

### A. Digital Recommendation Applications (चार किल्ला, अविवाहित, बसोबास)
1. As a **Citizen**, log in and click **+ नयाँ सिफारिस निवेदन**.
2. Select any recommendation type (e.g. *Four Boundaries Recommendation*).
3. Fill the dynamically generated fields (Kitta number, area, boundaries) and upload required certificates (Citizenship, Lalpurja).
4. Click **पेश गर्नुहोस् (Submit)**. An application number like `WS-2081-0001` is generated.
5. If the service has a fee, click **तत्काल परीक्षण भुक्तानी (Mock Pay)** or **Khalti** to mark fee paid.

### B. Staff Workflow & PDF Recommendation Generation
1. Open an incognito browser window and log in as Ward Secretary or Chair: `chair@ward32.gov.np` / `password123`.
2. In the **नागरिक निवेदन सूची**, open the submitted application.
3. Review uploaded attachments and click **स्वीकृत गरी सिफारिस पत्र जारी गर्नुहोस् (Approve & Issue Letter)**.
4. The system automatically:
   - Sets status to `approved`.
   - Generates the official bilingual recommendation letter PDF with Nepal Government emblem, official ward header, digital signature, and QR code token.
   - Saves the PDF to disk.
   - Dispatches a status change SMS notification.

### C. QR Code Verification (Zero-Forgery Feature)
1. Open the approved application as a citizen and click **सिफारिस पत्र (PDF) डाउनलोड**.
2. Notice the QR Code in the lower left corner.
3. Anyone (embassy, bank, immigration) scanning this QR code or visiting:
   `http://localhost:8000/verify/{token}`
   will instantly see the official verification status, citizen details, and issuing ward confirmation.

### D. Utility Bills & Public Grievances
1. Click **महसुल भुक्तानी (Bills)** to pay electricity (NEA), water (KUKL), or ward tax.
2. Click **गुनासो (Grievance)** to lodge sanitation or road repair issues anonymously or with your phone number.

---

## 📁 Architecture Highlights

- **Multi-Tenancy:** Strict ward-level scoping (`ward_id` / `palika_id`) enforced across queries, middleware, and `ApplicationPolicy`.
- **Dual Guards:** Clean separation of `citizen` guard (phone/OTP) and `staff` guard (email/bcrypt).
- **PDF Engine:** `Barryvdh\DomPDF` with graceful HTML preview fallback.
- **Payment Engine:** Khalti v2 ePayment integration with local test fallback.
- **Bilingual:** Fully localized in Nepali (`lang/ne.json`) and English (`lang/en.json`).
