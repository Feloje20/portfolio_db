<input type="text" name="skills[habilidades]" placeholder="[Programacion, organizacion, ingles...]" class="search-input" required value="<?php echo isset($data['habilidades']) ? $data['habilidades'] : '' ?>">
<label>
Categoría
    <select name="skills[categoria]" class="search-input">
        <?php foreach ($data['categorias'] as $categoria => $valor): ?>
            <option value="<?php echo $valor['categoria']; ?>" <?php echo isset($data['categorias_skills_categoria']) && $data['categorias_skills_categoria'] == $valor['categoria'] ? 'selected' : '' ?>>
                <?php echo $valor['categoria']; ?>
            </option>
        <?php endforeach; ?>
    </select>
</label>
<label>
    Visible en el portfolio:
    <input type="checkbox" name="skills[visible]" value="1" <?php echo isset($data['visible']) && $data['visible'] ? 'checked' : '' ?>>
</label>
