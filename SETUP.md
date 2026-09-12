# WardSewa (वार्डसेवा) — 4-Tier Administrative Hierarchy & Digital Ward Service Platform

**WardSewa** is a comprehensive e-governance platform for Nepal local governments (Palikas and Wards). It allows citizens to apply for official recommendation letters, track applications in real-time, pay utility bills, lodge grievances, book in-person ward office appointments, and download officially verified certificates with QR verification. 

It equips government administrators and staff with a **4-tier role-based administrative dashboard** with digital signature stamping, geographic jurisdiction scoping, and comprehensive audit logging.

---

## 🏛️ 4-Tier Administrative Hierarchy Architecture

WardSewa enforces strict multi-tenancy and geographic scoping matching Nepal's federal structure:

```
                            WARDSEWA SUPER ADMIN
               (Ministry of Federal Affairs / Central Platform)
                                     │
         ┌───────────────────────────┼───────────────────────────┐
         ▼                           ▼                           ▼
 KATHMANDU DISTRICT          LALITPUR DISTRICT          BHAKTAPUR DISTRICT
 (Kathmandu DCC Admin)       (Lalitpur DCC Admin)       (Bhaktapur DCC Admin)
         │                           │                           │
  11 Local Governments        6 Local Governments         4 Local Governments
  (KMC, Chandragiri, etc.)    (Lal. Metro, Godawari, etc.)(Bhaktapur, Madhyapur, etc.)
         │                           │                           │
     138 Wards                    71 Wards                    38 Wards
 (Ward Admin, Chair,         (Ward Admin, Chair,         (Ward Admin, Chair,
   Secretary, Clerk)           Secretary, Clerk)           Secretary, Clerk)
```

### Administrative Scope & Permissions

| Level | Role Identifier | Geographic Scope | Key Responsibilities & Capabilities |
| :--- | :--- | :--- | :--- |
| **Level 1** | `super_admin` | System-wide (`district_id = null`, `palika_id = null`, `ward_id = null`) | Complete system oversight, global analytics, geographic configuration (all 3 districts, 21 palikas, 247 wards), staff management, administrative audit log inspection. |
| **Level 2** | `district_admin` | Single District (`district_id = X`, `palika_id = null`, `ward_id = null`) | District Coordination Committee (DCC) level oversight. Monitors all palikas and wards within their assigned district, cross-palika application volume, and inter-ward coordination. |
| **Level 3** | `local_govt_admin` | Single Palika (`district_id = X`, `palika_id = Y`, `ward_id = null`) | Municipal IT / Executive Officer view. Oversees all wards in their municipality, municipal revenue, ward service loads, ward performance metrics, and service availability. |
| **Level 4** | `ward_admin`, `ward_chair`, `secretary`, `clerk` | Single Ward (`district_id = X`, `palika_id = Y`, `ward_id = Z`) | Operational ward team. Review citizen applications, request supporting documents, digitally approve and stamp recommendation certificates with QR codes, manage appointments and public grievances. |

---

## 🔑 Demo & Test Credentials (All 4 Tiers)

