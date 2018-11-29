<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks == 1): ?> checked <?php endif; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $tasks_key => $tasks_value): ?>
        <?php if ($show_complete_tasks == 1 OR $tasks_value['task_status'] == 0) : ?>
            <tr class="tasks__item task <?php if ( $tasks_value['task_status'] == 1): ?> task--completed <?php endif; ?> <?php if (leeway($tasks_value['deadline'])) : ?> task--important <?php endif; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" <?php if ( $tasks_value['task_status'] == '1'): ?> checked <?php endif; ?>>
                        <span class="checkbox__text"><?=esc($tasks_value['task_name']); ?></span>
                    </label>
                </td>
                <td class="task__date"><?php if($tasks_value['deadline']!='0000-00-00 00:00:00')
                    {$deadline_date = strtotime($tasks_value['deadline']);
                    echo date("d.m.Y",esc($deadline_date));}
                    else {echo "Нет";} ; ?></td>
                <td class="task__controls"> </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>