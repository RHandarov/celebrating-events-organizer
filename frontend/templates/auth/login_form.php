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

        <label for="passowrd">Парола:</label>
        <input id="passowrd" name="passowrd" type="password" required>

        <button type="submit">Влез</button>
    </form>