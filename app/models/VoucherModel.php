<?php

require_once 'app/core/BaseModel.php';

class VoucherModel extends BaseModel
{
    protected string $table = 'vouchers';
    protected array $fillable = ['code', 'discount', 'quota', 'status', 'type'];

    public function findByCode(string $code): ?array
    {
        return $this->queryOne("SELECT * FROM vouchers WHERE code = ?", [$code]);
    }
}