<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name_en' => 'Four Boundaries Recommendation (Char Killa)',
                'name_ne' => 'चार किल्ला प्रमाणित सिफारिस',
                'code' => 'char-killa',
                'category' => 'recommendation',
                'fee' => 500.00,
                'turnaround_days' => 3,
                'required_documents' => [
                    ['key' => 'citizenship', 'label_en' => 'Citizenship Certificate', 'label_ne' => 'नागरिकता प्रमाणपत्र', 'required' => true],
                    ['key' => 'land_ownership', 'label_en' => 'Land Ownership Certificate (Lalpurja)', 'label_ne' => 'जग्गाधनी प्रमाणपुर्जा (लालपुर्जा)', 'required' => true],
                    ['key' => 'land_tax_receipt', 'label_en' => 'Current Fiscal Year Land Tax Receipt', 'label_ne' => 'चालु आ.व. को मालपोत/कर तिरेको रसिद', 'required' => true],
                    ['key' => 'trace_map', 'label_en' => 'Cadastral Trace Map (Napi Naksha)', 'label_ne' => 'नापी नक्सा ट्रेस', 'required' => false],
                ],
                'form_fields' => [
                    ['name' => 'kitta_no', 'label_en' => 'Kitta Number (Plot No.)', 'label_ne' => 'कित्ता नम्बर', 'type' => 'text', 'required' => true],
                    ['name' => 'area_sqm', 'label_en' => 'Area (Ropani/Bigha/Sq.m)', 'label_ne' => 'क्षेत्रफल', 'type' => 'text', 'required' => true],
                    ['name' => 'east_boundary', 'label_en' => 'East Boundary', 'label_ne' => 'पूर्व किल्ला', 'type' => 'text', 'required' => true],
                    ['name' => 'west_boundary', 'label_en' => 'West Boundary', 'label_ne' => 'पश्चिम किल्ला', 'type' => 'text', 'required' => true],
                    ['name' => 'north_boundary', 'label_en' => 'North Boundary', 'label_ne' => 'उत्तर किल्ला', 'type' => 'text', 'required' => true],
                    ['name' => 'south_boundary', 'label_en' => 'South Boundary', 'label_ne' => 'दक्षिण किल्ला', 'type' => 'text', 'required' => true],
                    ['name' => 'purpose', 'label_en' => 'Purpose of Recommendation', 'label_ne' => 'सिफारिसको प्रयोजन', 'type' => 'text', 'required' => true],
                ],
                'is_active' => true,
            ],
            [
                'name_en' => 'Unmarried Status Certificate',
                'name_ne' => 'अविवाहित प्रमाणित सिफारिस',
                'code' => 'unmarried-cert',
                'category' => 'recommendation',
                'fee' => 300.00,
                'turnaround_days' => 2,
                'required_documents' => [
                    ['key' => 'citizenship', 'label_en' => 'Citizenship Certificate', 'label_ne' => 'नागरिकता प्रमाणपत्र', 'required' => true],
                    ['key' => 'passport_photo', 'label_en' => 'Passport Size Photo', 'label_ne' => 'पासपोर्ट साइजको फोटो', 'required' => true],
                    ['key' => 'witness_citizenship', 'label_en' => 'Witness Citizenship (Father/Mother/Guardian)', 'label_ne' => 'साक्षीको नागरिकता', 'required' => true],
                ],
                'form_fields' => [
                    ['name' => 'father_name', 'label_en' => "Father's Full Name", 'label_ne' => 'बुबाको नाम', 'type' => 'text', 'required' => true],
                    ['name' => 'mother_name', 'label_en' => "Mother's Full Name", 'label_ne' => 'आमाको नाम', 'type' => 'text', 'required' => true],
                    ['name' => 'witness_name', 'label_en' => 'Witness Name', 'label_ne' => 'साक्षीको नाम', 'type' => 'text', 'required' => true],
                    ['name' => 'witness_relation', 'label_en' => 'Witness Relationship', 'label_ne' => 'साक्षीसँगको नाता', 'type' => 'text', 'required' => true],
                    ['name' => 'purpose', 'label_en' => 'Purpose (e.g. Visa, Study, Employment)', 'label_ne' => 'प्रयोजन', 'type' => 'text', 'required' => true],
                ],
                'is_active' => true,
            ],
            [
                'name_en' => 'Residence Verification Certificate',
                'name_ne' => 'बसोबास प्रमाणित सिफारिस',
                'code' => 'residence-cert',
                'category' => 'recommendation',
                'fee' => 200.00,
                'turnaround_days' => 2,
                'required_documents' => [
                    ['key' => 'citizenship', 'label_en' => 'Citizenship Certificate', 'label_ne' => 'नागरिकता प्रमाणपत्र', 'required' => true],
                    ['key' => 'house_tax_receipt', 'label_en' => 'House / Land Tax Receipt or Electricity Bill', 'label_ne' => 'घरजग्गा कर रसिद वा बिजुलीको बिल', 'required' => true],
                ],
                'form_fields' => [
                    ['name' => 'toll_name', 'label_en' => 'Tole / Street Name', 'label_ne' => 'टोल / सडकको नाम', 'type' => 'text', 'required' => true],
                    ['name' => 'house_number', 'label_en' => 'House Number', 'label_ne' => 'घर नम्बर', 'type' => 'text', 'required' => false],
                    ['name' => 'residence_type', 'label_en' => 'Residence Type (Permanent / Temporary)', 'label_ne' => 'बसोबास प्रकार (स्थायी / अस्थायी)', 'type' => 'select', 'required' => true],
                    ['name' => 'living_since_year', 'label_en' => 'Living Since (Year BS)', 'label_ne' => 'बसोबास सुरु वर्ष (वि.सं.)', 'type' => 'text', 'required' => true],
                ],
                'is_active' => true,
            ],
            [
                'name_en' => 'Relationship Verification Certificate',
                'name_ne' => 'नाता प्रमाणित सिफारिस',
                'code' => 'relationship-cert',
                'category' => 'recommendation',
                'fee' => 400.00,
                'turnaround_days' => 3,
                'required_documents' => [
                    ['key' => 'applicant_citizenship', 'label_en' => 'Applicant Citizenship', 'label_ne' => 'निवेदकको नागरिकता', 'required' => true],
                    ['key' => 'relative_citizenships', 'label_en' => 'Relatives Citizenships / Birth Certificates', 'label_ne' => 'सम्बन्धित सदस्यहरुको नागरिकता वा जन्मदर्ता', 'required' => true],
                    ['key' => 'family_photo', 'label_en' => 'Combined or Individual Photos', 'label_ne' => 'पारिवारिक वा व्यक्तिगत फोटो', 'required' => true],
                ],
                'form_fields' => [
                    ['name' => 'relatives_list', 'label_en' => 'Relatives Details (Name, Relation, Citizenship/DOB)', 'label_ne' => 'नातेदारहरुको विवरण (नाम, नाता, नागरिकता नं)', 'type' => 'textarea', 'required' => true],
                    ['name' => 'purpose', 'label_en' => 'Purpose (Immigration, Banking, Inheritance)', 'label_ne' => 'प्रयोजन', 'type' => 'text', 'required' => true],
                ],
                'is_active' => true,
            ],
            [
                'name_en' => 'Birth Registration',
                'name_ne' => 'जन्म दर्ता',
                'code' => 'birth-reg',
                'category' => 'vital_registration',
                'fee' => 0.00, // free within 35 days in Nepal
                'turnaround_days' => 1,
                'required_documents' => [
                    ['key' => 'hospital_birth_certificate', 'label_en' => 'Hospital Birth Report / Maternity Card', 'label_ne' => 'अस्पतालको जन्म प्रमाणपत्र वा खोप कार्ड', 'required' => true],
                    ['key' => 'father_citizenship', 'label_en' => "Father's Citizenship", 'label_ne' => 'बुबाको नागरिकता', 'required' => true],
                    ['key' => 'mother_citizenship', 'label_en' => "Mother's Citizenship", 'label_ne' => 'आमाको नागरिकता', 'required' => true],
                    ['key' => 'parents_marriage_cert', 'label_en' => "Parents' Marriage Certificate", 'label_ne' => 'आमाबुबाको विवाह दर्ता प्रमाणपत्र', 'required' => true],
                ],
                'form_fields' => [
                    ['name' => 'child_name_en', 'label_en' => "Child's Full Name (English)", 'label_ne' => 'शिशुको पूरा नाम (अंग्रेजीमा)', 'type' => 'text', 'required' => true],
                    ['name' => 'child_name_ne', 'label_en' => "Child's Full Name (Nepali)", 'label_ne' => 'शिशुको पूरा नाम (नेपालीमा)', 'type' => 'text', 'required' => true],
                    ['name' => 'gender', 'label_en' => 'Gender', 'label_ne' => 'लिङ्ग', 'type' => 'select', 'required' => true],
                    ['name' => 'dob_bs', 'label_en' => 'Date of Birth (B.S. YYYY-MM-DD)', 'label_ne' => 'जन्म मिति (वि.सं.)', 'type' => 'text', 'required' => true],
                    ['name' => 'dob_ad', 'label_en' => 'Date of Birth (A.D.)', 'label_ne' => 'जन्म मिति (ई.सं.)', 'type' => 'date', 'required' => true],
                    ['name' => 'birth_place', 'label_en' => 'Birth Place (Hospital / Home)', 'label_ne' => 'जन्म स्थान', 'type' => 'text', 'required' => true],
                    ['name' => 'father_name', 'label_en' => "Father's Full Name", 'label_ne' => 'बुबाको नाम', 'type' => 'text', 'required' => true],
                    ['name' => 'mother_name', 'label_en' => "Mother's Full Name", 'label_ne' => 'आमाको नाम', 'type' => 'text', 'required' => true],
                    ['name' => 'grandfather_name', 'label_en' => "Grandfather's Full Name", 'label_ne' => 'हजुरबुबाको नाम', 'type' => 'text', 'required' => true],
                ],
                'is_active' => true,
            ],
            [
                'name_en' => 'Public Grievance / Complaint',
                'name_ne' => 'गुनासो तथा उजुरी',
                'code' => 'complaint',
                'category' => 'complaint',
                'fee' => 0.00,
                'turnaround_days' => 5,
                'required_documents' => [
                    ['key' => 'evidence_photos', 'label_en' => 'Evidence / Problem Photos', 'label_ne' => 'समस्याको फोटो', 'required' => false],
                ],
                'form_fields' => [
                    ['name' => 'complaint_category', 'label_en' => 'Category (Roads, Drainage, Waste, Noise, etc.)', 'label_ne' => 'गुनासो विधा', 'type' => 'text', 'required' => true],
                    ['name' => 'subject', 'label_en' => 'Subject', 'label_ne' => 'विषय', 'type' => 'text', 'required' => true],
                    ['name' => 'location', 'label_en' => 'Location / Landmark', 'label_ne' => 'स्थान / ल्यान्डमार्क', 'type' => 'text', 'required' => true],
                    ['name' => 'description', 'label_en' => 'Detailed Description', 'label_ne' => 'विस्तृत विवरण', 'type' => 'textarea', 'required' => true],
                ],
                'is_active' => true,
            ]
        ];

        foreach ($services as $service) {
            ServiceType::updateOrCreate(['code' => $service['code']], $service);
        }
    }
}
