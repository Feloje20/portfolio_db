<div class="skill"></div>
    <input type="text" name="skills[habilidades]" placeholder="[Programacion, organizacion, ingles...]" class="search-input">
    <label>
        Visible en el portfolio:
        <input type="checkbox" name="skills[visible]" value="1">
    </label>
    <label>
    Categorías:
        <select name="skills[categoria]" class="search-input">
            <?php foreach ($data['categorias'] as $categoria => $valor): ?>
                <option value="<?php echo $valor['categoria']; ?>">
                    <?php echo $valor['categoria']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</div>