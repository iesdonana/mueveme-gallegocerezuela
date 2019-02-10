<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

class UsuariosController extends Controller
{
    /**
     * Borra a los usuarios no confirmados registrados hace mas de $dias dias.
     * @param mixed $dias Numeros de dias trás los que el script borra a un
     *                    usuario no confirmado.
     * @return int        Exit code
     */
    public function actionLimpiar($dias = 7)
    {
        if (!is_numeric($dias) || $dias < 0) {
            echo "Error: El parametro debe ser un número positivo\n";
            return ExitCode::IOERR;
        }

        $dateTime = new \DateTime();
        $dateTime = $dateTime->sub(new \DateInterval("P{$dias}D"))
            ->format('Y-m-d H:m:s');

        $borrados = \app\models\Usuarios::deleteAll(
            'confirmado = false AND created_at < :created_at',
            [':created_at' => $dateTime]
        );

        echo "Se han borrado $borrados usuarios\n";

        return ExitCode::OK;
    }
}
