CREATE DATABASE IF NOT EXISTS bngrc;
\connect bngrc;

CREATE TABLE bgnrc_region (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

