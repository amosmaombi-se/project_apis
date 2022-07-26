<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NHIFStudentController extends Controller
{  
    private $auth_url = "196.13.105.15/omrs/stsidentity";
    private $member_registration_url = "http://196.13.105.15/OMRS/api/v1/Verification/StudentRegistration";
    private $verify_card_url = "http://196.13.105.15/OMRS/api/v1/Verification/GetStudentsCardStatus";
    private $payment_status_url = "http://196.13.105.15/OMRS/api/v1/Verification/GetPaymentStatus";
    private $reg_details_url = "http://196.13.105.15/OMRS/api/v1/Verification/GetRegistrationList";

   
    public function agentAuthentication()
    {
            $curl = curl_init();
            curl_setopt_array($curl, array(
             CURLOPT_URL => $this->auth_url,
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => "",
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => "POST",
             CURLOPT_POSTFIELDS =>
               "grant_type=client_credentials&client_id=NHIF&password=Nhif@2020&client_secret=
               fab035ac-25ae-967c-c3f6-b9c642d82be5&scope=OMRS",
               CURLOPT_HTTPHEADER => array("Content-Type: application/x-www-form-urlencoded"),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
           //  return $response->access_token;

            var_dump($response);

    }


    public function memberRegistration()
    {
        $member_info = array(
             "FormFourIndexNo" => request('FormFourIndexNo'),
             "FirstName"=>request('FirstName'),
             "MiddleName"=>request('MiddleName'),
             "Surname"=>request('Surname'),
             "AdmissionNo"=>request('AdmissionNo'),
             "CollageFaculty"=>request('Coet'),
             "MobileNo"=>request('MobileNo'),// eg "07663434507",
             "ProgrammeOfStudy"=>request('ProgrammeOfStudy'), //eg "Civil Engineering",
             "CourseDuration"=>request('CourseDuration'),//eg 4,
             "MaritalStatus"=>request('MaritalStatus'),//,
             "DateJoiningEmployer"=>request('DateJoiningEmployer'),
             "DateOfBirth"=>request('DateOfBirth'),
             "NationalID"=>request('NationalID'),  // eg"19930620141299000",
             "Gender"=>request('Gender'),//
        );

        $url = $this->member_registration_url;
        $response = $this->makeHttpRequests($url,$member_info);
        return $response;
    }


     public function verifyMemberCardStatus()
    {
        $CardNo = request('CardNo');
        $url = $this->verify_card_url;
        $dataArray = ['CardNo' => $CardNo];

        $response = $this->urlParameterRequests($url,$dataArray);
        return $response;
    }


    public function paymentStatus()
    {
        $ControlNo = request('ControlNo');
        $url = $this->payment_status_url;
        $dataArray = ['ControlNo' => $ControlNo];

        $response = $this->urlParameterRequests($url,$dataArray);
        return $response;
    }


     public function registrationDetails()
    {
        $Year = request('Year');
        $Gender = request('Gender');
        $admissionNo = request('admissionNo');
        $formFourIndexNo = request('formFourIndexNo');

        $dataArray = ['Year' => $Year,'Gender'=>$Gender,'admissionNo'=>$admissionNo,'formFourIndexNo'=>$formFourIndexNo];
        $url = $this->reg_details_url;
        $response = $this->urlParameterRequests($url,$dataArray);
        return $response;
    }

     public function urlParameterRequests($url,$dataArray)
     {
            $curl = curl_init();          
            $data = http_build_query($dataArray);
            $getUrl = $url."?".$data;

            curl_setopt_array($curl,
              array(
                    CURLOPT_URL => $getUrl,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json','Authorization:Bearer ' . $this->agentAuthentication()],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYPEER => false, 
                    CURLOPT_TIMEOUT => 80,
               )
           );
               
            $response = curl_exec($curl);
                
            if(curl_error($curl)){
                echo 'Request Error:' . curl_error($curl);
            }else{
                return $response;
            }
               
            curl_close($curl);
     }

     public function makeHttpRequests($url,$body)
    {
        $curl = curl_init();
        curl_setopt_array($curl,
              array(
                    CURLOPT_URL => $url,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json','Authorization:Bearer ' . $this->agentAuthentication()],
                    CURLOPT_RETURNTRANSER => true,
                    CURLOPT_POST => false,
                    CURLOPT_POSTFIELDS => json_encode($body),
               )
           );
        $curl_response = curl_exec($curl);
        curl_close($curl);

          if(curl_error($curl)){
                echo 'Request Error:' . curl_error($curl);
          }else{
            return $curl_response;
          }
               
    }
}
