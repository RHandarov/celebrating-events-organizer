<main id="gifts-form">
    <?php
        if (isset($errors)) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>
        <?php
            if ($edit_mode) {
                echo "Редактиране";
            } else {
                echo "Добавяне";
            }
        ?>
        на подарък
    </h2>

    <form method="POST">
        <label for="description">Описание на подаръка</label>
        <input id="description"
            name="description"
            type="text"
            required
            maxlength="512"
            value="<?php
                if ($edit_mode) {
                    echo $gift->get_description();
                } else {
                    echo "";
                }
            ?>">

        <button type="submit">
            <?php
                if ($edit_mode) {
                    echo "Редактирай";
                } else {
                    echo "Добави";
                }
            ?>
        </button>
    </form>
</main>
