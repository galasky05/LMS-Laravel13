<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function confirm(Request $request, $enrollmentId)
    {
        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $enrollment->update([
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}