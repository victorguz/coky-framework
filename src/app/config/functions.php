<?php

/**
 * functions.php
 */

use App\Controller\PublicAreaController;
use SVG\SVG;

/**
 * Funciones adicionales.
 * En este este archivo se puede añadir cualquier función adicional.
 * Puede hacerse uso de todas las funciones del sistema.
 */

/**
 * menu_header_items
 *
 * Devuelve un string con los ítems del menú desplegable del header
 *
 * @param \stdClass $user
 * @return string
 */
function menu_header_items(\stdClass $user): string
{
    $items = get_config('menus')['header_dropdown'];
    return $items->getHtml();
}

/**
 * menu_sidebar_items
 *
 * Devuelve un string con los ítems del menú lateral
 *
 * @param \stdClass $user
 * @return string
 */
function menu_sidebar_items(\stdClass $user): string
{
    $groups = get_config('menus')['sidebar'];
    return $groups->getHtml();
}

/**
 * datatables_proccessing_with_options
 *
 * @param array $options
 *
 * @var $options[request] \Slim\Http\Request, required
 * @var $options[mapper] \PiecesPHP\Core\Database\EntityMapper, required
 * @var $options[columns_order] array, required
 * @var $options[where_string] string
 * @var $options[on_set_data] callable Recibe por parámetro el elemento actual y debe devolver el valor que corresponderá a la fila
 * @var $options[as_mapper] bool
 * @var $options[on_set_model] callable
 * @return \PiecesPHP\Core\Utilities\ReturnTypes\ResultOperations
 */
function datatables_proccessing_with_options(array $options)
{
    return \PiecesPHP\Core\Utilities\Helpers\DataTablesHelper::process($options);
}

/**
 * datatables_proccessing
 *
 * Devuelve un string con la estructura de un orderBy para un EntityMapper
 *
 * @param \Slim\Http\Request $request
 * @param \PiecesPHP\Core\Database\EntityMapper $mapper
 * @param array $columns_order
 * @param string $where_string
 * @param callable $on_set_data Recibe por parámetro el elemento actual y debe devolver el valor que corresponderá a la fila
 * @param bool $as_mapper
 * @param callable $on_set_model
 * @return \PiecesPHP\Core\Utilities\ReturnTypes\ResultOperations
 */
function datatables_proccessing(
    \Slim\Http\Request $request,
    \PiecesPHP\Core\Database\EntityMapper $mapper,
    array $columns_order,
    string $where_string = null,
    callable $on_set_data = null,
    bool $as_mapper = false,
    callable $on_set_model = null
): \PiecesPHP\Core\Utilities\ReturnTypes\ResultOperations {
    return \PiecesPHP\Core\Utilities\Helpers\DataTablesHelper::process([
        'request' => $request,
        'mapper' => $mapper,
        'columns_order' => $columns_order,
        'where_string' => $where_string,
        'on_set_data' => $on_set_data,
        'as_mapper' => $as_mapper,
        'on_set_model' => $on_set_model,
    ]);
}

/**
 * array_to_html_options
 *
 * Devuelve un string con la estructura de un orderBy para un EntityMapper
 *
 * @param array $values
 * @param mixed $selected_values
 * @param bool $multiple
 * @param bool $key_as_value
 * @return string
 */
function array_to_html_options(array $values, $selected_values = null, bool $multiple = false, bool $key_as_value = true)
{
    foreach ($values as $key => $value) {
        if (!is_scalar($key) || !is_scalar($value)) {
            unset($values[$key]);
        }
    }

    if (!$key_as_value) {
        $values = array_flip($value);
    }

    $selected_values = is_array($selected_values) && !is_null($selected_values) ? $selected_values : [$selected_values];
    $has_selected_values = !is_null($selected_values);

    foreach ($selected_values as $key => $value) {
        if (!is_scalar($key) || !is_scalar($value)) {
            unset($selected_values[$key]);
        }
    }

    $options = [];

    $selected_setted = false;

    foreach ($values as $value => $display) {

        $option = new \PiecesPHP\Core\HTML\HtmlElement('option', $display);

        $option->setAttribute('value', (string) $value);

        if ($has_selected_values && in_array($value, $selected_values)) {

            if (!$selected_setted || $multiple) {

                $option->setAttribute('selected', '');
                $selected_setted = true;
            }
        }

        $options[] = (string) $option;
    }

    return trim(implode("\r\n", $options));
}

