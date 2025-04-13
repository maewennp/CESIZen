-- Suppression des tables si elles existent déjà (dans le bon ordre pour les FK)
DROP TABLE IF EXISTS stress_response;
DROP TABLE IF EXISTS connexion_log;
DROP TABLE IF EXISTS emotion_tracker;
DROP TABLE IF EXISTS favorite;
DROP TABLE IF EXISTS breathing_session;
DROP TABLE IF EXISTS stress_diagnostic;
DROP TABLE IF EXISTS content_page;
DROP TABLE IF EXISTS emotion;
DROP TABLE IF EXISTS basic_emotion;
DROP TABLE IF EXISTS relax_activity;
DROP TABLE IF EXISTS breath_exercise;
DROP TABLE IF EXISTS scale_event;
DROP TABLE IF EXISTS holmes_rahe_scale_result;
DROP TABLE IF EXISTS users;

-- Création des tables
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE,
    terms_accepted BOOLEAN DEFAULT FALSE,
    accepted_terms_at TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    deleted_at TIMESTAMP
);

CREATE TABLE breath_exercise (
    id_exercise INT AUTO_INCREMENT PRIMARY KEY,
    exercise_label VARCHAR(255) NOT NULL,
    breath_in INT,
    breath_out INT,
    holding_breath INT,
    media_breath_exercise TEXT
);

CREATE TABLE breathing_session (
    id_session INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_exercise INT,
    id_user INT,
    FOREIGN KEY(id_exercise) REFERENCES breath_exercise(id_exercise) ON DELETE SET NULL,
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE relax_activity (
    id_activity INT AUTO_INCREMENT PRIMARY KEY,
    activity_label VARCHAR(255) NOT NULL,
    content TEXT,
    category VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    type VARCHAR(255),
    media_activity TEXT
);

CREATE TABLE favorite (
    id_favorite INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    still_fav BOOLEAN DEFAULT TRUE,
    id_activity INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY(id_activity) REFERENCES relax_activity(id_activity) ON DELETE CASCADE,
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE basic_emotion (
    id_basic_emo INT AUTO_INCREMENT PRIMARY KEY,
    basic_emo_label VARCHAR(255) NOT NULL
);

CREATE TABLE emotion (
    id_emotion INT AUTO_INCREMENT PRIMARY KEY,
    emotion_label VARCHAR(255) NOT NULL,
    id_basic_emo INT NOT NULL,
    FOREIGN KEY(id_basic_emo) REFERENCES basic_emotion(id_basic_emo) ON DELETE CASCADE
);

CREATE TABLE emotion_tracker (
    id_emotion_tracker INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    intensity INT,
    comment TEXT,
    id_emotion INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY(id_emotion) REFERENCES emotion(id_emotion) ON DELETE CASCADE,
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE scale_event (
    id_event INT AUTO_INCREMENT PRIMARY KEY,
    event_label VARCHAR(255) NOT NULL,
    event_score INT
);

CREATE TABLE holmes_rahe_scale_result (
    id_result INT AUTO_INCREMENT PRIMARY KEY,
    result_label VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    min_score INT,
    max_score INT
);

CREATE TABLE stress_diagnostic (
    id_diagnostic INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_result INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY(id_result) REFERENCES holmes_rahe_scale_result(id_result) ON DELETE CASCADE,
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE connexion_log (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    date_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(255),
    id_user INT NOT NULL,
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE content_page (
    id_content INT AUTO_INCREMENT PRIMARY KEY,
    content_label VARCHAR(255) NOT NULL,
    body TEXT,
    media_content TEXT,
    visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_user INT,
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE SET NULL
);

CREATE TABLE stress_response (
    id_event INT,
    id_diagnostic INT,
    PRIMARY KEY(id_event, id_diagnostic),
    FOREIGN KEY(id_event) REFERENCES scale_event(id_event) ON DELETE CASCADE,
    FOREIGN KEY(id_diagnostic) REFERENCES stress_diagnostic(id_diagnostic) ON DELETE CASCADE
);