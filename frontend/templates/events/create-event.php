<main id="create-event">
    <?php
        if (isset($errors) && count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>Организирай ново събитие</h2>

    <form method="POST" action="/event/create" novalidate>
        <label for="title">Заглавие на събитието:</label>
        <input type="text"
            id="title"
            name="title"
            required    
            maxlength="256"
            placeholder="Напр: Парти изненада за Мария">

        <label for="location">Място на провеждане:</label>
        <input type="text"
            id="location"
            name="location"
            required
            maxlength="256"
            placeholder="Напр: Пицария 'Верди', Център">

        <label for="date_id" class="form-label">Избери повод (Дата на приятел):</label>
        <select name="date_id" id="date_id" required>
            <option value="">-- Избери повод --</option>
            <?php
                if (isset($available_dates)) {
                    foreach ($available_dates as $date) {
                        $id = $date->get_id();
                        $title = htmlspecialchars($date->get_title()); 
                        $date_str = $date->get_date(); 
                        $owner_name = htmlspecialchars($date->get_owner()->get_username());

                        echo "<option value='$id'>$date_str - $title ($owner_name)</option>";
                    }
                }
            ?>
        </select>
        <small>Можеш да избираш само дати на потребители, които следваш.</small>

        <label for="description">Детайлно описание:</label>
        <textarea id="description"
            name="description"
            rows="5"
            required
            placeholder="Опиши какво ще се прави, кой е поканен и т.н."></textarea>

        <button type="submit">Създай събитие</button>
    </form>
    
    <script src="/js/validate-event.js"></script>
</main>