/**
 * genericViewRoute
 *
 * @param string $name
 * @return string
 */
function genericViewRoute(string $name)
{

    return PublicAreaController::routeName('generic', [
        'name' => $name,
    ]);
}

/**
 * genericView2Route
 *
 * @param string $folder
 * @param string $name
 * @return string
 */
function genericView2Route(string $folder, string $name)
{

    return PublicAreaController::routeName('generic-2', [
        'folder' => $folder,
        'name' => $name,
    ]);
}



/**
 * Convierte la primera letra de cada palabra en mayuscula
 */
function strtitlecase(string $cad)
{
    $cad = clean_string($cad);
    if ($cad) {
        $cad = strtolower($cad);
        $arr = explode(" ", $cad);
        $cad = "";
        foreach ($arr as $value) {
            if (strlen($value) > 0 && strlen($value) == 1) {
                $cad .= " " . $value;
            } else {
                $cad .= " " . strtoupper($value[0] ? $value[0] : "") . substr($value, 1);
            }
        }
    }
    return $cad;
}


/**
 * Convierte la primera letra de una cadena en mayuscula
 */
function strphrasecase(string $cad)
{
    $cad = clean_string($cad);
    if ($cad) {
        $cad = strtoupper($cad[0]) . strtolower(strlen($cad) > 1 ? substr($cad, 1) : "");
    }
    return $cad;
}
/**
 * Crea un HtmlOption.
 * El campo method debe ser 'titlecase', 'phrasecase', 'uppercase', 'lowercase'.
 */
function createOption($value, string $description, bool $selectedValue = false, string $method = "titlecase")
{
    if ($description != "") {
        $description = trim($description);
    } else {
        throw new Exception("Debe proporcionar una descripción para el HtmlOption");
    }
    $selected =  $selectedValue === true ? "selected" : "";
    if (is_string($method)) {

        switch ($method) {
            case "titlecase":
                $description = strtitlecase($description);
                break;
            case "phrasecase":
                $description = strphrasecase($description);
                break;
            case "uppercase":
                $description = strtoupper($description);
                break;
            case "lowercase":
                $description = strtolower($description);
                break;
        }
    } else {
        throw new Exception("El campo method debe ser un string, 'titlecase', 'phrasecase', 'uppercase', 'lowercase'.");
    }
    return "<option value='$value' $selected>$description</option>";
}
/**
 * Crea un select a partir de un array. Se debe especificar el nombre del select, el valuekey y la descripcion.
 * La descripción en arreglo será concatenada en una sola
 * El campo method debe ser 'titlecase', 'phrasecase', 'uppercase', 'lowercase'.
 */
function createSelect(array $data, string $name, string $valueName, $descriptionName, string $method = "titlecase", bool $exceptions = false)
{

    $select = `<select name="$name" class="ui dropdown">
    
    `;
    $tempDescription = "";


    foreach ($data as $key => $object) {
        if (!is_array($object)) {
            $object = (array) $object;
        }
        if (is_array($descriptionName)) {
            foreach ($descriptionName as $key => $value) {
                if (
                    isset($object[$value])
                    && $object[$value] != null
                    && $object[$value] != ""
                ) {
                    $tempDescription .= $object[$value] . " ";
                }
            }
        }
        if (
            isset($object[$valueName])
            && $object[$valueName] != null
            && $object[$valueName] != ""
        ) {

            if (!is_array($descriptionName) && $exceptions && $tempDescription == "" && (!isset($object[$descriptionName]) || $object[$descriptionName] == null || $object[$descriptionName] == "")) {
                throw new Exception("El campo descriptionName no existe en el arreglo dado");
            } else if (!is_array($descriptionName) && isset($object[$descriptionName])) {
                $object[$descriptionName] = "";
            }
            $tempDescription = $tempDescription ? $tempDescription : (!is_array($descriptionName) ? $object[$descriptionName] : "");
            $select .= createOption($object[$valueName], $tempDescription, false, $method);
        } else if ($exceptions) {
            throw new Exception("El campo valueName no existe en el arreglo dado");
        }
    }
    $select .= "</select>";
    var_dump($select);
    return $select;
}

