<?php

namespace App\Repos;

class NfcRepos extends BaseRepos
{
    const TABLE = "nfc";


    protected function table()
    {
        return self::TABLE;
    }
}