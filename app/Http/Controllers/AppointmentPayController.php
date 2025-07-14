<?php

namespace App\Http\Controllers;

use App\AppointmentStatusEnum;
use App\Http\Requests\AppointmentPayRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class AppointmentPayController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AppointmentPayRequest $request)
    {
        $validated = $request->validated();

        // Process the payment for the appointment with Stripe PHP SDK
        $appointmentId = $validated['appointment_id'];

        // Interact with the Stripe API to handle the payment here.

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create a payment method with the card details
            // $paymentMethod = PaymentMethod::create([
            //     'type' => 'card',
            //     'card' => [
            //         'number' => $validated['card_number'],
            //         'exp_month' => $validated['exp_month'],
            //         'exp_year' => $validated['exp_year'],
            //         'cvc' => $validated['cvc'],
            //     ],
            // ])->attach(['customer' => $request->user()->stripe_id ?? null]);

            // Create a payment intent with the amount and currency
            // $paymentIntent = \Stripe\PaymentIntent::create([
            //     'amount' => 100,
            //     'currency' => 'usd',
            //     'payment_method' => $paymentMethod,
            //     'confirmation_method' => 'manual',
            //     'confirm' => true,
            // ]);

            // Handle successful payment
            // if ($paymentIntent->status === 'succeeded') {
                // Update appointment status to paid
                Appointment::query()
                    ->where('id', $appointmentId)
                    ->update([
                        'status' => AppointmentStatusEnum::PAID,
                    ]);

                // Return success response
                return response()->json(['status' => 'success', 'message' => 'Payment successful']);
            // } else {
            //     // Handle other statuses like requires_action, requires_payment_method, etc.
            //     return response()->json(['status' => 'pending', 'message' => 'Payment requires further action']);
            // }
        } catch (\Stripe\Exception\CardException $e) {
            // Handle card error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Handle rate limit error
            return response()->json(['status' => 'error', 'message' => 'Too many requests made to the API too quickly'], 429);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handle invalid request error
            return response()->json(['status' => 'error', 'message' => 'Invalid parameters provided to Stripe API'], 400);
        } catch (\Exception $e) {
            // Handle other errors
            return response()->json(['status' => 'error', 'message' => 'An unexpected error occurred'], 500);
        }
    }
}
