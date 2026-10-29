CAMPUS EVENT MANAGEMENT SYSTEM — DOCKER/AWS EC2 DEPLOYMENT

Application stack: PHP + Apache + MySQL
Docker image: <your-dockerhub-username>/campus-event-management:1.0
Web URL after deployment: http://<EC2-PUBLIC-IP>:8080
MySQL host port mapping: 3307 -> container port 3306

FILES ADDED
- Dockerfile: builds the PHP/Apache web image and installs mysqli.
- docker-compose.yml: runs web + MySQL, maps non-default host ports, and creates persistent database storage.
- .dockerignore: excludes unnecessary files from the image build.
- .env.example: template for local/EC2 environment settings.

QUICK START
1. Install and start Docker Desktop (Windows) or Docker Engine + Docker Compose plugin (Ubuntu).
2. Copy .env.example to .env.
3. Edit .env:
   - DOCKERHUB_USERNAME = your Docker Hub username
   - DB_PASSWORD = strong application DB password
   - MYSQL_ROOT_PASSWORD = a different strong root password
4. Build and test the image locally:
   docker build -t YOUR_DOCKERHUB_USERNAME/campus-event-management:1.0 .
   docker run --rm -p 8080:80 YOUR_DOCKERHUB_USERNAME/campus-event-management:1.0
   The app needs the MySQL service, so the standalone test above only verifies Apache/PHP starts; use Compose for the full functional test.
5. Push the image:
   docker login
   docker push YOUR_DOCKERHUB_USERNAME/campus-event-management:1.0
6. On EC2, copy this project folder (or at minimum docker-compose.yml, .env, and database/init.sql) to the server. Create .env with the same values and your Docker Hub username.
7. Run:
   docker compose pull
   docker compose up -d
8. Open http://EC2-PUBLIC-IP:8080 in your browser.

DATABASE PERSISTENCE
The named volume campus_db_data stores MySQL files outside the container lifecycle. The SQL seed file is automatically run only when MySQL initializes an empty data directory. Restarting/recreating containers will not erase the named volume. Do not run `docker compose down -v` unless you intentionally want to delete the database volume.

SECURITY
- Never upload the real .env file to GitHub or Docker Hub.
- For the assignment, if mapping MySQL host port 3307, restrict inbound TCP 3307 in the EC2 Security Group to your own public IP only. Do not allow 0.0.0.0/0 for MySQL.
- Public web access: inbound TCP 8080 from 0.0.0.0/0 (or restrict to your audience).
- Use strong unique passwords. This is a learning deployment, not a hardened production setup.
