<?php

namespace App\Helpers;

use App\Client;
use App\ClientProduct;
use App\DeliveryboyProduct;
use App\DeliveryBoys;
use App\Investigations;
use App\InvestigatorProduct;
use App\Investigators;
use App\Product;
use App\User;
use App\PerformaInvoice;
use App\Invoice;
use App\InvestigatorInvoice;
use App\DeliveryboyInvoice;
use DB;

class AppHelper
{

    public static function instance()
    {
        return new AppHelper();
    }

    public static function startQueryLog()
    {
        DB::enableQueryLog();
    }

    public static function showQueries()
    {
        dd(DB::getQueryLog());
    }

    public static function compareAddress($address1, $address2)
    {
        if (
            $address1['city'] == $address2['city']
            && $address1['state'] == $address2['state']
            && $address1['zipcode'] == $address2['zipcode']
        ) {
            return true;
        }

        return false;
    }

    public static function getInvestigatorIdFromUserId($userId)
    {
        return Investigators::where('user_id', $userId)->pluck('id')->first();
    }

    public static function getDeliveryboyIdFromUserId($userId)
    {
        return DeliveryBoys::where('user_id', $userId)->pluck('id')->first();
    }

    public static function getClientIdFromUserId($userId)
    {
        return Client::where('user_id', $userId)->pluck('id')->first();
    }

    public static function calculateInvestigationCost($data)
    {
        $productIsdel = $data['product_isdel'] == 'yes';
        $invCost = !empty($data['inv_cost']) ? $data['inv_cost'] : 0;
        $delCost = !empty($data['product_delcost']) ? $data['product_delcost'] : 0;
        $spouseCost = !empty($data['product_spousecost']) ? $data['product_spousecost'] : 0;
        $subSpouse = 0;
        $subOther = 0;
        $mainSubject = null;
        $spouseSubject = null;
        $isMainSpouseAddressSame = false;
        $finalInvCost = 0;

        foreach ($data['subjects'] ?? [] as $subject) {
            if ($subject['sub_type'] == 'Spouse') {
                $spouseSubject = ($spouseSubject == null) ? $subject : $spouseSubject;
                $subSpouse++;
            } else {
                if ($subject['sub_type'] == 'Main') {
                    $mainSubject = ($mainSubject == null) ? $subject : $mainSubject;
                }
                $subOther++;
            }
        }

        if ($mainSubject != null && $spouseSubject != null) {
            if (!empty($mainSubject['address']) && !empty($spouseSubject['address'])) {
                for ($i = 1; $i <= count($spouseSubject['address']); $i++) {
                    for ($j = 1; $j <= count($mainSubject['address']); $j++) {
                        $isMainSpouseAddressSame = AppHelper::compareAddress($spouseSubject['address'][$i], $mainSubject['address'][$j]);
                        if ($isMainSpouseAddressSame) {
                            break 2;
                        }
                    }
                }
            }
        }
        // oLd condition
        //$finalInvCost = ($invCost  * $subOther) + ((($invCost * $spouseCost) / 100) * $subSpouse) + ($delCost * $subOther);

        $finalInvCost = $subOther > 0 ? ($invCost  * $subOther) : $finalInvCost;
        $finalInvCost = $subSpouse > 0 && $spouseCost > 0 ? $finalInvCost + ((($invCost * $spouseCost) / 100) * $subSpouse) : $finalInvCost;

        if ($subSpouse > 0 && $productIsdel == true && $isMainSpouseAddressSame == false) {
            // $finalInvCost += $delCost;
            $finalInvCost += ($delCost * $subSpouse);
        }

        return $finalInvCost;
    }

