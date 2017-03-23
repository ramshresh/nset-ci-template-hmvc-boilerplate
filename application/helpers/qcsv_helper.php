<?php
/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/22/2017
 * Time: 5:53 AM
 */

class QCSV
{
	private static $append = ' 2>&1';

	public function __construct()
	{
	}


	public static function getQ()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$path = FCPATH . 'bin/q/windows/q';
		} else {
			$path = FCPATH . 'bin/q/linux/q';
		}
		return $path;
	}

	public static function getClExp($sql, $delimitation = ',', $header = false)
	{
		$q = self::getQ();

		$qStr = '';
		switch (strtolower($delimitation)) {
			case ',':
				$qStr = $q . ' -H -d , "' . $sql . '"';// . self::$append;
				break;
			case 't':
				$qStr = $q . ' -H -d $\'\t\' "' . $sql . '"';// . self::$append;
				break;
			default:
				$qStr = $q . ' -H -d , "' . $sql . '"';// . self::$append;
				break;
		}
		if ($header == true) {
			$qStr .= ' -O';
		}

		return $qStr;

	}

	public static function execute($sql, $header = false, $associative = false)
	{
		$header = ($associative == true) ? true : $header;


		$exp = self::getClExp($sql, ',', $header);


		try {
			$csvData = shell_exec($exp);
		} catch (Exception $e) {
			throw $e;
		}
		$fp = tmpfile();
		fwrite($fp, $csvData);
		rewind($fp); //rewind to process CSV


		if ($associative == true) {
			$array = $fields = array();
			$i = 0;

			while (($row = fgetcsv($fp, 0)) !== false) {
				if (empty($fields)) {
					$fields = $row;
					continue;
				}
				foreach ($row as $k => $value) {
					$array[$i][$fields[$k]] = $value;
				}
				$i++;
			}


			return $array;
		} else {
			$array = [];
			while (($row = fgetcsv($fp, 0)) !== FALSE) {
				//$line = str_getcsv($row);
				array_push($array, $row);
			}
			return $array;
		}
	}


}