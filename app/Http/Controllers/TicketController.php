<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = $request->user()->is_admin
            ? Ticket::with(['user', 'assignedTo', 'comments'])->latest()->paginate(15)
            : $request->user()->tickets()->with(['assignedTo', 'comments'])->latest()->paginate(15);

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = $request->user()->tickets()->create($request->validated());
        return new TicketResource($ticket);
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        return new TicketResource($ticket->load(['user', 'assignedTo', 'comments.user']));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->update($request->validated());
        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted successfully']);
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $ticket->update(['assigned_to' => $request->assigned_to]);
        return new TicketResource($ticket);
    }
}