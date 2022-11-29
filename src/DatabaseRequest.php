<?php

namespace Bubu\Database;

use PDO;
use Exception;

class DatabaseRequest
{
    /**
     * @param string $request
     * @param array $values
     * @param string|null $type
     * @param array $opt
     * @return array|bool
     */
    public static function request(
        string $strRequest,
        array $values = [],
        ?string $type = null,
        ?Database $dbInstance = null,
        array $opt = []
    ): mixed {
        try {
            if (count(explode(';', $strRequest)) > 1) {
                self::request(explode(';', $strRequest)[0]);
                $strRequest = explode(';', $strRequest)[1];
            }

            $strRequest = preg_replace_callback('((`[^`]+)(\.)(.+?`))', function ($matches) {
                return $matches[1] . '`.`' . $matches[3];
            }, $strRequest);

            if (is_null($dbInstance)) {
                $dbInstance = Database::createConnection($opt)->getPdo();
                $request = $dbInstance->prepare($strRequest);
            }
            else $request = $dbInstance->getPdo()->prepare($strRequest);

            foreach ($values as $key => $value) {
                switch (gettype($value)) {
                    case 'integer':
                        $request->bindValue($key, $value, PDO::PARAM_INT);
                        break;

                    case 'string':
                        $request->bindValue($key, $value, PDO::PARAM_STR);
                        break;

                    case 'boolean':
                        $request->bindValue($key, $value, PDO::PARAM_BOOL);
                        break;

                    case 'NULL':
                        $request->bindValue($key, $value, PDO::PARAM_NULL);
                        break;

                    default:
                        $request->bindValue($key, $value);
                        break;
                }
            }
            $request->execute();
            if ($type === 'fetchAll') {
                $return = $request->fetchAll();
                $request->closeCursor();
                return $return;
            } elseif ($type === 'fetch') {
                $return = $request->fetch();
                $request->closeCursor();
                return $return;
            } else {
                $request->closeCursor();
                return $dbInstance;
            }
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