/**
 * Guarda los archivos subidos pasando un arreglo con los parámetros de $_FILES
 * @return $file-paths array
 */
function saveFiles(array $new_files = [], array $old_files = [], $folder = "anexo", $deleteOlds = false)
{
    $temp_files = [];
    if (is_array($new_files["name"])) {
        $i = 0;
        foreach ($new_files["name"] as $new) {
            $new = ["name" => $new_files["name"][$i], "tmp_name" => $new_files["tmp_name"][$i]];
            $file = saveFile($new, $folder);
            if ($file) {
                array_push($temp_files, $file);
            }
            $i++;
        }
    } else {
        $file = saveFile($new_files, $folder);
        if ($file) {
            array_push($temp_files, $file);
        }
    }

    foreach ($old_files as $item) {
        if (file_exists($item->route)) {
            if ($deleteOlds) {
                unlink($item->route);
            } else {
                array_push($temp_files, $item);
            }
        }
    }
    return $temp_files;
}


/**
 * Guarda los archivos subidos pasando un arreglo con los parámetros de $_FILES
 * @return $file-paths array
 */
function saveFile(array $new_file = [],  $folder = "anexo")
{
    $temp_file = null;

    if ($new_file["name"] && !is_null($new_file["name"]) && $new_file["name"] != "") {
        $directoryUrl = "statics/uploads/" . $folder;
        $directory = basepath($directoryUrl);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // $new_file["name"] = remove_accents($new_file["name"]);
        $ext = substr($new_file["name"], strrpos($new_file["name"], '.') + 1);
        $newname = uniqid();
        // $newname = str_replace("-", "", $newname);

        if (file_exists($directory . "/" . $newname)) {
            $newname .= uniqid();
        }

        $newname .= ".{$ext}";

        move_uploaded_file($new_file['tmp_name'], $directory . "/" . $newname);

        if (file_exists($directory . "/" . $newname)) {
            $temp_file = [
                "extension" => $ext,
                "name" => str_replace(".{$ext}", "", $new_file["name"]),
                "route" => $directoryUrl . "/" . $newname,
                "status" => 1
            ];
        }
    }
    return $temp_file;
}

