CREATE DATABASE IF NOT EXISTS campus_events;
USE campus_events;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    department VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    venue VARCHAR(150) NOT NULL,
    capacity INT NOT NULL DEFAULT 50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (student_id, event_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

INSERT INTO students (name,email,department) VALUES
('Ali Khan','ali@example.com','Computer Science'),
('Sara Ahmed','sara@example.com','Software Engineering'),
('Hamza Malik','hamza@example.com','Information Technology');

INSERT INTO events (title,description,event_date,venue,capacity) VALUES
('Cloud Computing Workshop','Hands-on introduction to cloud computing and AWS.','2026-10-15','Computer Lab 1',60),
('DevOps Seminar','CI/CD, containers and cloud deployment seminar.','2026-10-20','Auditorium',120),
('Programming Contest','University programming and problem-solving contest.','2026-10-25','Lab 2',80);

INSERT INTO registrations (student_id,event_id) VALUES
(1,1),(2,1),(3,2);
