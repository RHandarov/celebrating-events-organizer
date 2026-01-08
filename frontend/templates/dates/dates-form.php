<main id="dates-form">
    <?php
        if (isset($errors)) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>
        <?php
            if ($add_date === true) {
                echo "Добавяне ";
            } else {
                echo "Редактиране ";
            }
        ?>
        на дата
    </h2>

    <form method="POST">
        <label for="date">Дата</label>
        <input id="date"
            name="date"
            type="date"
            required
            value="<?php
                if ($add_date === true) {
                    echo "";
                } else {
                    echo $date->get_date();
                }
            ?>">

        <label for="title">Повод</label>
        <input id="title"
            name="title"
            type="text"
            required
            maxlength="100"
            value="<?php
                if ($add_date === true) {
                    echo "";
                } else {
                    echo $date->get_title();
                }
            ?>">

        <button>
            <?php
                if ($add_date === true) {
                    echo "Добави";
                } else {
                    echo "Редактирай";
                }
            ?>
        </button>
    </form>
</main>