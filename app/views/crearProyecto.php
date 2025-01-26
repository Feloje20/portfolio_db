<div class="proyecto"></div>
    <input type="text" name="proyectos[titulo]" placeholder="Título del Proyecto 1" class="search-input" required value="<?php echo isset($data['titulo']) ? $data['titulo'] : '' ?>">
    <input type="text" name="proyectos[logo]" placeholder="Logo del Proyecto 1" class="search-input" required value="<?php echo isset($data['logo']) ? $data['logo'] : '' ?>">
    <input type="text" name="proyectos[tecnologias]" placeholder="Tecnologías del Proyecto 1" class="search-input" required value="<?php echo isset($data['tecnologias']) ? $data['tecnologias'] : '' ?>">
    <label>
    Visible en el portfolio:
    <input type="checkbox" name="proyectos[visible]" value="1" <?php echo isset($data['visible']) && $data['visible'] ? 'checked' : '' ?>>
    </label>
</div>