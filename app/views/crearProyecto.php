<label>Título del Proyecto</label>
<input type="text" name="proyectos[titulo]" placeholder="Título del Proyecto" class="search-input" required value="<?php echo isset($data['titulo']) ? $data['titulo'] : '' ?>">

<label>Logo del Proyecto</label>
<input type="file" id="proyecto_logo" name="proyecto_logo" accept="image/*">

<label>Tecnologías del Proyecto</label>
<input type="text" name="proyectos[tecnologias]" placeholder="Tecnologías del Proyecto" class="search-input" required value="<?php echo isset($data['tecnologias']) ? $data['tecnologias'] : '' ?>">

<label>
Visible en el portfolio:
<input type="checkbox" name="proyectos[visible]" value="1" <?php echo isset($data['visible']) && $data['visible'] ? 'checked' : '' ?>>
</label>
