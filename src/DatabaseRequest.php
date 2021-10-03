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
     * @param int $mode
     * @param array $opt
     * @return array|bool
     */
    public static function request(
        string $strRequest,
        array $values = [],
        ?string $type = null,
        int $mode = PDO::FETCH_ASSOC,
        ?Database $dbInstance = null,
        array $opt = []
    ): mixed {
        try {
            if (count(explode(';', $strRequest)) > 1) {
                self::request(explode(';', $strRequest)[0]);
                $strRequest = explode(';', $strRequest)[1];
            }

            if (is_null($dbInstance)) $request = Database::createConnection($opt)->getPdo()->prepare($strRequest);
            else $request = $dbInstance->getPdo()->prepare($strRequest);

            $i = 1;
            foreach ($values as $key => $value) {
                if (strpos($key, '?') !== false) {
                    $key = $i;
                } else {
                    $key = str_replace("bubu-fw-secure-{$i}-end-secure", '', ':' . ltrim($key, ':'), );
                }
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
                $i++;
            }
            $request->execute();
            if ($type === 'fetchAll') {
                $return = $request->fetchAll($mode);
                $request->closeCursor();
                return $return;
            } elseif ($type === 'fetch') {
                $return = $request->fetch($mode);
                $request->closeCursor();
                return $return;
            } else {
                $request->closeCursor();
                return true;
            }
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
