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
        return new \models\User($row["id"],
            $row["username"],
            $row["email"],
            $row["password"]);
    }

    public function find_user_by_username(string $username): ?\models\User {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM users WHERE username = ?"
            );

        $prepared_statement->bind_param("s", $username, );
        $prepared_statement->execute();

        $result = $prepared_statement->get_result();

        if ($result->num_rows !== 1) {
            return null;
        }

        $row = $result->fetch_assoc();
        return new \models\User($row["id"],
            $row["username"],
            $row["email"],
            $row["password"]);
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
        return new \models\User(
            $row["id"],
            $row["username"],
            $row["email"],
            $row["password"]
        );
    }

    public function change_user(\models\User $updated_user): \models\User {
        $prepared_statement =
            $this->db_connection->prepare(
                "UPDATE users
                SET email = ?, `password` = ?
                WHERE id = ?"
            );

        $email = $updated_user->get_email();
        $password = $updated_user->get_password();
        $id = $updated_user->get_id();
        $prepared_statement->bind_param("ssi", $email, $password, $id);
        $prepared_statement->execute();

        return $updated_user;
    }

    public function get_all_followers_of_user(\models\User $user): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT followed_id FROM followers WHERE follower_id = ?"
            );

        $user_id = $user->get_id(); // because bind_param accepts only reference
        $prepared_statement->bind_param("i", $user_id);
        $prepared_statement->execute();

        $followers = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break; // no nore rows or error occurred
            }

            array_push($followers, $this->find_user_by_id($row["followed_id"]));
        }

        return $followers;
    }

    public function get_all_followed_of_user(\models\User $user): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT follower_id FROM followers WHERE followed_id = ?"
            );

        $user_id = $user->get_id(); // because bind_param accepts only reference
        $prepared_statement->bind_param("i", $user_id);
        $prepared_statement->execute();

        $followers = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break; // no nore rows or error occurred
            }

            array_push($followers, $this->find_user_by_id($row["follower_id"]));
        }

        return $followers;
    }

    public function get_dates_of_user(\models\User $user): array {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT id, `date`, title FROM dates WHERE owner_id = ?"
            );

        $user_id = $user->get_id(); // because bind_param accepts only reference
        $prepared_statement->bind_param("i", $user_id);
        $prepared_statement->execute();

        $dates = [];

        $result = $prepared_statement->get_result();
        while (true) {
            $row = $result->fetch_assoc();

            if ($row === null || $row === false) {
                break; // no nore rows or error occurred
            }

            array_push($dates, new \models\Date(
                $row["id"],
                $user,
                $row["date"],
                $row["title"]
            ));
        }

        return $dates;
    }

    public function add_user(string $username, string $email, string $password_hash): \models\User {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO users (username, email, password)
                VALUES (?, ?, ?)"
            );

        $prepared_statement->bind_param("sss", $username, $email, $password_hash);
        $prepared_statement->execute();

        return new \models\User(
            $prepared_statement->insert_id,
            $username,
            $email,
            $password_hash
        );
    }

    public function follow_user(\models\User $follower, \models\User $followed): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO followers (follower_id, followed_id)
                VALUES (?, ?)"
            );

        $follower_id = $follower->get_id();
        $followed_id = $followed->get_id();
        $prepared_statement->bind_param("ii", $follower_id, $followed_id);
        $prepared_statement->execute();
    }

    public function unfollow_user(\models\User $follower, \models\User $followed): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "DELETE FROM followers WHERE follower_id = ? AND followed_id = ?"
            );

        $follower_id = $follower->get_id();
        $followed_id = $followed->get_id();
        $prepared_statement->bind_param("ii", $follower_id, $followed_id);
        $prepared_statement->execute();
    }

    public function are_users_already_following(\models\User $follower, \models\User $followed): bool {
        $prepared_statement =
            $this->db_connection->prepare(
                "SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?"
            );

        $follower_id = $follower->get_id();
        $followed_id = $followed->get_id();
        $prepared_statement->bind_param("ii", $follower_id, $followed_id);
        $prepared_statement->execute();

        if ($prepared_statement->get_result()->num_rows > 0) {
            return true;
        }

        return false;
    }

    public function add_date(\models\User $user, string $date, string $title): \models\Date {
        $prepared_statement =
            $this->db_connection->prepare(
                "INSERT INTO dates (owner_id, `date`, title)
                VALUES (?, ?, ?)"
            );

        $user_id = $user->get_id();
        $prepared_statement->bind_param("iss", $user_id, $date, $title);
        $prepared_statement->execute();

        return new \models\Date(
            $prepared_statement->insert_id,
            $user,
            $date,
            $title
        );
    }

    public function change_date(\models\Date $changed_date): \models\Date {
        $prepared_statement =
            $this->db_connection->prepare(
                "UPDATE dates
                SET `date` = ?, title = ?
                WHERE id = ?"
            );

        $date = $changed_date->get_date();
        $title = $changed_date->get_title();
        $id = $changed_date->get_id();
        $prepared_statement->bind_param("ssi", $date, $title, $id);
        $prepared_statement->execute();

        return $changed_date;
    }

    public function delete_date(\models\Date $date): void {
        $prepared_statement =
            $this->db_connection->prepare(
                "DELETE FROM dates WHERE id = ?"
            );

        $date_id = $date->get_id();
        $prepared_statement->bind_param("i", $date_id);
        $prepared_statement->execute();
    }
}