function remove_accents($string)
{
    if (!preg_match('/[\x80-\xff]/', $string))
        return $string;

    $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195) . chr(128) => 'A', chr(195) . chr(129) => 'A',
        chr(195) . chr(130) => 'A', chr(195) . chr(131) => 'A',
        chr(195) . chr(132) => 'A', chr(195) . chr(133) => 'A',
        chr(195) . chr(135) => 'C', chr(195) . chr(136) => 'E',
        chr(195) . chr(137) => 'E', chr(195) . chr(138) => 'E',
        chr(195) . chr(139) => 'E', chr(195) . chr(140) => 'I',
        chr(195) . chr(141) => 'I', chr(195) . chr(142) => 'I',
        chr(195) . chr(143) => 'I', chr(195) . chr(145) => 'N',
        chr(195) . chr(146) => 'O', chr(195) . chr(147) => 'O',
        chr(195) . chr(148) => 'O', chr(195) . chr(149) => 'O',
        chr(195) . chr(150) => 'O', chr(195) . chr(153) => 'U',
        chr(195) . chr(154) => 'U', chr(195) . chr(155) => 'U',
        chr(195) . chr(156) => 'U', chr(195) . chr(157) => 'Y',
        chr(195) . chr(159) => 's', chr(195) . chr(160) => 'a',
        chr(195) . chr(161) => 'a', chr(195) . chr(162) => 'a',
        chr(195) . chr(163) => 'a', chr(195) . chr(164) => 'a',
        chr(195) . chr(165) => 'a', chr(195) . chr(167) => 'c',
        chr(195) . chr(168) => 'e', chr(195) . chr(169) => 'e',
        chr(195) . chr(170) => 'e', chr(195) . chr(171) => 'e',
        chr(195) . chr(172) => 'i', chr(195) . chr(173) => 'i',
        chr(195) . chr(174) => 'i', chr(195) . chr(175) => 'i',
        chr(195) . chr(177) => 'n', chr(195) . chr(178) => 'o',
        chr(195) . chr(179) => 'o', chr(195) . chr(180) => 'o',
        chr(195) . chr(181) => 'o', chr(195) . chr(182) => 'o',
        chr(195) . chr(182) => 'o', chr(195) . chr(185) => 'u',
        chr(195) . chr(186) => 'u', chr(195) . chr(187) => 'u',
        chr(195) . chr(188) => 'u', chr(195) . chr(189) => 'y',
        chr(195) . chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196) . chr(128) => 'A', chr(196) . chr(129) => 'a',
        chr(196) . chr(130) => 'A', chr(196) . chr(131) => 'a',
        chr(196) . chr(132) => 'A', chr(196) . chr(133) => 'a',
        chr(196) . chr(134) => 'C', chr(196) . chr(135) => 'c',
        chr(196) . chr(136) => 'C', chr(196) . chr(137) => 'c',
        chr(196) . chr(138) => 'C', chr(196) . chr(139) => 'c',
        chr(196) . chr(140) => 'C', chr(196) . chr(141) => 'c',
        chr(196) . chr(142) => 'D', chr(196) . chr(143) => 'd',
        chr(196) . chr(144) => 'D', chr(196) . chr(145) => 'd',
        chr(196) . chr(146) => 'E', chr(196) . chr(147) => 'e',
        chr(196) . chr(148) => 'E', chr(196) . chr(149) => 'e',
        chr(196) . chr(150) => 'E', chr(196) . chr(151) => 'e',
        chr(196) . chr(152) => 'E', chr(196) . chr(153) => 'e',
        chr(196) . chr(154) => 'E', chr(196) . chr(155) => 'e',
        chr(196) . chr(156) => 'G', chr(196) . chr(157) => 'g',
        chr(196) . chr(158) => 'G', chr(196) . chr(159) => 'g',
        chr(196) . chr(160) => 'G', chr(196) . chr(161) => 'g',
        chr(196) . chr(162) => 'G', chr(196) . chr(163) => 'g',
        chr(196) . chr(164) => 'H', chr(196) . chr(165) => 'h',
        chr(196) . chr(166) => 'H', chr(196) . chr(167) => 'h',
        chr(196) . chr(168) => 'I', chr(196) . chr(169) => 'i',
        chr(196) . chr(170) => 'I', chr(196) . chr(171) => 'i',
        chr(196) . chr(172) => 'I', chr(196) . chr(173) => 'i',
        chr(196) . chr(174) => 'I', chr(196) . chr(175) => 'i',
        chr(196) . chr(176) => 'I', chr(196) . chr(177) => 'i',
        chr(196) . chr(178) => 'IJ', chr(196) . chr(179) => 'ij',
        chr(196) . chr(180) => 'J', chr(196) . chr(181) => 'j',
        chr(196) . chr(182) => 'K', chr(196) . chr(183) => 'k',
        chr(196) . chr(184) => 'k', chr(196) . chr(185) => 'L',
        chr(196) . chr(186) => 'l', chr(196) . chr(187) => 'L',
        chr(196) . chr(188) => 'l', chr(196) . chr(189) => 'L',
        chr(196) . chr(190) => 'l', chr(196) . chr(191) => 'L',
        chr(197) . chr(128) => 'l', chr(197) . chr(129) => 'L',
        chr(197) . chr(130) => 'l', chr(197) . chr(131) => 'N',
        chr(197) . chr(132) => 'n', chr(197) . chr(133) => 'N',
        chr(197) . chr(134) => 'n', chr(197) . chr(135) => 'N',
        chr(197) . chr(136) => 'n', chr(197) . chr(137) => 'N',
        chr(197) . chr(138) => 'n', chr(197) . chr(139) => 'N',
        chr(197) . chr(140) => 'O', chr(197) . chr(141) => 'o',
        chr(197) . chr(142) => 'O', chr(197) . chr(143) => 'o',
        chr(197) . chr(144) => 'O', chr(197) . chr(145) => 'o',
        chr(197) . chr(146) => 'OE', chr(197) . chr(147) => 'oe',
        chr(197) . chr(148) => 'R', chr(197) . chr(149) => 'r',
        chr(197) . chr(150) => 'R', chr(197) . chr(151) => 'r',
        chr(197) . chr(152) => 'R', chr(197) . chr(153) => 'r',
        chr(197) . chr(154) => 'S', chr(197) . chr(155) => 's',
        chr(197) . chr(156) => 'S', chr(197) . chr(157) => 's',
        chr(197) . chr(158) => 'S', chr(197) . chr(159) => 's',
        chr(197) . chr(160) => 'S', chr(197) . chr(161) => 's',
        chr(197) . chr(162) => 'T', chr(197) . chr(163) => 't',
        chr(197) . chr(164) => 'T', chr(197) . chr(165) => 't',
        chr(197) . chr(166) => 'T', chr(197) . chr(167) => 't',
        chr(197) . chr(168) => 'U', chr(197) . chr(169) => 'u',
        chr(197) . chr(170) => 'U', chr(197) . chr(171) => 'u',
        chr(197) . chr(172) => 'U', chr(197) . chr(173) => 'u',
        chr(197) . chr(174) => 'U', chr(197) . chr(175) => 'u',
        chr(197) . chr(176) => 'U', chr(197) . chr(177) => 'u',
        chr(197) . chr(178) => 'U', chr(197) . chr(179) => 'u',
        chr(197) . chr(180) => 'W', chr(197) . chr(181) => 'w',
        chr(197) . chr(182) => 'Y', chr(197) . chr(183) => 'y',
        chr(197) . chr(184) => 'Y', chr(197) . chr(185) => 'Z',
        chr(197) . chr(186) => 'z', chr(197) . chr(187) => 'Z',
        chr(197) . chr(188) => 'z', chr(197) . chr(189) => 'Z',
        chr(197) . chr(190) => 'z', chr(197) . chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}
