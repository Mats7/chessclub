# Chess Club IS mini project

## Information system for chess club users
### Features:
- Authentication system (registration / login)
- Profile editing
- User list
- Game creation
- Game list
- Leaderboard of top 10 players

---
### Structure
- src/ == controller + repository + entity
- templates/ == html files
- assets/styles == css style

---
### Required:
  - PHP 8.3.3 / Composer / Symfony 7.0 / MySQL 5.7.44

---
### How to run:
- install dependencies: ```composer install```
- create a new database: ```CREATE DATABASE chessclubdb```
- import project database: ```mysql -u admin -p chessclubdb < chessclubdb.sql```
- run mysql ```mysql -u admin -p``` with password ```77777777```
- run the server: ```symfony server:start```
- Project should be available at ```localhost:8000``` in the browser

There is a one test account already created: username:test password:00000000
