<label for="titulo">Título del Trabajo</label>
<input type="text" id="titulo" name="trabajos[titulo]" placeholder="Título del Trabajo" class="search-input <?php echo !empty($data['trabajo']["msjErrorTitulo"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['trabajo']['titulo']) ? $data['trabajo']['titulo'] : '' ?>"><?php if ($data['trabajo']["msjErrorTitulo"] != "") echo "<p class='error'>" . $data['trabajo']["msjErrorTitulo"] . "</p>";?>

<label for="descripcion">Descripción del Trabajo</label>
<textarea id="descripcion" name="trabajos[descripcion]" placeholder="Descripción del Trabajo" class="search-input <?php echo !empty($data['trabajo']["msjErrorDescripcion"]) ? 'errorInput' : '';?>"><?php echo isset($data['trabajo']['descripcion']) ? $data['trabajo']['descripcion'] : '' ?></textarea><?php if ($data['trabajo']["msjErrorDescripcion"] != "") echo "<p class='error'>" . $data['trabajo']["msjErrorDescripcion"] . "</p>";?>

<label for="fecha_inicio">Fecha Inicio</label>
<input type="date" id="fecha_inicio" name="trabajos[fecha_inicio]" placeholder="Fecha Inicio" class="search-input <?php echo !empty($data['trabajo']["msjErrorFechaInicial"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['trabajo']['fecha_inicio']) ? $data['trabajo']['fecha_inicio'] : '' ?>"><?php if ($data['trabajo']["msjErrorFechaInicial"] != "") echo "<p class='error'>" . $data['trabajo']["msjErrorFechaInicial"] . "</p>";?>

<label for="fecha_final">Fecha Final</label>
<input type="date" id="fecha_final" name="trabajos[fecha_final]" placeholder="Fecha Final" class="search-input <?php echo !empty($data['trabajo']["msjErrorFechaFinal"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['trabajo']['fecha_final']) ? $data['trabajo']['fecha_final'] : '' ?>"><?php if ($data['trabajo']["msjErrorFechaFinal"] != "") echo "<p class='error'>" . $data['trabajo']["msjErrorFechaFinal"] . "</p>";?>

<label for="logros">Logros del Trabajo</label>
<input type="text" id="logros" name="trabajos[logros]" placeholder="Logros del Trabajo" class="search-input <?php echo !empty($data['trabajo']["msjErrorLogros"]) ? 'errorInput' : '';?>" value="<?php echo isset($data['trabajo']['logros']) ? $data['trabajo']['logros'] : '' ?>"><?php if ($data['trabajo']["msjErrorLogros"] != "") echo "<p class='error'>" . $data['trabajo']["msjErrorLogros"] . "</p>";?>

<label for="visible">Visible en el portfolio
<input type="checkbox" id="visible" name="trabajos[visible]" value="1" <?php echo isset($data['trabajo']['visible']) && $data['trabajo']['visible'] ? 'checked' : '' ?>>
</label>
