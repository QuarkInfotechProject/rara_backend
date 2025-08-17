<?php

namespace Modules\Sales\App\Http\Service\Admin\Agent;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Sales\App\Models\Agent;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateAgentService
{

    public function updateAgent(array $data, $ipAddress)
    {
        $agent = Agent::findOrFail($data['id']);
        $this->validateAgentData($data, $agent->id);

        DB::beginTransaction();
        try {
            $agent->fill([
                'firstname' => $data['firstname'] ?? $agent->firstname,
                'lastname' => $data['lastname'] ?? $agent->lastname,
                'email' => $data['email'] ?? $agent->email,
                'phone' => $data['phone'] ?? $agent->phone,
                'company' => $data['company'] ?? $agent->company,
                'website' => $data['website'] ?? $agent->website,
                'homestay_margin' => $data['homestay_margin'] ?? $agent->homestay_margin,
                'experience_margin' => $data['experience_margin'] ?? $agent->experience_margin,
                'package_margin' => $data['package_margin'] ?? $agent->package_margin,
                'pan_no' => $data['pan_no'] ?? $agent->pan_no,
                'address' => $data['address'] ?? $agent->address,
                'city' => $data['city'] ?? $agent->city,
                'country' => $data['country'] ?? $agent->country,
                'postal_code' => $data['postal_code'] ?? $agent->postal_code,
                'contract_start_date' => $data['contract_start_date'] ?? $agent->contract_start_date,
                'contract_end_date' => $data['contract_end_date'] ?? $agent->contract_end_date,
                'bank_name' => $data['bank_name'] ?? $agent->bank_name,
                'bank_account_number' => $data['bank_account_number'] ?? $agent->bank_account_number,
                'bank_ifsc_code' => $data['bank_ifsc_code'] ?? $agent->bank_ifsc_code,
                'notes' => $data['notes'] ?? $agent->notes,
                'is_active' => $data['is_active'] ?? $agent->is_active,
            ]);

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

    private function validateAgentData(array $data, int $agentId): void
    {
        $validator = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('agents', 'email')->ignore($agentId),
            ],
            'phone' => 'sometimes|required|string|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'homestay_margin' => 'sometimes|required|numeric|between:0,100',
            'experience_margin' => 'sometimes|required|numeric|between:0,100',
            'package_margin' => 'sometimes|required|numeric|between:0,100',
            'pan_no' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('agents', 'pan_no')->ignore($agentId),
            ],
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
