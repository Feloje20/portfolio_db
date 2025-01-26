<div class="trabajo">
    <input type="text" name="trabajos[titulo]" placeholder="Título del Trabajo " class="search-input" required value="<?php echo isset($data['titulo']) ? $data['titulo'] : '' ?>">
    <textarea name="trabajos[descripcion]" placeholder="Descripción del Trabajo " class="search-input" required><?php echo isset($data['descripcion']) ? $data['descripcion'] : '' ?></textarea>
    <input type="date" name="trabajos[fecha_inicio]" placeholder="Fecha Inicio" class="search-input" required value="<?php echo isset($data['fecha_inicio']) ? $data['fecha_inicio'] : '' ?>">
    <input type="date" name="trabajos[fecha_final]" placeholder="Fecha Final" class="search-input" required value="<?php echo isset($data['fecha_final']) ? $data['fecha_final'] : '' ?>">
    <input type="text" name="trabajos[logros]" placeholder="Logros del Trabajo " class="search-input" required value="<?php echo isset($data['logros']) ? $data['logros'] : '' ?>">
    <label>
    Visible en el portfolio:
    <input type="checkbox" name="trabajos[visible]" value="1" <?php echo isset($data['visible']) && $data['visible'] ? 'checked' : '' ?>>
    </label>
</div>