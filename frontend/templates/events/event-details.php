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
</main>
