<?php

class Category {

    static function getCategory($id) {
        global $DB;
        $category = array();
        if ($id == -1) {
            $sql = "SELECT rubrieknaam, rubrieknummer, ouderrubriek, volgnummer FROM Rubriek WHERE ouderrubriek IS NULL ORDER BY volgnummer, rubrieknaam;";
            $stmt = sqlsrv_query($DB, $sql, array());
        } else {
            $sql = "SELECT rubrieknaam, rubrieknummer, ouderrubriek, volgnummer FROM Rubriek WHERE ouderrubriek = ? ORDER BY volgnummer, rubrieknaam;";
            $stmt = sqlsrv_query($DB, $sql, array($id));
        }

        if (!$stmt) {
            die(print_r(sqlsrv_errors()));
        }
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            //if ($row['rubrieknummer'] == 1508 ) continue;
            $category[$row['rubrieknummer']] = array(
                "rubrieknaam" => Category::fixUTF($row['rubrieknaam']),
                "rubrieknummer" => $row['rubrieknummer'],
                "ouderrubriek" => $row['ouderrubriek'],
                "volgnummer" => $row['volgnummer']
            );
        }
        return $category;
    }

    //http://stackoverflow.com/questions/1401317/remove-non-utf8-characters-from-string
    static function fixUTF($string) {
        $regex = <<<'END'
        /
          (
            (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
            |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
            |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
            |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
            ){1,100}                        # ...one or more times
          )
        | .                                 # anything else
        /x
END;
        return preg_replace($regex, '$1', $string);
    }
}
