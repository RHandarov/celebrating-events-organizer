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
        $follower_ids = $this->get_all_followers_of_user($row["id"]);
        $dates = $this->get_dates_of_user($row["id"]);
        return new \models\User($row["id"],
            $row["username"],
            $row["email"],
            $row["password"],
            $follower_ids,
            $dates);
    }

    public function find_user_by_id(int $user_id): ?\models\User {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM users WHERE id = ?"
            );

        $prepared_statement->bind_param("i", $user_id);
        $prepared_statement->execute();

        $result = $prepared_statement->get_result();

        if ($result->num_rows !== 1) {
            return null;
        }

        $row = $result->fetch_assoc();
        $follower_ids = $this->get_all_followers_of_user($row["id"]);
        $dates = $this->get_dates_of_user($row["id"]);
        return new \models\User(
            $row["id"],
            $row["username"],
            $row["email"],
            $row["password"],
            $follower_ids,
            $dates
        );
    }

    private function get_all_followers_of_user(int $user_id): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT followed_id FROM followers WHERE follower_id = ?"
            );

        $prepared_statement->bind_param("i", $user_id);
        $prepared_statement->execute();

        $result = $prepared_statement->get_result();
        $followers = [];
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break; // no nore rows or error occurred
            }

            array_push($followers, $row["followed_id"]);
        }

        return $followers;
    }

    private function get_dates_of_user(int $user_id): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT `date`, title FROM dates WHERE owner_id = ?"
            );

        $prepared_statement->bind_param("i", $user_id);
        $prepared_statement->execute();

        $dates = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break; // no nore rows or error occurred
            }

            array_push($dates, new \models\Date($row["date"], $row["title"]));
        }

        return $dates;
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

    public function follow_user(int $follower_id, int $followed_id): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO followers (follower_id, followed_id)
                VALUES (?, ?)"
            );

        $prepared_statement->bind_param("ii", $follower_id, $followed_id);
        $prepared_statement->execute();
    }

    public function unfollow_user(int $follower_id, int $followed_id): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "DELETE FROM followers WHERE follower_id = ? AND followed_id = ?"
            );

        $prepared_statement->bind_param("ii", $follower_id, $followed_id);
        $prepared_statement->execute();
    }

    public function are_users_already_following(int $follower_id, int $followed_id): bool {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?"
            );

        $prepared_statement->bind_param("ii", $follower_id, $followed_id);
        $prepared_statement->execute();

        if ($prepared_statement->get_result()->num_rows > 0) {
            return true;
        }

        return false;
    }

    public function add_date(int $user_id, string $date, string $title): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO dates (owner_id, `date`, title)
                VALUES (?, ?, ?)"
            );

        $prepared_statement->bind_param("iss", $user_id, $date, $title);
        var_dump($prepared_statement->execute());

        echo "<pre>";
        print_r($prepared_statement);
        echo "</pre>";
    }
}