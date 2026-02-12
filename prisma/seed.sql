INSERT INTO User (id, name, email, password, role, createdAt, updatedAt) VALUES 
('admin', 'Admin User', 'admin@example.com', '$2b$12$4ugxMxYlOh5rUQAPQcb4MeXM1jnSGHWufJvT7rRJg.g/xAN0GW9Tq', 'ADMIN', NOW(), NOW()),
('teacher', 'Bu Guru', 'teacher@example.com', '$2b$12$4ugxMxYlOh5rUQAPQcb4MeXM1jnSGHWufJvT7rRJg.g/xAN0GW9Tq', 'TEACHER', NOW(), NOW());

INSERT INTO Class (id, name, teacherId, createdAt, updatedAt) VALUES 
('class1', 'Kelas 12 IPS 1', 'teacher', NOW(), NOW());

INSERT INTO User (id, name, email, password, role, classId, createdAt, updatedAt) VALUES 
('student', 'Budi Santoso', 'student@example.com', '$2b$12$4ugxMxYlOh5rUQAPQcb4MeXM1jnSGHWufJvT7rRJg.g/xAN0GW9Tq', 'STUDENT', 'class1', NOW(), NOW());

INSERT INTO Journal (id, userId, title, content, mood, createdAt, updatedAt) VALUES
('j1', 'student', 'Hari yang berat', 'Saya merasa sangat lelah.', 'SAD', NOW(), NOW()),
('j2', 'student', 'Lumayan', 'Hari ini biasa saja.', 'NEUTRAL', NOW(), NOW()),
('j3', 'student', 'Senang sekali', 'Dapat nilai bagus.', 'HAPPY', NOW(), NOW());
