    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form"  action="add_project.php" method="post" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input <?php if (isset($errors['project_name'])): ?>form__input--error<?php endif; ?>" type="text" name="project_name" id="project_name" value="<?php if (isset($add_project['project_name'])){echo($add_project['project_name']);}?>" placeholder="Введите название проекта">
            <?php if (isset($errors['project_name'])): ?><p class="form__message"><?php echo $errors['project_name'];?></p><?php endif; ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>