<?php

namespace Modules\Sales\App\Http\Service\Admin\Agent;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Sales\App\Models\Agent;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class CreateAgentService
{

    public function createAgent(array $data, $ipAddress)
    {
        $this->validateAgentData($data);

        DB::beginTransaction();

        try {
            $agent = new Agent();

            $agent->firstname = $data['firstname'];
            $agent->lastname = $data['lastname'];
            $agent->email = $data['email'];
            $agent->phone = $data['phone'];
            $agent->company = $data['company'] ?? null;
            $agent->website = $data['website'] ?? null;
            $agent->homestay_margin = $data['homestay_margin'];
            $agent->experience_margin = $data['experience_margin'];
            $agent->package_margin = $data['package_margin'];
            $agent->pan_no = $data['pan_no'];
            $agent->address = $data['address'] ?? null;
            $agent->city = $data['city'] ?? null;
            $agent->country = $data['country'] ?? null;
            $agent->postal_code = $data['postal_code'] ?? null;
            $agent->contract_start_date = $data['contract_start_date'] ?? null;
            $agent->contract_end_date = $data['contract_end_date'] ?? null;
            $agent->bank_name = $data['bank_name'] ?? null;
            $agent->bank_account_number = $data['bank_account_number'] ?? null;
            $agent->bank_ifsc_code = $data['bank_ifsc_code'] ?? null;
            $agent->notes = $data['notes'] ?? null;
            $agent->is_active = $data['is_active'] ?? true;

            $agent->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$agent->firstname} Agent has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::AGENT_ADDED,
                $ipAddress)
        );
    }

    private function validateAgentData(array $data): void
    {
        $validator = Validator::make($data, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email',
            'phone' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'homestay_margin' => 'required|numeric|between:0,100',
            'experience_margin' => 'required|numeric|between:0,100',
            'package_margin' => 'required|numeric|between:0,100',
            'pan_no' => 'required|string|unique:agents,pan_no',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_ifsc_code' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

    }
}
