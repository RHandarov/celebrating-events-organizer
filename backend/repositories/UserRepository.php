<?php

namespace repositories;

class UserRepository {
    private \mysqli $db_connection;

    public function __construct(\mysqli $db_connection) {
        $this->db_connection = $db_connection;
    }

    public function find_user_by_username_and_password(string $username,
        string $password_hash): ?\models\User {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM users WHERE username = ? AND password = ?"
            );

        $prepared_statement->bind_param("ss", $username, $password_hash);
        $prepared_statement->execute();

        $result = $prepared_statement->get_result();

        if ($result->num_rows !== 1) {
            return null;
        }

        $row = $result->fetch_assoc();
        $user = new \models\User($row["id"],
            $row["username"],
            $row["email"],
            $row["password"]);

        return $user;
    }

    public function add_user(string $username, string $email, string $password_hash): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO users (username, email, password)
                VALUES (?, ?, ?)"
            );

        $prepared_statement->bind_param("sss", $username, $email, $password_hash);
        $prepared_statement->execute();
    }
}