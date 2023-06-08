<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketStoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConcertController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('type')) {
            $datas = Ticket::all()->where("type", "=", $request->get('type'));
        } else {
            $datas = Ticket::all();
        }

        if (count($datas) > 0) {
            return response()->json([
                "status" => 200,
                "message" => "success",
                "datas" => $datas,
                "length" => count($datas),
            ], 200);
        } else {
            return response()->json([
                "status" => 200,
                "message" => "success",
                "datas" => "empty data",
            ], 200);
        }
    }

    public function store(TicketStoreRequest $request)
    {
        $serialNumber = Uuid::uuid4()->toString();
        $validate = [
            "name" => $request->name,
            "type" => $request->type,
            "serial_number" => $serialNumber,
            "price" => $request->price,
            "startDate" => $request->startDate,
            "endDate" => $request->endDate,
        ];

        Ticket::create($validate);

        return response()->json([
            "status" => 202,
            "message" => "success",
            "data" => $validate,
        ]);
    }

    public function show($id)
    {
        try {
            $ticket = Ticket::where('id', $id)->firstOrFail();
            return response()->json([
                "status" => 200,
                "message" => "success",
                "data" => $ticket
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                "status" => 404,
                "message" => "data not found",
            ], 404);
        }
    }

    public function update(TicketStoreRequest $request,  $id)
    {
        try {
            $validate = [
                "name" => $request->name,
                "type" => $request->type,
                "price" => $request->price,
                "startDate" => $request->startDate,
                "endDate" => $request->endDate,
            ];

            $ticket = Ticket::findOrFail($id); // Retrieve the ticket record from the database
            $ticket->update($validate);
            return response()->json([
                "status" => 202,
                "message" => "Update successfully",
                "data" => $ticket,
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                "status" => 404,
                "message" => "Data not found",
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $ticket = Ticket::where('id', $id)->firstOrFail();
            $ticket->delete();
            return response()->json([
                "status" => 200,
                "message" => "delete success",
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                "status" => 404,
                "message" => "data not found",
            ], 404);
        }
    }
}
