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
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @param mixed $dias
     * @return int Exit code
     */
    public function actionLimpiar($dias = 7)
    {
        if (!is_numeric($dias)) {
            echo "Error: El parametro debe de ser un número\n";
            return ExitCode::IOERR;
        }
        $dateTime = new \DateTime();
        $dateTime->sub(new \DateInterval("P{$dias}D"))
            ->format('Y-m-d H:m:s');

        $borrados = \app\models\Usuarios::deleteAll()
            ->where(['confirmado' => false])
            ->andWhere(['<', 'created_at', $dateTime]);

        echo "Se han borrado $borrados usuarios\n";

        return ExitCode::OK;
    }
}
