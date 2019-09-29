<?php

namespace RunoFramework\Lib\ArrayHelper;

class ArrayHelper
{

    public function getArrayValueByKey(&$arrRef, $key, $defaultValue = null)
    {

        if (is_array($arrRef) && array_key_exists($key, $arrRef)) {
            return $arrRef[$key];
        } else if ($defaultValue != null) {
            return $defaultValue;
        } else {
            return null;
        }

    }

    // Bedingung: Array liegt vorsortiert vor
    // Beispiel [x,x,x,x,x,x,x,y,y,y,y,y,y,y,y,y,y]
    // Wir wollen aber [x,y,x,y,x,y,x,y,x,y,x,y,x,y,y,y]
    public function sortArraySuccessively($array, $firstArrayLength = null, $secondArrayLength = null, $firstArrayKeyValue = null)
    {

        $debug = false;

        $i = 0;
        $z = 0;

        if ($firstArrayLength == null || $secondArrayLength == null) {

            $firstArrayLength = 0;
            $secondArrayLength = 0;

            foreach ($array as $item) {
                if ($item[$firstArrayKeyValue[0]] == $firstArrayKeyValue[1]) {
                    $firstArrayLength++;
                } else {
                    $secondArrayLength++;
                }
            }

        }

        $resultArray = [];
        $maxLength = count($array);

        for ($i; $i < $maxLength; $i++) {

            if ($i % 2 == 0) {

                $evenIndex = $i / 2;

                if ($debug) {
                    echo "$i = $evenIndex";
                }

                $resultArray[$i] = $array[$evenIndex];

            } else {

                $oddIndex = ($firstArrayLength - 1) + ($i - $z);

                if ($oddIndex > $i && $oddIndex >= $maxLength) {

                    $oddIndex = $i - $secondArrayLength + 2;

                    if ($oddIndex == $firstArrayLength) {
                        $oddIndex = $oddIndex - 1;
                    }

                }

                if ($debug) {
                    echo "$i = <strong>$oddIndex</strong>";
                }

                $resultArray[$i] = $array[$oddIndex];

                $z++;

            }

            if ($debug) {
                echo "<br><br>";
            }

        }

        return $resultArray;

    }

}