    public static function calculateInvestigationCostForInvestigator($invnId, $invrId)
    {
        if($invnId == 'bulk') {
            $invnIds = json_decode($_COOKIE['invIds'], true);
            $finalCost = 0;
            foreach($invnIds as $invId) {
                $investigation = Investigations::with('subjects:id,investigation_id,sub_type')->where('id',$invId)->first();

                $product = InvestigatorProduct::with('product:id,price,spouse_cost')
                    ->whereHas('product')
                    ->where('investigator_id', $invrId)
                    ->where('product_id', $investigation->type_of_inquiry)
                    ->first();

                if(empty($product)){
                    $product = Product::where('id', $investigation->type_of_inquiry)->first();
                    $spouseCost = $product->spouse_cost;
                }else{
                    $spouseCost = !empty($product->product->spouse_cost) ? $product->product->spouse_cost : 0;
                }

                $invCost = !empty($product) && !empty($product->price) ? $product->price : 0;

                $subSpouse = 0;
                $subOther = 0;
                $finalInvCost = 0;

                foreach ($investigation->subjects ?? [] as $subject) {
                    if ($subject->sub_type == 'Spouse') {
                        $subSpouse++;
                    } else {
                        $subOther++;
                    }
                }

                $finalInvCost = $subOther > 0 ? ($invCost  * $subOther) : $finalInvCost;
                $finalInvCost = $subSpouse > 0 && $spouseCost > 0 ? $finalInvCost + ((($invCost * $spouseCost) / 100) * $subSpouse) : $finalInvCost;
                $finalCost += $finalInvCost;
            }
            return $finalCost;
        } else {
            $investigation = Investigations::with('subjects:id,investigation_id,sub_type')->where('id',$invnId)->first();

            $product = InvestigatorProduct::with('product:id,price,spouse_cost')
                ->whereHas('product')
                ->where('investigator_id', $invrId)
                ->where('product_id', $investigation->type_of_inquiry)
                ->first();

            if(empty($product)){
                $product = Product::where('id', $investigation->type_of_inquiry)->first();
                $spouseCost = $product->spouse_cost;
            }else{
                $spouseCost = !empty($product->product->spouse_cost) ? $product->product->spouse_cost : 0;
            }

            $invCost = !empty($product) && !empty($product->price) ? $product->price : 0;

            $subSpouse = 0;
            $subOther = 0;
            $finalInvCost = 0;

            foreach ($investigation->subjects ?? [] as $subject) {
                if ($subject->sub_type == 'Spouse') {
                    $subSpouse++;
                } else {
                    $subOther++;
                }
            }

            $finalInvCost = $subOther > 0 ? ($invCost  * $subOther) : $finalInvCost;
            $finalInvCost = $subSpouse > 0 && $spouseCost > 0 ? $finalInvCost + ((($invCost * $spouseCost) / 100) * $subSpouse) : $finalInvCost;

            return $finalInvCost;
        }
    }

    public static function calculateInvestigationCostForDeliveryboy($invnId, $delboyId)
    {
        $investigation = Investigations::with('subjects:id,investigation_id,sub_type')->where('id',$invnId)->first();

        $product = DeliveryboyProduct::with('product:id,price,spouse_cost')
            ->whereHas('product')
            ->where('deliveryboy_id', $delboyId)
            ->where('product_id', $investigation->type_of_inquiry)
            ->first();

        if(empty($product)){
            $product = Product::where('id', $investigation->type_of_inquiry)->first();
            $spouseCost = $product->spouse_cost;
        }else{
            $spouseCost = !empty($product->product->spouse_cost) ? $product->product->spouse_cost : 0;
        }

        $invCost = !empty($product) && !empty($product->price) ? $product->price : 0;

        $subSpouse = 0;
        $subOther = 0;
        $finalInvCost = 0;

        foreach ($investigation->subjects ?? [] as $subject) {
            if ($subject->sub_type == 'Spouse') {
                $subSpouse++;
            } else {
                $subOther++;
            }
        }

        $finalInvCost = $subOther > 0 ? ($invCost  * $subOther) : $finalInvCost;
        $finalInvCost = $subSpouse > 0 && $spouseCost > 0 ? $finalInvCost + ((($invCost * $spouseCost) / 100) * $subSpouse) : $finalInvCost;

        return $finalInvCost;
    }

    public static function getUsersAvailableCredit($userId, $invnId = null)
    {
        $totAvailCredit = 0;
        $totalSpent = 0;

        if (!empty($userId)) {
            $client = Client::select('id', 'credit_limit')->where('user_id', $userId)->first();

            if ($invnId != null) {
                $investigations = Investigations::where('user_id', $userId)
                    ->whereNotIn('id', [$invnId])
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $investigations = Investigations::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')->get();
            }

            foreach ($investigations as $investigation) {
                $totalSpent += !empty($investigation->inv_cost) ? (float) $investigation->inv_cost : 0;
            }

            if (!empty($client)) {
                $totAvailCredit = $client->credit_limit - $totalSpent;
            }
        }

        return $totAvailCredit;
    }

