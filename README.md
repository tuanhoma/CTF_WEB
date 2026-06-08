# CTF Web Vulnerable Lab

Welcome to the **CTF Web Vuln Lab**! This is a comprehensive Web Application Security lab designed for Capture The Flag (CTF) challenges and penetration testing practice. The environment simulates a corporate web infrastructure with multiple interconnected services, a Web Application Firewall (WAF), and a centralized database.

## Architecture

The lab is built using Docker Compose and consists of the following components:

- **BunkerWeb (WAF)**: A Web Application Firewall protecting the public-facing services.
- **Nginx**: A central reverse proxy routing traffic to the internal applications.
- **Portal**: The public-facing web application for users.
- **API**: A public API service for the portal.
- **Staff**: An internal application for staff members (accessible via specific domain).
- **Internal API**: A private backend API, isolated within the private network and inaccessible directly from the internet.
- **Database**: A MySQL 8.0 database holding user credentials, support tickets, and personal notes.
- **Wazuh Agent**: A security monitoring agent configured to ship logs to a Wazuh manager.

## Getting Started

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd CTF_WEB
   ```

2. **Configure local domains:**
   You need to map the lab's domains to your localhost. Add the following line to your `hosts` file:
   - On **Windows**: `C:\Windows\System32\drivers\etc\hosts`
   - On **Linux/macOS**: `/etc/hosts`
   
   ```text
   127.0.0.1 portal.lab.local staff.lab.local api.lab.local cdn.lab.local internal-api.lab.local
   ```

3. **Start the environment:**
   Launch the containers in detached mode:
   ```bash
   docker-compose up -d
   ```

   *Note: The database container has a healthcheck configured. It might take a few moments for all services (like the portal and API) to become fully available after the database is healthy.*

## Target Services

Once the lab is running, you can access the services via your browser:
- **Portal**: `http://portal.lab.local`
- **Staff Interface**: `http://staff.lab.local`
- **API Base**: `http://api.lab.local`

## Educational Purpose

The lab contains various intentional vulnerabilities typical in web applications (e.g., Cross-Site Scripting, weak cryptography, flawed logic, etc.). 

**⚠️ WARNING:** 
**DO NOT host this application on a public-facing server or VPS.** This environment contains intentional, severe security vulnerabilities. It is designed solely to be run in a localized, safe, and isolated environment for educational and training purposes.
