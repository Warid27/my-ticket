<?php

require_once 'app/core/BaseModel.php';

class VoucherModel extends BaseModel
{
    protected string $table = 'vouchers';
    protected array $fillable = ['code', 'discount', 'quota', 'status'];

    public function findByCode(string $code): array|false
    {
        return $this->queryOne("SELECT * FROM vouchers WHERE code = ? AND status = 'aktif' and quota > 0", [$code]);
    }
}