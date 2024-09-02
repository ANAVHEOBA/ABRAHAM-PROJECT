<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(): AnonymousResourceCollection
    {
        $transactions = $this->transactionService->getAllTransactions();
        return TransactionResource::collection($transactions);
    }

    public function show(Transaction $transaction): TransactionResource
    {
        return new TransactionResource($transaction);
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = $this->transactionService->createTransaction($request->validated());
        return (new TransactionResource($transaction))
            ->response()
            ->setStatusCode(201);
    }

    public function getUserTransactions(int $userId): AnonymousResourceCollection
    {
        $transactions = $this->transactionService->getUserTransactions($userId);
        return TransactionResource::collection($transactions);
    }

    public function getPilotTransactions(int $pilotId): AnonymousResourceCollection
    {
        $transactions = $this->transactionService->getPilotTransactions($pilotId);
        return TransactionResource::collection($transactions);
    }
}