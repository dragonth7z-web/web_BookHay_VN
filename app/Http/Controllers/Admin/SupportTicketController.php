<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SupportTicketPriority;
use App\Enums\SupportTicketStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSupportTicketRequest;
use App\Models\SupportTicket;
use App\Services\SupportTicketService;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(
        private SupportTicketService $service
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'search']);
        $tickets = $this->service->getPaginatedTickets($filters);
        $counts  = $this->service->getStatusCounts();

        $statuses   = SupportTicketStatus::cases();
        $priorities = SupportTicketPriority::cases();

        return view('admin.support-tickets.index', compact(
            'tickets',
            'counts',
            'statuses',
            'priorities',
            'filters'
        ));
    }

    public function show(SupportTicket $supportTicket)
    {
        $ticket     = $this->service->getTicketDetails($supportTicket->id);
        $statuses   = SupportTicketStatus::cases();
        $priorities = SupportTicketPriority::cases();

        return view('admin.support-tickets.show', compact('ticket', 'statuses', 'priorities'));
    }

    public function update(UpdateSupportTicketRequest $request, SupportTicket $supportTicket)
    {
        $this->service->updateTicket($supportTicket, $request->validated());

        return back()->with('success', 'Cập nhật yêu cầu hỗ trợ thành công.');
    }

    public function destroy(SupportTicket $supportTicket)
    {
        $this->service->deleteTicket($supportTicket);

        return redirect()->route('admin.support-tickets.index')
            ->with('success', 'Đã xóa yêu cầu hỗ trợ.');
    }
}
