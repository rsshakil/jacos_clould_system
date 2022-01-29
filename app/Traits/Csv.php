<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Csv
{
    public static function create($filePath, $data, $header, $encode='UTF-8', $bom=false)
    {
        \Log::debug(__METHOD__.':start---');

        if (!is_null($header)) {
            // ヘッダー付与
            array_unshift($data, $header);
        }
        $stream = fopen('php://temp', 'r+b');
        foreach ($data as $row) {
            // fputcsv($stream, $row, ',', '"');
            $tmp = [];
            foreach ($row as $val) {
                $tmp[]= '"'.str_replace('"', '""', $val).'"';
            }
            fwrite($stream, implode(',', $tmp)."\n");
        }
        rewind($stream);
        if (strtolower($encode) == 'sjis-win') {
            // shift-win
            $tmp = stream_get_contents($stream);
            // \Log::debug($tmp);
            $csv = mb_convert_encoding($tmp, $encode, 'UTF-8');
            // \Log::debug($csv);

            $csv = str_replace("\n", "\r\n", $csv);
        // \Log::debug($csv);
        } else {
            // utf-8
            if ($bom) {
                // BOM をつける
                fwrite($stream, pack('C*', 0xEF, 0xBB, 0xBF));
            }
            $csv = $stream;
        }

        // file save
        Storage::put($filePath, $csv);
        \Log::debug(__METHOD__.':end---');
    }

    /**
     * CSVファイルを生成する
     * @param $filePath
     */
    public static function createCsv($filePath, $bom=false)
    {
        $csv_file_path = storage_path($filePath);
        $result = fopen($csv_file_path, 'w');
        if ($result === false) {
            throw new \Exception('ファイルの書き込みに失敗しました。');
        }
        if ($bom) {
            // BOM をつける
            fwrite($result, pack('C*', 0xEF, 0xBB, 0xBF));
        }
        fclose($result);

        return $csv_file_path;
    }

    /**
     * CSVファイルに書き出す
     * @param $filepath
     * @param $records
     */
    public static function writeAll($filePath, $dataArray)
    {
        $result = fopen($filePath, 'a');

        // ファイルに書き出し
        foreach ($dataArray as $values) {
            fputcsv($result, $values);
        }

        fclose($result);
    }

    /**
     * CSVファイルに書き出す
     * @param $filepath
     * @param $records
     */
    public static function write($filePath, $records)
    {
        $result = fopen($filePath, 'a');

        // ファイルに書き出し
        fputcsv($result, $records);

        fclose($result);
    }

    /**
     * CSVファイルの削除
     * @param $filename
     */
    public static function purge($filePath)
    {
        // \Log::info(storage_path($filePath));
        return unlink(storage_path($filePath));
    }
}
