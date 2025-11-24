<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Ticket;
use App\Models\TicketPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketActive = Ticket::whereHas('ticketPayment', function($q){
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', '>=', $date);
        })->where('user_id', Auth::user()->id)->get();
        //

        $ticketNonActive = Ticket::whereHas('ticketPayment', function($q){
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', '<', $date);
        })->where('user_id', Auth::user()->id)->get();
        return view('tickets.index', compact('ticketActive', 'ticketNonActive'));
    }

    public function chart()
    {
        $tickets = Ticket::whereHas('ticketPayment', function($q){
            // ambil yang paid date nya udah bukan <> null udah di bayr
            $q->where('paid_date', '<>', NULL);
        })->get()->groupBy(function($ticket){
            // grup by mebgelompokan data tiket berdasarkan tgl pembayaran unutk
            // dihitung jumalah tiket tiap tgl nya
            return \Carbon\Carbon::parse($ticket->ticketPayment->paid_date)->format('Y-m-d');
        })->toArray();// To arrya data di sajikan dalam bentuk array agar bisa menggunakan fungsi fungsi arry
        $labels = array_keys($tickets);
        $data = [];
        foreach ($tickets as $value){
            // simpan jumlah vlue ke array di astas
        array_push($data, count($value));

        }
        // dd($tickets);
        // di proses lewat js jdi gunaakan respomse()->json
        return response()->json([
            'labels' => $labels,
            'data'  => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'rows_of_seats' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'tax' => 'required',
            'hour' => 'required',
        ]);

        $createData = Ticket::create([
            'user_id'      => $request->user_id,
            'schedule_id'  => $request->schedule_id,
            'rows_of_seats'=> $request->rows_of_seats,
            'quantity'     => $request->quantity,
            'total_price'  => $request->total_price,
            'tax'          => $request->tax,
            'hour'         => $request->hour,
            'date'         => now(),
            'actived'      => 0,
        ]);

        return response()->json([
            'message' => 'Berhasil membuat data tiket',
            'data'    => $createData,
        ]);
    }

    public function orderPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)
            ->with(['schedule', 'schedule.cinema', 'schedule.movie'])
            ->first();

        $promos = Promo::where('actived', 1)->get();

        return view('schedule.order', compact('ticket', 'promos'));
    }

    public function createQrcode(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required'
        ]);

        $ticket = Ticket::find($request->ticket_id);
        $kodeQr = 'TICKET-' . $ticket->id;

        // Buat QR Code
        $qrcode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($kodeQr);

        $filename = $kodeQr . '.svg';
        $folder = 'qrcode/' . $filename;

        Storage::disk('public')->put($folder, $qrcode);

        // Simpan data pembayaran tiket
        TicketPayment::create([
            'ticket_id'   => $ticket->id,
            'qrcode'      => $folder,
            'booked_date' => now(),
            'status'      => 'process'
        ]);

        // Update promo pada tiket jika ada
        if ($request->promo_id != null) {
            $promo = Promo::find($request->promo_id);

            if ($promo->type === 'percent') {
                $discount = $ticket->total_price * $promo->discount / 100;
            } else {
                $discount = $promo->discount;
            }

            $totalPriceNew = $ticket->total_price - $discount;

            $ticket->update([
                'total_price' => $totalPriceNew,
                'promo_id' => $request->promo_id
            ]);
        }

        return response()->json([
            'message' => 'Berhasil membuat data pembayaran dan update promo tiket!',
            'data'    => $ticket
        ]);
    }

    public function paymentPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)
            ->with('ticketPayment', 'promo')
            ->first();
        // dd($ticket);
        return view('schedule.payment', compact('ticket'));
    }

    public function updateStatusPayment(Request $request, $ticketId)
    {
        $updateData = TicketPayment::where('ticket_id', $ticketId)->update([
            'status' => 'paid-off',
            'paid_date' => now()
        ]);
        if ($updateData ){
            Ticket::where('id', $ticketId)->update([
                'actived' => 1
            ]);
        }
        return redirect()->route('tickets.payment.proof', $ticketId);
    }

    public function proofPayment($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie', 'promo', 'ticketPayment'])->first();
        return view('schedule.proof-payment', compact('ticket'));
    }


    public function exportPdf($ticketId)
    {
        // data
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema',
         'schedule.movie', 'promo', 'ticketPayment'])
        ->first()->toArray();
        // untuk inisial nama data yang di gunakan pada blade.pdf
        view()->share('ticket', $ticket);
        // generate file blade yangdi ceatk pdf
        $pdf = Pdf::loadView('schedule.export-pdf', $ticket);
        // penamaan file
        $fileName = 'TICKET' . $ticket['id'] . '.pdf';
        return $pdf->download($fileName);
    }
    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
