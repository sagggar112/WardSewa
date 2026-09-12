<?php

namespace Database\Seeders;

use App\Models\Palika;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        // 21 Palikas across 3 districts of Kathmandu Valley
        // Map of Palika code => [total_wards, domain, phone_prefix, [ward_num => prominent_area]]
        $palikaConfigs = [
            // ==========================================
            // 1. KATHMANDU DISTRICT (11 Palikas, 138 Wards)
            // ==========================================
            'KMC' => [
                'total_wards' => 32,
                'domain' => 'kathmandu.gov.np',
                'phone_prefix' => '01-4',
                'names' => [
                    1 => 'Naxal / नक्साल',
                    2 => 'Lazimpat / लाजिम्पाट',
                    3 => 'Maharajgunj / महाराजगञ्ज',
                    4 => 'Baluwatar / बालुवाटार',
                    5 => 'Tangal / हाँडीगाउँ',
                    6 => 'Boudha / बौद्ध',
                    7 => 'Chabahil / चाबहिल',
                    8 => 'Jayabageshwori / पशुपति',
                    9 => 'Sinamangal / सिनामङ्गल',
                    10 => 'Baneshwor / बानेश्वर',
                    11 => 'Tripureshwor / त्रिपुरेश्वर',
                    12 => 'Teku / टेकु',
                    13 => 'Kalimati / कालिमाटी',
                    14 => 'Kalanki / कलङ्की',
                    15 => 'Swayambhu / स्वयम्भू',
                    16 => 'Balaju / बालाजु',
                    17 => 'Chhetrapati / क्षेत्रपाटी',
                    18 => 'Naradevi / नरदेवी',
                    19 => 'Hanuman Dhoka / हनुमानढोका',
                    20 => 'Bhimsensthan / भीमसेनस्थान',
                    21 => 'Lagantole / लगनटोल',
                    22 => 'Tewal / टेबहाल',
                    23 => 'Basantapur / वसन्तपुर',
                    24 => 'Indrachowk / इन्द्रचोक',
                    25 => 'Ason / असन',
                    26 => 'Samakhusi / सामाखुसी',
                    27 => 'Bhotahiti / भोटाहिटी',
                    28 => 'Putalisadak / पुतलीसडक',
                    29 => 'Anamnagar / अनामनगर',
                    30 => 'Maitidevi / मैतीदेवी',
                    31 => 'Shantinagar / शान्तिनगर',
                    32 => 'Koteshwor / कोटेश्वर (Pilot Ward)',
                ],
            ],
            'KMM' => [
                'total_wards' => 9,
                'domain' => 'kageshworimanoharamun.gov.np',
                'phone_prefix' => '01-49',
                'names' => [
                    1 => 'Gagalphedi / गागलफेदी',
                    2 => 'Aalapot / आलापोट',
                    3 => 'Bhadrabas / भद्रबास',
                    4 => 'Danchhi / डाँछी',
                    5 => 'Thali / थली',
                    6 => 'Mulpani / मूलपानी',
                    7 => 'Harhar Mahadev / हरहर महादेव',
                    8 => 'Gothatar / गोठाटार',
                    9 => 'Kandaghari / काँडाघारी',
                ],
            ],
            'KRM' => [
                'total_wards' => 10,
                'domain' => 'kirtipurmun.gov.np',
                'phone_prefix' => '01-43',
                'names' => [
                    1 => 'Baghbhairab / बाघभैरव',
                    2 => 'Devdhoka / देवढोका',
                    3 => 'Khangla / खाङ्गला',
                    4 => 'Bhatkepati / भत्केपाटी',
                    5 => 'Panga Dobato / पाँगा दोबाटो',
                    6 => 'Chhyasikot / छ्यासीकोट',
                    7 => 'Khasibazar / खसीबजार',
                    8 => 'Panga Bishnudevi / विष्णुदेवी',
                    9 => 'Nagar Mandap / नयाँबजार',
                    10 => 'Chobhar / चोभार',
                ],
            ],
            'GKM' => [
                'total_wards' => 9,
                'domain' => 'gokarneshwormun.gov.np',
                'phone_prefix' => '01-49',
                'names' => [
                    1 => 'Sundarijal / सुन्दरीजल',
                    2 => 'Nayapati / नयाँपाटी',
                    3 => 'Gokarna / गोकर्ण',
                    4 => 'Uttarbahini / उत्तरवाहिनी',
                    5 => 'Jorpati / जोरपाटी',
                    6 => 'Nayabasti / नयाँबस्ती',
                    7 => 'Dakshindhoka / दक्षिणढोका',
                    8 => 'Besigaun / बेसीगाउँ',
                    9 => 'Aarubari / आरुबारी',
                ],
            ],
            'CGM' => [
                'total_wards' => 15,
                'domain' => 'chandragirimun.gov.np',
                'phone_prefix' => '01-43',
                'names' => [
                    1 => 'Dahachok / दहचोक',
                    2 => 'Badbhanjyang / बाडभञ्ज्याङ',
                    3 => 'Thankot / थानकोट',
                    4 => 'Godam / गोदाम चोक',
                    5 => 'Kisipidi / किसिपिडी',
                    6 => 'Mahadevsthan / महादेवस्थान',
                    7 => 'Checkpost / चेकपोस्ट',
                    8 => 'Matatirtha / मातातीर्थ',
                    9 => 'Machhegaun / मच्छेगाउँ',
                    10 => 'Satungal / सतुङ्गल',
                    11 => 'Bishnudevi / विष्णुदेवी',
                    12 => 'Balambu / बलम्बु',
                    13 => 'Purano Naikap / पुरानो नैकाप',
                    14 => 'Naya Naikap / नयाँ नैकाप',
                    15 => 'Tinthana / तीनथाना',
                ],
            ],
            'TKM' => [
                'total_wards' => 11,
                'domain' => 'tokhamun.gov.np',
                'phone_prefix' => '01-43',
                'names' => [
                    1 => 'Baudeshwor / बौडेश्वर',
                    2 => 'Tokha Chandeshwori / टोखा चण्डेश्वरी',
                    3 => 'Tokha Saraswati / टोखा सरस्वती',
                    4 => 'Dhapasi Height / धापासी हाइट',
                    5 => 'Dhapasi Basundhara / बसुन्धरा',
                    6 => 'Grande / धापासी',
                    7 => 'Tilganga / टोखा',
                    8 => 'Baniyatar / बानियाँटार',
                    9 => 'Gongabu / गोङ्गबु',
                    10 => 'Gongabu Chowk / नयाँ बसपार्क',
                    11 => 'Samakhusi Border / सामाखुसी सिमाना',
                ],
            ],
            'TRM' => [
                'total_wards' => 11,
                'domain' => 'tarakeshwormun.gov.np',
                'phone_prefix' => '01-40',
                'names' => [
                    1 => 'Sangla / साङ्ला',
                    2 => 'Kavresthali / काभ्रेस्थली',
                    3 => 'Jitpurphedi / जितपुरफेदी',
                    4 => 'Goldhunga / गोलढुङ्गा',
                    5 => 'Lopchu / लोप्चु',
                    6 => 'Dharmasthali / धर्मस्थली',
                    7 => 'Phutung / फुटुङ',
                    8 => 'Manamaiju / मनमैजु',
                    9 => 'Manamaiju Gate / मनमैजु गेट',
                    10 => 'Nepal Chowk / नेपाल चोक',
                    11 => 'Lolang / लोलङ',
                ],
            ],
            'DKM' => [
                'total_wards' => 9,
                'domain' => 'dakshinkalimun.gov.np',
                'phone_prefix' => '01-47',
                'names' => [
                    1 => 'Chhaimale / छैमले',
                    2 => 'Talku Dudechaur / टल्कु डुडेचौर',
                    3 => 'Setidevi / सेतीदेवी',
                    4 => 'Pharping / फर्पिङ',
                    5 => 'Dakshinkali / दक्षिणकाली मन्दिर',
                    6 => 'Sheshnarayan / शेषनारायण',
                    7 => 'Balkumari / बालकुमारी',
                    8 => 'Chhampi Border / चम्पी सिमाना',
                    9 => 'Khahare / खहरे',
                ],
            ],
            'NJM' => [
                'total_wards' => 10,
                'domain' => 'nagarjunmun.gov.np',
                'phone_prefix' => '01-48',
                'names' => [
                    1 => 'Raniban / रानीवन',
                    2 => 'Sanobharyang / सानो भर्‍याङ',
                    3 => 'Ichhangunarayan / इचङ्गुनारायण',
                    4 => 'Sitapaila / सीतापाइला',
                    5 => 'Halchok / हलचोक',
                    6 => 'Ramkot / रामकोट',
                    7 => 'Hasanpur / हसनपुर',
                    8 => 'Bhimdhunga / भीमढुङ्गा',
                    9 => 'Syuchatar / स्युचाटार',
                    10 => 'Kalanki Border / कलङ्की सिमाना',
                ],
            ],
            'BNM' => [
                'total_wards' => 13,
                'domain' => 'budhanilkanthamun.gov.np',
                'phone_prefix' => '01-43',
                'names' => [
                    1 => 'Taudaha / तौदह',
                    2 => 'Bhangal / भङ्गाल',
                    3 => 'Budhanilkantha Mandir / बूढानीलकण्ठ मन्दिर',
                    4 => 'Pasikot / पासिकोट',
                    5 => 'Chapakali / चपली',
                    6 => 'Mahankal / महाङ्काल',
                    7 => 'Golfutar / गल्फुटार',
                    8 => 'Mandikhatar / मण्डिखाटार',
                    9 => 'Sukedhara / सुकेधारा',
                    10 => 'Kapan Sano / सानो कपन',
                    11 => 'Kapan / कपन',
                    12 => 'Kapan Paiyatar / पैयुँटार',
                    13 => 'Chunnikhel / चुनिखेल',
                ],
            ],
            'SKM' => [
                'total_wards' => 9,
                'domain' => 'shankharapurmun.gov.np',
                'phone_prefix' => '01-44',
                'names' => [
                    1 => 'Naglebhare / नाङ्गलेभारे',
                    2 => 'Lapsiphedi / लप्सीफेदी',
                    3 => 'Jaharsingh Pauwa / जहरसिं पौवा',
                    4 => 'Pukhulachhi / पुखुलाछी',
                    5 => 'Sankhu Bazaar / साँखु बजार',
                    6 => 'Salinadi / शालीनदी',
                    7 => 'Suntol / सुन्टोल',
                    8 => 'Bajrayogini / वज्रयोगिनी',
                    9 => 'Kuntabesi Border / कुन्ताबेंसी',
                ],
            ],

            // ==========================================
            // 2. LALITPUR DISTRICT (6 Palikas, 71 Wards)
            // ==========================================
            'LMC' => [
                'total_wards' => 29,
                'domain' => 'lalitpurmun.gov.np',
                'phone_prefix' => '01-5',
                'names' => [
                    1 => 'Kupandole / कुपण्डोल',
                    2 => 'Sanepa / सानेपा',
                    3 => 'Pulchowk / पुल्चोक',
                    4 => 'Jawalakhel / जावलाखेल',
                    5 => 'Kumaripati / कुमारीपाटी',
                    6 => 'Kanimahal / कनिमहल',
                    7 => 'Sundhara / सुन्धरा पाटन',
                    8 => 'Chyasal / च्यासल',
                    9 => 'Balkumari / बालकुमारी',
                    10 => 'Kupandole Height / कुपण्डोल हाइट',
                    11 => 'Alko / अलको',
                    12 => 'Mangalbazar / मङ्गलबजार',
                    13 => 'Lagankhel / लगनखेल',
                    14 => 'Talchikhel / तालछीखेल',
                    15 => 'Satdobato / सातदोबाटो',
                    16 => 'Dhapakhel / धापाखेल',
                    17 => 'Gwarko / ग्वार्को',
                    18 => 'Imadol Border / इमाडोल सिमाना',
                    19 => 'Thecho Border / लेले बाटो',
                    20 => 'Patan Durbar / पाटन दरबार',
                    21 => 'Khokana / खोकना',
                    22 => 'Bungamati / बुङ्गमती',
                    23 => 'Hattiban / हात्तीवन',
                    24 => 'Dhajikhel / धाजीखेल',
                    25 => 'Bhaisepati / भैंसेपाटी',
                    26 => 'Sainbu / सैंबु',
                    27 => 'Chhampi / छम्पी',
                    28 => 'Harisiddhi / हरिसिद्धि',
                    29 => 'Chhampi Dovan / छम्पी दोभान',
                ],
            ],
            'MLM' => [
                'total_wards' => 10,
                'domain' => 'mahalaxmimun.gov.np',
                'phone_prefix' => '01-52',
                'names' => [
                    1 => 'Imadol / इमाडोल',
                    2 => 'Sanagaun / सानागाउँ',
                    3 => 'Bojepokhari / बोझेपोखरी',
                    4 => 'Tikathali / टीकाथली',
                    5 => 'Siddhipur / सिद्धिपुर',
                    6 => 'Lubhu Bazaar / लुभु बजार',
                    7 => 'Lubhu Gobhalthok / लुभु गोभाल्थोक',
                    8 => 'Lamatar / लामाटार',
                    9 => 'Sisneri / सिसनेरी',
                    10 => 'Lakuribhanjyang / लाँकुरीभञ्ज्याङ',
                ],
            ],
            'GDM' => [
                'total_wards' => 14,
                'domain' => 'godawarimun.gov.np',
                'phone_prefix' => '01-56',
                'names' => [
                    1 => 'Godamchaur / गोदामचौर',
                    2 => 'Bisankhunarayan / विशङ्खुनारायण',
                    3 => 'Godawari Botanical / गोदावरी',
                    4 => 'Badikhel / बडिखेल',
                    5 => 'Lele / लेले',
                    6 => 'Tika Bhairab / टीकाभैरव',
                    7 => 'Devichaur / देवीचौर',
                    8 => 'Dungin / डुङ्गिन',
                    9 => 'Chhampi / छम्पी',
                    10 => 'Tahakhel / ताहाखेल',
                    11 => 'Chapagaun / चापागाउँ',
                    12 => 'Thecho / थेचो',
                    13 => 'Jharuwarasi / झरुवारासी',
                    14 => 'Godawari Kunda / गोदावरी कुण्ड',
                ],
            ],
            'KJM' => [
                'total_wards' => 5,
                'domain' => 'konjyosomrm.gov.np',
                'phone_prefix' => '01-69',
                'names' => [
                    1 => 'Chaughare / चौघरे',
                    2 => 'Sankhu / शङ्खु',
                    3 => 'Dalchoki / दलचोकी',
                    4 => 'Nallu / नल्लु',
                    5 => 'Bhardev / भारदेउ',
                ],
            ],
            'BRM' => [
                'total_wards' => 7,
                'domain' => 'bagmatirm.gov.np',
                'phone_prefix' => '01-69',
                'names' => [
                    1 => 'Dhuseni / धुसेनी',
                    2 => 'Bhattedanda / भट्टेडाँडा',
                    3 => 'Ikudol / इकुडोल',
                    4 => 'Pangdur / पाङदुर',
                    5 => 'Ashrang / आश्राङ',
                    6 => 'Gimdi / गिम्दी',
                    7 => 'Malta / माल्टा',
                ],
            ],
            'MHM' => [
                'total_wards' => 6,
                'domain' => 'mahankalrm.gov.np',
                'phone_prefix' => '01-69',
                'names' => [
                    1 => 'Bukhel / बुखेल',
                    2 => 'Manikhel / मानिखेल',
                    3 => 'Gotikhel / गोटीखेल',
                    4 => 'Kaleshwor / कालेश्वर',
                    5 => 'Chandanpur / चन्दनपुर',
                    6 => 'Thuladurlung / ठूलादुर्लुङ',
                ],
            ],

            // ==========================================
            // 3. BHAKTAPUR DISTRICT (4 Palikas, 38 Wards)
            // ==========================================
            'BKM' => [
                'total_wards' => 10,
                'domain' => 'bhaktapurmun.gov.np',
                'phone_prefix' => '01-66',
                'names' => [
                    1 => 'Durbar Square / भक्तपुर दरबार',
                    2 => 'Byasi / ब्यासी',
                    3 => 'Golmadhi / गोलमढी',
                    4 => 'Taumadhi / तौमढी',
                    5 => 'Dattatreya / दत्तात्रेय',
                    6 => 'Kamalbinayak / कमलविनायक',
                    7 => 'Chyamhasingh / च्याम्हासिंह',
                    8 => 'Khandbar / खाण्डबारी',
                    9 => 'Tumacho / तुमाचो',
                    10 => 'Bhelukhel / भेलुखेल',
                ],
            ],
            'MTM' => [
                'total_wards' => 9,
                'domain' => 'madhyapurthimimun.gov.np',
                'phone_prefix' => '01-66',
                'names' => [
                    1 => 'Lokanthali / लोकन्थली',
                    2 => 'Balkumari / बालकुमारी थिमी',
                    3 => 'Kaushaltar / कौशलटार',
                    4 => 'Radhe Radhe / राधे राधे',
                    5 => 'Chapacho / चापाचो',
                    6 => 'Nagadesh / नगदेश',
                    7 => 'Bahakha / बहाखा',
                    8 => 'Bode / बोडे',
                    9 => 'Tigani / तिगनी',
                ],
            ],
            'SVM' => [
                'total_wards' => 10,
                'domain' => 'suryabinayakmun.gov.np',
                'phone_prefix' => '01-66',
                'names' => [
                    1 => 'Sirutar / सिरुटार',
                    2 => 'Balkot / बालकोट',
                    3 => 'Dadhikot / दधिकोट',
                    4 => 'Gamphedi / गामफेदी',
                    5 => 'Katunje / कटुञ्जे',
                    6 => 'Pandubazar / पाण्डुबजार',
                    7 => 'Suryabinayak / सूर्यविनायक',
                    8 => 'Sipadol / सिपाडोल',
                    9 => 'Nankhel / ननखेल',
                    10 => 'Gundu / गुण्डु',
                ],
            ],
            'CNM' => [
                'total_wards' => 9,
                'domain' => 'changunarayanmun.gov.np',
                'phone_prefix' => '01-66',
                'names' => [
                    1 => 'Duwakot / दुवाकोट',
                    2 => 'Jhaukhel / झौखेल',
                    3 => 'Chhaling / छालिङ',
                    4 => 'Changunarayan Mandir / चाँगुनारायण',
                    5 => 'Bageshwori / बागेश्वरी',
                    6 => 'Nagarkot / नगरकोट',
                    7 => 'Kharipati / खरिपाटी',
                    8 => 'Sudal / सुडाल',
                    9 => 'Tathali / ताथली',
                ],
            ],
        ];

        $totalSeeded = 0;

        foreach ($palikaConfigs as $code => $config) {
            $palika = Palika::where('code', $code)->first();
            if (!$palika) {
                continue;
            }

            for ($w = 1; $w <= $config['total_wards']; $w++) {
                $isPilotKmc32 = ($code === 'KMC' && $w === 32);
                $areaName = $config['names'][$w] ?? "Ward {$w}";

                // Office address
                $officeAddress = $isPilotKmc32
                    ? 'Koteshwor, Kathmandu'
                    : "{$areaName}, {$palika->name_en}";

                // Office phone
                $phoneSuffix = str_pad((string)$w, 4, '0', STR_PAD_LEFT);
                $officePhone = $isPilotKmc32
                    ? '01-4601234'
                    : "{$config['phone_prefix']}{$phoneSuffix}";

                // Official email
                $officeEmail = $isPilotKmc32
                    ? 'ward32@kathmandu.gov.np'
                    : "ward{$w}@{$config['domain']}";

                Ward::updateOrCreate(
                    [
                        'palika_id' => $palika->id,
                        'ward_number' => $w,
                    ],
                    [
                        'office_address' => $officeAddress,
                        'office_phone' => $officePhone,
                        'office_email' => $officeEmail,
                    ]
                );

                $totalSeeded++;
            }
        }

        $this->command?->info("Successfully seeded {$totalSeeded} wards across 21 palikas in Kathmandu, Lalitpur, and Bhaktapur.");
    }
}
