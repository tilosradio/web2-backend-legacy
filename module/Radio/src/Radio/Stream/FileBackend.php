<?php

namespace Radio\Stream;

class FileBackend
{

    public function getSize($file)
    {
        if (file_exists(file)) {
            return filesize($file);
        }
        return 0;
    }


    public function checkExistence($currentFile)
    {
        return file_exists($currentFile);
    }

    public function stream($from, $to, $resource)
    {
        # 1 megabyte buffer
        $buffer_size = 1048576;
        $ret = 0;
        if (!file_exists($resource->file)) {
            die("File doesn't exist " . getcwd() . "/" . $resource->file);
        }
        $fin = fopen($resource->file, "rb");
        if ($fin == false) {
            echo "--$from--";
            die("file open error");
        }

        $out = 0;
        if ($from) {
            fseek($fin, $from);
            $out = $from;
        }

        while (!feof($fin)) {
            if ($out + $buffer_size > $to) {
                if ($to != $out) {
                    echo fread($fin, $to - $out);
                }
                break;

            }
            $res = fread($fin, $buffer_size);
            echo $res;
            $out += sizeof($res);
        }
        fclose($fin);
    }


}

?>