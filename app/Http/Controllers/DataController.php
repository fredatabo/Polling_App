<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DB;

class DataController extends Controller
{
    // function to get state
    public function getStates()
    {
           $state_id = 25; 
        $states = DB::table('states')->where("state_id",$state_id)->pluck("state_name","state_id");
        return view('polling_unit_result',compact('states'));
    }

    // function to get the local governments under the state

    public function getLGA($state_id) 
    {        
            $states = DB::table("lga")->where("state_id",$state_id)->pluck("lga_name","lga_id");
            return json_encode($states);
    }

    // function to get wards

    public function getWards($lga_id) 
    {        
            $wards = DB::table("ward")->where("lga_id",$lga_id)->pluck("ward_name","ward_id");
            return json_encode($wards);
    }

  
    // get polling uniits
    public function getPollingUnits($ward_id) 
    {        
            $polling_units = DB::table("polling_unit")->where("ward_id",$ward_id)->pluck("polling_unit_name","uniqueid");
            return json_encode($polling_units);
    }

    public function getResultFromPollingUnit($id) {
        $results = DB::table('announced_pu_results')->where('polling_unit_uniqueid', $id)->get();  

        $name = DB::table('polling_unit')->where('uniqueid', $id)->first(); 
        
        if($results) {
                return view('wardResults', compact('results','name'));  
        }

        else {
                return view('noResults');      
        }

        

    }



    public function getLGAResult($id) {
        $results = DB::table('announced_pu_results')->where('polling_unit_uniqueid', $id)->get();  

         
        
        if($results) {
                return view('lgaResults', compact('results'));  
        }

        else {
                return view('noResults');      
        }

        

    }

    // this function loads the add result page so that a new entry can be made for a polling unit

    public function addResultPage($id)
    {
        $name = DB::table('polling_unit')->where('uniqueid', $id)->first(); 
        
        $party = DB::table('party')->pluck("partyname","partyid");
                return view('addResultPage', compact('name','party'));  
        

        
                  

    }

    // function to bring up page for adding election result

    public function AddResult()
    {
           $state_id = 25; 
        $states = DB::table('states')->where("state_id",$state_id)->pluck("state_name","state_id");
        return view('addResult',compact('states'));
    }

    /*
The method below is used for adding election result for new polling uniit
    */

  public function  addElectionResult(Request $request) {
          $party_abbreviation = $request->party_abbreviation;
          $party_score = $request->party_score;
          $entered_by_user =  $request->entered_by_user;
          $polling_unit_uniqueid = $request->polling_unit_uniqueid;
// code to check if result has been added
$count = DB::table('announced_pu_results')->where('polling_unit_uniqueid', $polling_unit_uniqueid)->where("party_abbreviation",$party_abbreviation)->count();

    if ($count >= 1) {
        return redirect()->back()->with('failure', 'result already added for this party, you can only edit');    
    }

    else {

        $data = array();
$data['party_abbreviation'] = $request->party_abbreviation;
$data['party_score'] = $request->party_score;
$data['entered_by_user'] = $request->entered_by_user;
$data['polling_unit_uniqueid'] = $request->polling_unit_uniqueid;
 
$data['user_ip_address'] = "192.168.10";
$data['date_entered']  = Carbon::now();
DB::table('announced_pu_results')->insert($data);

return redirect()->back()->with('success', 'result added successfully,  coontinue by selecting another party');   

    }

  }


  public function showLGAResultPage()
  {
         $state_id = 25; 
      $states = DB::table('states')->where("state_id",$state_id)->pluck("state_name","state_id");
      return view('lgaResultPage',compact('states'));
  }

  // function to get total resuls from each LGA

 
  public function showLGAResultPageTotal($id) {
      
        $pollingUnits = DB::table('polling_unit')->where('lga_id', $id)->get(); 
        //dd($pollingUnits);

        $PDP = 0;
        $DPP = 0;
       $ACN = 0;
      $PPA = 0;
     $CDC = 0;
     $JP = 0;
   $ANPP = 0;
  $LABOUR = 0;
  $CPP = 0;
 
 
    foreach ($pollingUnits as $polls)
   {
     dd($polls->uniqueid);
        $results = DB::table('announced_pu_results')->where('polling_unit_uniqueid', $polls->uniqueid)->get(); 
        
        if($results) {
        foreach ($results as $result)
        {
               dd($result->party_score);
        if($result->party_abbreviation == "PDP") {
                $PDP = $PDP + $result->party_score;
        }

      else  if($result->party_abbreviation == "DPP") {
                $DPP = $DPP + $result->party_score;
        }

        else  if($result->party_abbreviation == "ACN") {
                $ACN = $DPP + $result->party_score;
        }

        else  if($result->party_abbreviation == "PPA") {
                $PPA = $DPP + $result->party_score;
        }

        else  if($result->party_abbreviation == "CDC") {
                $CDC = $CDC + $result->party_score;
        }

        else  if($result->party_abbreviation == "JP") {
                $JP = $JP + $result->party_score;
        } 

        else  if($result->party_abbreviation == "ANPP") {
                $ANPP = $ANPP + $result->party_score;
        } 

        else  if($result->party_abbreviation == "LABOUR") {
                $ANPP = $LABOUR + $result->party_score;
        } 

        else  if($result->party_abbreviation == "CPP") {
                $CPP = $CPP + $result->party_score;
        } 

        
} 

} // for each for results ends


   }

   return view('ToalLgaResultPage',compact('PDP','DPP','ACN','PPA','CDC','JP','ANPP','LABOUR','CPP')); 

   }


}
