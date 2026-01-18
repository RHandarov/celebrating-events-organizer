<main id="event-details">
    <header>
        <?php
            $action = "/event/leave";
            $text = "Отпиши се от събитието";

            if (!$is_logged_user_guest) {
                $action = "/event/enroll";
                $text = "Запиши се за събитието";
            }
        ?>

        <h2>Детайли за събитие <?php echo $event->get_title(); ?></h2>
        <form method="POST" action="<?php echo $action; ?>">
            <input type="hidden" name="event_id" value="<?php echo $event->get_id(); ?>">
            <button type="submit"><?php echo $text; ?></button>
        </form>
    </header>
    <p>Кога: <?php echo $event->get_celebrating_date(); ?></p>
    <p>Къде: <?php echo $event->get_location(); ?></p>
    <p>
        Организатор: <?php echo "<a href=\"/user/" . $event->get_organizer()->get_id() . "\">" . $event->get_organizer()->get_username() . "</a>"; ?>
    </p>
    <p>
        Организирано за: <?php echo "<a href=\"/user/" . $event->get_organized()->get_id() . "\">" . $event->get_organized()->get_username() . "</a>"; ?>
    </p>
    <p>Детайлно описаниe:</p>
    <p><?php echo $event->get_description(); ?></p>
    <h3>Гости</h3>
    <?php
        foreach ($guests as $guest) {
            echo "<p><a href=\"/user/" . $guest->get_id() . "\">";
            echo $guest->get_username();
            echo "</a></p>";
        }
    ?>
    <h3>Подаръци</h3>
    <form method="GET" action="/gift/add/<?php echo $event->get_id(); ?>">
        <button type="submit">Добави подарък</button>
    </form>
    <table class="table">
        <tr>
            <th scope="col">Подарък</th>
            <th scope="col">Отговорен за</th>
            <th scope="col">Действия</th>
        </tr>
        <?php
            foreach ($gifts as $gift) {
                echo "<tr>";
                echo "<td>" . $gift->get_description() . "</td>";
                echo "<td><a href=\"/user/" . $gift->get_assigned_guest()->get_id() . "\">" . $gift->get_assigned_guest()->get_username() . "</a></td>";
                if ($gift->get_assigned_guest()->get_id() === SessionManager::get_logged_user_id()) {
                    $actions = "<a href=\"/gift/edit/" . $gift->get_id() . "\">Редактирай</a> | <a href=\"/gift/delete/" . $gift->get_id() . "?back=" . $event->get_id() . "\">Изтрий</a>";
                    echo "<td>" . $actions . "</td>";
                } else {
                    echo "<td></td>";
                }
                echo "</tr>";
            }
        ?>
    </table>
</main>
