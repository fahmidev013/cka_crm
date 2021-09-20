<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class SaleTaxesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sale_taxes')->delete();
        
        \DB::table('sale_taxes')->insert(array (
            0 => 
            array (
                'id' => 71,
                'sale_order_detail_id' => 60,
                'tax_type_id' => 1,
                'tax_amount' => 14.985,
            ),
            1 => 
            array (
                'id' => 72,
                'sale_order_detail_id' => 61,
                'tax_type_id' => 1,
                'tax_amount' => 10.575,
            ),
            2 => 
            array (
                'id' => 73,
                'sale_order_detail_id' => 62,
                'tax_type_id' => 1,
                'tax_amount' => 0.075,
            ),
            3 => 
            array (
                'id' => 80,
                'sale_order_detail_id' => 70,
                'tax_type_id' => 1,
                'tax_amount' => 1.17,
            ),
            4 => 
            array (
                'id' => 81,
                'sale_order_detail_id' => 71,
                'tax_type_id' => 1,
                'tax_amount' => 0.24375,
            ),
            5 => 
            array (
                'id' => 82,
                'sale_order_detail_id' => 72,
                'tax_type_id' => 1,
                'tax_amount' => 0.507,
            ),
            6 => 
            array (
                'id' => 83,
                'sale_order_detail_id' => 19,
                'tax_type_id' => 1,
                'tax_amount' => 1.17,
            ),
            7 => 
            array (
                'id' => 84,
                'sale_order_detail_id' => 20,
                'tax_type_id' => 1,
                'tax_amount' => 0.24375,
            ),
            8 => 
            array (
                'id' => 85,
                'sale_order_detail_id' => 69,
                'tax_type_id' => 1,
                'tax_amount' => 0.507,
            ),
            9 => 
            array (
                'id' => 86,
                'sale_order_detail_id' => 73,
                'tax_type_id' => 1,
                'tax_amount' => 0.04875,
            ),
            10 => 
            array (
                'id' => 87,
                'sale_order_detail_id' => 74,
                'tax_type_id' => 1,
                'tax_amount' => 0.04875,
            ),
            11 => 
            array (
                'id' => 88,
                'sale_order_detail_id' => 75,
                'tax_type_id' => 1,
                'tax_amount' => 11.355,
            ),
            12 => 
            array (
                'id' => 89,
                'sale_order_detail_id' => 76,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            13 => 
            array (
                'id' => 90,
                'sale_order_detail_id' => 77,
                'tax_type_id' => 1,
                'tax_amount' => 7.8,
            ),
            14 => 
            array (
                'id' => 91,
                'sale_order_detail_id' => 14,
                'tax_type_id' => 1,
                'tax_amount' => 11.355,
            ),
            15 => 
            array (
                'id' => 92,
                'sale_order_detail_id' => 15,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            16 => 
            array (
                'id' => 93,
                'sale_order_detail_id' => 55,
                'tax_type_id' => 1,
                'tax_amount' => 7.8,
            ),
            17 => 
            array (
                'id' => 94,
                'sale_order_detail_id' => 78,
                'tax_type_id' => 1,
                'tax_amount' => 1.56,
            ),
            18 => 
            array (
                'id' => 95,
                'sale_order_detail_id' => 79,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            19 => 
            array (
                'id' => 96,
                'sale_order_detail_id' => 23,
                'tax_type_id' => 1,
                'tax_amount' => 1.56,
            ),
            20 => 
            array (
                'id' => 97,
                'sale_order_detail_id' => 24,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            21 => 
            array (
                'id' => 98,
                'sale_order_detail_id' => 80,
                'tax_type_id' => 1,
                'tax_amount' => 11.355,
            ),
            22 => 
            array (
                'id' => 99,
                'sale_order_detail_id' => 81,
                'tax_type_id' => 1,
                'tax_amount' => 11.355,
            ),
            23 => 
            array (
                'id' => 106,
                'sale_order_detail_id' => 86,
                'tax_type_id' => 1,
                'tax_amount' => 29.97,
            ),
            24 => 
            array (
                'id' => 107,
                'sale_order_detail_id' => 87,
                'tax_type_id' => 1,
                'tax_amount' => 21.15,
            ),
            25 => 
            array (
                'id' => 108,
                'sale_order_detail_id' => 88,
                'tax_type_id' => 1,
                'tax_amount' => 0.75,
            ),
            26 => 
            array (
                'id' => 109,
                'sale_order_detail_id' => 32,
                'tax_type_id' => 1,
                'tax_amount' => 29.97,
            ),
            27 => 
            array (
                'id' => 110,
                'sale_order_detail_id' => 33,
                'tax_type_id' => 1,
                'tax_amount' => 21.15,
            ),
            28 => 
            array (
                'id' => 111,
                'sale_order_detail_id' => 34,
                'tax_type_id' => 1,
                'tax_amount' => 0.75,
            ),
            29 => 
            array (
                'id' => 118,
                'sale_order_detail_id' => 92,
                'tax_type_id' => 1,
                'tax_amount' => 16.575,
            ),
            30 => 
            array (
                'id' => 119,
                'sale_order_detail_id' => 93,
                'tax_type_id' => 1,
                'tax_amount' => 2.925,
            ),
            31 => 
            array (
                'id' => 120,
                'sale_order_detail_id' => 94,
                'tax_type_id' => 2,
                'tax_amount' => 52.325,
            ),
            32 => 
            array (
                'id' => 121,
                'sale_order_detail_id' => 38,
                'tax_type_id' => 1,
                'tax_amount' => 16.575,
            ),
            33 => 
            array (
                'id' => 122,
                'sale_order_detail_id' => 39,
                'tax_type_id' => 1,
                'tax_amount' => 2.925,
            ),
            34 => 
            array (
                'id' => 123,
                'sale_order_detail_id' => 40,
                'tax_type_id' => 2,
                'tax_amount' => 52.325,
            ),
            35 => 
            array (
                'id' => 124,
                'sale_order_detail_id' => 95,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            36 => 
            array (
                'id' => 125,
                'sale_order_detail_id' => 96,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            37 => 
            array (
                'id' => 126,
                'sale_order_detail_id' => 97,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            38 => 
            array (
                'id' => 127,
                'sale_order_detail_id' => 98,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            39 => 
            array (
                'id' => 128,
                'sale_order_detail_id' => 99,
                'tax_type_id' => 1,
                'tax_amount' => 16.575,
            ),
            40 => 
            array (
                'id' => 129,
                'sale_order_detail_id' => 100,
                'tax_type_id' => 1,
                'tax_amount' => 16.575,
            ),
            41 => 
            array (
                'id' => 130,
                'sale_order_detail_id' => 101,
                'tax_type_id' => 1,
                'tax_amount' => 0.8775,
            ),
            42 => 
            array (
                'id' => 131,
                'sale_order_detail_id' => 102,
                'tax_type_id' => 1,
                'tax_amount' => 0.8775,
            ),
            43 => 
            array (
                'id' => 132,
                'sale_order_detail_id' => 103,
                'tax_type_id' => 2,
                'tax_amount' => 10.465,
            ),
            44 => 
            array (
                'id' => 133,
                'sale_order_detail_id' => 104,
                'tax_type_id' => 2,
                'tax_amount' => 10.465,
            ),
            45 => 
            array (
                'id' => 134,
                'sale_order_detail_id' => 105,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            46 => 
            array (
                'id' => 135,
                'sale_order_detail_id' => 106,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            47 => 
            array (
                'id' => 136,
                'sale_order_detail_id' => 107,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            48 => 
            array (
                'id' => 137,
                'sale_order_detail_id' => 108,
                'tax_type_id' => 1,
                'tax_amount' => 0.0975,
            ),
            49 => 
            array (
                'id' => 138,
                'sale_order_detail_id' => 109,
                'tax_type_id' => 1,
                'tax_amount' => 14.985,
            ),
            50 => 
            array (
                'id' => 139,
                'sale_order_detail_id' => 110,
                'tax_type_id' => 1,
                'tax_amount' => 14.985,
            ),
            51 => 
            array (
                'id' => 140,
                'sale_order_detail_id' => 111,
                'tax_type_id' => 1,
                'tax_amount' => 10.575,
            ),
            52 => 
            array (
                'id' => 141,
                'sale_order_detail_id' => 112,
                'tax_type_id' => 1,
                'tax_amount' => 10.575,
            ),
            53 => 
            array (
                'id' => 142,
                'sale_order_detail_id' => 113,
                'tax_type_id' => 1,
                'tax_amount' => 0.15,
            ),
            54 => 
            array (
                'id' => 143,
                'sale_order_detail_id' => 114,
                'tax_type_id' => 1,
                'tax_amount' => 0.15,
            ),
            55 => 
            array (
                'id' => 144,
                'sale_order_detail_id' => 115,
                'tax_type_id' => 1,
                'tax_amount' => 1.56,
            ),
            56 => 
            array (
                'id' => 145,
                'sale_order_detail_id' => 116,
                'tax_type_id' => 1,
                'tax_amount' => 1.56,
            ),
            57 => 
            array (
                'id' => 146,
                'sale_order_detail_id' => 117,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            58 => 
            array (
                'id' => 147,
                'sale_order_detail_id' => 118,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            59 => 
            array (
                'id' => 148,
                'sale_order_detail_id' => 119,
                'tax_type_id' => 1,
                'tax_amount' => 11.355,
            ),
            60 => 
            array (
                'id' => 149,
                'sale_order_detail_id' => 120,
                'tax_type_id' => 1,
                'tax_amount' => 11.355,
            ),
            61 => 
            array (
                'id' => 150,
                'sale_order_detail_id' => 9,
                'tax_type_id' => 1,
                'tax_amount' => 0.45,
            ),
            62 => 
            array (
                'id' => 151,
                'sale_order_detail_id' => 10,
                'tax_type_id' => 1,
                'tax_amount' => 10.575,
            ),
            63 => 
            array (
                'id' => 152,
                'sale_order_detail_id' => 11,
                'tax_type_id' => 2,
                'tax_amount' => 16.099999999999998,
            ),
            64 => 
            array (
                'id' => 163,
                'sale_order_detail_id' => 126,
                'tax_type_id' => 1,
                'tax_amount' => 14.985,
            ),
            65 => 
            array (
                'id' => 164,
                'sale_order_detail_id' => 127,
                'tax_type_id' => 1,
                'tax_amount' => 10.575,
            ),
            66 => 
            array (
                'id' => 165,
                'sale_order_detail_id' => 128,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            67 => 
            array (
                'id' => 166,
                'sale_order_detail_id' => 129,
                'tax_type_id' => 2,
                'tax_amount' => 16.099999999999998,
            ),
            68 => 
            array (
                'id' => 167,
                'sale_order_detail_id' => 130,
                'tax_type_id' => 1,
                'tax_amount' => 0.45,
            ),
            69 => 
            array (
                'id' => 168,
                'sale_order_detail_id' => 131,
                'tax_type_id' => 1,
                'tax_amount' => 14.985,
            ),
            70 => 
            array (
                'id' => 169,
                'sale_order_detail_id' => 132,
                'tax_type_id' => 1,
                'tax_amount' => 10.575,
            ),
            71 => 
            array (
                'id' => 170,
                'sale_order_detail_id' => 133,
                'tax_type_id' => 1,
                'tax_amount' => 0.075,
            ),
            72 => 
            array (
                'id' => 171,
                'sale_order_detail_id' => 134,
                'tax_type_id' => 1,
                'tax_amount' => 44.28450000000001,
            ),
            73 => 
            array (
                'id' => 172,
                'sale_order_detail_id' => 135,
                'tax_type_id' => 2,
                'tax_amount' => 16.444999999999997,
            ),
            74 => 
            array (
                'id' => 173,
                'sale_order_detail_id' => 136,
                'tax_type_id' => 1,
                'tax_amount' => 0.507,
            ),
            75 => 
            array (
                'id' => 174,
                'sale_order_detail_id' => 27,
                'tax_type_id' => 1,
                'tax_amount' => 44.28450000000001,
            ),
            76 => 
            array (
                'id' => 175,
                'sale_order_detail_id' => 28,
                'tax_type_id' => 2,
                'tax_amount' => 16.444999999999997,
            ),
            77 => 
            array (
                'id' => 176,
                'sale_order_detail_id' => 85,
                'tax_type_id' => 1,
                'tax_amount' => 0.507,
            ),
            78 => 
            array (
                'id' => 177,
                'sale_order_detail_id' => 121,
                'tax_type_id' => 1,
                'tax_amount' => 14.985,
            ),
            79 => 
            array (
                'id' => 178,
                'sale_order_detail_id' => 122,
                'tax_type_id' => 1,
                'tax_amount' => 10.575,
            ),
            80 => 
            array (
                'id' => 179,
                'sale_order_detail_id' => 123,
                'tax_type_id' => 2,
                'tax_amount' => 12.65,
            ),
            81 => 
            array (
                'id' => 180,
                'sale_order_detail_id' => 124,
                'tax_type_id' => 2,
                'tax_amount' => 16.099999999999998,
            ),
            82 => 
            array (
                'id' => 181,
                'sale_order_detail_id' => 125,
                'tax_type_id' => 1,
                'tax_amount' => 0.45,
            ),
            83 => 
            array (
                'id' => 182,
                'sale_order_detail_id' => 137,
                'tax_type_id' => 1,
                'tax_amount' => 101.898,
            ),
            84 => 
            array (
                'id' => 183,
                'sale_order_detail_id' => 138,
                'tax_type_id' => 1,
                'tax_amount' => 71.91,
            ),
            85 => 
            array (
                'id' => 184,
                'sale_order_detail_id' => 139,
                'tax_type_id' => 2,
                'tax_amount' => 87.92,
            ),
            86 => 
            array (
                'id' => 185,
                'sale_order_detail_id' => 140,
                'tax_type_id' => 1,
                'tax_amount' => 0.51,
            ),
            87 => 
            array (
                'id' => 186,
                'sale_order_detail_id' => 141,
                'tax_type_id' => 1,
                'tax_amount' => 5.303999999999999,
            ),
        ));
        
        
    }
}