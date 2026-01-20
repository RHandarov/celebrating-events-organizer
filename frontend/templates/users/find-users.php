<main id="find-users" class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4">Намери приятели</h2>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="ps-4">Потребител</th>
                                <th scope="col">Имейл</th>
                                <th scope="col" class="text-end pe-4">Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_users as $user): ?>
                                <tr>
                                    <td class="align-middle ps-4">
                                        <strong><?php echo htmlspecialchars($user->get_username()); ?></strong>
                                    </td>
                                    <td class="align-middle">
                                        <?php echo htmlspecialchars($user->get_email()); ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php 
                                        if (in_array($user->get_id(), $following_ids)): 
                                        ?>
                                            <form method="POST" action="/user/unfollow" class="d-inline">
                                                <input type="hidden" name="followed_id" value="<?php echo $user->get_id(); ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    Отследвай
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="/user/follow" class="d-inline">
                                                <input type="hidden" name="followed_id" value="<?php echo $user->get_id(); ?>">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    + Последвай
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if (count($all_users) === 0): ?>
                        <div class="p-4 text-center text-muted">
                            Все още няма други регистрирани потребители.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>