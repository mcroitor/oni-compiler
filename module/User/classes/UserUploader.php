<?php

namespace User;

class UserUploader
{
    public static function upload(array $params)
    {
        $tmpFile = $_FILES['users']['tmp_name'];
        return "";
    }
}
