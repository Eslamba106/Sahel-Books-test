<?php

namespace App\Repositories\Invoices;

use App\Models\InvoiceModel;
use App\Repositories\AbstractRepository;

class InvoicesRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(InvoiceModel::class);
    }
}