All pre-seeded administrative accounts use the unified password: **`password123`**  
Login Portal: **[http://localhost:8000/staff/login](http://localhost:8000/staff/login)**  
*(The system dynamically routes each administrator to their designated level dashboard upon authentication).*

### Tier 1: Super Admin
- **Email:** `superadmin@wardsewa.gov.np`
- **Password:** `password123`
- **Dashboard:** System-wide metrics, geography registry, staff administrator management, complete audit trails.

### Tier 2: District Admins (DCC Level)
| District | Email | Password | Scope |
| :--- | :--- | :--- | :--- |
| **Kathmandu District** | `admin.ktm@wardsewa.gov.np` | `password123` | 11 Palikas, 138 Wards |
| **Lalitpur District** | `admin.lalitpur@wardsewa.gov.np` | `password123` | 6 Palikas, 71 Wards |
| **Bhaktapur District** | `admin.bhaktapur@wardsewa.gov.np` | `password123` | 4 Palikas, 38 Wards |

### Tier 3: Local Government Admins (Palika Level)
| Local Government | Email | Password | Scope |
| :--- | :--- | :--- | :--- |
| **Kathmandu Metropolitan City (KMC)** | `admin.kmc@wardsewa.gov.np` | `password123` | 32 Wards |
| **Lalitpur Metropolitan City (LMC)** | `admin.lmc@wardsewa.gov.np` | `password123` | 29 Wards |
| **Bhaktapur Municipality (BKM)** | `admin.bkm@wardsewa.gov.np` | `password123` | 10 Wards |
| **Chandragiri Municipality** | `admin.chandragiri@wardsewa.gov.np` | `password123` | 15 Wards |

### Tier 4: Ward Operational Staff (Ward 32 Pilot & Valley Wards)
| Role | Office | Email | Password | Permissions |
| :--- | :--- | :--- | :--- | :--- |
| **Ward Chair (वडा अध्यक्ष)** | KMC Ward 32 | `chair@ward32.gov.np` | `password123` | Full review, reject, digital signature stamping, PDF issuance |
| **Ward Secretary (वडा सचिव)** | KMC Ward 32 | `secretary@ward32.gov.np` | `password123` | Application review, document requests, certificate signing |
| **Front Desk Clerk (वडा सहायक)** | KMC Ward 32 | `clerk@ward32.gov.np` | `password123` | Triage, initial verification, document checks |
| **Ward Admin (वडा व्यवस्थापक)** | KMC Ward 32 | `admin.ward32@wardsewa.gov.np` | `password123` | Ward staff configuration and notices |
| **Ward Chair** | Lalitpur Metro Ward 1 | `chair@lmc1.gov.np` | `password123` | Ward 1 Lalitpur approvals |
| **Ward Chair** | Bhaktapur Ward 1 | `chair@bkm1.gov.np` | `password123` | Ward 1 Bhaktapur approvals |

### Citizen Portal Accounts
- **URL:** [http://localhost:8000/citizen/login](http://localhost:8000/citizen/login)
- **Login Options:**
  1. **Password Login / Registration:** Enter email and password, or register as a new citizen.
  2. **Phone + OTP Fallback:** Enter mobile number (e.g. `9841234567`). The 6-digit OTP is shown in an amber top banner during local testing.

---

## 🗺️ Complete Geographic Coverage (Kathmandu Valley)

The database includes all **21 Local Governments** and **247 Wards** across 3 districts:

### 1. Kathmandu District (11 Palikas / 138 Wards)
1. **Kathmandu Metropolitan City (KMC):** Wards 01–32 (32 Wards)
2. **Chandragiri Municipality:** Wards 01–15 (15 Wards)
3. **Budhanilkantha Municipality:** Wards 01–13 (13 Wards)
4. **Tarakeshwor Municipality:** Wards 01–11 (11 Wards)
5. **Tokha Municipality:** Wards 01–11 (11 Wards)
6. **Kirtipur Municipality:** Wards 01–10 (10 Wards)
7. **Nagarjun Municipality:** Wards 01–10 (10 Wards)
8. **Dakshinkali Municipality:** Wards 01–09 (9 Wards)
9. **Gokarneshwor Municipality:** Wards 01–09 (9 Wards)
10. **Kageshwori Manohara Municipality:** Wards 01–09 (9 Wards)
11. **Shankharapur Municipality:** Wards 01–09 (9 Wards)

### 2. Lalitpur District (6 Palikas / 71 Wards)
1. **Lalitpur Metropolitan City (LMC):** Wards 01–29 (29 Wards)
2. **Mahalaxmi Municipality:** Wards 01–10 (10 Wards)
3. **Godawari Municipality:** Wards 01–14 (14 Wards)
4. **Konjyosom Rural Municipality:** Wards 01–05 (5 Wards)
5. **Bagmati Rural Municipality:** Wards 01–07 (7 Wards)
6. **Mahankal Rural Municipality:** Wards 01–06 (6 Wards)

### 3. Bhaktapur District (4 Palikas / 38 Wards)
1. **Bhaktapur Municipality:** Wards 01–10 (10 Wards)
2. **Madhyapur Thimi Municipality:** Wards 01–09 (9 Wards)
3. **Suryabinayak Municipality:** Wards 01–10 (10 Wards)
4. **Changunarayan Municipality:** Wards 01–09 (9 Wards)

---

## 🚀 Setup & Execution Guide

### 1. Prerequisites
- **PHP 8.2+** (with extensions: `pdo_pgsql` or `pdo_sqlite`, `mbstring`, `gd`, `openssl`, `curl`)
- **Composer** (PHP dependency manager)
- **Node.js (v18+)** and **npm**
- **PostgreSQL 15+** (or SQLite for quick zero-config local testing)

### 2. Environment Configuration
```bash
cp .env.example .env
```

Set database connection in `.env`:
```env
# For PostgreSQL (Production & Staging default):
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=wardsewa
DB_USERNAME=postgres
DB_PASSWORD=postgres

# OR for zero-config local SQLite:
DB_CONNECTION=sqlite
```
*(If using SQLite, create `database/database.sqlite` file first).*

### 3. Installation & Database Migration
```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup application key & storage link
php artisan key:generate
php artisan storage:link

# 3. Compile frontend assets
npm run build

# 4. Run migrations and seed all 21 Palikas, 247 Wards, 4-tier staff & service types
php artisan migrate:fresh --seed
```

### 4. Run Development Server
```bash
php artisan serve
```
Open **[http://localhost:8000](http://localhost:8000)** in your browser.

---

## 🌟 Core Feature Modules

### 1. 4-Tier Scoped Administrative Routing
- **Unified Login:** All administrative personnel log in via `/staff/login`.
- **Dynamic Role Redirection:**
  - `super_admin` $\rightarrow$ `/staff/superadmin/dashboard`
  - `district_admin` $\rightarrow$ `/staff/district/dashboard`
  - `local_govt_admin` $\rightarrow$ `/staff/localgovt/dashboard`
  - Ward roles (`ward_chair`, `secretary`, `clerk`, `ward_admin`) $\rightarrow$ `/staff/dashboard`
- **Dynamic Sidebar Navigation:** Sidebar links and jurisdiction badges dynamically adjust based on active role and jurisdiction scope.

### 2. Citizens In-Person Ward Visit Appointments
- **Booking Flow:** Citizens can book time-slot appointments (`/citizen/appointments/book`) with their ward office for signature collection, inquiries, or document verification.
- **Queue Prevention:** Select preferred date and slot (`10:00 AM - 11:00 AM`, `11:00 AM - 12:00 PM`, etc.) with checklist of required documents.
- **Staff Appointment Management:** Ward staff review scheduled appointments (`/staff/appointments`), mark them as completed, or update notes.

### 3. Administrative Audit Trails (`audit_logs`)
- Every critical administrative action is securely tracked in `audit_logs`:
  - Staff logins & logouts with timestamp and client IP.
  - Application approvals, rejections, and document requests.
  - Creation and updates of administrative accounts.
- Super Admin can search, filter, and inspect audit logs at `/staff/superadmin/audit-logs`.

### 4. Official Recommendation PDF with QR Verification
- Ward Chair / Secretary approval automatically attaches digital signature and stamp.
- Official bilingual recommendation letter PDF is generated with secure verification token.
- Public QR verification at `/verify/{token}` allows embassies, banks, and authorities to verify certificate authenticity with zero forgery risk.

### 5. Utility Bills & Public Grievances
- Citizens can pay electricity (NEA), water (KUKL), and local tax bills online with receipt generation.
- Citizens can report civic issues (sanitation, road repairs, streetlights) with location and photo attachments.
