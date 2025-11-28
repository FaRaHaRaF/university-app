

CREATE DATABASE IF NOT EXISTS university_db;
USE university_db;

-- Students table (Exercise 4)
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fullname VARCHAR(100) NOT NULL,
    matricule VARCHAR(20) NOT NULL UNIQUE,
    group_id VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table (Exercise 5)
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) NOT NULL UNIQUE
);

-- Professors table (Exercise 5)
CREATE TABLE IF NOT EXISTS professors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100)
);

-- Attendance sessions table (Exercise 5)
CREATE TABLE IF NOT EXISTS attendance_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    group_id VARCHAR(10) NOT NULL,
    date DATE NOT NULL,
    opened_by INT NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (opened_by) REFERENCES professors(id)
);

-- Attendance records table
CREATE TABLE IF NOT EXISTS attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    status ENUM('present', 'absent') NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES attendance_sessions(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    UNIQUE KEY unique_attendance (session_id, student_id)
);

-- Sample data for testing
INSERT INTO courses (name, code) VALUES 
('Advanced Web Programming', 'AWP'),
('Database Systems', 'DBS'),
('Software Engineering', 'SE');

INSERT INTO professors (name, email) VALUES 
('Dr. Ahmed Zaki', 'ahmed.zaki@university.dz'),
('Dr. Fatima Mohammed', 'fatima.mohammed@university.dz');

INSERT INTO students (fullname, matricule, group_id) VALUES 
('John Smith', '2024001', 'ISIL-01'),
('Sarah Johnson', '2024002', 'ISIL-01'),
('Mike Brown', '2024003', 'ISIL-02');
