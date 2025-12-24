<?php
    if (isset($errors)) {
        foreach ($errors as $error) {
            echo "<h1>" . $error . "</h1>";
        }
    }
?>
    <form method="post">
        <label for="username">Потребителско име:</label>
        <input id="username" name="username" type="text" maxlength="30" required>

        <label for="email">Имейл:</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Парола:</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Регистрирай се</button>
    </form>