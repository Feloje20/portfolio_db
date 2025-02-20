<input type="text" name="skills[habilidades]" placeholder="[Programacion, organizacion, ingles...]" class="search-input <?php echo !empty($data['skill'] ["msjErrorHabilidades"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['skill']['habilidades']) ? $data['skill']['habilidades'] : '' ?>"><?php if ($data['skill']["msjErrorHabilidades"] != "") echo "<p class='error'>" . $data['skill']["msjErrorHabilidades"] . "</p>";?>
<label>
Categoría
    <select name="skills[categoria]" class="search-input">
        <?php foreach ($data['categorias'] as $categoria => $valor): ?>
            <option value="<?php echo $valor['categoria']; ?>" <?php echo isset($data['skill']['categorias_skills_categoria']) && $data['skill']['categorias_skills_categoria'] == $valor['categoria'] ? 'selected' : '' ?>>
                <?php echo $valor['categoria']; ?>
            </option>
        <?php endforeach; ?>
    </select>
</label>
<label>
    Visible en el portfolio:
    <input type="checkbox" name="skills[visible]" value="1" <?php echo isset($data['skill']['visible']) && $data['skill']['visible'] ? 'checked' : '' ?>>
</label>
