<main id="create-event" class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Организирай ново събитие</h2>
                </div>
                <div class="card-body">
                    
                    <?php
                        if (isset($errors) && count($errors) > 0) {
                            echo '<div class="alert alert-danger">';
                            echo '<ul class="mb-0">';
                            foreach ($errors as $error) {
                                echo "<li>" . $error . "</li>";
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                    ?>

                    <form method="POST" action="/event/create">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Заглавие на събитието:</label>
                            <input type="text" id="title" name="title" required class="form-control" placeholder="Напр: Парти изненада за Мария">
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Място на провеждане:</label>
                            <input type="text" id="location" name="location" required class="form-control" placeholder="Напр: Пицария 'Верди', Център">
                        </div>

                        <div class="mb-3">
                            <label for="date_id" class="form-label">Избери повод (Дата на приятел):</label>
                            <select name="date_id" id="date_id" required class="form-select form-control">
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
                            <small class="text-muted">Можеш да избираш само дати на потребители, които следваш.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Детайлно описание:</label>
                            <textarea id="description" name="description" rows="5" required class="form-control" placeholder="Опиши какво ще се прави, кой е поканен и т.н."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Създай събитие</button>
                            <a href="/all-events" class="btn btn-outline-secondary">Отказ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>