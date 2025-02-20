<label>Título del Proyecto</label>
<input type="text" name="proyectos[titulo]" placeholder="Título del Proyecto" class="search-input <?php echo !empty($data['proyecto']["msjErrorTitulo"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['proyecto']['titulo']) ? $data['proyecto']['titulo'] : '' ?>"><?php if ($data['proyecto']["msjErrorTitulo"] != "") echo "<p class='error'>" . $data['proyecto']["msjErrorTitulo"] . "</p>";?>

<label>Logo del Proyecto</label>
<input type="file" id="proyecto_logo" name="proyecto_logo" accept="image/*">

<label>Tecnologías del Proyecto</label>
<input type="text" name="proyectos[tecnologias]" placeholder="Tecnologías del Proyecto" class="search-input <?php echo !empty($data['proyecto']["msjErrorTecnologias"]) ? 'errorInput' : '';?>"  value="<?php echo isset($data['proyecto']['tecnologias']) ? $data['proyecto']['tecnologias'] : '' ?>"><?php if ($data['proyecto']["msjErrorTecnologias"] != "") echo "<p class='error'>" . $data['proyecto']["msjErrorTecnologias"] . "</p>";?>

<label>
Visible en el portfolio:
<input type="checkbox" name="proyectos[visible]" value="1" <?php echo isset($data['proyecto']['visible']) && $data['proyecto']['visible'] ? 'checked' : '' ?>>
</label>
