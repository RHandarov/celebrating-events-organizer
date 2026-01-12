<main id="event-details">
    <header>
        <h2>Детайли за събитие <?php echo $event->get_title(); ?></h2>
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
