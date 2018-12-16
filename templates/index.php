<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
    <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <?php $filter =  $_SESSION['task_filter'];?>

    <nav class="tasks-switch">
        <a href="/?filter=all_tasks" class="tasks-switch__item <?php if($filter == 'all_tasks' ) : ?>tasks-switch__item--active<?php endif; ?>">Все задачи</a>
        <a href="/?filter=agenda" class="tasks-switch__item <?php if($filter == 'agenda' ) : ?>tasks-switch__item--active<?php endif; ?>">Повестка дня</a>
        <a href="/?filter=tomorrow" class="tasks-switch__item <?php if($filter == 'tomorrow' ) : ?>tasks-switch__item--active<?php endif; ?>">Завтра</a>
        <a href="/?filter=overdue" class="tasks-switch__item <?php if($filter == 'overdue' ) : ?>tasks-switch__item--active<?php endif; ?>">Просроченные</a>
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
                        <!--<input class="checkbox__input visually-hidden task__checkbox" type="checkbox"  name="<?/*=$tasks_value['task_id'];*/?>" <?php /*if ( $tasks_value['task_status'] == '1'): */?> checked --><?php /*endif; */?>
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" <?php if ( $tasks_value['task_status'] == '1'): ?> checked <?php endif; ?> value="<?=$tasks_value['task_id'];?>">
                        <span class="checkbox__text"><?=esc($tasks_value['task_name']); ?></span>
                    </label>
                </td>
                <td class="task__date"><?php deadline($tasks_value['deadline']); ?></td>
                <td class="task__controls"><a href="<?php echo $tasks_value['file_atach']; ?> " target="_blank"> <?php if(isset($tasks_value['file_atach'])){echo 'файл';} ?> </a></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>