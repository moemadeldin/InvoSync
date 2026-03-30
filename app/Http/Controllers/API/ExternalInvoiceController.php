<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Actions\API\SyncExternalInvoiceAction;
use App\Http\Requests\API\StoreExternalInvoiceRequest;
use App\Utils\APIResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class ExternalInvoiceController
{
    use APIResponder;

    public function __invoke(StoreExternalInvoiceRequest $request, SyncExternalInvoiceAction $action): JsonResponse
    {
        /** @var array{customer_email: string, customer_name: string, user_id: string, amount: float, description: string} $data */
        $data = $request->validated();
        $invoice = $action->execute($data);

        return $this->success($invoice, 'Invoice and client synced successfully', Response::HTTP_CREATED);
    }
}