    public static function getUserIdFromInvestigatorId($invrId)
    {
        return Investigators::where('id', $invrId)->pluck('user_id')->first();
    }

    public static function getUserIdFromDeliveryboyId($delboyId)
    {
        return DeliveryBoys::where('id', $delboyId)->pluck('user_id')->first();
    }

    public static function findExistingCase($investigation){
        $matchingInvn = null;

        $subjects = $investigation->subjects->first();
        $subjectAddress = $subjects->subject_addresses->first();

        if($investigation && !empty($subjectAddress)){
            $invns = Investigations::where('user_id', $investigation->user_id)
                ->where('paying_customerid', $investigation->paying_customerid)
                ->where('id', '!=',$investigation->id)
                ->where('created_at', '>=', date("Y-m-d", strtotime("-6 months")))
                ->get();

            if($invns->isNotEmpty()){
                foreach($invns as $invn){
                    $invnSubjects = $invn->subjects->first();
                    $invnSubjectAddress = isset($invnSubjects->subject_addresses)?$invnSubjects->subject_addresses->first():[];

                    if(!empty($invnSubjectAddress)){
                        $isSame = self::compareAddress($subjectAddress, $invnSubjectAddress);

                        if($isSame){
                            $matchingInvn = $invn;
                            break;
                        }
                    }
                }
            }
        }

        if($matchingInvn){
           return $matchingInvn->case_id;
        }

        return false;
    }

    public static function checkIfUserIsSM($userId){
        $user = User::with('user_type')->where('id', $userId)->first();
        $userType = $user->user_type->type_name;
        return $userType == env('USER_TYPE_STATION_MANAGER');
    }

    public static function genratePerformaInvoiceNumber()
    {
        $totalPerforma = PerformaInvoice::count();
        if($totalPerforma > 0){
            if(strlen($totalPerforma) > 5){
                return ($totalPerforma + 1);
            } else {
                $zero = "";
                for ($i=0; $i <= (5 - strlen($totalPerforma)); $i++) { 
                    $zero.='0';
                }
                return $zero.($totalPerforma+1);
            }
        } else {
            $zero = "";
            for ($i=0; $i < 5; $i++) { 
                $zero.='0';
            }
            return $zero."1";
        }
    }

    public static function genrateInvoiceNumber($model)
    {
        $totalInvoice = $model::count();
        if($totalInvoice > 0){
            if(strlen($totalInvoice) > 5){
                return ($totalInvoice + 1);
            } else {
                $zero = "";
                for ($i=0; $i <= (5 - strlen($totalInvoice)); $i++) { 
                    $zero.='0';
                }
                return $zero.($totalInvoice+1);
            }
        } else {
            $zero = "";
            for ($i=0; $i < 5; $i++) { 
                $zero.='0';
            }
            return $zero."1";
        }
    }

    public static function genrateInvestigationNumber($model)
    {
        $totalInvestigation = $model::count();
        $starts = 10000;
        if($totalInvestigation > 0){
            return ($totalInvestigation + $starts);
        } else {
            return $starts;
        }
    }

    public static function find_key_value($array, $key, $val)
	{
		foreach ($array as $item){
            if (isset($item[$key]) && $item[$key] == $val){
                return false;
            }
        }      
        return true;
	}

    public static function searchForId($id, $index, $array) {
        foreach ($array as $key => $val) {
            if ($val[$index] === $id) {
                return $key;
            }
        }
        return null;
     }

    public static function getInvoiceInvestigatorId($investigationId,$investigatorId) {
        try {
            $invoice = InvestigatorInvoice::select('id','status')->where('investigation_id',$investigationId)->where('investigator_id',$investigatorId)->first();
            if($invoice){
                return ['id' =>$invoice->id,'status'=>$invoice->status];
            }
            return ['id' =>0,'status'=>''];
        } catch (Exception $e) {
            return ['id' =>0,'status'=>''];
        }
    }


    public static function getInvoiceIDelboyId($investigationId,$deliveryboyId) {
        try {
            $invoice = DeliveryboyInvoice::select('id','status')->where('investigation_id',$investigationId)->where('deliveryboy_id',$deliveryboyId)->first();
            if($invoice){
                return ['id' =>$invoice->id,'status'=>$invoice->status];
            }
            return ['id' =>0,'status'=>''];
        } catch (Exception $e) {
            return ['id' =>0,'status'=>''];
        }
    }
}