/**
 * Da formato de moneda a un valor numérico
 */
function moneyFormat($value)
{
    if ($value != null) {
        return "$" . $value;
    } else {
        return $value;
    }
}


/**
 * Devuelve un HTML utilizando los íconos de la librería IONICONS
 */
function getIcon(string $name = "home",  string $color = "", string $sizePx = null, string $class = null)
{
    if ($name != "") {
        $name = str_replace(" ", "-", trim($name));
        $file = "statics/images/ionicons/" . $name . ".svg";

        if (file_exists($file)) {
            $image = SVG::fromFile($file);

            if ($color != "") {
                $doc = $image->getDocument();
                // var_dump($doc->countChildren());
                for ($i = 0; $i < $doc->countChildren() - 1; $i++) {
                    $rect = $doc->getChild($i);
                    // var_dump($rect);
                    if (strpos($name, "outline") === false) {
                        $rect->setStyle('fill', $color);
                        $rect->setAttribute('fill', $color);
                    }
                    $rect->setStyle('stroke', $color);
                    $rect->setAttribute('stroke', $color);
                }
            }

            if ($sizePx != null && is_string($sizePx)) {
                $item = "<div class='ionicono " . ($class != null ? $class : "") . "' style='width:$sizePx; height:$sizePx;'>$image</div>";
            } else {
                $item = "<div class='ionicono " . ($class != null ? $class : "") . "'>$image</div>";
            }
            return $item;
        } else {
            return "(icon)";
        }
    } else {
        return "(icon)";
    }
}
